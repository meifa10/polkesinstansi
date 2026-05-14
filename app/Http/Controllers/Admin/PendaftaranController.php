<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranPoli;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PendaftaranController extends Controller
{
    /**
     * Menampilkan daftar antrean pasien hari ini.
     */
    public function index(Request $request)
    {
        // Eager loading 'dokter' untuk menghindari N+1 query problem
        $query = PendaftaranPoli::with('dokter')
                    ->whereDate('created_at', Carbon::today()) 
                    ->latest();

        // Fitur Pencarian (Nama Pasien atau NIK)
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nama_pasien', 'like', "%{$search}%")
                  ->orWhere('no_identitas', 'like', "%{$search}%");
            });
        }

        // Fitur Filter per Poliklinik
        if ($request->filled('poli')) {
            $query->where('poli', $request->poli);
        }

        $pendaftaran = $query->get();

        return view('admin.pendaftaran.index', compact('pendaftaran'));
    }

    /**
     * Memperbarui status pasien (Verifikasi Admin ke Dokter).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $pasien = PendaftaranPoli::findOrFail($id);

        /**
         * LOGIKA ALUR KERJA (Workflow Protection):
         * Admin hanya boleh mengubah status ke 'diproses_dokter' 
         * JIKA status saat ini sudah 'menunggu_admin' (berarti Petugas sudah input).
         */
        if ($request->status === 'diproses_dokter') {
            if ($pasien->status === 'menunggu_petugas') {
                return back()->with('error', 'Gagal! Petugas kesehatan belum mengisi pemeriksaan awal (Vital Sign).');
            }
            
            // Opsional: Pastikan data vital sign tidak kosong secara fisik
            if (empty($pasien->berat_badan) || empty($pasien->keluhan)) {
                return back()->with('error', 'Gagal! Data pemeriksaan awal belum lengkap.');
            }
        }

        // Eksekusi perubahan status
        $pasien->status = $request->status;
        $pasien->save();

        return back()->with('success', 'Pasien berhasil diverifikasi dan dikirim ke Dokter.');
    }
}
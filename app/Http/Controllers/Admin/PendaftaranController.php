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
     * Memperbarui status pasien (Jika Admin masih butuh kontrol manual).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $pasien = PendaftaranPoli::findOrFail($id);

        // Jika admin mencoba mem-bypass antrean secara manual ke dokter
        if ($request->status === 'diproses_dokter') {
            // Cukup pastikan data vital sign tidak kosong
            if (empty($pasien->berat_badan) || empty($pasien->keluhan)) {
                return back()->with('error', 'Gagal! Petugas belum mengisi data pemeriksaan awal.');
            }
        }

        // Eksekusi perubahan status
        $pasien->status = $request->status;
        $pasien->save();

        return back()->with('success', 'Status pendaftaran pasien berhasil diubah.');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranPoli;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PendaftaranController extends Controller
{
    /**
     * Menampilkan daftar antrean pasien.
     */
    public function index(Request $request)
    {
        // Eager loading 'dokter' untuk menghindari N+1 query problem
        $query = PendaftaranPoli::with('dokter')->latest();

        // ==============================================================
        // PERBAIKAN LOGIKA:
        // Filter tanggal HANYA berjalan jika input tanggal diisi.
        // Jika dikosongkan (di-clear), maka query whereDate tidak akan dieksekusi
        // sehingga semua data dari awal akan muncul.
        // ==============================================================
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

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

        // Menggunakan paginate(10) untuk membatasi maksimal 10 baris per halaman
        $pendaftaran = $query->paginate(10);

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

        if ($request->status === 'diproses_dokter') {
            if (empty($pasien->berat_badan) || empty($pasien->keluhan)) {
                return back()->with('error', 'Gagal! Petugas belum mengisi data pemeriksaan awal.');
            }
        }

        $pasien->status = $request->status;
        $pasien->save();

        return back()->with('success', 'Status pendaftaran pasien berhasil diubah.');
    }
}
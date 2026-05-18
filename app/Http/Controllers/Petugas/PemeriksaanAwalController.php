<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PendaftaranPoli;
use App\Models\Kunjungan;      
use App\Models\RekamMedis;   

class PemeriksaanAwalController extends Controller
{
  
    public function index()
    {
        // Hanya menampilkan pasien dengan status 'menunggu_petugas'
        $pasien = PendaftaranPoli::where('status', 'menunggu_petugas')
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('petugas.pemeriksaan_awal.index', compact('pasien'));
    }

    /**
     * Menampilkan form input pemeriksaan awal (Triage / Vital Sign)
     */
    public function edit($id)
    {
        // 1. Ambil data pendaftaran poli berdasarkan ID yang di-request
        $pasien = PendaftaranPoli::findOrFail($id);

        /**
         * 2. Ambil data riwayat Kunjungan dan Rekam Medis untuk Accordion sebelah kanan.
         * 
         * Catatan Asumsi: 
         * - Jika tabel `pendaftaran_poli` Anda memiliki kolom `pasien_id` / `id_pasien`, 
         *   Gunakan: $pasien->pasien_id
         * - Jika Anda menggunakan relasi Eloquent (misal: $pasien->pasien->id), 
         *   Silakan sesuaikan bagian di bawah ini.
         */
        $pasienId = $pasien->pasien_id ?? $pasien->id_pasien ?? null;

        if ($pasienId) {
            // Ambil riwayat kunjungan pasien ini beserta relasi pembayarannya
            $kunjungan = Kunjungan::where('pasien_id', $pasienId)
                            ->with('pembayaran')
                            ->orderBy('created_at', 'desc')
                            ->get();

            // Ambil riwayat rekam medis pasien ini beserta relasi pendaftarannya
            $rekamMedis = RekamMedis::where('pasien_id', $pasienId)
                            ->with('pendaftaran')
                            ->orderBy('created_at', 'desc')
                            ->get();
        } else {
            // Antispasi jika data pasien lama/kosong, buat koleksi kosong agar view tidak crash
            $kunjungan = collect();
            $rekamMedis = collect();
        }

        // 3. Oper semua variabel yang dibutuhkan oleh view edit.blade.php
        return view('petugas.pemeriksaan_awal.edit', compact('pasien', 'kunjungan', 'rekamMedis'));
    }

    /**
     * Menyimpan data pemeriksaan awal dan meneruskan pasien ke dokter
     */
    public function update(Request $request, $id)
    {
        // 1. Validasi input (termasuk tensi yang baru ditambahkan)
        $request->validate([
            'berat_badan' => 'required|numeric',
            'tinggi_badan' => 'required|numeric',
            'tensi' => 'required|string|max:20',
            'keluhan' => 'required|string',
        ]);

        // 2. Ambil data pasien / pendaftaran
        $pasien = PendaftaranPoli::findOrFail($id);

        // 3. Masukkan data pemeriksaan fisik ke database
        $pasien->berat_badan = $request->berat_badan;
        $pasien->tinggi_badan = $request->tinggi_badan;
        $pasien->tensi = $request->tensi;
        $pasien->keluhan = $request->keluhan;

        // 4. PERUBAHAN ALUR KERJA (Workflow): 
        // Pasien langsung diteruskan ke dokter tanpa persetujuan admin
        $pasien->status = 'diproses_dokter';

        // 5. Simpan perubahan
        $pasien->save();

        // 6. Redirect kembali ke halaman antrean petugas dengan pesan sukses
        return redirect()
            ->route('petugas.pemeriksaan_awal.index')
            ->with('success', 'Pemeriksaan awal berhasil disimpan. Pasien telah diteruskan ke antrean Dokter.');
    }
}
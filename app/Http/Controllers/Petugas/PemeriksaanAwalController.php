<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PendaftaranPoli;

class PemeriksaanAwalController extends Controller
{
    /**
     * Menampilkan daftar pasien yang menunggu pemeriksaan awal oleh petugas
     */
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
        $pasien = PendaftaranPoli::findOrFail($id);

        return view('petugas.pemeriksaan_awal.edit', compact('pasien'));
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

        // 2. Ambil data pasien
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
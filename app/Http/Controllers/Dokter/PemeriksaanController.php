<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PendaftaranPoli;
use App\Models\RekamMedis;
// use App\Models\Pembayaran;


class PemeriksaanController extends Controller
{
    // DAFTAR PASIEN YANG SIAP DIPERIKSA
    public function index()
    {
        $pasien = PendaftaranPoli::where('status', 'diproses')
            ->orderBy('created_at')
            ->get();

        return view('dokter.pasien', compact('pasien'));
    }

    // FORM PEMERIKSAAN
    public function show($id)
    {
        $pasien = PendaftaranPoli::findOrFail($id);
        return view('dokter.pemeriksaan', compact('pasien'));
    }

    // SIMPAN HASIL PEMERIKSAAN
    public function store($id, Request $request)
    {
        $request->validate([
            'keluhan'   => 'required',
            'diagnosis' => 'required',
            'tindakan'  => 'required',
            'resep'     => 'nullable'
        ]);

        RekamMedis::create([
            'pendaftaran_id' => $id,
            'dokter_id'      => auth()->id(),
            'keluhan'        => $request->keluhan,
            'diagnosis'      => $request->diagnosis,
            'tindakan'       => $request->tindakan,
            'resep'          => $request->resep,
        ]);

        PendaftaranPoli::where('id', $id)->update([
            'status' => 'selesai'
        ]);

        return redirect()->route('dokter.pasien')
            ->with('success', 'Pemeriksaan berhasil disimpan');
    }

    // RIWAYAT REKAM MEDIS DOKTER
    public function rekamMedis()
    {
        $data = RekamMedis::with('pendaftaran')
            ->where('dokter_id', auth()->id())
            ->latest()
            ->get();

        return view('dokter.rekammedis', compact('data'));
    }
}

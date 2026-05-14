<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PendaftaranPoli;

class PemeriksaanAwalController extends Controller
{
    public function index()
    {
        $pasien = PendaftaranPoli::where('status', 'menunggu_petugas')
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('petugas.pemeriksaan_awal.index', compact('pasien'));
    }

    public function edit($id)
    {
        $pasien = PendaftaranPoli::findOrFail($id);

        return view('petugas.pemeriksaan_awal.edit', compact('pasien'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'berat_badan' => 'required',
            'tinggi_badan' => 'required',
            'keluhan' => 'required',
        ]);

        $pasien = PendaftaranPoli::findOrFail($id);

        $pasien->berat_badan = $request->berat_badan;
        $pasien->tinggi_badan = $request->tinggi_badan;
        $pasien->keluhan = $request->keluhan;

        // setelah petugas input -> masuk admin
        $pasien->status = 'menunggu_admin';

        $pasien->save();

        return redirect()
            ->route('petugas.pemeriksaan_awal.index')
            ->with('success', 'Pemeriksaan awal berhasil disimpan');
    }
}
<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use App\Models\PendaftaranPoli;
use Illuminate\Http\Request;

class RekamMedisController extends Controller
{
    // Pastikan pada fungsi index/tampil data, Anda memanggilnya seperti ini:
    // $data = RekamMedis::with('pendaftaran')->latest()->get();

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required',
            'keluhan'        => 'required',
            'diagnosis'      => 'required',
            'tindakan'       => 'required',
            'resep'          => 'nullable',
        ]);

        // simpan rekam medis
        RekamMedis::create([
            'pendaftaran_id' => $request->pendaftaran_id,
            'dokter_id'      => auth()->id(),
            'keluhan'        => $request->keluhan,
            'diagnosis'      => $request->diagnosis,
            'tindakan'       => $request->tindakan,
            'resep'          => $request->resep,
        ]);

        // ubah status pasien menjadi selesai
        PendaftaranPoli::where('id', $request->pendaftaran_id)
            ->update([
                'status' => 'selesai'
            ]);

        return redirect()
            ->route('dokter.pasien')
            ->with('success', 'Rekam medis berhasil disimpan');
    }
}
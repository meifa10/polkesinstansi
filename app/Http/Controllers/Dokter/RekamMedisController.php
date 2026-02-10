<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use Illuminate\Http\Request;

class RekamMedisController extends Controller
{
    public function store(Request $request)
    {
        RekamMedis::create([
            'pendaftaran_id' => $request->pendaftaran_id,
            'dokter_id' => auth()->id(),
            'keluhan' => $request->keluhan,
            'diagnosis' => $request->diagnosis,
            'tindakan' => $request->tindakan,
            'resep' => $request->resep,
        ]);

        return back()->with('success','Rekam medis berhasil disimpan');
    }
}

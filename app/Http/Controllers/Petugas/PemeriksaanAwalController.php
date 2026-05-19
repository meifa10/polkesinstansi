<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PendaftaranPoli;
use App\Models\RekamMedis;

class PemeriksaanAwalController extends Controller
{
    
public function index(Request $request)
    {
        
        $query = PendaftaranPoli::with('pasien')
                    ->where('status', 'menunggu_petugas')
                    ->orderBy('created_at', 'asc');

        if ($request->has('q') && $request->q != '') {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('nama_pasien', 'like', '%' . $search . '%')
                  ->orWhere('poli', 'like', '%' . $search . '%')
                  ->orWhereHas('pasien', function($qPasien) use ($search) {
                      $qPasien->where('nik', 'like', '%' . $search . '%');
                  })
                  ->orWhere('nik', 'like', '%' . $search . '%');
            });
        }

        $pasien = $query->paginate(10)->withQueryString();

        return view('petugas.pemeriksaan_awal.index', compact('pasien'));
    }

  
    public function edit($id)
    {
        $pasien = PendaftaranPoli::findOrFail($id);

        $pasienId = $pasien->pasien_id ?? $pasien->id_pasien ?? null;

        $kunjungan = collect();
        if ($pasienId) {
            $kunjungan = PendaftaranPoli::where('pasien_id', $pasienId)
                            ->orWhere('id_pasien', $pasienId)
                            ->orderBy('created_at', 'desc')
                            ->get();
        }

        $rekamMedis = collect();
        if ($pasienId && class_exists('App\Models\RekamMedis')) {
            $rekamMedis = RekamMedis::where('pasien_id', $pasienId)
                            ->orderBy('created_at', 'desc')
                            ->get();
        }

        return view('petugas.pemeriksaan_awal.edit', compact('pasien', 'kunjungan', 'rekamMedis'));
    }

 
    public function update(Request $request, $id)
    {
        $request->validate([
            'berat_badan'  => 'required|numeric',
            'tinggi_badan' => 'required|numeric',
            'tensi'        => 'required|string|max:20',
            'keluhan'      => 'required|string',
        ]);

        $pasien = PendaftaranPoli::findOrFail($id);

        $pasien->berat_badan  = $request->berat_badan;
        $pasien->tinggi_badan = $request->tinggi_badan;
        $pasien->tensi        = $request->tensi;
        $pasien->keluhan      = $request->keluhan;
        $pasien->status       = 'diproses_dokter';
        $pasien->save();

        return redirect()
            ->route('petugas.pemeriksaan_awal.index')
            ->with('success', 'Pemeriksaan awal berhasil disimpan. Pasien telah diteruskan ke antrean Dokter.');
    }
}
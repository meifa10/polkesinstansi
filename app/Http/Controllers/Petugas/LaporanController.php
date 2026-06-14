<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PendaftaranPoli;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function diagnosa(Request $request)
    {
        // Mengambil daftar pasien unik yang sudah selesai berobat
        $query = PendaftaranPoli::where('status', 'selesai');

        if ($request->filled('q')) {
            $query->where('nama_pasien', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('poli')) {
            $query->where('poli', $request->poli);
        }

        $pasien = $query->select('id', 'nama_pasien', 'no_identitas', 'poli')
            ->distinct()
            ->paginate(10)
            ->withQueryString();

        return view('petugas.laporan-diagnosa', compact('pasien'));
    }

    public function showRiwayat(Request $request, $id)
    {
        $pasien = PendaftaranPoli::findOrFail($id);
        
        // Mengambil semua rekam medis untuk pasien tersebut
        $riwayat = \App\Models\RekamMedis::whereHas('pendaftaran', function($q) use ($pasien) {
            $q->where('nama_pasien', $pasien->nama_pasien);
        })->latest()->paginate(10);

        return view('petugas.laporan-show', compact('pasien', 'riwayat'));
    }
}
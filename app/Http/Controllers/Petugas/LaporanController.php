<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PendaftaranPoli;

class LaporanController extends Controller
{
    public function diagnosa(Request $request)
    {
        $query = PendaftaranPoli::where('status', 'selesai');

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_pasien', 'like', '%' . $request->q . '%')
                  ->orWhere('no_identitas', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->filled('poli')) {
            $query->where('poli', $request->poli);
        }

        $ids = (clone $query)
            ->selectRaw('MAX(id) as id')
            ->groupBy('no_identitas')
            ->pluck('id');

        $pasien = PendaftaranPoli::with('rekamMedis')
            ->whereIn('id', $ids)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('petugas.laporan-diagnosa.index', compact('pasien'));
    }

    public function show(Request $request, $id)
    {
        $pasien = PendaftaranPoli::findOrFail($id);

        $riwayat = PendaftaranPoli::with('rekamMedis')
            ->where('no_identitas', $pasien->no_identitas)
            ->where('status', 'selesai')
            ->when($request->tanggal, function ($q) use ($request) {
                $q->whereDate('created_at', $request->tanggal);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'petugas.laporan-diagnosa.show',
            compact(
                'pasien',
                'riwayat'
            )
        );
    }
}
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
        $query = PendaftaranPoli::with('rekamMedis')
            ->where('status', 'selesai');

        // Filter tanggal hanya jika diisi
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // Filter poli hanya jika dipilih
        if ($request->filled('poli')) {
            $query->where('poli', $request->poli);
        }

        $laporanSemua = (clone $query)
            ->latest()
            ->get();

        $laporan = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('petugas.laporan-diagnosa', compact(
            'laporan',
            'laporanSemua'
        ));
    }
}
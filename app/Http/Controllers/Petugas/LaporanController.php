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
        $tanggal = $request->filled('tanggal')
            ? $request->tanggal
            : Carbon::today()->format('Y-m-d');

        $query = PendaftaranPoli::with('rekamMedis')
            ->where('status', 'selesai')
            ->whereDate('created_at', $tanggal);

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
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

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        } else {
            $query->whereDate('created_at', Carbon::today());
        }

        if ($request->filled('poli')) {
            $query->where('poli', $request->poli);
        }

        $laporanSemua = $query->clone()->latest()->get();
        $laporan = $query->latest()->paginate(10)->withQueryString();

        return view('petugas.laporan-diagnosa', compact('laporan', 'laporanSemua'));
    }
}
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

        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $startDate = Carbon::parse($request->tgl_mulai)->startOfDay();
            $endDate = Carbon::parse($request->tgl_selesai)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } else {
            $query->whereDate('created_at', Carbon::today());
        }

        if ($request->filled('poli')) {
            $query->where('poli', $request->poli);
        }

        $laporan = $query->latest()->get();

        return view('petugas.laporan-diagnosa', compact('laporan'));
    }
}
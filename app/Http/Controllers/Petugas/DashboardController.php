<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranPoli;

class DashboardController extends Controller
{
    public function index()
    {
        $today = today();

        $totalPasien = PendaftaranPoli::whereDate('created_at', $today)->count();

        $menungguPetugas = PendaftaranPoli::where('status', 'menunggu_petugas')->count();

        $sudahDiperiksa = PendaftaranPoli::where('status', 'menunggu_admin')->count();

        $pasienUmum = PendaftaranPoli::whereDate('created_at', $today)
            ->where('jenis_pasien', 'UMUM')
            ->count();

        $pasienJkn = PendaftaranPoli::whereDate('created_at', $today)
            ->where('jenis_pasien', 'JKN')
            ->count();

        $pasienTerbaru = PendaftaranPoli::latest()
            ->take(10)
            ->get();

        return view('petugas.dashboard', compact(
            'totalPasien',
            'menungguPetugas',
            'sudahDiperiksa',
            'pasienUmum',
            'pasienJkn',
            'pasienTerbaru'
        ));
    }
}
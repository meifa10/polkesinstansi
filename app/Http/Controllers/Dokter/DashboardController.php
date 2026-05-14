<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranPoli;
use App\Models\RekamMedis;

class DashboardController extends Controller
{
    public function index()
    {
        // pasien yang siap diperiksa dokter
        $pasien = PendaftaranPoli::where('status', 'diproses_dokter')
            ->orderBy('created_at', 'asc')
            ->get();

        $today = today();

        // total pasien hari ini
        $totalPasienHariIni = PendaftaranPoli::whereDate('created_at', $today)
            ->count();

        // pasien umum
        $totalPasienUmum = PendaftaranPoli::whereDate('created_at', $today)
            ->where('jenis_pasien', 'UMUM')
            ->count();

        // pasien JKN/BPJS
        $totalPasienJKN = PendaftaranPoli::whereDate('created_at', $today)
            ->where('jenis_pasien', 'JKN')
            ->count();

        // total rekam medis
        $totalRekamMedis = RekamMedis::count();

        return view('dokter.dashboard', compact(
            'pasien',
            'totalPasienHariIni',
            'totalPasienUmum',
            'totalPasienJKN',
            'totalRekamMedis'
        ));
    }
}
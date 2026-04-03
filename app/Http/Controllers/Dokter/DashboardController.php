<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranPoli;
use App\Models\RekamMedis;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // ===============================
        // PASIEN SIAP DIPROSES
        // ===============================
        $pasien = PendaftaranPoli::where('status', 'diproses')
            ->orderBy('created_at')
            ->get();

        // ===============================
        // TOTAL REKAM MEDIS
        // ===============================
        $totalRekamMedis = RekamMedis::count();

        // ===============================
        // STATISTIK TAMBAHAN
        // ===============================
        $totalPasienHariIni = $pasien->count();

        $totalPasienUmum = $pasien
            ->where('jenis_pasien', 'umum')
            ->count();

        $totalPasienBPJS = $pasien
            ->where('jenis_pasien', 'bpjs')
            ->count();

        return view('dokter.dashboard', compact(
            'pasien',
            'totalRekamMedis',
            'totalPasienHariIni',
            'totalPasienUmum',
            'totalPasienBPJS'
        ));
    }
}
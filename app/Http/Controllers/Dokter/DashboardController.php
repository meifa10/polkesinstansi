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
        
        $pasien = PendaftaranPoli::where('status', 'diproses')
            ->orderBy('created_at', 'asc')
            ->get();

        $today = today();

        $totalPasienHariIni = PendaftaranPoli::whereDate('created_at', $today)->count();

        $totalPasienUmum = PendaftaranPoli::whereDate('created_at', $today)
            ->whereRaw('LOWER(jenis_pasien) = ?', ['umum'])
            ->count();

        $totalPasienBPJS = PendaftaranPoli::whereDate('created_at', $today)
            ->whereRaw('LOWER(jenis_pasien) = ?', ['bpjs'])
            ->count();

        $totalPasienBaru = PendaftaranPoli::whereDate('created_at', $today)
            ->whereRaw('LOWER(jenis_pasien) = ?', ['baru'])
            ->count();

        $totalRekamMedis = RekamMedis::count();

        return view('dokter.dashboard', compact(
            'pasien',
            'totalRekamMedis',
            'totalPasienHariIni',
            'totalPasienUmum',
            'totalPasienBPJS',
            'totalPasienBaru'
        ));
    }
}
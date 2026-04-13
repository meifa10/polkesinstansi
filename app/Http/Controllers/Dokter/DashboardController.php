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
        $pasien = PendaftaranPoli::whereIn('status', ['diproses', 'menunggu'])
            ->orderBy('created_at')
            ->get();

        
        $totalPasienHariIni = PendaftaranPoli::whereDate('created_at', today())->count();
        
        $totalPasienUmum = PendaftaranPoli::whereDate('created_at', today())
            ->where('jenis_pasien', 'LIKE', '%umum%')
            ->count();

        $totalPasienBPJS = PendaftaranPoli::whereDate('created_at', today())
            ->where('jenis_pasien', 'LIKE', '%bpjs%')
            ->count();

        
        $totalPasienBaru = PendaftaranPoli::whereDate('created_at', today())
            ->where('jenis_pasien', 'LIKE', '%baru%') 
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
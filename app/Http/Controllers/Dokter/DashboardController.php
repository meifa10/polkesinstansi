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
        $today = today();
        $dokterId = Auth::id();
        $isSuperDokter = Auth::user()->akses_semua_poli == 1; // Cek status Super Dokter

        // 1. Daftar Pasien yang siap diperiksa (Status: diproses_dokter)
        if ($isSuperDokter) {
            $pasien = PendaftaranPoli::where('status', 'diproses_dokter')
                ->orderBy('created_at', 'asc')
                ->get();
        } else {
            $pasien = PendaftaranPoli::where('dokter_id', $dokterId)
                ->where('status', 'diproses_dokter')
                ->orderBy('created_at', 'asc')
                ->get();
        }

        // 2. Total pasien hari ini
        if ($isSuperDokter) {
            $totalPasienHariIni = PendaftaranPoli::whereDate('created_at', $today)->count();
        } else {
            $totalPasienHariIni = PendaftaranPoli::where('dokter_id', $dokterId)
                ->whereDate('created_at', $today)
                ->count();
        }

        // 3. Pasien Umum hari ini
        if ($isSuperDokter) {
            $totalPasienUmum = PendaftaranPoli::whereDate('created_at', $today)
                ->where('jenis_pasien', 'UMUM')
                ->count();
        } else {
            $totalPasienUmum = PendaftaranPoli::where('dokter_id', $dokterId)
                ->whereDate('created_at', $today)
                ->where('jenis_pasien', 'UMUM')
                ->count();
        }

        // 4. Pasien JKN/BPJS hari ini
        if ($isSuperDokter) {
            $totalPasienJKN = PendaftaranPoli::whereDate('created_at', $today)
                ->where('jenis_pasien', 'JKN')
                ->count();
        } else {
            $totalPasienJKN = PendaftaranPoli::where('dokter_id', $dokterId)
                ->whereDate('created_at', $today)
                ->where('jenis_pasien', 'JKN')
                ->count();
        }

        // 5. Total rekam medis
        if ($isSuperDokter) {
            $totalRekamMedis = RekamMedis::count(); // Ambil semua data
        } else {
            $totalRekamMedis = RekamMedis::where('dokter_id', $dokterId)->count();
        }

        return view('dokter.dashboard', compact(
            'pasien',
            'totalPasienHariIni',
            'totalPasienUmum',
            'totalPasienJKN',
            'totalRekamMedis'
        ));
    }
}
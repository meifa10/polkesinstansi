<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranPoli;
use App\Models\RekamMedis;
use App\Models\JadwalDokter;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $tahunIni = date('Y');


        $pendaftaranHariIni = PendaftaranPoli::whereDate('created_at', today())->count();

        $totalPasien = Patient::count();

        $totalDokter = User::where('role', 'dokter')->count();

        $dokterAktif = JadwalDokter::where('status', 'aktif')
            ->distinct('dokter_id')
            ->count('dokter_id');

        $totalPemeriksaan = RekamMedis::count();


        $bulan = [];
        $dataKunjungan = [];
        $dataPemeriksaan = [];
        $dataDokter = [];

        for ($i = 1; $i <= 12; $i++) {

            $bulan[] = Carbon::create()->month($i)->translatedFormat('F');

            $dataKunjungan[] = PendaftaranPoli::whereMonth('created_at', $i)
                ->whereYear('created_at', $tahunIni)
                ->count();

            $dataPemeriksaan[] = RekamMedis::whereMonth('created_at', $i)
                ->whereYear('created_at', $tahunIni)
                ->count();

            $dataDokter[] = $dokterAktif;
        }

        return view('admin.dashboard.index', compact(
            'pendaftaranHariIni',
            'totalPasien',
            'totalDokter',
            'dokterAktif',
            'totalPemeriksaan',
            'bulan',
            'dataKunjungan',
            'dataPemeriksaan',
            'dataDokter'
        ));
    }
}
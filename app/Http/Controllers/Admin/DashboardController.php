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

        // Statistik Card
        $pendaftaranHariIni = PendaftaranPoli::whereDate('created_at', today())->count();
        $totalPasien = Patient::count();
        $totalDokter = User::where('role', 'dokter')->count();
        
        $dokterAktif = JadwalDokter::where('status', 'aktif')
            ->distinct('dokter_id')
            ->count('dokter_id');
            
        $totalPemeriksaan = RekamMedis::count();

        // Variabel untuk Chart
        $bulan = [];
        $dataPoliUmum = [];
        $dataPoliGigi = [];
        $dataPoliKiaKb = [];

        for ($i = 1; $i <= 12; $i++) {
            $bulan[] = Carbon::create()->month($i)->translatedFormat('F');

            // PERBAIKAN: Menggunakan where() karena 'poli' adalah nama kolom, bukan relasi.

            // Hitung Kunjungan Poli Umum
            $dataPoliUmum[] = PendaftaranPoli::whereMonth('created_at', $i)
                ->whereYear('created_at', $tahunIni)
                ->where('poli', 'like', '%Umum%')
                ->count();

            // Hitung Kunjungan Poli Gigi
            $dataPoliGigi[] = PendaftaranPoli::whereMonth('created_at', $i)
                ->whereYear('created_at', $tahunIni)
                ->where('poli', 'like', '%Gigi%')
                ->count();

            // Hitung Kunjungan Poli KIA & KB
            $dataPoliKiaKb[] = PendaftaranPoli::whereMonth('created_at', $i)
                ->whereYear('created_at', $tahunIni)
                ->where(function($query) {
                    $query->where('poli', 'like', '%KIA%')
                          ->orWhere('poli', 'like', '%KB%');
                })->count();
        }

        return view('admin.dashboard.index', compact(
            'pendaftaranHariIni',
            'totalPasien',
            'totalDokter',
            'dokterAktif',
            'totalPemeriksaan',
            'bulan',
            'dataPoliUmum',
            'dataPoliGigi',
            'dataPoliKiaKb'
        ));
    }
}
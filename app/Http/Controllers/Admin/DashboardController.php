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

        /*
        |--------------------------------------------------------------------------
        | STATISTIK KARTU DASHBOARD
        |--------------------------------------------------------------------------
        */

        // Pendaftaran hari ini
        $pendaftaranHariIni = PendaftaranPoli::whereDate('created_at', today())->count();

        // Total pasien
        $totalPasien = Patient::count();

        // Total dokter terdaftar (role dokter)
        $totalDokter = User::where('role', 'dokter')->count();

        // 🔥 Dokter aktif berdasarkan tabel jadwal_dokter
        // Hitung dokter unik dengan status aktif
        $dokterAktif = JadwalDokter::where('status', 'aktif')
            ->distinct('dokter_id')
            ->count('dokter_id');

        // Total pemeriksaan
        $totalPemeriksaan = RekamMedis::count();


        /*
        |--------------------------------------------------------------------------
        | DATA GRAFIK PER BULAN
        |--------------------------------------------------------------------------
        */

        $bulan = [];
        $dataKunjungan = [];
        $dataPemeriksaan = [];
        $dataDokter = [];

        for ($i = 1; $i <= 12; $i++) {

            // Nama bulan (Indonesia)
            $bulan[] = Carbon::create()->month($i)->translatedFormat('F');

            // Total kunjungan per bulan
            $dataKunjungan[] = PendaftaranPoli::whereMonth('created_at', $i)
                ->whereYear('created_at', $tahunIni)
                ->count();

            // Total pemeriksaan per bulan
            $dataPemeriksaan[] = RekamMedis::whereMonth('created_at', $i)
                ->whereYear('created_at', $tahunIni)
                ->count();

            // 🔥 Dokter aktif (flat sesuai jumlah aktif sekarang)
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
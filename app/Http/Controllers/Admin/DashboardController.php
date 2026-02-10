<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranPoli;
use App\Models\RekamMedis;
use App\Models\JadwalDokter;
use App\Models\Patient;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // =====================
        // STAT KARTU
        // =====================
        $pendaftaranHariIni = PendaftaranPoli::whereDate('created_at', today())->count();
        $totalPasien        = Patient::count();
        $dokterAktif        = JadwalDokter::where('status', 'aktif')->count();
        $totalPemeriksaan   = RekamMedis::count();

        // =====================
        // DATA GRAFIK (PER BULAN)
        // =====================
        $bulan = [];
        $dataKunjungan = [];
        $dataPemeriksaan = [];
        $dataDokter = [];

        for ($i = 1; $i <= 12; $i++) {
            $bulan[] = Carbon::create()->month($i)->translatedFormat('F');

            $dataKunjungan[] = PendaftaranPoli::whereMonth('created_at', $i)
                ->whereYear('created_at', date('Y'))
                ->count();

            $dataPemeriksaan[] = RekamMedis::whereMonth('created_at', $i)
                ->whereYear('created_at', date('Y'))
                ->count();

            // dokter aktif (flat tapi valid)
            $dataDokter[] = JadwalDokter::where('status', 'aktif')->count();
        }

        return view('admin.dashboard.index', compact(
            'pendaftaranHariIni',
            'totalPasien',
            'dokterAktif',
            'totalPemeriksaan',
            'bulan',
            'dataKunjungan',
            'dataPemeriksaan',
            'dataDokter'
        ));
    }
}

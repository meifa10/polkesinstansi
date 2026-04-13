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
        // Ambil pasien dengan status 'diproses' atau 'menunggu'
        $pasien = PendaftaranPoli::whereIn('status', ['diproses', 'menunggu'])
            ->orderBy('created_at')
            ->get();

        $totalRekamMedis = RekamMedis::count();

        // PERBAIKAN: Hapus spasi pada nama variabel
        $totalPasienHariIni = $pasien->count(); 

        // Gunakan filter untuk menangani masalah huruf besar/kecil di database
        $totalPasienUmum = $pasien->filter(function ($item) {
            return strtolower($item->jenis_pasien) == 'umum';
        })->count();

        $totalPasienBPJS = $pasien->filter(function ($item) {
            return strtolower($item->jenis_pasien) == 'bpjs';
        })->count();

        // Tambahkan variabel ini agar tidak error di Blade (Pasien Baru)
        $totalPasienBaru = $pasien->filter(function ($item) {
            return strtolower($item->jenis_pasien) == 'baru';
        })->count();

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
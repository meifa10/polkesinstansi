<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranPoli;
use App\Models\RekamMedis;
use Illuminate\Support\Facades\Auth; // WAJIB ditambahkan agar bisa membaca siapa yang login

class DashboardController extends Controller
{
    public function index()
    {
        $today = today();
        $dokterId = Auth::id(); // Mengambil ID dari akun dokter yang sedang aktif

        // 1. Daftar Pasien yang siap diperiksa KHUSUS untuk dokter ini
        $pasien = PendaftaranPoli::where('dokter_id', $dokterId)
            ->where('status', 'diproses_dokter')
            ->orderBy('created_at', 'asc')
            ->get();

        // 2. Total pasien hari ini KHUSUS untuk antrean dokter ini
        $totalPasienHariIni = PendaftaranPoli::where('dokter_id', $dokterId)
            ->whereDate('created_at', $today)
            ->count();

        // 3. Pasien umum KHUSUS untuk dokter ini
        $totalPasienUmum = PendaftaranPoli::where('dokter_id', $dokterId)
            ->whereDate('created_at', $today)
            ->where('jenis_pasien', 'UMUM')
            ->count();

        // 4. Pasien JKN/BPJS KHUSUS untuk dokter ini
        $totalPasienJKN = PendaftaranPoli::where('dokter_id', $dokterId)
            ->whereDate('created_at', $today)
            ->where('jenis_pasien', 'JKN')
            ->count();

        // 5. Total rekam medis yang pernah ditangani oleh dokter ini saja
        $totalRekamMedis = RekamMedis::where('dokter_id', $dokterId)->count();

        return view('dokter.dashboard', compact(
            'pasien',
            'totalPasienHariIni',
            'totalPasienUmum',
            'totalPasienJKN',
            'totalRekamMedis'
        ));
    }
}
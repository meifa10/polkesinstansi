<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranPoli;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = today();

        // 1. Ambil Statistik Utama
        $totalPasien = PendaftaranPoli::whereDate('created_at', $today)->count();

        // Jumlah pasien yang masih menunggu di meja petugas
        $menungguPetugas = PendaftaranPoli::where('status', 'menunggu_petugas')->count();

        // Jumlah pasien yang sudah divalidasi (status menunggu admin/pembayaran atau selesai)
        $sudahDiperiksa = PendaftaranPoli::whereIn('status', ['menunggu_admin', 'selesai'])->count();

        // 2. AMBIL DATA DISTRIBUSI POLI (Untuk Chart)
        // Mengambil nama poli dan jumlahnya berdasarkan pendaftaran hari ini
        $statistikPoli = PendaftaranPoli::select('poli', DB::raw('count(*) as total'))
            ->whereDate('created_at', $today)
            ->groupBy('poli')
            ->get();

        // Kita pecah menjadi dua array untuk dikirim ke Chart.js
        $dataPoli = $statistikPoli->pluck('poli')->toArray(); 
        $jumlahPasienPoli = $statistikPoli->pluck('total')->toArray();

        // 3. Ambil 10 Antrean Terbaru untuk Tabel
        $pasienTerbaru = PendaftaranPoli::latest()
            ->take(10)
            ->get();

        // 4. Return ke view dengan variabel yang dibutuhkan
        return view('petugas.dashboard', compact(
            'totalPasien',
            'menungguPetugas',
            'sudahDiperiksa',
            'dataPoli',
            'jumlahPasienPoli',
            'pasienTerbaru'
        ));
    }
}
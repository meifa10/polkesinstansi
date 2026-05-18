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

        $totalPasien = PendaftaranPoli::whereDate('created_at', $today)->count();

        $menungguPetugas = PendaftaranPoli::where('status', 'menunggu_petugas')->count();

        $sudahDiperiksa = PendaftaranPoli::whereIn('status', ['menunggu_admin', 'selesai'])->count();

        $statistikPoli = PendaftaranPoli::select('poli', DB::raw('count(*) as total'))
            ->whereDate('created_at', $today)
            ->groupBy('poli')
            ->get();

        $dataPoli = $statistikPoli->pluck('poli')->toArray(); 
        $jumlahPasienPoli = $statistikPoli->pluck('total')->toArray();

        
        $pasienTerbaru = PendaftaranPoli::latest()
            ->paginate(5); 

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
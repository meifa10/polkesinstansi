<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PendaftaranPoli;

class LaporanController extends Controller
{
    public function index()
    {
        $laporan = PendaftaranPoli::where('status', 'selesai')
            ->select('nama_pasien', 'nik', 'poli')
            ->groupBy('nama_pasien', 'nik', 'poli')
            ->paginate(10);

        return view('petugas.index-pasien', compact('laporan'));
    }

    public function riwayat($nik)
    {
        $riwayat = PendaftaranPoli::with('rekamMedis')
            ->where('nik', $nik)
            ->where('status', 'selesai')
            ->orderBy('created_at', 'desc')
            ->get();

        $namaPasien = $riwayat->first()->nama_pasien ?? 'Tidak Ditemukan';

        return view('petugas.riwayat-pasien', compact('riwayat', 'namaPasien'));
    }
}
<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranPoli;

class DashboardController extends Controller
{
    public function index()
    {
        $pasien = PendaftaranPoli::where('status', 'diproses')
            ->orderBy('created_at')
            ->get();

        return view('dokter.dashboard', compact('pasien'));
    }
}

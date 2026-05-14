<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranPoli;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PendaftaranController extends Controller
{
    public function index(Request $request)
    {
        // Petugas harus bisa melihat dokter mana yang dituju pasien
        $query = PendaftaranPoli::with('dokter')
                    ->whereDate('created_at', Carbon::today())
                    ->latest();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where('nama_pasien', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pendaftaran = $query->get();

        return view('petugas.pendaftaran.index', compact('pendaftaran'));
    }
}
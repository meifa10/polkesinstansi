<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Obat; // Sesuaikan dengan nama Model Obat asli di proyek Anda
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        // Menarik data dari tabel obat yang sama seperti petugas
        if ($query) {
            $obat = Obat::where('nama_obat', 'LIKE', '%' . $query . '%')
                        ->orderBy('nama_obat', 'asc')
                        ->get();
        } else {
            $obat = Obat::orderBy('nama_obat', 'asc')->get();
        }

        return view('dokter.stok_obat', compact('obat'));
    }
}
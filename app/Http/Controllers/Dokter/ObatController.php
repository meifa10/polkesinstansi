<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        if ($query) {
            $obat = Obat::where('nama_obat', 'LIKE', '%' . $query . '%')
                        ->orderBy('nama_obat', 'asc')
                        ->paginate(5) 
                        ->withQueryString(); 
        } else {
            $obat = Obat::orderBy('nama_obat', 'asc')->paginate(5); 
        }

        return view('dokter.stok_obat', compact('obat'));
    }
}
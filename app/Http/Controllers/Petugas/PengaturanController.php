<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengaturan;

class PengaturanController extends Controller
{
    public function index()
    {
        $biayaDokter = Pengaturan::where('key', 'biaya_dokter')->first();
        $biayaAdmin = Pengaturan::where('key', 'biaya_admin')->first();

        return view('petugas.master-harga', compact('biayaDokter', 'biayaAdmin'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'biaya_dokter' => 'required|numeric|min:0',
            'biaya_admin' => 'required|numeric|min:0',
        ]);

        Pengaturan::where('key', 'biaya_dokter')->update(['value' => $request->biaya_dokter]);
        Pengaturan::where('key', 'biaya_admin')->update(['value' => $request->biaya_admin]);

        return redirect()->route('petugas.master_harga.index')->with('success', 'Tarif harga jasa berhasil diperbarui.');
    }
}
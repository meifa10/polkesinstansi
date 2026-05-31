<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
   
    public function index(Request $request)
    {
        $search = $request->query('q');

        $obat = Obat::query()
            ->when($search, function ($query, $search) {
                return $query->where('nama_obat', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->get();

        return view('petugas.stok_obat.index', compact('obat'));
    }

  
    public function store(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'harga'     => 'required|numeric|min:0',
            'stok'      => 'required|integer|min:0',
            'satuan'    => 'nullable|string|max:50',
        ]);

        Obat::create([
            'nama_obat' => $request->nama_obat,
            'harga'     => $request->harga,
            'stok'      => $request->stok,
            'satuan'    => $request->satuan,
        ]);

        return back()->with('success', 'Obat berhasil ditambahkan');
    }


    public function update(Request $request, $id)
    {
        $obat = Obat::findOrFail($id);

        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'harga'     => 'required|numeric|min:0',
            'stok'      => 'required|integer|min:0',
            'satuan'    => 'nullable|string|max:50',
        ]);

        $obat->update([
            'nama_obat' => $request->nama_obat,
            'harga'     => $request->harga,
            'stok'      => $request->stok,
            'satuan'    => $request->satuan,
        ]);

        return back()->with('success', 'Obat berhasil diupdate');
    }

  
    public function destroy($id)
    {
        $obat = Obat::findOrFail($id);
        $obat->delete();

        return back()->with('success', 'Obat berhasil dihapus');
    }
}
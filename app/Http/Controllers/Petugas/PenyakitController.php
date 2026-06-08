<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penyakit;

class PenyakitController extends Controller
{
    public function index(Request $request)
    {
        $query = Penyakit::query();

        if ($request->filled('q')) {
            $query->where('nama_penyakit', 'like', '%' . $request->q . '%')
                  ->orWhere('kode_icd10', 'like', '%' . $request->q . '%');
        }

        $penyakit = $query->orderBy('nama_penyakit', 'asc')->paginate(10)->withQueryString();
        return view('petugas.master-penyakit', compact('penyakit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_icd10' => 'required|string|unique:penyakits,kode_icd10',
            'nama_penyakit' => 'required|string',
            'poli_tujuan' => 'required|string'
        ]);

        Penyakit::create($request->all());

        return redirect()->route('petugas.master_penyakit.index')->with('success', 'Data penyakit berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_icd10' => 'required|string|unique:penyakits,kode_icd10,' . $id,
            'nama_penyakit' => 'required|string',
            'poli_tujuan' => 'required|string'
        ]);

        $penyakit = Penyakit::findOrFail($id);
        $penyakit->update($request->all());

        return redirect()->route('petugas.master_penyakit.index')->with('success', 'Data penyakit berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $penyakit = Penyakit::findOrFail($id);
        $penyakit->delete();

        return redirect()->route('petugas.master_penyakit.index')->with('success', 'Data penyakit berhasil dihapus.');
    }
}
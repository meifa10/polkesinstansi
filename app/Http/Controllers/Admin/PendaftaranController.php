<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranPoli;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{

    public function index(Request $request)
    {
        $query = PendaftaranPoli::orderBy('created_at', 'desc');

        if ($request->filled('q')) {
            $search = trim($request->q);
            $query->where(function ($q) use ($search) {
                $q->where('nama_pasien', 'like', "%{$search}%")
                  ->orWhere('no_identitas', 'like', "%{$search}%")
                  ->orWhere('poli', 'like', "%{$search}%");
            });
        }

        if ($request->filled('poli')) {
            $query->where('poli', $request->poli);
        }

        $pendaftaran = $query->get();

        return view('admin.pendaftaran.index', compact('pendaftaran'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diproses,selesai'
        ]);

        $data = PendaftaranPoli::findOrFail($id);
        $data->status = $request->status;
        $data->save();

        return back()->with('success', 'Status pasien berhasil diperbarui.');
    }
}
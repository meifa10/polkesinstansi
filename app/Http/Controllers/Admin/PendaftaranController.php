<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranPoli;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PendaftaranController extends Controller
{
    public function index(Request $request)
    {
        // Gunakan eager loading 'dokter' agar aplikasi tidak lambat
        $query = PendaftaranPoli::with('dokter')
                    ->whereDate('created_at', Carbon::today()) 
                    ->latest();

        // SEARCH
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nama_pasien', 'like', "%{$search}%")
                  ->orWhere('no_identitas', 'like', "%{$search}%");
            });
        }

        // FILTER POLI
        if ($request->filled('poli')) {
            $query->where('poli', $request->poli);
        }

        $pendaftaran = $query->get();

        return view('admin.pendaftaran.index', compact('pendaftaran'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required']);
        $data = PendaftaranPoli::findOrFail($id);
        $data->status = $request->status;
        $data->save();

        return back()->with('success', 'Status pasien berhasil diperbarui');
    }
}
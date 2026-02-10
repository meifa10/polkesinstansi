<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranPoli;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    /**
     * =========================
     * LIST PENDAFTARAN + SEARCH
     * =========================
     */
    public function index(Request $request)
    {
        $query = PendaftaranPoli::orderBy('created_at', 'desc');

        // 🔍 SEARCH (Nama / No Identitas / Poli)
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_pasien', 'like', '%' . $request->q . '%')
                  ->orWhere('no_identitas', 'like', '%' . $request->q . '%')
                  ->orWhere('poli', 'like', '%' . $request->q . '%')
                  ->orWhere('jenis_pasien', 'like', '%' . $request->q . '%');
            });
        }

        $pendaftaran = $query->get();

        return view('admin.pendaftaran.index', compact('pendaftaran'));
    }

    /**
     * =========================
     * UPDATE STATUS PENDAFTARAN
     * =========================
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diproses,selesai,ditolak'
        ]);

        $data = PendaftaranPoli::findOrFail($id);
        $data->status = $request->status;
        $data->save();

        return back()->with('success', 'Status pasien berhasil diperbarui.');
    }
}

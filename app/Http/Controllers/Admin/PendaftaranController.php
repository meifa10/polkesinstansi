<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranPoli;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    /**
     * =========================
     * LIST PENDAFTARAN + SEARCH + FILTER POLI
     * =========================
     */
    public function index(Request $request)
    {
        $query = PendaftaranPoli::orderBy('created_at', 'desc');

        /**
         * =========================
         * SEARCH (Nama / No Identitas / Poli / Jenis)
         * =========================
         */
        if ($request->filled('q')) {
            $search = trim($request->q);

            $query->where(function ($q) use ($search) {
                $q->where('nama_pasien', 'like', "%{$search}%")
                  ->orWhere('no_identitas', 'like', "%{$search}%")
                  ->orWhere('poli', 'like', "%{$search}%")
                  ->orWhere('jenis_pasien', 'like', "%{$search}%");
            });
        }

        /**
         * =========================
         * FILTER POLI 🔥
         * =========================
         */
        if ($request->filled('poli')) {
            $query->where('poli', $request->poli);
        }

        /**
         * =========================
         * GET DATA
         * =========================
         */
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
        /**
         * VALIDASI (TANPA DITOLAK)
         */
        $request->validate([
            'status' => 'required|in:menunggu,diproses,selesai'
        ]);

        /**
         * UPDATE DATA
         */
        $data = PendaftaranPoli::findOrFail($id);
        $data->status = $request->status;
        $data->save();

        return back()->with('success', 'Status pasien berhasil diperbarui.');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use Illuminate\Http\Request;

class PemeriksaanController extends Controller
{
    /**
     * =========================
     * LIST PEMERIKSAAN + SEARCH
     * =========================
     */
    public function index(Request $request)
    {
        $query = RekamMedis::with([
            'pendaftaran',
            'dokter'
        ])->latest();

        /**
         * 🔍 SEARCH
         * - Nama Pasien
         * - Poli
         * - Dokter
         * - Diagnosis
         * - Tindakan
         */
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {

                // search di tabel rekam_medis
                $q->where('diagnosis', 'like', '%' . $request->q . '%')
                  ->orWhere('tindakan', 'like', '%' . $request->q . '%');

            })->orWhereHas('pendaftaran', function ($q) use ($request) {

                // search di pendaftaran_poli
                $q->where('nama_pasien', 'like', '%' . $request->q . '%')
                  ->orWhere('poli', 'like', '%' . $request->q . '%');

            })->orWhereHas('dokter', function ($q) use ($request) {

                // search di users (dokter)
                $q->where('name', 'like', '%' . $request->q . '%');

            });
        }

        $pemeriksaan = $query->get();

        return view('admin.pemeriksaan.index', compact('pemeriksaan'));
    }
}

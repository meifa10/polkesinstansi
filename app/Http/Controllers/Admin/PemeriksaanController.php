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
        // ✅ BASE QUERY
        $query = RekamMedis::with([
            'pendaftaran',
            'dokter'
        ])->latest();

        /**
         * =========================
         * 🔍 SEARCH (FIXED)
         * =========================
         */
        if ($request->filled('q')) {

            $search = $request->q;

            $query->where(function ($main) use ($search) {

                /**
                 * 1️⃣ SEARCH DI REKAM MEDIS
                 */
                $main->where(function ($q) use ($search) {
                    $q->where('diagnosis', 'like', '%' . $search . '%')
                      ->orWhere('tindakan', 'like', '%' . $search . '%');
                });

                /**
                 * 2️⃣ SEARCH DI PENDAFTARAN (PASIEN & POLI)
                 */
                $main->orWhereHas('pendaftaran', function ($q) use ($search) {
                    $q->where('nama_pasien', 'like', '%' . $search . '%')
                      ->orWhere('poli', 'like', '%' . $search . '%');
                });

                /**
                 * 3️⃣ SEARCH DI DOKTER
                 */
                $main->orWhereHas('dokter', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });

            });
        }

        /**
         * =========================
         * EXECUTE QUERY
         * =========================
         */
        $pemeriksaan = $query->get();

        /**
         * =========================
         * RETURN VIEW
         * =========================
         */
        return view('admin.pemeriksaan.index', compact('pemeriksaan'));
    }
}
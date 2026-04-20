<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use Illuminate\Http\Request;

class PemeriksaanController extends Controller
{
   
    public function index(Request $request)
    {
       
        $query = RekamMedis::with([
            'pendaftaran',
            'dokter'
        ])->latest();


       
        if ($request->filled('q')) {

            $search = trim($request->q);

            $query->where(function ($main) use ($search) {

                // Rekam medis
                $main->where(function ($q) use ($search) {
                    $q->where('diagnosis', 'like', "%{$search}%")
                      ->orWhere('tindakan', 'like', "%{$search}%");
                });

                // Pasien & Poli
                $main->orWhereHas('pendaftaran', function ($q) use ($search) {
                    $q->where('nama_pasien', 'like', "%{$search}%")
                      ->orWhere('poli', 'like', "%{$search}%");
                });

                // Dokter
                $main->orWhereHas('dokter', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });

            });
        }


        if ($request->filled('poli')) {

            $query->whereHas('pendaftaran', function ($q) use ($request) {
                $q->where('poli', $request->poli);
            });
        }


        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {

            $query->whereBetween('created_at', [
                $request->tanggal_dari . ' 00:00:00',
                $request->tanggal_sampai . ' 23:59:59'
            ]);

        } elseif ($request->filled('tanggal_dari')) {

            $query->whereDate('created_at', $request->tanggal_dari);
        }


        $pemeriksaan = $query->get();

        return view('admin.pemeriksaan.index', compact('pemeriksaan'));
    }
}
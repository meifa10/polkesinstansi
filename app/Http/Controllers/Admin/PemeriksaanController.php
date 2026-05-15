<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use Illuminate\Http\Request;
use App\Exports\RekamMedisExport;
use Maatwebsite\Excel\Facades\Excel;

class PemeriksaanController extends Controller
{
    public function index(Request $request)
    {
        // Memastikan relasi pendaftaran (yang berisi keluhan) ikut terload
        $query = RekamMedis::with(['pendaftaran', 'dokter'])->latest();

        // Filter Pencarian
        if ($request->filled('q')) {
            $search = trim($request->q);
            $query->where(function ($main) use ($search) {
                $main->where('diagnosis', 'like', "%{$search}%")
                     ->orWhere('tindakan', 'like', "%{$search}%")
                     ->orWhere('keluhan', 'like', "%{$search}%") // Tambah filter keluhan
                     ->orWhereHas('pendaftaran', function ($q) use ($search) {
                         $q->where('nama_pasien', 'like', "%{$search}%")
                           ->orWhere('no_identitas', 'like', "%{$search}%");
                     });
            });
        }

        // Filter Poli
        if ($request->filled('poli')) {
            $query->whereHas('pendaftaran', function ($q) use ($request) {
                $q->where('poli', $request->poli);
            });
        }

        // Filter Tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // Fitur Download Excel
        if ($request->has('download')) {
            $dataExport = $query->get();
            return Excel::download(new RekamMedisExport($dataExport), 'Rekap-Medis-'.now()->format('d-m-Y').'.xlsx');
        }

        $pemeriksaan = $query->get();
        return view('admin.pemeriksaan.index', compact('pemeriksaan'));
    }
}
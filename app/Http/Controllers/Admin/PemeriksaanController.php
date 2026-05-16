<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use Illuminate\Http\Request;

class PemeriksaanController extends Controller
{
    public function index(Request $request)
    {
        // Load relasi
        $query = RekamMedis::with(['pendaftaran', 'dokter'])->latest();

        // Filter pencarian
        if ($request->filled('q')) {

            $search = trim($request->q);

            $query->where(function ($main) use ($search) {

                $main->where('diagnosis', 'like', "%{$search}%")
                    ->orWhere('tindakan', 'like', "%{$search}%")
                    ->orWhere('keluhan', 'like', "%{$search}%")
                    ->orWhereHas('pendaftaran', function ($q) use ($search) {

                        $q->where('nama_pasien', 'like', "%{$search}%")
                          ->orWhere('no_identitas', 'like', "%{$search}%");

                    });

            });
        }

        // Filter poli
        if ($request->filled('poli')) {

            $query->whereHas('pendaftaran', function ($q) use ($request) {

                $q->where('poli', $request->poli);

            });
        }

        // Filter tanggal
        if ($request->filled('tanggal')) {

            $query->whereDate('created_at', $request->tanggal);

        }

        // Download CSV tanpa package excel
        if ($request->has('download')) {

            $filename = 'Rekap-Medis-' . now()->format('d-m-Y') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $callback = function () use ($query) {

                $file = fopen('php://output', 'w');

                // Header kolom CSV
                fputcsv($file, [
                    'No',
                    'Nama Pasien',
                    'No Identitas',
                    'Poli',
                    'Keluhan',
                    'Diagnosis',
                    'Tindakan',
                    'Dokter',
                    'Tanggal',
                ]);

                $no = 1;

                foreach ($query->get() as $item) {

                    fputcsv($file, [

                        $no++,

                        $item->pendaftaran->nama_pasien ?? '-',

                        $item->pendaftaran->no_identitas ?? '-',

                        $item->pendaftaran->poli ?? '-',

                        $item->keluhan ?? '-',

                        $item->diagnosis ?? '-',

                        $item->tindakan ?? '-',

                        $item->dokter->name ?? '-',

                        $item->created_at
                            ? $item->created_at->format('d-m-Y')
                            : '-',
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // Tampilkan data ke view
        $pemeriksaan = $query->get();

        return view('admin.pemeriksaan.index', compact('pemeriksaan'));
    }
}
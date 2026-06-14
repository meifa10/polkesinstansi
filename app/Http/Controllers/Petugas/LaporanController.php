<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PendaftaranPoli;

class LaporanController extends Controller
{
    public function diagnosa(Request $request)
    {
        $query = PendaftaranPoli::where('status', 'selesai');

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_pasien', 'like', '%' . $request->q . '%')
                  ->orWhere('no_identitas', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->filled('poli')) {
            $query->where('poli', $request->poli);
        }

        $ids = (clone $query)
            ->selectRaw('MAX(id) as id')
            ->groupBy('no_identitas')
            ->pluck('id');

        $pasien = PendaftaranPoli::with('rekamMedis')
            ->whereIn('id', $ids)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('petugas.laporan-diagnosa.index', compact('pasien'));
    }

    public function show(Request $request, $id)
    {
        $pasien = PendaftaranPoli::findOrFail($id);

        $query = PendaftaranPoli::with(['rekamMedis', 'dokter'])
            ->where('no_identitas', $pasien->no_identitas)
            ->where('status', 'selesai');

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // Export Excel
        if ($request->download == 'excel') {
            $data = $query->latest()->get();

            return response()->streamDownload(function () use ($data) {
                $file = fopen('php://output', 'w');
                // Header CSV
                fputcsv($file, ['Tanggal', 'Keluhan', 'Tensi', 'BB (kg)', 'TB (cm)', 'Diagnosis', 'Tindakan', 'Resep', 'Dokter']);

                foreach ($data as $item) {
                    fputcsv($file, [
                        $item->created_at->format('d-m-Y H:i'),
                        $item->keluhan ?? '-',           
                        $item->tensi ?? '-',             
                        $item->berat_badan ?? '-',       
                        $item->tinggi_badan ?? '-',     
                        $item->rekamMedis?->diagnosis ?? '-',
                        $item->rekamMedis?->tindakan ?? '-',
                        $item->rekamMedis?->resep ?? '-',
                        $item->dokter?->name ?? '-'
                    ]);
                }
                fclose($file);
            }, 'Riwayat_' . $pasien->nama_pasien . '.csv');
        }

        $riwayat = $query->latest()->paginate(10)->withQueryString();

        return view('petugas.laporan-diagnosa.show', compact('pasien', 'riwayat'));
    }
}
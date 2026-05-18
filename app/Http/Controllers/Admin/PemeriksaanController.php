<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use App\Models\Pendaftaran; // Asumsi model pendaftaran/pasien Anda
use Illuminate\Http\Request;

class PemeriksaanController extends Controller
{
    /**
     * Halaman Utama: Menampilkan daftar pasien unik yang memiliki rekam medis
     */
    public function index(Request $request)
    {
        // Query dasar mengambil data pendaftaran yang memiliki rekam medis
        $query = Pendaftaran::whereHas('rekamMedis')->with(['rekamMedis']);

        // Filter Pencarian Teks (Nama / NIK)
        if ($request->filled('q')) {
            $search = trim($request->q);
            $query->where(function ($q) use ($search) {
                $q->where('nama_pasien', 'like', "%{$search}%")
                  ->orWhere('no_identitas', 'like', "%{$search}%");
            });
        }

        // Filter Poli
        if ($request->filled('poli')) {
            $query->where('poli', $request->poli);
        }

        $pasien = $query->latest()->paginate(10);

        return view('admin.pemeriksaan.index', compact('pasien'));
    }

    /**
     * Halaman Detail: Menampilkan runtunan seluruh riwayat pemeriksaan spesifik milik 1 pasien
     */
    public function show(Request $request, $id)
    {
        $pasien = Pendaftaran::findOrFail($id);

        $query = RekamMedis::where('pendaftaran_id', $id)->with('dokter');

        // Filter tanggal spesifik di dalam riwayat pasien
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // --- FITUR EXPORT EXCEL DATA RIWAYAT PASIEN ---
        if ($request->has('download')) {
            $filename = 'Riwayat-Pemeriksaan-' . str_replace(' ', '-', $pasien->nama_pasien) . '-' . now()->format('d-m-Y') . '.xls';
            $headers = [
                "Content-Type" => "application/vnd.ms-excel",
                "Content-Disposition" => "attachment; filename=\"$filename\"",
            ];

            $html = '
            <html>
            <head>
                <meta charset="UTF-8">
                <style>
                    body { font-family: Arial, sans-serif; padding:20px; color:#1f2937; }
                    .header { text-align:center; margin-bottom:25px; }
                    .title { font-size:20px; font-weight:bold; color:white; background:#059669; padding:15px; border-radius:8px; }
                    .subtitle { margin-top:10px; line-height:1.6; font-size:12px; color:#374151; }
                    table { width:100%; border-collapse:collapse; margin-top:20px; }
                    th { background:#059669; color:white; padding:10px; border:1px solid #d1d5db; font-size:11px; font-weight:bold; }
                    td { border:1px solid #d1d5db; padding:8px; font-size:11px; vertical-align:top; }
                    tr:nth-child(even) { background:#f9fafb; }
                    .text-center { text-align:center; }
                </style>
            </head>
            <body>
                <div class="header">
                    <div class="title">RIWAYAT REKAM MEDIS PASIEN</div>
                    <div class="subtitle">
                        <b>Nama Pasien:</b> ' . $pasien->nama_pasien . ' | <b>NIK:</b> ' . $pasien->no_identitas . ' | <b>Poli:</b> ' . $pasien->poli . '<br>
                        POLKES 05.09.15 JOMBANG<br>
                        Dicetak pada: ' . now()->format('d-m-Y H:i') . ' WIB
                    </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th width="4%">No</th>
                            <th width="12%">Waktu Periksa</th>
                            <th width="15%">Keluhan</th>
                            <th width="8%">Tensi</th>
                            <th width="6%">BB</th>
                            <th width="6%">TB</th>
                            <th width="15%">Diagnosis</th>
                            <th width="15%">Tindakan</th>
                            <th width="19%">Resep Obat</th>
                        </tr>
                    </thead>
                    <tbody>';

            $no = 1;
            foreach ($query->latest()->get() as $item) {
                $html .= '
                        <tr>
                            <td class="text-center">' . $no++ . '</td>
                            <td class="text-center">' . ($item->created_at ? $item->created_at->format('d-m-Y H:i') : '-') . ' WIB</td>
                            <td>' . ($item->keluhan ?? '-') . '</td>
                            <td class="text-center">' . ($item->tensi ?? '-') . ' mmHg</td>
                            <td class="text-center">' . ($item->bb ?? '-') . ' kg</td>
                            <td class="text-center">' . ($item->tb ?? '-') . ' cm</td>
                            <td>' . ($item->diagnosis ?? '-') . '</td>
                            <td>' . ($item->tindakan ?? '-') . '</td>
                            <td>' . str_replace("\n", ", ", $item->resep ?? '-') . '</td>
                        </tr>';
            }

            $html .= '
                    </tbody>
                </table>
            </body>
            </html>';

            return response($html, 200, $headers);
        }

        $riwayat = $query->latest()->get();

        return view('admin.pemeriksaan.show', compact('pasien', 'riwayat'));
    }
}
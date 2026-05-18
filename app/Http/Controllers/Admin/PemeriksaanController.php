<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use App\Models\PendaftaranPoli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemeriksaanController extends Controller
{
    /**
     * Halaman Utama: Menampilkan daftar nama pasien unik (Tanpa duplikat nama)
     */
    public function index(Request $request)
    {
        // Mengelompokkan data pendaftaran poli berdasarkan nama pasien dan NIK agar tidak double di tabel utama
        $query = PendaftaranPoli::whereHas('rekamMedis')
            ->select('nama_pasien', 'no_identitas', 'poli', DB::raw('MAX(id) as id'))
            ->groupBy('nama_pasien', 'no_identitas', 'poli');

        // Filter Pencarian Pasien Berdasarkan Nama atau NIK
        if ($request->filled('q')) {
            $search = trim($request->q);
            $query->where(function ($q) use ($search) {
                $q->where('nama_pasien', 'like', "%{$search}%")
                  ->orWhere('no_identitas', 'like', "%{$search}%");
            });
        }

        // Filter Poliklinik Asal Pasien
        if ($request->filled('poli')) {
            $query->where('poli', $request->poli);
        }

        // Menampilkan maksimal 10 data pasien per halaman laporan utama
        $pasien = $query->latest('id')->paginate(10);

        return view('admin.pemeriksaan.index', compact('pasien'));
    }

    /**
     * Halaman Detail: Menampilkan riwayat kronologis rekam medis & tanda vital pasien pilihan (Maksimal 10 per halaman)
     */
    public function show(Request $request, $id)
    {
        // Ambil pendaftaran acuan berdasarkan parameter rute ID untuk menemukan nama pasien
        $pasienAcuan = PendaftaranPoli::findOrFail($id);
        $namaPasien = $pasienAcuan->nama_pasien;

        // Cari seluruh riwayat kunjungan yang memiliki kecocokan nama pasien yang sama langsung dari pendaftaran_poli
        // Menghubungkan data berat_badan, tinggi_badan, tensi, keluhan, dan relasi rekamMedis
        $query = PendaftaranPoli::where('nama_pasien', $namaPasien)
            ->with(['rekamMedis', 'dokter']);

        // Filter tanggal spesifik di dalam riwayat pasien jika diinputkan
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // --- FITUR EXPORT EXCEL DATA RIWAYAT MEDIS JIKA TOMBOL EXPORT DIKLIK ---
        if ($request->has('download')) {
            $filename = 'Riwayat-Pemeriksaan-' . str_replace(' ', '-', $namaPasien) . '-' . now()->format('d-m-Y') . '.xls';
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
                        <b>Nama Pasien:</b> ' . $namaPasien . ' | <b>NIK:</b> ' . $pasienAcuan->no_identitas . '<br>
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
                            <td class="text-center">' . ($item->berat_badan ?? '-') . ' kg</td>
                            <td class="text-center">' . ($item->tinggi_badan ?? '-') . ' cm</td>
                            <td>' . ($item->rekamMedis->diagnosis ?? '-') . '</td>
                            <td>' . ($item->rekamMedis->tindakan ?? '-') . '</td>
                            <td>' . (isset($item->rekamMedis->resep) ? str_replace("\n", ", ", $item->rekamMedis->resep) : '-') . '</td>
                        </tr>';
            }

            $html .= '
                    </tbody>
                </table>
            </body>
            </html>';

            return response($html, 200, $headers);
        }

        // Membatasi riwayat kunjungan maksimal 10 data per halaman detail pasien
        $riwayat = $query->latest()->paginate(10);
        $pasien = $pasienAcuan;

        return view('admin.pemeriksaan.show', compact('pasien', 'riwayat'));
    }
}
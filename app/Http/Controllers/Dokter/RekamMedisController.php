<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use App\Models\PendaftaranPoli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RekamMedisController extends Controller
{
    /**
     * Halaman Utama: Menampilkan daftar nama pasien unik
     * Cocok dengan -> Route::get('/rekam-medis', [DokterRekamMedis::class, 'index'])
     */
    public function index(Request $request)
    {
        // Subquery untuk mengambil ID pendaftaran terbaru yang MEMILIKI rekam medis
        $subQuery = PendaftaranPoli::whereHas('rekamMedis')
            ->select(DB::raw('MAX(id) as latest_id'))
            ->groupBy('nama_pasien', 'no_identitas', 'poli');

        // Mengambil data lengkap pendaftaran berdasarkan filter subquery di atas
        $query = PendaftaranPoli::whereIn('id', $subQuery);

        // Jika dokter bukan "semua_poli", batasi pasien berdasarkan poli dokter login
        if (Auth::user()->kategori_poli != 'semua_poli') {
            $query->where('poli', Auth::user()->kategori_poli);
        }

        // Fitur Pencarian (Sesuai input name="q" di Blade)
        if ($request->filled('q')) {
            $search = trim($request->q);
            $query->where(function ($q) use ($search) {
                $q->where('nama_pasien', 'like', "%{$search}%")
                  ->orWhere('no_identitas', 'like', "%{$search}%");
            });
        }

        // Filter Poliklinik Asal (Sesuai select name="poli" di Blade)
        if ($request->filled('poli')) {
            $query->where('poli', $request->poli);
        }

        // Urutkan dari yang terbaru dan pecah menjadi 10 data per halaman
        $pasien = $query->orderBy('id', 'desc')->paginate(10);

        return view('dokter.rekammedis.index', compact('pasien'));
    }

    /**
     * Halaman Detail: Menampilkan riwayat kronologis rekam medis pasien
     * Cocok dengan -> Route::get('/rekam-medis/{id}', [DokterRekamMedis::class, 'show'])
     */
    public function show(Request $request, $id)
    {
        $pasienAcuan = PendaftaranPoli::findOrFail($id);
        $namaPasien = $pasienAcuan->nama_pasien;

        // Ambil riwayat rekam medis berdasarkan kesamaan nama pasien secara kronologis
        $query = PendaftaranPoli::where('nama_pasien', $namaPasien)
            ->with(['rekamMedis', 'dokter']);

        // Saring berdasarkan tanggal (jika dokter memilih tanggal di filter Blade)
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // Fitur Export XLS (Excel)
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
                        <b>Nama Pasien:</b> ' . htmlspecialchars($namaPasien) . ' | <b>NIK:</b> ' . htmlspecialchars($pasienAcuan->no_identitas) . '<br>
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
                            <td>' . htmlspecialchars($item->keluhan ?? '-') . '</td>
                            <td class="text-center">' . htmlspecialchars($item->tensi ?? '-') . ' mmHg</td>
                            <td class="text-center">' . htmlspecialchars($item->berat_badan ?? '-') . ' kg</td>
                            <td class="text-center">' . htmlspecialchars($item->tinggi_badan ?? '-') . ' cm</td>
                            <td>' . htmlspecialchars($item->rekamMedis->diagnosis ?? '-') . '</td>
                            <td>' . htmlspecialchars($item->rekamMedis->tindakan ?? '-') . '</td>
                            <td>' . (isset($item->rekamMedis->resep) ? htmlspecialchars(str_replace("\n", ", ", $item->rekamMedis->resep)) : '-') . '</td>
                        </tr>';
            }

            $html .= '
                    </tbody>
                </table>
            </body>
            </html>';

            return response($html, 200, $headers);
        }

        // Paginasi riwayat kunjungan untuk tabel detail
        $riwayat = $query->latest()->paginate(10);
        $pasien = $pasienAcuan;

        return view('dokter.rekammedis.show', compact('pasien', 'riwayat'));
    }
}
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

        // Export Excel Tanpa Package
        if ($request->has('download')) {

            $filename = 'Laporan-Pemeriksaan-' . now()->format('d-m-Y') . '.xls';

            $headers = [
                "Content-Type" => "application/vnd.ms-excel",
                "Content-Disposition" => "attachment; filename=\"$filename\"",
            ];

            $html = '
            <html>
            <head>
                <meta charset="UTF-8">

                <style>

                    body{
                        font-family: Arial, sans-serif;
                        padding:20px;
                        color:#1f2937;
                    }

                    .header{
                        text-align:center;
                        margin-bottom:25px;
                    }

                    .title{
                        font-size:24px;
                        font-weight:bold;
                        color:white;
                        background:#059669;
                        padding:18px;
                        border-radius:10px;
                        letter-spacing:1px;
                    }

                    .subtitle{
                        margin-top:15px;
                        line-height:1.8;
                        font-size:13px;
                        color:#374151;
                    }

                    .report-date{
                        margin-top:20px;
                        font-size:12px;
                        color:#6b7280;
                    }

                    table{
                        width:100%;
                        border-collapse:collapse;
                        margin-top:30px;
                    }

                    th{
                        background:#059669;
                        color:white;
                        padding:12px;
                        border:1px solid #d1d5db;
                        text-align:center;
                        font-size:12px;
                        font-weight:bold;
                    }

                    td{
                        border:1px solid #d1d5db;
                        padding:10px;
                        font-size:12px;
                        vertical-align:top;
                    }

                    tr:nth-child(even){
                        background:#f9fafb;
                    }

                    .text-center{
                        text-align:center;
                    }

                </style>
            </head>

            <body>

                <div class="header">

                    <div class="title">
                        LAPORAN PEMERIKSAAN POLKES 05.09.15 JOMBANG
                    </div>

                    <div class="subtitle">
                        Jl. KH. Wahid Hasyim No.28 B<br>
                        Jombang, Jawa Timur<br><br>

                        Telp / WA: 0877-7723-5386<br>
                        Email: jombangposkes@gmail.com
                    </div>

                    <div class="report-date">
                        Dicetak pada: '.now()->format('d-m-Y H:i').'
                    </div>

                </div>

                <table>

                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="18%">Nama Pasien</th>
                            <th width="14%">NIK</th>
                            <th width="12%">Poli</th>
                            <th width="15%">Keluhan</th>
                            <th width="15%">Diagnosis</th>
                            <th width="15%">Tindakan</th>
                            <th width="12%">Dokter</th>
                            <th width="10%">Tanggal</th>
                        </tr>
                    </thead>

                    <tbody>
            ';

            $no = 1;

            foreach ($query->get() as $item) {

                $html .= '
                    <tr>

                        <td class="text-center">'.$no++.'</td>

                        <td>'.($item->pendaftaran->nama_pasien ?? '-').'</td>

                        <td>'.($item->pendaftaran->no_identitas ?? '-').'</td>

                        <td>'.($item->pendaftaran->poli ?? '-').'</td>

                        <td>'.($item->keluhan ?? '-').'</td>

                        <td>'.($item->diagnosis ?? '-').'</td>

                        <td>'.($item->tindakan ?? '-').'</td>

                        <td>'.($item->dokter->name ?? '-').'</td>

                        <td class="text-center">'.
                            ($item->created_at
                                ? $item->created_at->format('d-m-Y')
                                : '-') .
                        '</td>

                    </tr>
                ';
            }

            $html .= '
                    </tbody>

                </table>

            </body>
            </html>
            ';

            return response($html, 200, $headers);
        }

        // Tampilkan data ke view
        $pemeriksaan = $query->get();

        return view('admin.pemeriksaan.index', compact('pemeriksaan'));
    }
}
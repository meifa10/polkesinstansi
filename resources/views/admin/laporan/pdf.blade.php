<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pelayanan Polkes Jombang</title>
    <style>
        body { 
            font-family: 'Helvetica', Arial, sans-serif; 
            font-size: 10px; 
            color: #334155; 
            margin: 20px;
            line-height: 1.5;
        }

        /* HEADER */
        .header { 
            text-align: center; 
            border-bottom: 3px double #0f172a; 
            padding-bottom: 12px; 
            margin-bottom: 25px; 
        }
        .header h1 { 
            margin: 0; font-size: 22px; color: #0f172a; 
            letter-spacing: 1px; text-transform: uppercase;
        }
        .header p { margin: 2px 0; font-size: 10px; color: #64748b; }

        .title-section { text-align: center; margin-bottom: 30px; }
        .title-section h2 { margin: 0; font-size: 14px; text-transform: uppercase; }
        .title-section p { margin: 4px 0; font-size: 11px; color: #3b82f6; font-weight: bold; }

        /* SUMMARY CARDS */
        .summary-wrapper { width: 100%; margin-bottom: 30px; }
        .card { 
            width: 22%; float: left; background: #f8fafc; 
            border: 1px solid #e2e8f0; padding: 15px 5px; 
            text-align: center; margin-right: 1.5%; border-radius: 10px;
        }
        .card-income { margin-right: 0; width: 25.5%; background: #eff6ff; border-color: #bfdbfe; }
        .card-label { display: block; font-size: 8px; color: #64748b; text-transform: uppercase; font-weight: bold; }
        .card-value { display: block; font-size: 13px; color: #0f172a; font-weight: bold; }
        .income-text { color: #2563eb; }

        .clear { clear: both; }

        /* TABLE */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th { background-color: #1e293b; color: #ffffff; padding: 12px 8px; text-transform: uppercase; font-size: 8.5px; }
        table td { border-bottom: 1px solid #f1f5f9; padding: 10px 8px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .badge { padding: 3px 6px; background: #f1f5f9; border-radius: 4px; font-size: 8px; font-weight: bold; }

        /* SIGNATURE AREA (FIXED WITH TABLE) */
        .footer-table {
            width: 100%;
            margin-top: 50px;
            border: none;
        }
        .footer-table td {
            border: none;
            width: 50%;
            vertical-align: top;
        }
        .signature-wrapper {
            text-align: center;
            width: 250px;
            margin-left: auto; /* Mendorong ke kanan */
        }
        .signature-space {
            height: 70px;
            position: relative;
        }
        .stamp-placeholder {
            position: absolute;
            top: 10px;
            left: 20px;
            width: 70px;
            height: 70px;
            border: 2px solid rgba(37, 99, 235, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: rotate(-15deg);
        }
        .stamp-text { color: rgba(37, 99, 235, 0.1); font-size: 7px; font-weight: bold; }
        .signature-name {
            font-size: 11px;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            margin: 0;
        }
        .signature-nrp {
            color: #475569;
            font-size: 9px;
            font-weight: bold;
            margin: 2px 0 0 0;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>POLKES JOMBANG</h1>
        <p>Jl. KH. Wahid Hasyim No.28 B, Jombang - Jawa Timur</p>
        <p>Email: jombangposkes@gmail.com | Telp: 0877-7723-5386</p>
    </div>

    <div class="title-section">
        <h2>Laporan Detail Pelayanan Pasien</h2>
        <p>Periode: {{ strtoupper($namaBulan) }} {{ $tahun }}</p>
    </div>

    <div class="summary-wrapper">
        <div class="card"><span class="card-label">Total Kunjungan</span><span class="card-value">{{ $totalKunjungan }} Pasien</span></div>
        <div class="card"><span class="card-label">Pasien Umum</span><span class="card-value">{{ $totalUmum }} Orang</span></div>
        <div class="card"><span class="card-label">Pasien JKN</span><span class="card-value">{{ $totalBpjs }} Orang</span></div>
        <div class="card card-income"><span class="card-label">Total Pendapatan</span><span class="card-value income-text">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span></div>
        <div class="clear"></div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="12%">Tanggal</th>
                <th style="text-align: left;">Nama Pasien</th>
                <th width="10%">Jenis</th>
                <th width="15%">Poli</th>
                <th width="15%">Biaya</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dataLaporan as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $item->created_at->format('d/m/Y') }}</td>
                <td class="font-bold">{{ $item->nama_pasien }}</td>
                <td class="text-center"><span class="badge">{{ strtoupper($item->jenis_pasien) }}</span></td>
                <td>{{ $item->poli }}</td>
                <td class="text-right font-bold">Rp {{ number_format($item->pembayaran->total_biaya ?? 0, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="footer-table">
        <tr>
            <td></td> <td>
                <div class="signature-wrapper">
                    <p style="margin-bottom: 5px;">Jombang, {{ now()->translatedFormat('d F Y') }}</p>
                    <p style="margin: 0; font-weight: bold; text-transform: uppercase;">Mengetahui,</p>
                    <p style="margin: 0; font-weight: bold; text-transform: uppercase;">Kepala Polkes Jombang</p>
                    
                    <div class="signature-space">
                        <div class="stamp-placeholder">
                            <span class="stamp-text">POLKES<br>JOMBANG</span>
                        </div>
                    </div>

                    <p class="signature-name">SUCIPTO BIANTORO</p>
                    <p class="signature-nrp">NRP. 21970098511276</p>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
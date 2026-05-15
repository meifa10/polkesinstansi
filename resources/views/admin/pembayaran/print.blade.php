<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Struk Pembayaran_{{ $pembayaran->payment_ref }}</title>
    <style>
        @page {
            margin: 10px;
        }
        body {
            font-family: 'Helvetica', sans-serif;
            color: #000;
            margin: 0;
            padding: 0;
            font-size: 10px;
            line-height: 1.3;
        }
        .wrapper {
            width: 100%;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td { padding: 2px 0; vertical-align: top; }
        
        /* Gaya Khusus Detail Resep */
        .resep-box {
            margin-top: 2px;
            padding-right: 5px;
            font-style: italic;
            color: #333;
            font-size: 9px;
            display: block;
        }
        
        .header h2 {
            margin: 0;
            font-size: 13px;
            text-transform: uppercase;
        }
        .header p {
            margin: 1px 0;
            font-size: 8px;
        }
        .total-row td {
            font-weight: bold;
            font-size: 12px;
            padding-top: 10px;
        }
        .status-badge {
            margin-top: 10px;
            font-weight: bold;
            font-size: 11px;
            border: 1px solid #000;
            display: inline-block;
            padding: 2px 8px;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="text-center header">
        <h2>POLKES 05.09.15 JOMBANG</h2>
        <p>Jl. KH. Wahid Hasyim No.28 B Jombang, Jawa Timur</p>
        <p>Telp: (0877) 7723-5386</p>
    </div>

    <div class="divider"></div>

    <table>
        <tr>
            <td style="width: 30%;">No Ref</td>
            <td>: {{ $pembayaran->payment_ref }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: {{ $pembayaran->created_at->format('d-m-Y H:i') }}</td>
        </tr>
        <tr>
            <td>Pasien</td>
            <td>: {{ $pembayaran->pendaftaran->nama_pasien ?? '-' }}</td>
        </tr>
        <tr>
            <td>Unit/Poli</td>
            <td>: {{ $pembayaran->pendaftaran->poli ?? '-' }}</td>
        </tr>
        <tr>
            <td>Metode</td>
            <td>: {{ strtoupper($pembayaran->metode) }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <table>
        <tr>
            <td>Jasa Dokter & Konsultasi</td>
            <td class="text-right">Rp {{ number_format($pembayaran->biaya_dokter, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Administrasi</td>
            <td class="text-right">Rp {{ number_format($pembayaran->biaya_admin, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>
                Obat & Farmasi
                <div class="resep-box">
                    Resep: {{ $pembayaran->pendaftaran->rekamMedis->resep ?? 'Tidak ada rincian obat' }}
                </div>
            </td>
            <td class="text-right">Rp {{ number_format($pembayaran->total_obat, 0, ',', '.') }}</td>
        </tr>
        
        <tr>
            <td colspan="2"><div class="divider"></div></td>
        </tr>

        <tr class="total-row">
            <td>TOTAL BAYAR</td>
            <td class="text-right">Rp {{ number_format($pembayaran->total_biaya, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="text-center">
        <div class="status-badge">
            STATUS: {{ strtoupper($pembayaran->status) }}
        </div>
        <p style="margin-top: 15px; font-size: 9px;">
            Terima kasih atas kepercayaan Anda.<br>
            Semoga lekas sembuh.
        </p>
    </div>
</div>

</body>
</html>
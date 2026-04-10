<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Resmi Polkes Jombang</title>

    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 11px;
            margin: 35px;
            color: #000;
        }

        /* ===== WATERMARK TEXT (100% AMAN UNTUK DOMPDF) ===== */
        .text-watermark {
            position: fixed;
            top: 40%;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 60px;
            font-weight: bold;
            color: #ececec; /* Pengganti opacity, warna abu-abu sangat pudar */
            z-index: -1;
            /* transform: rotate(-30deg); DOMPDF sering crash karena ini, jadi kita nonaktifkan */
        }

        /* ===== HEADER ===== */
        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .header h2 {
            margin: 2px 0;
            font-size: 14px;
            font-weight: normal;
        }

        .header p {
            margin: 2px 0;
            font-size: 10px;
        }

        /* ===== TITLE ===== */
        .title {
            text-align: center;
            margin: 25px 0;
        }

        .title h3 {
            font-size: 14px;
            text-decoration: underline;
            line-height: 1.5;
            margin: 0;
        }

        /* ===== TABLE ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 11px;
        }

        table th {
            background: #f2f2f2;
            text-align: center;
        }

        table td {
            vertical-align: middle;
        }

        /* ===== FOOTER ===== */
        .footer {
            margin-top: 60px;
            width: 100%;
        }

        .footer .ttd {
            width: 40%;
            float: right;
            text-align: center;
        }

        .footer p {
            margin: 4px 0;
            font-size: 11px;
        }

        .clear {
            clear: both;
        }
    </style>
</head>
<body>

    {{-- WATERMARK TEXT --}}
    <div class="text-watermark">
        POLKES JOMBANG
    </div>

    {{-- HEADER --}}
    <div class="header">
        <h1>POLKES JOMBANG</h1>
        <h2>Poliklinik Kesehatan Jombang</h2>
        <p>Jl. KH. Wahid Hasyim No.28 B, Jombang - Jawa Timur</p>
        <p>Telp / WA: 0877-7723-5386 &nbsp;|&nbsp; Email: jombangposkes@gmail.com</p>
    </div>

    {{-- TITLE --}}
    <div class="title">
        <h3>
            LAPORAN PELAYANAN PASIEN<br>
            BULAN {{ strtoupper($namaBulan) }} TAHUN {{ $tahun }}
        </h3>
    </div>

    {{-- ISI LAPORAN --}}
    <table>
        <tr>
            <th style="width:60%">Keterangan</th>
            <th style="width:40%">Jumlah</th>
        </tr>
        <tr>
            <td>Total Kunjungan Pasien</td>
            <td align="center">{{ $totalKunjungan }}</td>
        </tr>
        <tr>
            <td>Pasien BPJS</td>
            <td align="center">{{ $bpjs }}</td>
        </tr>
        <tr>
            <td>Pasien Umum</td>
            <td align="center">{{ $umum }}</td>
        </tr>
        <tr>
            <td>Total Pemeriksaan Dokter</td>
            <td align="center">{{ $totalPemeriksaan }}</td>
        </tr>
        <tr>
            <td>Total Pemasukan (Pembayaran Lunas)</td>
            <td align="center">
                Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
            </td>
        </tr>
    </table>

    {{-- FOOTER --}}
    <div class="footer">
        <div class="ttd">
            <p>Jombang, {{ now()->translatedFormat('d F Y') }}</p>
            <p><strong>Kepala Polkes</strong></p>
            <br><br><br>
            <p><strong>( Sucipto Biantoro )</strong></p>
            <p>NIK. 3517035005040005</p>
        </div>
    </div>

    <div class="clear"></div>

</body>
</html>
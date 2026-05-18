<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Struk Pembayaran_{{ $pembayaran->payment_ref }}</title>
    <style>
        @page {
            margin: 8px;
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
            margin: 6px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td { padding: 2px 0; vertical-align: top; }
        
        .header h2 {
            margin: 0;
            font-size: 12px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .header p {
            margin: 1px 0;
            font-size: 8px;
        }
        .total-row td {
            font-weight: bold;
            font-size: 11px;
            padding-top: 6px;
        }
        .status-badge {
            margin-top: 8px;
            font-weight: bold;
            font-size: 10px;
            border: 1px solid #000;
            display: inline-block;
            padding: 1px 6px;
        }
        .item-child {
            font-size: 9px;
            padding-left: 6px;
            font-style: italic;
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
            <td style="width: 28%;">No Ref</td>
            <td>: {{ $pembayaran->payment_ref }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: {{ $pembayaran->created_at->format('d-m-Y H:i') }} WIB</td>
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

    {{-- DAFTAR ITEM TRANSAKSI --}}
    <table>
        {{-- Jasa Tindakan --}}
        <tr>
            <td>Jasa Dokter & Konsultasi</td>
            <td class="text-right">Rp {{ number_format($pembayaran->biaya_dokter, 0, ',', '.') }}</td>
        </tr>
        {{-- Admin --}}
        <tr>
            <td>Administrasi</td>
            <td class="text-right">Rp {{ number_format($pembayaran->biaya_admin, 0, ',', '.') }}</td>
        </tr>
        
        {{-- BREAKDOWN DAFTAR OBAT RINCI --}}
        <tr>
            <td colspan="2" style="font-weight: bold; padding-top: 4px;">Obat & Farmasi:</td>
        </tr>
        
        @forelse($rincianObat as $item)
        <tr>
            <td class="item-child">
                • {{ $item['nama'] }}
                @if($item['harga'] > 0)
                    <br>&nbsp;&nbsp;({{ $item['qty'] }} x Rp {{ number_format($item['harga'], 0, ',', '.') }})
                @else
                    <br>&nbsp;&nbsp;(Qty: {{ $item['qty'] }})
                @endif
            </td>
            <td class="text-right" style="vertical-align: bottom; font-size: 9px;">
                @if($item['total'] > 0)
                    Rp {{ number_format($item['total'], 0, ',', '.') }}
                @else
                    -
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td class="item-child" style="color: #444;">- Tanpa resep obat</td>
            <td class="text-right">Rp 0</td>
        </tr>
        @endforelse

        {{-- Cadangan Paket Nominal Obat (Jika total subitem bernilai 0) --}}
        @if(count($rincianObat) > 0 && collect($rincianObat)->sum('total') == 0)
        <tr>
            <td class="item-child" style="font-weight: bold;">Subtotal Paket Obat</td>
            <td class="text-right" style="vertical-align: bottom;">Rp {{ number_format($pembayaran->total_obat, 0, ',', '.') }}</td>
        </tr>
        @endif
        
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
        <p style="margin-top: 12px; font-size: 8px; line-height: 1.2;">
            Terima kasih atas kepercayaan Anda.<br>
            Semoga lekas sembuh.
        </p>
    </div>
</div>

</body>
</html>
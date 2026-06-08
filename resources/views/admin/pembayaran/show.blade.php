@extends('layouts.admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<div class="p-6 bg-slate-100 min-h-screen font-['Plus_Jakarta_Sans'] flex justify-center items-center">
    <div class="w-full max-w-3xl bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
        
        {{-- CARD HEADER --}}
        <div class="bg-slate-900 p-6 text-white flex justify-between items-center">
            <div>
                <p class="text-emerald-400 font-extrabold text-[10px] uppercase tracking-[0.2em] mb-1">RINCIAN INVOICE</p>
                <h2 class="text-xl font-black tracking-tight font-mono">{{ $pembayaran->payment_ref }}</h2>
            </div>
            <div>
                @if($pembayaran->status == 'lunas')
                    <span class="px-4 py-1.5 bg-emerald-500 text-white rounded-full text-[10px] font-black uppercase tracking-wider">LUNAS / VERIFIED</span>
                @else
                    <span class="px-4 py-1.5 bg-amber-500 text-white rounded-full text-[10px] font-black uppercase tracking-wider">MENUNGGU BAYAR</span>
                @endif
            </div>
        </div>

        <div class="p-8 space-y-8">
            {{-- DATA PROFILE PASIEN --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 p-5 rounded-2xl border border-slate-200 text-sm">
                <div>
                    <h4 class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-2">Informasi Pasien</h4>
                    <p class="font-bold text-slate-900 text-base">{{ $pembayaran->pendaftaran->nama_pasien ?? 'N/A' }}</p>
                    <p class="text-slate-500 font-mono text-xs mt-0.5">No ID: {{ $pembayaran->pendaftaran->no_identitas ?? '-' }}</p>
                    <p class="text-emerald-700 font-bold text-xs mt-2 uppercase inline-block bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                        {{ $pembayaran->pendaftaran->poli ?? '-' }}
                    </p>
                </div>
                <div class="md:text-right flex flex-col justify-between">
                    <div>
                        <h4 class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-1">Metode & Tanggal</h4>
                        <p class="font-black text-slate-800 uppercase text-sm">{{ $pembayaran->metode }}</p>
                        <p class="text-slate-500 text-xs mt-1">{{ $pembayaran->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                    </div>
                </div>
            </div>

            {{-- TRANSAKSI UTAMA --}}
            <div>
                <h3 class="text-slate-900 font-extrabold text-sm uppercase tracking-wider mb-3">Item Transaksi & Rincian Farmasi</h3>
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-300 text-slate-400 text-[11px] uppercase font-bold">
                            <th class="pb-2">Komponen Layanan</th>
                            <th class="pb-2 text-center w-24">Kuantitas</th>
                            <th class="pb-2 text-right w-36">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        {{-- Jasa Dokter --}}
                        <tr>
                            <td class="py-4" colspan="2">
                                <p class="font-bold text-slate-800">Jasa Konsultasi / Tindakan Dokter</p>
                                <p class="text-xs text-slate-400">Pemeriksaan medis klinis dasar poli</p>
                            </td>
                            <td class="py-4 text-right font-bold text-slate-900 align-middle">
                                Rp {{ number_format($pembayaran->biaya_dokter ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                        
                        {{-- Administrasi --}}
                        <tr>
                            <td class="py-4" colspan="2">
                                <p class="font-bold text-slate-800">Biaya Administrasi Rumah Sakit</p>
                                <p class="text-xs text-slate-400">Pencatatan data rekam medis pasien</p>
                            </td>
                            <td class="py-4 text-right font-bold text-slate-900 align-middle">
                                Rp {{ number_format($pembayaran->biaya_admin ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                        
                        @foreach($rincianObat as $obat)
                        <tr class="bg-slate-50/50">
                            <td class="py-3 pl-4">
                                <p class="font-semibold text-slate-800">{{ $obat['nama'] }}</p>
                                <p class="text-[11px] text-slate-400">({{ $obat['qty'] }} x Rp {{ number_format($obat['harga'], 0, ',', '.') }})</p>
                            </td>
                            <td class="py-3 text-center font-bold text-slate-700">{{ $obat['qty'] }} Item</td>
                            <td class="py-3 text-right font-bold text-slate-900">Rp {{ number_format($obat['total'], 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-slate-900">
                            <td class="pt-4 text-base font-black text-slate-900 uppercase" colspan="2">Total Tagihan Bersih</td>
                            <td class="pt-4 text-right text-xl font-black text-emerald-600">
                                Rp {{ number_format($pembayaran->total_biaya ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="flex justify-between items-center pt-4 border-t border-slate-200">
                <a href="{{ route('admin.pembayaran') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors uppercase tracking-wider">← Kembali ke Daftar</a>
                <a href="{{ route('admin.pembayaran.print', $pembayaran->id) }}" target="_blank"
                   class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow-lg shadow-blue-100 transition-all active:scale-95 flex items-center gap-2">
                    <i class="ph-bold ph-printer"></i> Cetak Struk
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
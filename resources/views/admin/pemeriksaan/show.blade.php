@extends('layouts.admin')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
</style>

<div class="p-6 lg:p-8 bg-slate-50 min-h-screen font-sans">

    {{-- BREADCRUMB & HEADER TOMBOL KEMBALI --}}
    <div class="mb-6 flex items-center justify-between border-b border-slate-200 pb-5">
        <a href="{{ route('admin.pemeriksaan') }}" class="inline-flex items-center gap-2.5 px-4 py-2 bg-white hover:bg-emerald-50 text-emerald-700 hover:text-emerald-800 rounded-xl border border-slate-200 hover:border-emerald-300 transition-all text-sm font-bold shadow-sm group cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar Laporan Utama
        </a>
        <span class="text-xs font-bold text-slate-400 tracking-wider uppercase font-mono bg-slate-100 px-3 py-1.5 rounded-md">ID Pasien Ref: #{{ $pasien->id }}</span>
    </div>

    {{-- PROFIL KARTU IDENTITAS PASIEN --}}
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center mb-8 gap-6 bg-gradient-to-r from-emerald-900 to-slate-900 text-white p-6 lg:p-8 rounded-3xl shadow-xl border border-emerald-950">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-emerald-500/20 text-emerald-300 text-xs font-black tracking-widest uppercase mb-3 border border-emerald-500/30">
                Poli Asal: {{ $pasien->poli }}
            </div>
            <h1 class="text-3xl lg:text-4xl font-black tracking-tight uppercase">
                {{ $pasien->nama_pasien }}
            </h1>
            <p class="text-emerald-200/80 font-medium mt-1.5 text-sm lg:text-base">
                Nomor Identitas NIK: <span class="font-mono font-bold text-white bg-slate-800/40 px-2 py-0.5 rounded-md border border-white/10 ml-1">{{ $pasien->no_identitas }}</span>
            </p>
        </div>

        {{-- Download Excel Khusus Pasien Terpilih --}}
        <form method="GET" action="{{ route('admin.pemeriksaan.show', $pasien->id) }}" class="w-full lg:w-auto">
            <input type="hidden" name="tanggal" value="{{ request('tanggal') }}">
            <button type="submit" name="download" value="1" 
                class="w-full lg:w-auto flex items-center justify-center gap-2.5 bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-4 rounded-2xl shadow-lg hover:shadow-emerald-500/10 transition-all font-extrabold text-base border border-emerald-400/20 cursor-pointer active:scale-98">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:-translate-y-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Riwayat Pasien (Excel)
            </button>
        </form>
    </div>

    {{-- FILTER TANGGAL RIWAYAT --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 mb-8">
        <form method="GET" action="{{ route('admin.pemeriksaan.show', $pasien->id) }}" class="flex flex-col sm:flex-row gap-3 items-end">
            <div class="w-full sm:w-64">
                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Saring Riwayat Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" 
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none cursor-pointer">
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-none bg-slate-800 hover:bg-slate-900 text-white px-6 py-3 rounded-xl font-bold transition-colors cursor-pointer text-sm uppercase tracking-wider active:scale-98">
                    Filter
                </button>
                @if(request('tanggal'))
                    <a href="{{ route('admin.pemeriksaan.show', $pasien->id) }}" class="px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl border border-slate-300 flex items-center justify-center transition-colors shadow-sm" title="Bersihkan Filter">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- TABEL RIWAYAT MEDIS BERKALA --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1250px]">
                <thead>
                    <tr class="bg-slate-900 text-white text-xs uppercase tracking-widest font-black">
                        <th class="py-5 px-6 w-20 text-center rounded-tl-2xl">No</th>
                        <th class="py-5 px-6 w-44 text-center">Waktu Kunjungan</th>
                        <th class="py-5 px-6 min-w-[200px]">Keluhan Utama</th>
                        <th class="py-5 px-6 min-w-[240px] text-center">Tanda-Tanda Vital (TB, BB, Tensi)</th>
                        <th class="py-5 px-6 min-w-[300px]">Diagnosis & Tindakan Klinis</th>
                        <th class="py-5 px-6 min-w-[220px]">Resep Obat</th>
                        <th class="py-5 px-6 min-w-[160px] rounded-tr-2xl">Dokter</th>
                    </tr>
                </thead>
                <tbody class="text-base divide-y divide-slate-200">
                    @forelse($riwayat as $item)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        
                        {{-- NO URUT KRONOLOGIS --}}
                        <td class="py-6 px-6 align-middle text-center font-extrabold text-slate-400">
                            {{ ($riwayat->currentPage() - 1) * $riwayat->perPage() + $loop->iteration }}
                        </td>

                        {{-- WAKTU PERIKSA --}}
                        <td class="py-6 px-6 align-middle text-center group-hover:bg-emerald-50/20 transition-colors">
                            <p class="font-extrabold text-slate-800 text-sm">{{ $item->created_at->translatedFormat('d M Y') }}</p>
                            <span class="text-xs font-bold font-mono text-emerald-600 block mt-1 bg-emerald-50 rounded-md py-0.5 px-2 border border-emerald-100 inline-block">{{ $item->created_at->format('H:i') }} WIB</span>
                        </td>
                        
                        {{-- KELUHAN --}}
                        <td class="py-6 px-6 align-middle italic text-slate-600 font-medium text-sm leading-relaxed">
                            "{{ $item->keluhan ?? 'Tidak ada keluhan tertulis' }}"
                        </td>
                        
                        {{-- TANDA VITAL (DESAIN ULTRA-JELAS, BESAR, TEBAL, HITAM PEKAT) --}}
                        <td class="py-6 px-6 align-middle">
                            <div class="space-y-3 w-full max-w-[220px] mx-auto">
                                
                                {{-- Tensi Box (Jauh Lebih Besar & Jelas) --}}
                                <div class="flex items-center justify-between border-2 border-slate-300 p-2.5 rounded-xl bg-white shadow-md">
                                    <span class="text-base font-extrabold text-slate-950 uppercase tracking-tight">Tensi</span>
                                    <span class="font-mono text-slate-950 font-black text-lg">
                                        {{ $item->tensi ?? '-' }} <span class="text-xs font-black ml-0.5">mmHg</span>
                                    </span>
                                </div>
                                
                                {{-- Berat Badan Box (Jauh Lebih Besar & Jelas) --}}
                                <div class="flex items-center justify-between border-2 border-slate-300 p-2.5 rounded-xl bg-white shadow-md">
                                    <span class="text-base font-extrabold text-slate-950 uppercase tracking-tight">BB</span>
                                    <span class="font-mono text-slate-950 font-black text-lg">
                                        {{ $item->berat_badan ?? '-' }} <span class="text-xs font-black ml-0.5">kg</span>
                                    </span>
                                </div>
                                
                                {{-- Tinggi Badan Box (Jauh Lebih Besar & Jelas) --}}
                                <div class="flex items-center justify-between border-2 border-slate-300 p-2.5 rounded-xl bg-white shadow-md">
                                    <span class="text-base font-extrabold text-slate-950 uppercase tracking-tight">TB</span>
                                    <span class="font-mono text-slate-950 font-black text-lg">
                                        {{ $item->tinggi_badan ?? '-' }} <span class="text-xs font-black ml-0.5">cm</span>
                                    </span>
                                </div>

                            </div>
                        </td>
                        
                        {{-- DIAGNOSIS & TINDAKAN --}}
                        <td class="py-6 px-6 align-middle group-hover:bg-rose-50/20 transition-colors">
                            <div class="space-y-2">
                                <div class="p-2.5 rounded-xl bg-rose-50 border border-rose-100 text-sm">
                                    <span class="text-[9px] font-black text-rose-600 block uppercase tracking-widest mb-0.5">Diagnosis</span>
                                    <p class="font-extrabold text-slate-800 leading-tight">{{ $item->rekamMedis->diagnosis ?? '-' }}</p>
                                </div>
                                <div class="p-2.5 rounded-xl bg-blue-50 border border-blue-100 text-sm overflow-auto max-h-24">
                                    <span class="text-[9px] font-black text-blue-600 block uppercase tracking-widest mb-0.5">Tindakan Klinis</span>
                                    <p class="font-bold text-slate-700 leading-tight whitespace-pre-line">{{ $item->rekamMedis->tindakan ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        
                        {{-- RESEP OBAT --}}
                        <td class="py-6 px-6 align-middle">
                            @if($item->rekamMedis && $item->rekamMedis->resep)
                                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 whitespace-pre-line leading-relaxed shadow-sm overflow-auto max-h-32">
                                    {{ $item->rekamMedis->resep }}
                                </div>
                            @else
                                <span class="text-xs text-slate-400 font-bold italic bg-slate-100 px-2.5 py-1.5 rounded-lg border border-slate-200">Tanpa Resep Obat</span>
                            @endif
                        </td>
                        
                        {{-- DOKTER --}}
                        <td class="py-6 px-6 align-middle font-extrabold text-slate-800 text-sm leading-tight">
                            {{ $item->dokter->name ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-20 text-center text-slate-400 font-semibold italic bg-white rounded-b-2xl">
                            Belum ada riwayat kunjungan rekam medis tercatat untuk pasien ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- KONTROL TOMBOL PREVIOUS & NEXT PAGINATION --}}
        @if($riwayat->hasPages())
        <div class="p-5 border-t border-slate-200 bg-slate-100/50 rounded-b-2xl">
            {{ $riwayat->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

@endsection
@extends('layouts.dokter')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
</style>

<div class="p-6 lg:p-8 bg-slate-50 min-h-screen font-sans">

    <div class="mb-6 flex items-center justify-between border-b border-slate-200 pb-5">
        <a href="{{ route('dokter.rekammedis') }}" class="inline-flex items-center gap-2.5 px-4 py-2 bg-white hover:bg-emerald-50 text-emerald-700 hover:text-emerald-800 rounded-xl border border-slate-200 hover:border-emerald-300 transition-all text-sm font-bold shadow-sm group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar Laporan Utama
        </a>
        <span class="text-xs font-bold text-slate-500 tracking-wider uppercase font-mono bg-slate-200 px-3 py-1.5 rounded-md">ID Pasien: #{{ $pasien->id }}</span>
    </div>

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

        <form method="GET" action="{{ route('dokter.rekammedis.show', $pasien->id) }}" class="w-full lg:w-auto">
            <input type="hidden" name="tanggal" value="{{ request('tanggal') }}">
            <button type="submit" name="download" value="1" 
                class="w-full lg:w-auto flex items-center justify-center gap-2.5 bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-4 rounded-2xl shadow-lg hover:shadow-emerald-500/10 transition-all font-extrabold text-base border border-emerald-400/20 cursor-pointer active:scale-98">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Riwayat Pasien (Excel)
            </button>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 mb-8">
        <form method="GET" action="{{ route('dokter.rekammedis.show', $pasien->id) }}" class="flex flex-col sm:flex-row gap-3 items-end">
            <div class="w-full sm:w-64">
                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Saring Riwayat Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" 
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none cursor-pointer">
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-none bg-slate-800 hover:bg-slate-900 text-white px-6 py-3 rounded-xl font-bold transition-colors cursor-pointer text-sm uppercase tracking-wider">
                    Filter
                </button>
                @if(request('tanggal'))
                    <a href="{{ route('dokter.rekammedis.show', $pasien->id) }}" class="px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl border border-slate-300 flex items-center justify-center transition-colors" title="Bersihkan Filter">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1200px]">
                <thead>
                    <tr class="bg-slate-900 text-white text-xs uppercase tracking-widest font-black">
                        <th class="py-5 px-6 w-16 text-center rounded-tl-2xl">No</th>
                        <th class="py-5 px-6 w-44 text-center">Waktu Kunjungan</th>
                        <th class="py-5 px-6 min-w-[220px]">Keluhan Utama</th>
                        <th class="py-5 px-6 min-w-[200px] text-center">Tanda-Tanda Vital</th>
                        <th class="py-5 px-6 min-w-[300px]">Diagnosis & Tindakan Klinis</th>
                        <th class="py-5 px-6 min-w-[240px]">Resep Obat</th>
                        <th class="py-5 px-6 min-w-[160px] rounded-tr-2xl">Dokter</th>
                    </tr>
                </thead>
                <tbody class="text-base divide-y divide-slate-200">
                    @forelse($riwayat as $item)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        
                        <td class="py-6 px-6 align-top text-center font-bold text-slate-500">
                            {{ ($riwayat->currentPage() - 1) * $riwayat->perPage() + $loop->iteration }}
                        </td>

                        <td class="py-6 px-6 align-top text-center">
                            <p class="font-bold text-slate-900 text-sm">{{ $item->created_at->translatedFormat('d M Y') }}</p>
                            <span class="text-xs font-bold font-mono text-emerald-700 block mt-1.5 bg-emerald-100/50 rounded-md py-1 px-2 border border-emerald-200 inline-block">
                                {{ $item->created_at->format('H:i') }} WIB
                            </span>
                        </td>
                        
                        <td class="py-6 px-6 align-top">
                            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 text-slate-800 font-medium text-sm leading-relaxed shadow-sm">
                                {{ $item->keluhan ?? 'Tidak ada keluhan tertulis' }}
                            </div>
                        </td>
                        
                        <td class="py-6 px-6 align-top">
                            <div class="space-y-2.5 w-full max-w-[200px] mx-auto">
                                <div class="flex items-center justify-between border border-slate-300 p-2.5 rounded-lg bg-white shadow-sm">
                                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Tensi:</span>
                                    <span class="text-slate-900 font-mono font-bold text-sm">
                                        {{ $item->tensi ?? '-' }} <span class="text-[11px] font-semibold text-slate-600 ml-0.5">mmHg</span>
                                    </span>
                                </div>
                                <div class="flex items-center justify-between border border-slate-300 p-2.5 rounded-lg bg-white shadow-sm">
                                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Berat:</span>
                                    <span class="text-slate-900 font-mono font-bold text-sm">
                                        {{ $item->berat_badan ?? '-' }} <span class="text-[11px] font-semibold text-slate-600 ml-0.5">kg</span>
                                    </span>
                                </div>
                                <div class="flex items-center justify-between border border-slate-300 p-2.5 rounded-lg bg-white shadow-sm">
                                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Tinggi:</span>
                                    <span class="text-slate-900 font-mono font-bold text-sm">
                                        {{ $item->tinggi_badan ?? '-' }} <span class="text-[11px] font-semibold text-slate-600 ml-0.5">cm</span>
                                    </span>
                                </div>
                            </div>
                        </td>
                        
                        <td class="py-6 px-6 align-top">
                            <div class="space-y-3">
                                <div class="p-3.5 rounded-xl bg-rose-50/80 border border-rose-200 text-sm shadow-sm">
                                    <span class="text-[10px] font-black text-rose-700 block uppercase tracking-widest mb-1.5">Diagnosis Utama</span>
                                    <p class="font-semibold text-slate-900 leading-relaxed">{{ $item->rekamMedis->diagnosis ?? '-' }}</p>
                                </div>
                                <div class="p-3.5 rounded-xl bg-blue-50/80 border border-blue-200 text-sm shadow-sm">
                                    <span class="text-[10px] font-black text-blue-700 block uppercase tracking-widest mb-1.5">Tindakan Klinis</span>
                                    <p class="font-semibold text-slate-900 leading-relaxed">{{ $item->rekamMedis->tindakan ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        
                        <td class="py-6 px-6 align-top">
                            @if($item->rekamMedis && $item->rekamMedis->resep)
                                <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-xs font-semibold text-slate-900 whitespace-pre-line leading-relaxed shadow-sm">
                                    {{ $item->rekamMedis->resep }}
                                </div>
                            @else
                                <span class="text-xs text-slate-500 font-semibold italic bg-slate-100 px-3 py-2 rounded-lg border border-slate-200 block text-center">
                                    Tidak ada resep obat
                                </span>
                            @endif
                        </td>
                        
                        <td class="py-6 px-6 align-top">
                            <div class="inline-flex items-center gap-2 bg-slate-100 px-3 py-2 rounded-lg border border-slate-200">
                                <span class="font-bold text-slate-800 text-sm leading-tight">
                                    {{ $item->dokter->name ?? '-' }}
                                </span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-20 text-center text-slate-500 font-medium bg-slate-50/50">
                            Belum ada riwayat kunjungan rekam medis tercatat untuk pasien ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($riwayat->hasPages())
        <div class="p-5 border-t border-slate-200 bg-slate-50">
            {{ $riwayat->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

@endsection
@extends('layouts.admin')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    body { 
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    /* Menghilangkan panah default pada tag summary */
    summary {
        list-style: none;
    }
    summary::-webkit-details-marker {
        display: none;
    }
    /* Animasi panah custom saat details dibuka */
    details[open] summary .accordion-icon {
        transform: rotate(180deg);
    }
</style>

<div class="p-6 lg:p-8 bg-slate-50 min-h-screen font-sans">

    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-8 gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-sm font-bold tracking-wide uppercase mb-3 border border-emerald-300">
                <a href="{{ route('admin.data_pasien.index') }}" class="hover:text-emerald-600 transition-colors">Data Pasien</a>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                Profil Lengkap
            </div>
            <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight">
                Detail <span class="text-emerald-600">Pasien</span>
            </h1>
            <p class="text-slate-600 font-medium mt-3 text-base lg:text-lg">
                Informasi profil lengkap, riwayat klinis, dan status transaksi administrasi.
            </p>
        </div>

        <a href="{{ route('admin.data_pasien.index') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-white text-slate-700 border-2 border-slate-300 rounded-xl text-sm font-bold hover:bg-slate-100 hover:text-slate-900 transition-all shadow-sm active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
        
        {{-- ================= KIRI: INFORMASI UTAMA ================= --}}
        <div class="xl:col-span-4 space-y-6">
            
            {{-- PROFIL CARD --}}
            <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200 overflow-hidden relative">
                <div class="h-32 bg-slate-900 w-full relative">
                    <div class="absolute -bottom-12 left-8">
                        <div class="w-24 h-24 bg-emerald-100 text-emerald-700 rounded-2xl border-4 border-white shadow-sm flex items-center justify-center text-4xl font-black">
                            {{ strtoupper(substr($pasien->nama_pasien, 0, 1)) }}
                        </div>
                    </div>
                </div>
                
                <div class="px-8 pt-16 pb-8">
                    <h2 class="text-2xl font-extrabold text-slate-900 uppercase tracking-tight">{{ $pasien->nama_pasien }}</h2>
                    <div class="mt-2">
                        @if(strtolower($pasien->jenis_pasien) == 'jkn' || strtolower($pasien->jenis_pasien) == 'bpjs')
                            <span class="inline-flex px-3 py-1 rounded-md bg-emerald-100 text-emerald-700 text-xs font-black uppercase tracking-wider border border-emerald-300 shadow-sm">
                                Pasien {{ $pasien->jenis_pasien }}
                            </span>
                        @else
                            <span class="inline-flex px-3 py-1 rounded-md bg-blue-100 text-blue-700 text-xs font-black uppercase tracking-wider border border-blue-300 shadow-sm">
                                Pasien {{ $pasien->jenis_pasien }}
                            </span>
                        @endif
                    </div>

                    <div class="mt-8 space-y-6 border-t-2 border-slate-100 pt-6">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 border-2 border-slate-200 flex items-center justify-center text-slate-500 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest leading-none mb-1">Nomor Identitas</p>
                                <p class="text-base font-extrabold text-slate-800 font-mono tracking-tight">{{ $pasien->no_identitas }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 border-2 border-slate-200 flex items-center justify-center text-slate-500 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest leading-none mb-1">Tanggal Lahir</p>
                                <p class="text-base font-extrabold text-slate-800">{{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TOTAL KUNJUNGAN BOX --}}
            <div class="bg-slate-900 rounded-2xl p-8 text-white shadow-md border-b-4 border-emerald-500 relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Total Kunjungan</p>
                    <h3 class="text-5xl font-black text-white flex items-baseline gap-2">
                        {{ $kunjungan->count() }}<span class="text-lg text-emerald-400 font-extrabold tracking-wider">KALI</span>
                    </h3>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-white/5 absolute -right-4 -bottom-4 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
            </div>
        </div>

        {{-- ================= KANAN: RIWAYAT AKTIVITAS (ACCORDION) ================= --}}
        <div class="xl:col-span-8 space-y-8">
            
            {{-- SEKSI KUNJUNGAN & PEMBAYARAN --}}
            <section class="bg-white rounded-2xl shadow-md border-2 border-slate-200 p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6 pb-5 border-b-2 border-slate-100">
                    <div class="bg-emerald-100 p-2 rounded-lg text-emerald-600 border border-emerald-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">Status Transaksi & Kunjungan</h2>
                </div>

                <div class="space-y-4">
                    @forelse($kunjungan as $k)
                    {{-- ACCORDION KUNJUNGAN --}}
                    <details class="group bg-white border-2 border-slate-200 rounded-xl overflow-hidden shadow-sm" {{ $loop->first ? 'open' : '' }}>
                        
                        {{-- HEADER ACCORDION --}}
                        <summary class="flex justify-between items-center cursor-pointer p-5 bg-slate-50 hover:bg-slate-100 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-emerald-600 border border-slate-200 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-base font-extrabold text-slate-900">{{ $k->created_at->translatedFormat('d M Y') }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="inline-flex px-2 py-0.5 rounded bg-slate-200 text-slate-700 text-[10px] font-black uppercase border border-slate-300">
                                            {{ $k->poli }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="hidden md:block">
                                    @if($k->pembayaran)
                                        @if($k->pembayaran->status === 'lunas')
                                            <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest">Lunas</span>
                                        @else
                                            <span class="text-xs font-bold text-amber-600 uppercase tracking-widest animate-pulse">Pending</span>
                                        @endif
                                    @else
                                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Tidak Ada</span>
                                    @endif
                                </div>
                                <div class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-200 text-slate-500 accordion-icon transition-transform duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </summary>

                        {{-- BODY ACCORDION --}}
                        <div class="p-5 border-t-2 border-slate-200 bg-white flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div class="text-sm text-slate-600">
                                Waktu Kunjungan: <span class="font-bold text-slate-800">{{ $k->created_at->format('H:i') }} WIB</span>
                            </div>

                            <div class="w-full md:w-auto">
                                @if($k->pembayaran)
                                    @if($k->pembayaran->status === 'lunas')
                                        <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-end">
                                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-extrabold shadow-sm uppercase tracking-wider">
                                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                                Terverifikasi Lunas
                                            </div>
                                            <a href="{{ route('admin.pembayaran.print', $k->pembayaran->id) }}" 
                                               class="p-2.5 bg-slate-800 text-white rounded-lg hover:bg-emerald-600 transition-all shadow-md active:scale-95 border border-slate-800" title="Cetak Struk">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                </svg>
                                            </a>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-end">
                                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-100 border border-amber-300 text-amber-800 text-xs font-extrabold shadow-sm uppercase tracking-wider animate-pulse">
                                                <span class="flex h-2 w-2 relative">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                                </span>
                                                Menunggu Pembayaran
                                            </div>
                                            <a href="{{ route('admin.pembayaran.show', $k->pembayaran->id) }}" 
                                               class="p-2.5 bg-white text-slate-700 border-2 border-slate-300 rounded-lg hover:bg-slate-100 transition-all shadow-sm active:scale-95" title="Lihat Detail Tagihan">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-100 border border-slate-300 text-slate-500 text-xs font-extrabold shadow-sm uppercase tracking-wider">
                                        Belum Ada Tagihan
                                    </div>
                                @endif
                            </div>
                        </div>
                    </details>
                    @empty
                    <div class="py-12 text-center border-2 border-dashed border-slate-300 rounded-xl bg-slate-50">
                        <div class="flex flex-col items-center justify-center">
                            <div class="bg-emerald-50 p-5 rounded-full mb-4 border border-emerald-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 mb-1 uppercase tracking-wide">Belum Ada Kunjungan</h3>
                            <p class="text-slate-500 text-sm">Pasien ini belum memiliki riwayat kunjungan atau transaksi.</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </section>

            {{-- SEKSI REKAM MEDIS --}}
            <section class="bg-white rounded-2xl shadow-md border-2 border-slate-200 p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6 pb-5 border-b-2 border-slate-100">
                    <div class="bg-blue-100 p-2 rounded-lg text-blue-600 border border-blue-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">Riwayat Klinis & Rekam Medis</h2>
                </div>

                <div class="space-y-4">
                    @forelse($rekamMedis as $rm)
                    {{-- ACCORDION REKAM MEDIS --}}
                    <details class="group bg-white border-2 border-slate-200 rounded-xl overflow-hidden shadow-sm" {{ $loop->first ? 'open' : '' }}>
                        
                        {{-- HEADER ACCORDION --}}
                        <summary class="flex justify-between items-center cursor-pointer p-5 bg-slate-50 hover:bg-slate-100 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-blue-600 border border-slate-200 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-base font-extrabold text-slate-900">{{ $rm->created_at->translatedFormat('d M Y') }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="inline-flex px-2 py-0.5 rounded bg-blue-100 text-blue-700 text-[10px] font-black uppercase border border-blue-200">
                                            Poli: {{ $rm->pendaftaran->poli ?? '-' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-200 text-slate-500 accordion-icon transition-transform duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </summary>

                        {{-- BODY ACCORDION --}}
                        <div class="p-6 md:p-8 border-t-2 border-slate-200 bg-white space-y-6">
                            
                            {{-- BLOK KELUHAN DAN TANDA VITAL --}}
                            <div class="grid md:grid-cols-12 gap-5">
                                {{-- Keluhan Utama --}}
                                <div class="md:col-span-12 bg-amber-50 p-5 rounded-xl border border-amber-200 shadow-sm">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                        <p class="text-xs font-black text-amber-700 uppercase tracking-widest">Keluhan Utama</p>
                                    </div>
                                    <p class="text-sm font-medium text-slate-800">{{ $rm->keluhan ?? $rm->pendaftaran->keluhan ?? '-' }}</p>
                                </div>

                                {{-- Pemeriksaan Fisik (Grid) --}}
                                <div class="md:col-span-12 grid grid-cols-2 md:grid-cols-4 gap-4">
                                    {{-- BB --}}
                                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex flex-col justify-center items-center text-center shadow-sm">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Berat Badan</span>
                                        <p class="text-lg font-black text-slate-800">{{ $rm->berat_badan ?? '-' }} <span class="text-xs font-bold text-slate-500">kg</span></p>
                                    </div>
                                    {{-- TB --}}
                                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex flex-col justify-center items-center text-center shadow-sm">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tinggi Badan</span>
                                        <p class="text-lg font-black text-slate-800">{{ $rm->tinggi_badan ?? '-' }} <span class="text-xs font-bold text-slate-500">cm</span></p>
                                    </div>
                                    {{-- Tensi --}}
                                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex flex-col justify-center items-center text-center shadow-sm">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tensi Darah</span>
                                        <p class="text-lg font-black text-slate-800">{{ $rm->tekanan_darah ?? $rm->tensi ?? '-' }} <span class="text-xs font-bold text-slate-500">mmHg</span></p>
                                    </div>
                                    {{-- Suhu --}}
                                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex flex-col justify-center items-center text-center shadow-sm">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Suhu Tubuh</span>
                                        <p class="text-lg font-black text-slate-800">{{ $rm->suhu_tubuh ?? $rm->suhu ?? '-' }} <span class="text-xs font-bold text-slate-500">°C</span></p>
                                    </div>
                                </div>
                            </div>

                            {{-- DIAGNOSIS & TINDAKAN MEDIS --}}
                            <div class="grid md:grid-cols-2 gap-5 pt-2">
                                <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 shadow-sm">
                                    <div class="flex items-center gap-2 mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Diagnosis</p>
                                    </div>
                                    <p class="text-sm font-bold text-slate-800 leading-relaxed italic">"{{ $rm->diagnosis }}"</p>
                                </div>
                                
                                <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 shadow-sm">
                                    <div class="flex items-center gap-2 mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                        </svg>
                                        <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Tindakan Medis</p>
                                    </div>
                                    <p class="text-sm font-medium text-slate-800 leading-relaxed">{{ $rm->tindakan }}</p>
                                </div>
                            </div>

                            {{-- RESEP OBAT --}}
                            @if($rm->resep)
                            <div class="mt-2 pt-5 border-t-2 border-slate-100">
                                <div class="flex items-center gap-2 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                    <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Resep Obat</p>
                                </div>
                                <div class="flex flex-wrap gap-2.5">
                                    @php
                                        $obatArray = explode(',', $rm->resep);
                                    @endphp
                                    @foreach($obatArray as $obat)
                                    <span class="px-3 py-1.5 bg-emerald-50 border border-emerald-200 text-xs font-extrabold text-emerald-800 rounded-lg shadow-sm uppercase">
                                        {{ trim($obat) }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </details>
                    @empty
                    <div class="py-12 text-center border-2 border-dashed border-slate-300 rounded-xl bg-slate-50">
                        <div class="flex flex-col items-center justify-center">
                            <div class="bg-slate-200 p-5 rounded-full mb-4 border border-slate-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 mb-1 uppercase tracking-wide">Belum Ada Rekam Medis</h3>
                            <p class="text-slate-500 text-sm">Dokter belum mencatat riwayat diagnosis atau tindakan untuk pasien ini.</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</div>

@endsection
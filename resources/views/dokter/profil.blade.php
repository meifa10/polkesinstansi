@extends('layouts.dokter')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #f8fafc; /* slate-50 */
    }
</style>

<div class="min-h-[calc(100vh-80px)] flex items-center justify-center p-4 md:p-8">

    {{-- KOTAK UTAMA PROFIL --}}
    <div class="w-full max-w-3xl bg-white rounded-[2rem] shadow-xl shadow-slate-200/60 border border-slate-200 overflow-hidden relative">
        
        {{-- ACCENT TOP BAR (BANNER) --}}
        <div class="h-40 bg-slate-900 relative">
            {{-- Dekorasi Pattern Abstract di Banner (Opsional) --}}
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 20px 20px;"></div>
        </div>

        <div class="px-6 md:px-12 pb-12 relative">
            
            {{-- HEADER / AVATAR --}}
            <div class="flex flex-col items-center -mt-20 mb-10 relative z-10">
                {{-- Lingkaran Avatar --}}
                <div class="w-36 h-36 bg-emerald-500 rounded-3xl border-8 border-white shadow-lg flex items-center justify-center text-white text-5xl font-black mb-5 transition-transform hover:-translate-y-2 duration-300">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                
                {{-- Nama dan Badge --}}
                <div class="text-center">
                    <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight uppercase mb-3">
                        {{ auth()->user()->name }}
                    </h1>
                    <div class="flex flex-wrap items-center justify-center gap-2.5">
                        <span class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-emerald-100 text-emerald-800 text-xs font-extrabold rounded-full uppercase tracking-widest border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            {{ auth()->user()->role }} Pelaksana
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-blue-100 text-blue-800 text-xs font-extrabold rounded-full uppercase tracking-widest border border-blue-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Terverifikasi
                        </span>
                    </div>
                </div>
            </div>

            {{-- GRID INFORMASI (Lebih besar, garis tegas, font jelas) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-10">
                
                {{-- Kartu Email --}}
                <div class="bg-slate-50 p-6 rounded-2xl border-2 border-slate-100 hover:border-emerald-300 hover:bg-white transition-colors group">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-emerald-100 rounded-lg text-emerald-600 group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-xs font-extrabold text-slate-500 uppercase tracking-widest">Alamat Surel</p>
                    </div>
                    <h2 class="text-base font-bold text-slate-900 pl-11">{{ auth()->user()->email }}</h2>
                </div>

                {{-- Kartu Status --}}
                <div class="bg-slate-50 p-6 rounded-2xl border-2 border-slate-100 hover:border-emerald-300 hover:bg-white transition-colors group">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-emerald-100 rounded-lg text-emerald-600 group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <p class="text-xs font-extrabold text-slate-500 uppercase tracking-widest">Status Akun</p>
                    </div>
                    <h2 class="text-base font-black text-emerald-600 uppercase pl-11">Aktif & Terintegrasi</h2>
                </div>

                {{-- Kartu Bergabung --}}
                <div class="bg-slate-50 p-6 rounded-2xl border-2 border-slate-100 hover:border-emerald-300 hover:bg-white transition-colors group">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-emerald-100 rounded-lg text-emerald-600 group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-xs font-extrabold text-slate-500 uppercase tracking-widest">Masa Bakti Dimulai</p>
                    </div>
                    <h2 class="text-base font-black text-slate-900 uppercase pl-11">
                        {{ auth()->user()->created_at->translatedFormat('d F Y') }}
                    </h2>
                </div>

                {{-- Kartu Kode Identitas --}}
                <div class="bg-slate-50 p-6 rounded-2xl border-2 border-slate-100 hover:border-emerald-300 hover:bg-white transition-colors group">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-emerald-100 rounded-lg text-emerald-600 group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                            </svg>
                        </div>
                        <p class="text-xs font-extrabold text-slate-500 uppercase tracking-widest">Kode Identitas Dokter</p>
                    </div>
                    <h2 class="text-base font-black tracking-widest font-mono text-slate-900 pl-11">
                        PKJ-DR-{{ str_pad(auth()->user()->id, 3, '0', STR_PAD_LEFT) }}
                    </h2>
                </div>

            </div>

            {{-- TOMBOL AKSI & KETERANGAN --}}
            <div class="flex flex-col items-center gap-4 pt-6 border-t-2 border-slate-100">
                <a href="{{ route('dokter.dashboard') }}"
                   class="inline-flex items-center justify-center gap-2 bg-slate-900 hover:bg-emerald-600 text-white px-8 py-4 rounded-xl text-sm font-black uppercase tracking-widest transition-all shadow-md active:scale-95 w-full md:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali Ke Dashboard
                </a>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider text-center">
                    Data profil dikelola secara otomatis oleh sistem pusat Polkes.
                </p>
            </div>

        </div>

        {{-- DEKORASI BAWAH --}}
        <div class="h-2 bg-emerald-500 w-full absolute bottom-0 left-0"></div>
    </div>

</div>

@endsection
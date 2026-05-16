@extends('layouts.dokter')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
<script src="https://unpkg.com/@phosphor-icons/web"></script>

<div class="min-h-screen bg-slate-100 flex items-center justify-center p-6 font-['Plus_Jakarta_Sans']">

    <div class="w-full max-w-2xl bg-white rounded-[3rem] shadow-2xl shadow-slate-200 border border-white overflow-hidden relative">
        
        {{-- ACCENT TOP BAR --}}
        <div class="h-32 bg-slate-900 w-full absolute top-0 left-0"></div>

        <div class="relative px-8 pt-16 pb-10">
            
            {{-- HEADER / AVATAR --}}
            <div class="flex flex-col items-center mb-10">
                <div class="w-28 h-28 bg-emerald-500 rounded-[2.5rem] border-8 border-white shadow-xl flex items-center justify-center text-white text-4xl font-black mb-4 transition-transform hover:rotate-6 duration-300">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="text-center">
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight leading-none uppercase">
                        {{ auth()->user()->name }}
                    </h1>
                    <div class="flex items-center justify-center gap-2 mt-2">
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-black rounded-full uppercase tracking-widest border border-emerald-200">
                            {{ auth()->user()->role }} Pelaksana
                        </span>
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-[10px] font-black rounded-full uppercase tracking-widest border border-blue-200">
                            Terverifikasi
                        </span>
                    </div>
                </div>
            </div>

            {{-- INFO GRID --}}
            <div class="grid md:grid-cols-2 gap-4 mb-10">
                
                {{-- Email --}}
                <div class="bg-slate-50 p-5 rounded-[2rem] border border-slate-200 group hover:bg-white hover:border-emerald-500 transition-all">
                    <div class="flex items-center gap-3 mb-1">
                        <i class="ph-fill ph-envelope-simple text-emerald-600 text-lg"></i>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Alamat Surel</p>
                    </div>
                    <h2 class="text-sm font-bold text-slate-900 italic lowercase">{{ auth()->user()->email }}</h2>
                </div>

                {{-- Status --}}
                <div class="bg-slate-50 p-5 rounded-[2rem] border border-slate-200 group hover:bg-white hover:border-emerald-500 transition-all">
                    <div class="flex items-center gap-3 mb-1">
                        <i class="ph-fill ph-shield-check text-emerald-600 text-lg"></i>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Akun</p>
                    </div>
                    <h2 class="text-sm font-black text-emerald-600 uppercase">Aktif & Terintegrasi</h2>
                </div>

                {{-- Bergabung --}}
                <div class="bg-slate-50 p-5 rounded-[2rem] border border-slate-200 group hover:bg-white hover:border-emerald-500 transition-all">
                    <div class="flex items-center gap-3 mb-1">
                        <i class="ph-fill ph-calendar-plus text-emerald-600 text-lg"></i>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Masa Bakti</p>
                    </div>
                    <h2 class="text-sm font-black text-slate-900 uppercase">
                        {{ auth()->user()->created_at->translatedFormat('d F Y') }}
                    </h2>
                </div>

                {{-- Kode Dokter --}}
                <div class="bg-slate-50 p-5 rounded-[2rem] border border-slate-200 group hover:bg-white hover:border-emerald-500 transition-all text-slate-900">
                    <div class="flex items-center gap-3 mb-1">
                        <i class="ph-fill ph-identification-card text-emerald-600 text-lg"></i>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kode Identitas</p>
                    </div>
                    <h2 class="text-sm font-black tracking-widest font-mono uppercase">
                        PKJ-DR-{{ str_pad(auth()->user()->id, 3, '0', STR_PAD_LEFT) }}
                    </h2>
                </div>

            </div>

            {{-- ACTION BUTTON --}}
            <div class="flex flex-col items-center gap-4">
                <a href="{{ route('dokter.dashboard') }}"
                   class="inline-flex items-center gap-2 bg-slate-900 hover:bg-emerald-600 text-white px-10 py-4 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] transition-all shadow-xl shadow-slate-200 active:scale-95">
                    <i class="ph-bold ph-arrow-left"></i>
                    Kembali Ke Dashboard
                </a>
                <p class="text-[10px] text-slate-400 font-bold italic uppercase tracking-wider text-center">
                    * Data profil ini dikelola secara otomatis oleh sistem pusat Polkes Jombang.
                </p>
            </div>

        </div>

        {{-- BOTTOM DECORATION --}}
        <div class="h-2 bg-emerald-500 w-full"></div>
    </div>

</div>

<style>
    body {
        background-color: #f1f5f9;
        -webkit-font-smoothing: antialiased;
        text-rendering: optimizeLegibility;
    }
    
    /* Hover effect for info boxes */
    .bg-slate-50 {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>

@endsection
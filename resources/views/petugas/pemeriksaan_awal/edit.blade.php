@extends('layouts.petugas') {{-- Pastikan nama layout sesuai dengan yang Anda gunakan --}}

@section('content')

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    body { 
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
</style>

<div class="p-6 lg:p-8 bg-slate-50 min-h-screen">

    {{-- TOMBOL KEMBALI --}}
    <div class="mb-6 max-w-6xl mx-auto">
        <a href="{{ route('petugas.pemeriksaan_awal.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-emerald-600 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar Antrean
        </a>
    </div>

    <div class="max-w-6xl mx-auto">
        
        {{-- HEADER SECTION --}}
        <div class="mb-8">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-sm font-bold tracking-wide uppercase mb-3 border border-emerald-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                </svg>
                Formulir Triage
            </div>
            <h1 class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">
                Pemeriksaan <span class="text-emerald-600">Vital Sign</span>
            </h1>
            <p class="text-slate-600 font-medium mt-2 text-base">
                Lengkapi data dasar fisik dan tanda vital pasien sebelum melakukan konsultasi dengan dokter.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            {{-- LEFT COLUMN: INFO PASIEN & INSTRUKSI --}}
            <div class="lg:col-span-4 space-y-6">
                
                {{-- KARTU IDENTITAS PASIEN (Dark Theme) --}}
                <div class="bg-slate-900 rounded-2xl p-8 text-white shadow-lg border border-slate-800 relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mr-16 -mt-16 w-48 h-48 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
                    
                    <h3 class="text-xs font-black uppercase tracking-[0.2em] text-emerald-400 mb-6 relative z-10">Data Identitas</h3>
                    
                    <div class="space-y-6 relative z-10">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Nama Lengkap</p>
                            <p class="text-xl font-bold leading-tight">{{ $pasien->nama_pasien }}</p>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-1.5 bg-emerald-500 rounded-full"></div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Unit Layanan</p>
                                <p class="text-sm font-bold">{{ $pasien->poli }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">ID Pendaftaran</p>
                            <p class="text-sm font-bold font-mono text-emerald-100">#REG-{{ str_pad($pasien->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>

                    <div class="mt-8 p-4 bg-slate-800/50 rounded-xl border border-slate-700 backdrop-blur-sm relative z-10">
                        <p class="text-[10px] font-bold text-slate-300 uppercase tracking-wider text-center">
                            Petugas Pemeriksa: <span class="text-white">{{ auth()->user()->name ?? 'Petugas' }}</span>
                        </p>
                    </div>
                </div>

                {{-- INSTRUKSI PENGISIAN --}}
                <div class="bg-emerald-50 rounded-2xl p-6 border border-emerald-100">
                    <h4 class="text-sm font-bold text-emerald-800 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Instruksi Pengisian
                    </h4>
                    <ul class="text-sm text-emerald-700 space-y-3 font-medium">
                        <li class="flex items-start gap-2">
                            <span class="font-black text-emerald-600">1.</span> 
                            Gunakan timbangan digital untuk Berat Badan (BB).
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="font-black text-emerald-600">2.</span> 
                            Pastikan pasien berdiri tegak tanpa alas kaki untuk Tinggi Badan (TB).
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="font-black text-emerald-600">3.</span> 
                            Gunakan tensimeter untuk mengukur Tensi (cth: 120/80).
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="font-black text-emerald-600">4.</span> 
                            Tulis keluhan utama secara spesifik, padat, dan jelas.
                        </li>
                    </ul>
                </div>
            </div>

            {{-- RIGHT COLUMN: FORM INPUT --}}
            <div class="lg:col-span-8">
                <div class="bg-white rounded-2xl p-8 lg:p-10 shadow-sm border border-slate-200">
                    <form method="POST" action="{{ route('petugas.pemeriksaan_awal.update', $pasien->id) }}" class="space-y-8">
                        @csrf
                        {{-- @method('PUT') --}}

                        {{-- Ubah grid menjadi 3 kolom untuk BB, TB, dan Tensi --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            {{-- BERAT BADAN --}}
                            <div>
                                <label class="flex items-center gap-2 text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                                    </svg>
                                    Berat Badan
                                </label>
                                <div class="relative">
                                    <input type="number" step="0.1" name="berat_badan" placeholder="0.0" required
                                        class="w-full pl-5 pr-12 py-4 bg-slate-50 border border-slate-300 rounded-xl text-lg font-bold text-slate-800 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all shadow-sm focus:bg-white placeholder:text-slate-400">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                        <span class="text-slate-500 font-black">kg</span>
                                    </div>
                                </div>
                            </div>

                            {{-- TINGGI BADAN --}}
                            <div>
                                <label class="flex items-center gap-2 text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                    </svg>
                                    Tinggi Badan
                                </label>
                                <div class="relative">
                                    <input type="number" step="0.1" name="tinggi_badan" placeholder="0.0" required
                                        class="w-full pl-5 pr-12 py-4 bg-slate-50 border border-slate-300 rounded-xl text-lg font-bold text-slate-800 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all shadow-sm focus:bg-white placeholder:text-slate-400">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                        <span class="text-slate-500 font-black">cm</span>
                                    </div>
                                </div>
                            </div>

                            {{-- TENSI (TEKANAN DARAH) --}}
                            <div>
                                <label class="flex items-center gap-2 text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    Tensi Darah
                                </label>
                                <div class="relative">
                                    <input type="text" name="tensi" placeholder="120/80" required
                                        class="w-full pl-5 pr-16 py-4 bg-slate-50 border border-slate-300 rounded-xl text-lg font-bold text-slate-800 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all shadow-sm focus:bg-white placeholder:text-slate-400">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                        <span class="text-slate-500 font-black text-xs">mmHg</span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- KELUHAN --}}
                        <div>
                            <label class="flex items-center gap-2 text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Keluhan Utama Pasien
                            </label>
                            <textarea name="keluhan" rows="5" placeholder="Deskripsikan keluhan utama pasien secara detail..." required
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all shadow-sm focus:bg-white resize-y placeholder:text-slate-400"></textarea>
                        </div>

                        {{-- TOMBOL SUBMIT --}}
                        <div class="pt-6 border-t border-slate-100">
                            <button type="submit" 
                                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-4 rounded-xl shadow-lg shadow-emerald-200 flex items-center justify-center gap-3 transition-colors text-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Simpan Data Pemeriksaan
                            </button>
                            <p class="text-center text-xs text-slate-400 font-bold uppercase tracking-wider mt-4">
                                Data yang disimpan akan langsung diteruskan ke bagian verifikasi admin/dokter.
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
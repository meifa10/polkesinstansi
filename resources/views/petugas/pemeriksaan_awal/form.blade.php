@extends('petugas.petugas')

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
    <div class="mb-6">
        <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-emerald-600 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar Antrean
        </a>
    </div>

    {{-- HEADER SECTION --}}
    <div class="mb-8">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-sm font-bold tracking-wide uppercase mb-3 border border-emerald-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
            </svg>
            Formulir Triage
        </div>
        <h1 class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">
            Input <span class="text-emerald-600">Pemeriksaan Awal</span>
        </h1>
        <p class="text-slate-600 font-medium mt-2 text-base">
            Silakan lengkapi data tanda vital dan keluhan utama pasien di bawah ini.
        </p>
    </div>

    {{-- MAIN FORM CARD --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 lg:p-10 max-w-4xl">
        <form method="POST" action="{{ route('petugas.pemeriksaan_awal.update', $pasien->id) }}">
            @csrf
            {{-- Tambahkan @method('PUT') jika di route Anda menggunakan method PUT/PATCH --}}
            {{-- @method('PUT') --}}

            {{-- BIODATA (READONLY) --}}
            <div class="mb-8 p-5 bg-slate-50 border border-slate-200 rounded-xl flex flex-col md:flex-row md:items-center gap-5">
                <div class="w-16 h-16 rounded-xl bg-slate-200 text-slate-500 flex items-center justify-center font-black text-2xl flex-shrink-0 shadow-sm border border-slate-300">
                    {{ strtoupper(substr($pasien->nama_pasien, 0, 1)) }}
                </div>
                <div class="flex-grow">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Nama Pasien</label>
                    <input type="text" value="{{ $pasien->nama_pasien }}" readonly
                        class="w-full bg-transparent border-none p-0 text-2xl font-extrabold text-slate-800 focus:ring-0 cursor-not-allowed uppercase tracking-tight outline-none pointer-events-none">
                </div>
                <div class="hidden md:block w-px h-12 bg-slate-300 mx-4"></div>
                <div class="md:text-right">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-1">ID Pemeriksaan</label>
                    <p class="text-lg font-bold text-slate-600">#PX-{{ $pasien->id + 1000 }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                {{-- BERAT BADAN --}}
                <div>
                    <label class="flex items-center gap-2 text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                        </svg>
                        Berat Badan (BB)
                    </label>
                    <div class="relative">
                        <input type="number" step="0.1" name="bb" placeholder="0.0" required
                            class="w-full pl-5 pr-14 py-4 bg-white border border-slate-300 rounded-xl text-lg font-bold text-slate-800 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all shadow-sm placeholder:text-slate-300">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-5 pointer-events-none">
                            <span class="text-slate-400 font-black">kg</span>
                        </div>
                    </div>
                </div>

                {{-- TINGGI BADAN --}}
                <div>
                    <label class="flex items-center gap-2 text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                        </svg>
                        Tinggi Badan (TB)
                    </label>
                    <div class="relative">
                        <input type="number" step="0.1" name="tb" placeholder="0.0" required
                            class="w-full pl-5 pr-14 py-4 bg-white border border-slate-300 rounded-xl text-lg font-bold text-slate-800 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all shadow-sm placeholder:text-slate-300">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-5 pointer-events-none">
                            <span class="text-slate-400 font-black">cm</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KELUHAN --}}
            <div class="mb-8">
                <label class="flex items-center gap-2 text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Keluhan Utama
                </label>
                <textarea name="keluhan" rows="4" placeholder="Tuliskan keluhan yang dirasakan pasien dengan detail..." required
                    class="w-full px-5 py-4 bg-white border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all shadow-sm resize-y"></textarea>
            </div>

            {{-- TOMBOL SUBMIT --}}
            <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-slate-100">
                <button type="submit" class="inline-flex items-center justify-center gap-2 bg-emerald-600 text-white px-8 py-4 rounded-xl text-base font-bold hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-200 flex-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Simpan Data Pemeriksaan
                </button>
                <a href="{{ url()->previous() }}" class="inline-flex items-center justify-center gap-2 bg-slate-100 text-slate-600 px-8 py-4 rounded-xl text-base font-bold hover:bg-slate-200 transition-colors border border-slate-300 sm:w-auto">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>

@endsection
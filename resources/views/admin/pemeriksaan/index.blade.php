@extends('layouts.admin')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    body { 
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    
    /* Custom Styling for Date Input Icon */
    input[type="date"]::-webkit-calendar-picker-indicator {
        opacity: 0.6;
        cursor: pointer;
        transition: opacity 0.2s;
    }
    input[type="date"]::-webkit-calendar-picker-indicator:hover {
        opacity: 1;
    }
</style>

<div class="p-6 lg:p-8 bg-slate-50 min-h-screen font-sans">

    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-8 gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-sm font-bold tracking-wide uppercase mb-3 border border-emerald-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                </svg>
                Admin / Rekam Medis
            </div>
            <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight">
                Laporan <span class="text-emerald-600">Pemeriksaan</span>
            </h1>
            <p class="text-slate-600 font-medium mt-3 text-base lg:text-lg">
                Pantau rekam medis, keluhan pasien, diagnosis, dan resep obat yang diberikan.
            </p>
        </div>

        {{-- Form Export --}}
        <form method="GET" action="{{ route('admin.pemeriksaan') }}" class="w-full md:w-auto">
            <input type="hidden" name="q" value="{{ request('q') }}">
            <input type="hidden" name="poli" value="{{ request('poli') }}">
            <input type="hidden" name="tanggal" value="{{ request('tanggal') }}">
            
            <button type="submit" name="download" value="1" 
                class="w-full md:w-auto flex items-center justify-center gap-2.5 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3.5 rounded-xl shadow-md shadow-emerald-500/20 transition-all font-bold text-base active:scale-95 border border-emerald-700 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:-translate-y-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export ke Excel
            </button>
        </form>
    </div>

    {{-- FILTER BOX --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 lg:p-8 mb-8">
        <div class="flex items-center gap-3 mb-6 pb-5 border-b border-slate-100">
            <div class="bg-emerald-100 p-2 rounded-lg text-emerald-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                </svg>
            </div>
            <h2 class="text-xl font-bold text-slate-800">Pencarian & Filter Data</h2>
        </div>

        <form method="GET" action="{{ route('admin.pemeriksaan') }}" class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
            
            {{-- Search --}}
            <div class="md:col-span-5 relative">
                <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Cari Pemeriksaan</label>
                <div class="absolute inset-y-0 bottom-0 left-0 pl-4 flex items-center pointer-events-none" style="top: 28px;">
                    <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Nama Pasien atau Diagnosis..."
                    class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none">
            </div>

            {{-- Filter Poli --}}
            <div class="md:col-span-3 relative">
                <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Poliklinik</label>
                <select name="poli" onchange="this.form.submit()" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none appearance-none cursor-pointer">
                    <option value="">Semua Poliklinik</option>
                    <option value="Poli Umum" {{ request('poli') == 'Poli Umum' ? 'selected' : '' }}>Poli Umum</option>
                    <option value="Poli Gigi" {{ request('poli') == 'Poli Gigi' ? 'selected' : '' }}>Poli Gigi</option>
                    <option value="Poli KIA & KB" {{ request('poli') == 'Poli KIA & KB' ? 'selected' : '' }}>Poli KIA & KB</option>
                </select>
                <div class="absolute inset-y-0 bottom-0 right-0 flex items-center pr-4 pointer-events-none" style="top: 28px;">
                    <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            {{-- Single Date Filter --}}
            <div class="md:col-span-4 flex gap-3">
                <div class="relative flex-1">
                    <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}" 
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none cursor-pointer">
                </div>
                
                <div class="flex gap-2 pb-[1px]">
                    <button type="submit" class="flex items-center justify-center bg-slate-800 text-white px-6 py-3.5 mt-auto rounded-xl font-bold text-base hover:bg-slate-900 transition-colors shadow-md h-[50px]">
                        Filter
                    </button>
                    @if(request('q') || request('poli') || request('tanggal'))
                        <a href="{{ route('admin.pemeriksaan') }}" class="flex items-center justify-center px-4 py-3.5 mt-auto bg-slate-100 text-slate-600 rounded-xl text-base font-bold hover:bg-slate-200 transition-colors border border-slate-300 h-[50px]" title="Reset Filter">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- DATA TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1200px]">
                <thead>
                    <tr class="bg-emerald-900 text-white text-sm uppercase tracking-widest font-bold">
                        <th class="py-5 px-6 w-20 text-center rounded-tl-xl">No</th>
                        <th class="py-5 px-6 min-w-[260px]">Informasi Pasien</th>
                        <th class="py-5 px-6 min-w-[220px]">Keluhan Utama</th>
                        <th class="py-5 px-6 min-w-[300px]">Diagnosis & Tindakan</th>
                        <th class="py-5 px-6 min-w-[220px]">Resep Obat (Item)</th>
                        <th class="py-5 px-6 min-w-[180px] text-center rounded-tr-xl">Waktu Periksa</th>
                    </tr>
                </thead>

                <tbody class="text-base divide-y divide-slate-200">
                    @forelse($pemeriksaan as $item)
                    <tr class="hover:bg-emerald-50/60 transition-colors group">
                        
                        {{-- NO --}}
                        <td class="py-6 px-6 text-center align-middle">
                            <div class="font-extrabold text-slate-500 text-lg group-hover:text-emerald-600 transition-colors">{{ $loop->iteration }}</div>
                        </td>
                        
                        {{-- INFORMASI PASIEN --}}
                        <td class="py-6 px-6 align-middle">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-slate-900 text-white flex items-center justify-center font-black text-xl shadow-md flex-shrink-0">
                                    {{ strtoupper(substr($item->pendaftaran->nama_pasien ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-extrabold text-slate-800 text-lg uppercase tracking-tight leading-tight mb-1">{{ $item->pendaftaran->nama_pasien ?? '-' }}</p>
                                    <div class="flex flex-col gap-1.5">
                                        <p class="text-xs text-slate-500 font-bold tracking-wider font-mono">NIK: {{ $item->pendaftaran->no_identitas ?? '-' }}</p>
                                        <span class="inline-flex items-center w-max px-2.5 py-0.5 rounded-md bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-wider border border-emerald-300 shadow-sm">
                                            {{ $item->pendaftaran->poli ?? '-' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- KELUHAN UTAMA --}}
                        <td class="py-6 px-6 align-middle">
                            <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-300 absolute -top-2 -left-2 bg-white rounded-full" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                                <p class="text-sm font-bold text-slate-700 leading-relaxed italic mt-1 ml-1 line-clamp-3">
                                    "{{ $item->keluhan ?? 'Tidak ada keluhan' }}"
                                </p>
                            </div>
                        </td>

                        {{-- DIAGNOSIS & TINDAKAN --}}
                        <td class="py-6 px-6 align-middle">
                            <div class="space-y-3">
                                <div class="bg-white p-3.5 rounded-xl border border-slate-200 shadow-sm">
                                    <div class="flex items-center gap-2 mb-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Diagnosis</p>
                                    </div>
                                    <p class="text-sm font-bold text-slate-800 leading-tight">{{ $item->diagnosis }}</p>
                                </div>
                                <div class="bg-white p-3.5 rounded-xl border border-slate-200 shadow-sm">
                                    <div class="flex items-center gap-2 mb-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                        </svg>
                                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Tindakan</p>
                                    </div>
                                    <p class="text-sm font-medium text-slate-700 leading-tight">{{ $item->tindakan }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- RESEP OBAT --}}
                        <td class="py-6 px-6 align-middle">
                            @if($item->resep)
                                <div class="flex flex-wrap gap-2">
                                    @php
                                        $obatArray = preg_split('/[\n,]+/', $item->resep);
                                    @endphp
                                    @foreach($obatArray as $obat)
                                        @if(trim($obat) !== "")
                                            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 border border-slate-300 text-xs font-extrabold text-slate-700 rounded-lg shadow-sm uppercase tracking-wide">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                {{ trim($obat) }}
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-200 text-slate-400 text-xs font-bold italic tracking-wide">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                    Tanpa Resep
                                </div>
                            @endif
                        </td>

                        {{-- WAKTU PERIKSA --}}
                        <td class="py-6 px-6 align-middle text-center">
                            <p class="font-black text-slate-900 text-base mb-1">{{ $item->created_at->translatedFormat('d M Y') }}</p>
                            <span class="inline-flex items-center justify-center gap-1.5 px-3 py-1 bg-slate-100 border border-slate-200 text-slate-600 rounded-lg text-xs font-bold tracking-widest">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $item->created_at->format('H:i') }} WIB
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-24 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="bg-emerald-50 p-6 rounded-full mb-5 border border-emerald-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-slate-800 mb-2 uppercase tracking-wide">Data Pemeriksaan Tidak Ditemukan</h3>
                                <p class="text-slate-500 text-base max-w-md">
                                    Silakan sesuaikan filter pencarian, poliklinik, atau tanggal untuk menemukan riwayat pemeriksaan.
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- FOOTER INFO --}}
        <div class="bg-slate-50 px-6 py-4 border-t border-slate-200">
            <p class="text-xs text-slate-500 font-extrabold italic uppercase tracking-wider flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Menampilkan laporan rekam medis pasien terdaftar.
            </p>
        </div>
    </div>
</div>

@endsection
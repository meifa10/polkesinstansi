@extends('layouts.petugas')

@section('content')

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    body { 
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
</style>

<div class="p-6 lg:p-8 bg-slate-50 min-h-screen">

    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-8 gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-sm font-bold tracking-wide uppercase mb-3 border border-emerald-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v2H5V6zm6 5H5v2h6v-2z" clip-rule="evenodd" />
                    <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z" />
                </svg>
                Sistem Arsip Medis
            </div>
            <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight">
                Riwayat <span class="text-emerald-600">Pendaftaran</span>
            </h1>
            <p class="text-slate-600 font-medium mt-3 text-base lg:text-lg">
                Menampilkan seluruh data pemeriksaan awal pasien yang tersimpan di database.
            </p>
        </div>

        {{-- METRIC CARD --}}
        <div class="bg-white px-8 py-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-6 min-w-[220px]">
            <div class="p-4 bg-emerald-50 rounded-xl text-emerald-600 border border-emerald-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm uppercase font-bold text-slate-400 tracking-wider">Total Entri</p>
                <h2 class="text-4xl font-black text-slate-800 leading-none mt-1">{{ $pendaftaran->count() }}</h2>
            </div>
        </div>
    </div>

    {{-- FILTER & SEARCH BOX --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 mb-8">
        <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-3">Pencarian & Filter Data</label>
        <form method="GET" action="{{ route('petugas.pendaftaran.index') }}" class="flex flex-col lg:flex-row gap-4">
            
            {{-- Search Bar --}}
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-6 w-6 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" name="q" value="{{ request('q') }}" 
                    placeholder="Cari nama pasien atau NIK..." 
                    class="w-full pl-12 pr-5 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
            </div>

            {{-- Dropdown Status --}}
            <div class="relative min-w-[250px]">
                <select name="status" onchange="this.form.submit()" 
                    class="w-full px-5 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all appearance-none cursor-pointer">
                    <option value="" class="font-medium">Semua Status</option>
                    <option value="menunggu_petugas" {{ request('status') == 'menunggu_petugas' ? 'selected' : '' }} class="font-medium">Menunggu Petugas</option>
                    <option value="menunggu_admin" {{ request('status') == 'menunggu_admin' ? 'selected' : '' }} class="font-medium">Menunggu Admin</option>
                    <option value="diproses_dokter" {{ request('status') == 'diproses_dokter' ? 'selected' : '' }} class="font-medium">Diproses Dokter</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }} class="font-medium">Selesai</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                    <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3">
                <button type="submit" class="bg-slate-800 text-white px-8 py-3.5 rounded-xl text-base font-bold hover:bg-slate-900 transition-colors shadow-md flex-1 lg:flex-none">
                    Filter
                </button>
                @if(request('q') || request('status'))
                    <a href="{{ route('petugas.pendaftaran.index') }}" class="bg-slate-100 text-slate-600 px-6 py-3.5 rounded-xl text-base font-bold hover:bg-slate-200 transition-colors border border-slate-300 flex items-center justify-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- DATA TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-emerald-900 text-white text-sm uppercase tracking-widest font-bold">
                        <th class="py-5 px-6 w-16 text-center rounded-tl-xl">No</th>
                        <th class="py-5 px-6 min-w-[300px]">Biodata Pasien</th>
                        <th class="py-5 px-6 min-w-[200px]">Unit Layanan</th>
                        <th class="py-5 px-6 min-w-[200px] text-center">Tanda Vital</th>
                        <th class="py-5 px-6 min-w-[200px] text-center rounded-tr-xl">Status Alur</th>
                    </tr>
                </thead>
                <tbody class="text-base divide-y divide-slate-200">
                    @forelse($pendaftaran as $item)
                    <tr class="hover:bg-emerald-50/60 transition-colors group">
                        
                        {{-- Nomor --}}
                        <td class="py-5 px-6 text-center text-slate-500 font-bold align-middle text-lg">
                            {{ $loop->iteration }}
                        </td>
                        
                        {{-- Biodata Pasien --}}
                        <td class="py-5 px-6 align-middle">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-black text-xl border border-emerald-200 shadow-sm flex-shrink-0">
                                    {{ strtoupper(substr($item->nama_pasien, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-extrabold text-slate-800 text-lg uppercase tracking-tight">{{ $item->nama_pasien }}</p>
                                    <div class="flex items-center gap-1.5 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                                        </svg>
                                        <p class="text-sm font-bold text-slate-500">{{ $item->no_identitas }}</p>
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Unit Layanan / Poli --}}
                        <td class="py-5 px-6 align-middle">
                            <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-sm font-extrabold uppercase border border-slate-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" />
                                </svg>
                                {{ $item->poli }}
                            </span>
                        </td>

                        {{-- Tanda Vital --}}
                        <td class="py-5 px-6 align-middle text-center">
                            <div class="flex items-center justify-center gap-5">
                                <div class="bg-slate-50 px-3 py-2 rounded-lg border border-slate-200">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Berat</p>
                                    <p class="text-base font-extrabold text-slate-800">{{ $item->berat_badan ?? '-' }}<span class="text-xs text-slate-500 ml-0.5">kg</span></p>
                                </div>
                                <div class="bg-slate-50 px-3 py-2 rounded-lg border border-slate-200">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Tinggi</p>
                                    <p class="text-base font-extrabold text-slate-800">{{ $item->tinggi_badan ?? '-' }}<span class="text-xs text-slate-500 ml-0.5">cm</span></p>
                                </div>
                            </div>
                        </td>

                        {{-- Status Alur --}}
                        <td class="py-5 px-6 align-middle text-center">
                            @php
                                $statusStyles = [
                                    'menunggu_petugas' => [
                                        'bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'border' => 'border-amber-300', 'dot' => 'bg-amber-500', 'pulse' => true
                                    ],
                                    'menunggu_admin' => [
                                        'bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'border' => 'border-blue-300', 'dot' => 'bg-blue-500', 'pulse' => true
                                    ],
                                    'diproses_dokter' => [
                                        'bg' => 'bg-indigo-100', 'text' => 'text-indigo-800', 'border' => 'border-indigo-300', 'dot' => 'bg-indigo-500', 'pulse' => true
                                    ],
                                    'selesai' => [
                                        'bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'border' => 'border-emerald-300', 'dot' => 'bg-emerald-500', 'pulse' => false
                                    ]
                                ];
                                $style = $statusStyles[$item->status] ?? ['bg' => 'bg-slate-100', 'text' => 'text-slate-800', 'border' => 'border-slate-300', 'dot' => 'bg-slate-500', 'pulse' => false];
                            @endphp

                            <div class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-full {{ $style['bg'] }} {{ $style['border'] }} {{ $style['text'] }} text-xs font-black uppercase tracking-wide shadow-sm border">
                                <span class="flex h-2.5 w-2.5 relative">
                                    @if($style['pulse'])
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $style['dot'] }} opacity-75"></span>
                                    @endif
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 {{ $style['dot'] }}"></span>
                                </span>
                                {{ str_replace('_', ' ', $item->status) }}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="bg-emerald-50 p-6 rounded-full mb-5 border border-emerald-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-slate-800 mb-2">Data Pendaftaran Kosong</h3>
                                <p class="text-slate-500 text-base max-w-md">
                                    Belum ada data pasien yang mendaftar atau pencarian filter Anda tidak membuahkan hasil.
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
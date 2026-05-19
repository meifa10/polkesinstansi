@extends('layouts.petugas')

@section('content')

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    body { 
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    /* Memastikan pagination bawaan Tailwind tampil rapi */
    nav[role="navigation"] { margin-top: 0; }
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
                Triage & Pemeriksaan
            </div>
            <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight">
                Pemeriksaan <span class="text-emerald-600">Awal</span>
            </h1>
            <p class="text-slate-600 font-medium mt-3 text-base lg:text-lg">
                Kelola data tanda vital (vital sign) dan antrean awal pasien sebelum ke dokter.
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
                <p class="text-sm uppercase font-bold text-slate-400 tracking-wider">Total Antrean</p>
                {{-- Menggunakan total() dari Pagination --}}
                <h2 class="text-4xl font-black text-slate-800 leading-none mt-1">{{ $pasien->total() }}</h2>
            </div>
        </div>
    </div>

    {{-- SEARCH BOX --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 mb-8 flex flex-col justify-center">
        <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-3">Pencarian Antrean</label>
        <form method="GET" action="{{ route('petugas.pemeriksaan_awal.index') }}" class="flex flex-col lg:flex-row gap-4">
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-6 w-6 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama pasien, NIK, atau Poliklinik..."
                    class="w-full pl-12 pr-5 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all shadow-sm">
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-slate-800 text-white px-8 py-3.5 rounded-xl text-base font-bold hover:bg-slate-900 transition-colors shadow-md flex-1 lg:flex-none">
                    Cari Pasien
                </button>
                @if(request('q'))
                    <a href="{{ route('petugas.pemeriksaan_awal.index') }}" class="bg-slate-100 text-slate-600 px-6 py-3.5 rounded-xl text-base font-bold hover:bg-slate-200 transition-colors border border-slate-300 flex items-center justify-center">
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
                        <th class="py-5 px-6 min-w-[150px]">Waktu Daftar</th>
                        <th class="py-5 px-6 min-w-[300px]">Informasi Pasien</th>
                        <th class="py-5 px-6 min-w-[200px]">Poliklinik Tujuan</th>
                        <th class="py-5 px-6 min-w-[180px] text-center">Status</th>
                        <th class="py-5 px-6 min-w-[180px] text-center rounded-tr-xl">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="text-base divide-y divide-slate-200">
                    @forelse($pasien as $index => $item)
                    <tr class="hover:bg-emerald-50/60 transition-colors group">
                        
                        {{-- Nomor dengan Pagination Offset --}}
                        <td class="py-5 px-6 text-center text-slate-500 font-bold align-middle text-lg">
                            {{ str_pad($pasien->firstItem() + $index, 2, '0', STR_PAD_LEFT) }}
                        </td>
                        
                        {{-- Waktu Daftar --}}
                        <td class="py-5 px-6 align-middle">
                            <div class="font-extrabold text-slate-800 text-base">
                                {{ $item->created_at->format('H:i') }} <span class="text-xs text-slate-500">WIB</span>
                            </div>
                            <div class="text-xs text-emerald-600 font-bold mt-1 uppercase tracking-wide">
                                {{ $item->created_at->translatedFormat('d M Y') }}
                            </div>
                        </td>

                        {{-- Informasi Pasien --}}
                        <td class="py-5 px-6 align-middle">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-black text-xl border border-emerald-200 shadow-sm flex-shrink-0">
                                    {{ strtoupper(substr($item->nama_pasien, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-extrabold text-slate-800 text-lg uppercase tracking-tight group-hover:text-emerald-700 transition-colors">
                                        {{ $item->nama_pasien }}
                                    </p>
                                    <div class="flex items-center gap-3 mt-1">
                                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                                            ID: <span class="text-slate-700">#PX-{{ $item->id + 1000 }}</span>
                                        </p>
                                        <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                                            NIK: <span class="text-slate-700">{{ $item->pasien->nik ?? $item->nik ?? 'Tidak Ada' }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Poliklinik Tujuan --}}
                        <td class="py-5 px-6 align-middle">
                            <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-sm font-extrabold uppercase border border-slate-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" />
                                </svg>
                                {{ $item->poli }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td class="py-5 px-6 align-middle text-center">
                            @php
                                $statusStyles = [
                                    'menunggu_petugas' => [
                                        'bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'border' => 'border-amber-300', 'dot' => 'bg-amber-500', 'pulse' => true, 'label' => 'Menunggu Petugas'
                                    ],
                                    'menunggu_admin' => [
                                        'bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'border' => 'border-blue-300', 'dot' => 'bg-blue-500', 'pulse' => true, 'label' => 'Menunggu Admin'
                                    ],
                                    'diproses_dokter' => [
                                        'bg' => 'bg-indigo-100', 'text' => 'text-indigo-800', 'border' => 'border-indigo-300', 'dot' => 'bg-indigo-500', 'pulse' => true, 'label' => 'Diproses Dokter'
                                    ],
                                    'selesai' => [
                                        'bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'border' => 'border-emerald-300', 'dot' => 'bg-emerald-500', 'pulse' => false, 'label' => 'Selesai'
                                    ]
                                ];
                                $style = $statusStyles[$item->status] ?? [
                                    'bg' => 'bg-slate-100', 'text' => 'text-slate-800', 'border' => 'border-slate-300', 'dot' => 'bg-slate-500', 'pulse' => false, 'label' => str_replace('_', ' ', $item->status)
                                ];
                            @endphp

                            <div class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-full {{ $style['bg'] }} {{ $style['border'] }} {{ $style['text'] }} text-xs font-black uppercase tracking-wide shadow-sm border">
                                <span class="flex h-2.5 w-2.5 relative">
                                    @if($style['pulse'])
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $style['dot'] }} opacity-75"></span>
                                    @endif
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 {{ $style['dot'] }}"></span>
                                </span>
                                {{ $style['label'] }}
                            </div>
                        </td>

                        {{-- Tindakan --}}
                        <td class="py-5 px-6 align-middle text-center">
                            <a href="{{ route('petugas.pemeriksaan_awal.edit', $item->id) }}"
                                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-emerald-600 text-white hover:bg-emerald-700 font-bold rounded-xl transition-colors border border-emerald-700 text-sm shadow-md w-full max-w-[180px]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                Input Periksa
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-20 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="bg-emerald-50 p-6 rounded-full mb-5 border border-emerald-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-slate-800 mb-2">Tidak Ada Antrean</h3>
                                <p class="text-slate-500 text-base max-w-md">
                                    @if(request('q'))
                                        Pencarian untuk "<strong>{{ request('q') }}</strong>" tidak ditemukan. Coba gunakan kata kunci lain.
                                    @else
                                        Saat ini tidak ada pasien yang menunggu untuk dilakukan pemeriksaan awal (Triage).
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- PAGINATION / FOOTER INFO --}}
        @if($pasien->total() > 0)
        <div class="px-8 py-5 bg-slate-50 border-t border-slate-200 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-sm font-bold text-slate-500 uppercase tracking-widest text-center md:text-left">
                Menampilkan <span class="text-emerald-600">{{ $pasien->firstItem() }} - {{ $pasien->lastItem() }}</span> 
                dari <span class="text-emerald-600">{{ $pasien->total() }}</span> Pasien
            </p>
            <div class="w-full md:w-auto">
                {{ $pasien->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

@endsection
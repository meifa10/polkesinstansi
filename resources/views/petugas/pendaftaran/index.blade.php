@extends('layouts.petugas')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

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
                Sistem Arsip Medis Petugas
            </div>
            <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight">
                Riwayat <span class="text-emerald-600">Pendaftaran</span>
            </h1>
            <p class="text-slate-600 font-medium mt-3 text-base">
                Menampilkan seluruh data pemeriksaan awal vital dan antrean poliklinik pasien.
            </p>
        </div>

        {{-- METRIC CARD TOTAL ENTRI --}}
        <div class="bg-white px-8 py-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-6 min-w-[240px]">
            <div class="p-4 bg-emerald-50 rounded-xl text-emerald-600 border border-emerald-100">
                <i class="fa-solid fa-users-medical text-3xl"></i>
            </div>
            <div>
                <p class="text-xs uppercase font-black text-slate-400 tracking-wider">Total Entri</p>
                <h2 class="text-4xl font-black text-slate-800 leading-none mt-1">{{ $pendaftaran->total() }}</h2>
            </div>
        </div>
    </div>

    {{-- FILTER & SEARCH BOX --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 lg:p-8 mb-8">
        <form method="GET" action="{{ route('petugas.pendaftaran.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            
            {{-- Search Input (Nama/NIK) --}}
            <div class="md:col-span-5 relative">
                <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">Cari Pasien</label>
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none" style="top: 24px;">
                    <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                </div>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama pasien atau NIK..." 
                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
            </div>

            {{-- Filter Tanggal --}}
            <div class="md:col-span-3">
                <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">Saring Tanggal</label>
                <input type="date" name="date" value="{{ request('date', date('Y-m-d')) }}" 
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all cursor-pointer">
            </div>

            {{-- Dropdown Status --}}
            <div class="md:col-span-2 relative">
                <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">Status Alur</label>
                <select name="status" onchange="this.form.submit()" 
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all appearance-none cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="menunggu_petugas" {{ request('status') == 'menunggu_petugas' ? 'selected' : '' }}>Menunggu Petugas</option>
                    <option value="diproses_dokter" {{ request('status') == 'diproses_dokter' ? 'selected' : '' }}>Diproses Dokter</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none" style="top: 24px;">
                    <i class="fa-solid fa-chevron-down text-slate-500 text-xs"></i>
                </div>
            </div>

            {{-- Buttons Action --}}
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 bg-slate-800 text-white py-3 rounded-xl text-sm font-bold hover:bg-slate-900 transition-colors shadow-sm cursor-pointer uppercase tracking-wider active:scale-98">
                    Filter
                </button>
                @if(request('q') || request('status') || request('date'))
                    <a href="{{ route('petugas.pendaftaran.index') }}" class="bg-slate-100 text-slate-600 px-4 py-3 rounded-xl text-sm font-bold hover:bg-slate-200 transition-colors border border-slate-300 flex items-center justify-center shadow-sm" title="Reset Saringan">
                        <i class="fa-solid fa-rotate-right"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- DATA TABLE UTAMA --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1250px]">
                <thead>
                    {{-- HEADER TABEL: Tetap Hijau Emerald Kontras --}}
                    <tr class="bg-emerald-900 text-white text-xs uppercase tracking-widest font-black border-b border-emerald-950">
                        <th class="py-5 px-6 w-16 text-center rounded-tl-2xl">No</th>
                        <th class="py-5 px-6 w-48 text-center">Waktu Masuk</th>
                        <th class="py-5 px-6 min-w-[280px]">Biodata Pasien / NIK</th>
                        <th class="py-5 px-6 min-w-[200px]">Keluhan Utama</th>
                        <th class="py-5 px-6 min-w-[220px]">Unit Layanan & Dokter</th>
                        <th class="py-5 px-6 min-w-[230px] text-center">Tanda-Tanda Vital (Ultra-Jelas)</th>
                        <th class="py-5 px-6 min-w-[180px] text-center rounded-tr-2xl">Status Alur</th>
                    </tr>
                </thead>
                <tbody class="text-base divide-y divide-slate-200">
                    @forelse($pendaftaran as $item)
                    <tr class="hover:bg-emerald-50/40 transition-colors group">
                        
                        {{-- Nomor dengan Urutan Pagination --}}
                        <td class="py-6 px-6 text-center text-slate-400 font-extrabold align-middle">
                            {{ ($pendaftaran->currentPage() - 1) * $pendaftaran->perPage() + $loop->iteration }}
                        </td>
                        
                        {{-- Waktu Masuk --}}
                        <td class="py-6 px-6 align-middle text-center">
                            <p class="font-extrabold text-slate-800 text-sm">{{ $item->created_at->translatedFormat('d M Y') }}</p>
                            <span class="text-xs font-bold font-mono text-emerald-600 block mt-1 bg-emerald-50 rounded-md py-0.5 px-2 border border-emerald-100 inline-block">
                                {{ $item->created_at->format('H:i') }} WIB
                            </span>
                        </td>

                        {{-- Biodata Pasien --}}
                        <td class="py-6 px-6 align-middle">
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 rounded-xl bg-emerald-700 text-white flex items-center justify-center font-black text-lg shadow-sm flex-shrink-0 uppercase">
                                    {{ strtoupper(substr($item->nama_pasien, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-black text-slate-800 text-base uppercase leading-tight tracking-tight">{{ $item->nama_pasien }}</p>
                                    <p class="text-xs font-bold font-mono text-slate-400 mt-1 tracking-wider">NIK: {{ $item->no_identitas }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Keluhan Utama Pasien --}}
                        <td class="py-6 px-6 align-middle italic text-slate-600 font-medium text-sm leading-relaxed max-w-[220px]">
                            "{{ $item->keluhan ?? 'Tidak ada keluhan tertulis' }}"
                        </td>

                        {{-- Unit Layanan & Dokter Tujuan --}}
                        <td class="py-6 px-6 align-middle">
                            <span class="inline-flex px-3 py-1 rounded-md bg-emerald-50 text-emerald-800 text-xs font-black uppercase tracking-wider border border-emerald-200 mb-1.5">
                                {{ $item->poli }}
                            </span>
                            <p class="text-xs font-bold text-slate-500">
                                <i class="fa-solid fa-user-doctor-hair text-slate-400 mr-1"></i>Dr. {{ $item->dokter->name ?? 'Belum Ditentukan' }}
                            </p>
                        </td>

                        {{-- Tanda Vital Terperinci (Ultra-Jelas, Besar, Hitam Pekat) --}}
                        <td class="py-6 px-6 align-middle">
                            <div class="space-y-1.5 w-full max-w-[200px] mx-auto">
                                <div class="flex items-center justify-between border-2 border-slate-300 px-2 py-1 rounded-xl bg-white shadow-sm">
                                    <span class="text-[11px] font-black text-slate-700 uppercase">Tensi:</span>
                                    <span class="text-slate-950 font-mono font-black text-sm">
                                        {{ $item->tensi ?? '-' }} <span class="text-[11px] font-black text-slate-950 ml-0.5">mmHg</span>
                                    </span>
                                </div>
                                <div class="flex items-center justify-between border-2 border-slate-300 px-2 py-1 rounded-xl bg-white shadow-sm">
                                    <span class="text-[11px] font-black text-slate-700 uppercase">Berat:</span>
                                    <span class="text-slate-950 font-mono font-black text-sm">
                                        {{ $item->berat_badan ?? '-' }} <span class="text-[11px] font-black text-slate-950 ml-0.5">kg</span>
                                    </span>
                                </div>
                                <div class="flex items-center justify-between border-2 border-slate-300 px-2 py-1 rounded-xl bg-white shadow-sm">
                                    <span class="text-[11px] font-black text-slate-700 uppercase">Tinggi:</span>
                                    <span class="text-slate-950 font-mono font-black text-sm">
                                        {{ $item->tinggi_badan ?? '-' }} <span class="text-[11px] font-black text-slate-950 ml-0.5">cm</span>
                                    </span>
                                </div>
                            </div>
                        </td>

                        {{-- Status Alur --}}
                        <td class="py-6 px-6 align-middle text-center">
                            @php
                                $statusStyles = [
                                    'menunggu_petugas' => [
                                        'bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'border' => 'border-amber-300', 'dot' => 'bg-amber-500', 'pulse' => true
                                    ],
                                    'diproses_dokter' => [
                                        'bg' => 'bg-indigo-100', 'text' => 'text-indigo-800', 'border' => 'border-indigo-300', 'dot' => 'bg-indigo-500', 'pulse' => true
                                    ],
                                    // NEW UPDATE: Warna Selesai Hijau Emerald Menyala
                                    'selesai' => [
                                        'bg' => 'bg-emerald-600', 'text' => 'text-white', 'border' => 'border-emerald-700', 'dot' => 'bg-emerald-100', 'pulse' => false
                                    ]
                                ];
                                $style = $statusStyles[$item->status] ?? ['bg' => 'bg-slate-100', 'text' => 'text-slate-800', 'border' => 'border-slate-300', 'dot' => 'bg-slate-500', 'pulse' => false];
                            @endphp

                            <div class="inline-flex items-center justify-center gap-2 px-3.5 py-2 rounded-full {{ $style['bg'] }} {{ $style['border'] }} {{ $style['text'] }} text-xs font-black uppercase tracking-wide border shadow-sm transition-all">
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
                        <td colspan="7" class="py-24 text-center rounded-b-2xl bg-white">
                            <div class="flex flex-col items-center justify-center">
                                <div class="bg-slate-100 p-5 rounded-full text-slate-400 mb-4 border border-slate-200">
                                    <i class="fa-solid fa-folder-open text-3xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-800 mb-1 uppercase tracking-wide">Data Antrean Kosong</h3>
                                <p class="text-slate-500 text-sm max-w-sm">Tidak ada pasien yang mendaftar atau hasil saringan filter kosong.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- NAVIGASI LINKS PAGINATION (MAXIMAL 10 DATA) --}}
        @if($pendaftaran->hasPages())
        <div class="p-5 border-t border-slate-200 bg-slate-50 rounded-b-2xl">
            {{ $pendaftaran->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

@endsection
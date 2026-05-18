@extends('layouts.admin')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    body { 
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
</style>

<div class="p-6 lg:p-8 bg-slate-50 min-h-screen font-sans">

    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-8 gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-sm font-bold tracking-wide uppercase mb-3 border border-emerald-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
                Admin / Antrian Pasien
            </div>
            <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight">
                Pendaftaran <span class="text-emerald-600">Pasien</span>
            </h1>
            <p class="text-slate-600 font-medium mt-3 text-base lg:text-lg">
                Kelola data antrian pasien dan pantau status pemeriksaan secara real-time.
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
                <p class="text-sm uppercase font-bold text-slate-400 tracking-wider">Total Pasien</p>
                <h2 class="text-4xl font-black text-slate-800 leading-none mt-1">{{ $pendaftaran->total() }}</h2>
            </div>
        </div>
    </div>

    {{-- FILTER BOX --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 mb-8 flex flex-col justify-center">
        <div class="flex items-center gap-3 mb-6 pb-5 border-b border-slate-100">
            <div class="bg-emerald-100 p-2 rounded-lg text-emerald-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <h2 class="text-xl font-bold text-slate-800">Pencarian & Filter Antrean</h2>
        </div>

        <form method="GET" action="{{ route('admin.pendaftaran.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
            
            {{-- Search Input --}}
            <div class="md:col-span-4 relative">
                <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Cari Pasien</label>
                <div class="absolute inset-y-0 bottom-0 left-0 pl-4 flex items-center pointer-events-none" style="top: 28px;">
                    <svg class="h-6 w-6 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau NIK..." 
                    class="w-full pl-12 pr-5 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none">
            </div>

            {{-- Filter Tanggal --}}
            <div class="md:col-span-3 relative">
                <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal', \Carbon\Carbon::today()->format('Y-m-d')) }}" onchange="this.form.submit()" 
                    class="w-full px-5 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none cursor-pointer">
            </div>

            {{-- Poli Select --}}
            <div class="md:col-span-3 relative">
                <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Pilih Poli</label>
                <select name="poli" onchange="this.form.submit()" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none appearance-none cursor-pointer">
                    <option value="">Semua Poli</option>
                    <option value="Poli Umum" {{ request('poli') == 'Poli Umum' ? 'selected' : '' }}>Poli Umum</option>
                    <option value="Poli Gigi" {{ request('poli') == 'Poli Gigi' ? 'selected' : '' }}>Poli Gigi</option>
                    <option value="Poli KIA" {{ request('poli') == 'Poli KIA' ? 'selected' : '' }}>Poli KIA & KB</option>
                </select>
                <div class="absolute inset-y-0 bottom-0 right-0 flex items-center pr-4 pointer-events-none" style="top: 28px;">
                    <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
            
            {{-- Action Buttons --}}
            <div class="md:col-span-2 flex gap-3">
                <button type="submit" class="flex-1 bg-slate-800 text-white py-3.5 rounded-xl text-base font-bold hover:bg-slate-900 transition-colors shadow-md">
                    Filter
                </button>
                @if(request('q') || request('poli') || request('tanggal'))
                    <a href="{{ route('admin.pendaftaran.index') }}" class="px-5 py-3.5 bg-slate-100 text-slate-600 rounded-xl text-base font-bold hover:bg-slate-200 transition-colors border border-slate-300 flex items-center justify-center" title="Reset">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- MESSAGES (SUCCESS & ERROR) --}}
    @if(session('success'))
    <div class="mb-5 bg-emerald-50 border border-emerald-300 text-emerald-800 px-5 py-4 rounded-xl font-bold text-base flex justify-between items-center shadow-sm">
        <span class="flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </span>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-900 font-black text-xl hover:opacity-70">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-5 bg-rose-50 border border-rose-300 text-rose-800 px-5 py-4 rounded-xl font-bold text-base flex justify-between items-center shadow-sm">
        <span class="flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('error') }}
        </span>
        <button type="button" onclick="this.parentElement.remove()" class="text-rose-900 font-black text-xl hover:opacity-70">&times;</button>
    </div>
    @endif

    {{-- DATA TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-emerald-900 text-white text-sm uppercase tracking-widest font-bold">
                        <th class="py-5 px-6 w-24 text-center rounded-tl-xl">No</th>
                        <th class="py-5 px-6 min-w-[260px]">Pasien & Dokter</th>
                        <th class="py-5 px-6 min-w-[160px] text-center">Tanggal Daftar</th>
                        <th class="py-5 px-6 min-w-[150px] text-center">Unit / Poli</th>
                        <th class="py-5 px-6 min-w-[320px]">Pemeriksaan Awal (Vital Sign)</th>
                        <th class="py-5 px-6 min-w-[180px] text-center">Status</th>
                        <th class="py-5 px-6 min-w-[180px] text-center rounded-tr-xl">Aksi</th>
                    </tr>
                </thead>

                <tbody class="text-base divide-y divide-slate-200">
                    @forelse($pendaftaran as $item)
                    <tr class="hover:bg-emerald-50/60 transition-colors">
                        
                        {{-- NO & ANTREAN --}}
                        <td class="py-5 px-6 text-center align-middle">
                            <div class="font-extrabold text-slate-500 text-lg">
                                {{ ($pendaftaran->currentPage() - 1) * $pendaftaran->perPage() + $loop->iteration }}
                            </div>
                            <div class="inline-flex items-center justify-center text-xs font-black bg-slate-100 text-slate-600 rounded-lg px-2.5 py-1 mt-1 border border-slate-300 shadow-sm">
                                Q-{{ str_pad($item->nomor_antrian, 2, '0', STR_PAD_LEFT) }}
                            </div>
                        </td>

                        {{-- PASIEN & DOKTER --}}
                        <td class="py-5 px-6 align-middle">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-black text-xl border border-emerald-200 shadow-sm flex-shrink-0">
                                    {{ strtoupper(substr($item->nama_pasien,0,1)) }}
                                </div>
                                <div>
                                    <p class="font-extrabold text-slate-800 text-lg uppercase tracking-tight leading-tight mb-1">{{ $item->nama_pasien }}</p>
                                    <p class="text-xs text-slate-500 font-bold tracking-wider">NIK: {{ $item->no_identitas }}</p>
                                    <div class="mt-1.5 flex items-center gap-1.5">
                                        <i class="fa-solid fa-user-doctor text-xs text-emerald-500"></i>
                                        <span class="text-xs text-emerald-700 font-extrabold uppercase tracking-wide">
                                            {{ $item->dokter->name ?? 'Dokter Belum Ditentukan' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- TANGGAL DAFTAR --}}
                        <td class="py-5 px-6 align-middle text-center">
                            <p class="font-extrabold text-slate-800">{{ $item->created_at->translatedFormat('d M Y') }}</p>
                            <p class="text-xs font-bold text-slate-500 mt-0.5">{{ $item->created_at->format('H:i') }} WIB</p>
                        </td>

                        {{-- POLI --}}
                        <td class="py-5 px-6 align-middle text-center">
                            <span class="inline-flex px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-extrabold uppercase border border-slate-300">
                                {{ $item->poli }}
                            </span>
                        </td>

                        {{-- TANDA VITAL --}}
                        <td class="py-5 px-6 align-middle">
                            @if($item->berat_badan)
                                <div class="space-y-2">
                                    <div class="flex flex-wrap gap-2">
                                        <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-lg text-xs font-black border border-blue-200 shadow-sm">
                                            BB: {{ $item->berat_badan }} KG
                                        </span>
                                        <span class="bg-purple-50 text-purple-700 px-3 py-1 rounded-lg text-xs font-black border border-purple-200 shadow-sm">
                                            TB: {{ $item->tinggi_badan }} CM
                                        </span>
                                        @if($item->tensi)
                                        <span class="bg-rose-50 text-rose-700 px-3 py-1 rounded-lg text-xs font-black border border-rose-200 shadow-sm">
                                            Tensi: {{ $item->tensi }} mmHg
                                        </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-slate-600 font-bold italic line-clamp-2 leading-relaxed bg-slate-50 p-2.5 rounded-lg border border-slate-200">
                                        "{{ $item->keluhan }}"
                                    </p>
                                </div>
                            @else
                                <div class="inline-flex items-center gap-2 text-amber-600 bg-amber-50 px-4 py-2 rounded-xl border border-amber-200 shadow-sm">
                                    <i class="fa-solid fa-clock-rotate-left animate-spin" style="animation-duration: 3s;"></i>
                                    <span class="text-xs font-black uppercase italic tracking-wide">Menunggu Petugas...</span>
                                </div>
                            @endif
                        </td>

                        {{-- STATUS --}}
                        <td class="py-5 px-6 align-middle text-center">
                            @if($item->status == 'menunggu_petugas')
                                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-100 border border-slate-300 text-slate-600 text-xs font-extrabold shadow-sm uppercase">
                                    Tahap Petugas
                                </div>
                            @elseif($item->status == 'diproses_dokter' || $item->status == 'proses')
                                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-100 border border-blue-300 text-blue-800 text-xs font-extrabold shadow-sm uppercase">
                                    Di Ruang Dokter
                                </div>
                            @elseif($item->status == 'selesai')
                                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-extrabold shadow-sm uppercase">
                                    Menunggu
                                </div>
                            @else
                                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-100 border border-amber-300 text-amber-800 text-xs font-extrabold shadow-sm uppercase">
                                    {{ str_replace('_', ' ', $item->status) }}
                                </div>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td class="py-5 px-6 align-middle text-center">
                            <div class="flex items-center justify-center gap-3">
                                @if($item->status == 'menunggu_petugas')
                                    <span class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-slate-100 text-slate-400 text-sm font-bold border border-slate-300 cursor-not-allowed">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                        </svg>
                                        Terkunci
                                    </span>
                                @elseif($item->status == 'diproses_dokter' || $item->status == 'proses')
                                    <div class="inline-flex items-center justify-center gap-2 text-blue-500 bg-blue-50 px-4 py-2 rounded-xl border border-blue-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-sm font-bold italic">Diperiksa</span>
                                    </div>
                                @elseif($item->status == 'selesai')
                                    <div class="inline-flex items-center justify-center gap-2 text-emerald-500 bg-emerald-50 px-4 py-2 rounded-xl border border-emerald-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-sm font-bold italic">Menunggu</span>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-20 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="bg-emerald-50 p-6 rounded-full mb-5 border border-emerald-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-slate-800 mb-2">Tidak Ada Antrian Pasien</h3>
                                <p class="text-slate-500 text-base max-w-md">
                                    Silakan periksa kembali filter pencarian Anda atau belum ada pasien yang mendaftar pada tanggal ini.
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- PAGINATION LINKS --}}
        @if($pendaftaran->hasPages())
        <div class="p-6 border-t border-slate-200">
            {{ $pendaftaran->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

@endsection
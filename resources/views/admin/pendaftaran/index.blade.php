@extends('layouts.admin')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    body { 
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
</style>

<div class="p-6 lg:p-8 bg-slate-50 min-h-screen">

    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
        <div>
            <nav class="flex text-xs text-slate-500 mb-2 gap-1.5 font-bold uppercase tracking-widest">
                <span>Admin</span>
                <span class="text-slate-300">/</span>
                <span class="text-emerald-600">Antrian Pasien Hari Ini</span>
            </nav>
            <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight">
                Pendaftaran <span class="text-emerald-600">Pasien</span>
            </h1>
        </div>

        {{-- METRIC CARD --}}
        <div class="bg-slate-900 px-8 py-4 rounded-2xl shadow-md border-b-4 border-emerald-500 min-w-[220px]">
            <p class="text-[10px] uppercase tracking-widest text-slate-400 font-black">
                Total Pasien Hari Ini
            </p>
            <p class="text-3xl font-black text-white mt-1 leading-none flex items-baseline gap-2">
                {{ $pendaftaran->count() }}
                <span class="text-xs text-emerald-400 font-bold tracking-wider">PASIEN</span>
            </p>
        </div>
    </div>

    {{-- FILTER BOX --}}
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm mb-6">
        <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-3">Pencarian & Filter Unit</label>
        <form method="GET" action="{{ route('admin.pendaftaran.index') }}" class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama pasien atau NIK..." 
                    class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-300 rounded-xl outline-none text-base font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all">
            </div>

            <div class="relative min-w-[220px]">
                <select name="poli" onchange="this.form.submit()" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-300 rounded-xl outline-none text-base font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all appearance-none cursor-pointer">
                    <option value="">Semua Poli</option>
                    <option value="Poli Umum" {{ request('poli') == 'Poli Umum' ? 'selected' : '' }}>Poli Umum</option>
                    <option value="Poli Gigi" {{ request('poli') == 'Poli Gigi' ? 'selected' : '' }}>Poli Gigi</option>
                    <option value="Poli KIA" {{ request('poli') == 'Poli KIA' ? 'selected' : '' }}>Poli KIA & KB</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                    <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
            
            <div class="flex gap-2">
                <button type="submit" class="bg-emerald-600 text-white px-8 py-3.5 rounded-xl font-bold text-base hover:bg-emerald-700 shadow-md shadow-emerald-500/20 transition-colors flex-1 lg:flex-none">
                    Filter
                </button>
                @if(request('q') || request('poli'))
                    <a href="{{ route('admin.pendaftaran.index') }}" class="px-5 py-3.5 bg-slate-100 text-slate-600 rounded-xl text-base font-bold hover:bg-slate-200 transition-colors border border-slate-300 flex items-center justify-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- MESSAGES (SUCCESS & ERROR) --}}
    @if(session('success'))
    <div class="mb-5 bg-emerald-50 border border-emerald-300 text-emerald-800 px-5 py-4 rounded-xl font-bold text-base flex justify-between items-center shadow-sm">
        <span class="flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i> 
            {{ session('success') }}
        </span>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-900 font-black text-xl hover:opacity-70">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-5 bg-rose-50 border border-rose-300 text-rose-800 px-5 py-4 rounded-xl font-bold text-base flex justify-between items-center shadow-sm">
        <span class="flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-rose-600 text-lg"></i> 
            {{ session('error') }}
        </span>
        <button type="button" onclick="this.parentElement.remove()" class="text-rose-900 font-black text-xl hover:opacity-70">&times;</button>
    </div>
    @endif

    {{-- DATA TABLE --}}
    <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-emerald-900 text-white text-sm uppercase tracking-widest font-bold">
                        <th class="py-5 px-6 w-24 text-center rounded-tl-xl">No</th>
                        <th class="py-5 px-6 min-w-[280px]">Pasien & Dokter</th>
                        <th class="py-5 px-6 min-w-[150px] text-center">Unit / Poli</th>
                        <th class="py-5 px-6 min-w-[280px]">Pemeriksaan Awal (Vital Sign)</th>
                        <th class="py-5 px-6 min-w-[180px] text-center">Status</th>
                        <th class="py-5 px-6 min-w-[180px] text-center rounded-tr-xl">Aksi</th>
                    </tr>
                </thead>

                <tbody class="text-base divide-y divide-slate-200">
                    @forelse($pendaftaran as $item)
                    <tr class="hover:bg-emerald-50/40 transition-colors">
                        
                        {{-- NO & ANTREAN --}}
                        <td class="py-5 px-6 text-center align-middle">
                            <div class="font-extrabold text-slate-500 text-lg">#{{ $loop->iteration }}</div>
                            <div class="inline-block text-[11px] font-black bg-slate-100 text-slate-600 rounded-lg px-2 py-0.5 mt-1.5 border border-slate-300 shadow-sm">
                                Q-{{ str_pad($item->nomor_antrian, 2, '0', STR_PAD_LEFT) }}
                            </div>
                        </td>

                        {{-- PASIEN & DOKTER --}}
                        <td class="py-5 px-6 align-middle">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-slate-900 text-white flex items-center justify-center font-black text-xl shadow-md flex-shrink-0">
                                    {{ strtoupper(substr($item->nama_pasien,0,1)) }}
                                </div>
                                <div>
                                    <p class="font-extrabold text-slate-800 text-lg uppercase tracking-tight leading-tight mb-1">{{ $item->nama_pasien }}</p>
                                    <p class="text-xs text-slate-400 font-extrabold tracking-wider">NIK: {{ $item->no_identitas }}</p>
                                    <div class="mt-2 flex items-center gap-1.5">
                                        <i class="fa-solid fa-user-doctor text-xs text-emerald-500"></i>
                                        <span class="text-xs text-emerald-700 font-black uppercase tracking-wide">
                                            {{ $item->dokter->name ?? 'Dokter Belum Ditentukan' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
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
                                    </div>
                                    <p class="text-sm text-slate-600 font-bold italic line-clamp-2 leading-relaxed bg-slate-50 p-2 rounded-lg border border-slate-200">
                                        "{{ $item->keluhan }}"
                                    </p>
                                </div>
                            @else
                                <div class="inline-flex items-center gap-2 text-amber-600 bg-amber-50 px-3 py-1.5 rounded-xl border border-amber-200">
                                    <i class="fa-solid fa-clock-rotate-left animate-spin" style="animation-duration: 3s;"></i>
                                    <span class="text-xs font-black uppercase italic tracking-wide">Menunggu Petugas...</span>
                                </div>
                            @endif
                        </td>

                        {{-- STATUS --}}
                        <td class="py-5 px-6 align-middle text-center">
                            @if($item->status == 'menunggu_petugas')
                                <span class="px-4 py-2 rounded-full bg-slate-100 text-slate-500 border border-slate-300 text-xs font-black uppercase tracking-wide shadow-sm border">
                                    Tahap Petugas
                                </span>
                            @elseif($item->status == 'menunggu_admin')
                                <span class="px-4 py-2 rounded-full bg-amber-100 text-amber-800 border border-amber-300 text-xs font-black uppercase tracking-wide shadow-sm border animate-pulse">
                                    Siap Verifikasi
                                </span>
                            @elseif($item->status == 'diproses_dokter' || $item->status == 'proses')
                                <span class="px-4 py-2 rounded-full bg-blue-100 text-blue-800 border border-blue-300 text-xs font-black uppercase tracking-wide shadow-sm border">
                                    Siap Diperiksa
                                </span>
                            @elseif($item->status == 'selesai')
                                <span class="px-4 py-2 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-300 text-xs font-black uppercase tracking-wide shadow-sm border">
                                    Selesai
                                </span>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td class="py-5 px-6 align-middle text-center">
                            @if($item->status == 'menunggu_admin')
                                <form method="POST" action="{{ route('admin.pendaftaran.status', $item->id) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="diproses_dokter">
                                    <button type="submit" class="group inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-wider shadow-md shadow-emerald-500/20 transition-all active:scale-95 border border-emerald-700">
                                        Kirim ke Dokter 
                                        <i class="fa-solid fa-paper-plane group-hover:translate-x-1 transition-transform"></i>
                                    </button>
                                </form>
                            @elseif($item->status == 'menunggu_petugas')
                                <span class="inline-flex items-center justify-center gap-1.5 px-5 py-3 rounded-xl bg-slate-100 text-slate-400 text-xs font-black uppercase border border-slate-300 shadow-inner cursor-not-allowed">
                                    <i class="fa-solid fa-lock"></i> Terkunci
                                </span>
                            @else
                                <div class="inline-flex items-center justify-center gap-1.5 text-slate-400 bg-slate-50 px-4 py-2 rounded-xl border border-slate-200">
                                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                                    <span class="text-xs font-extrabold italic">Selesai</span>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-24 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="bg-emerald-50 p-6 rounded-full mb-5 border border-emerald-100">
                                    <i class="fa-solid fa-user-slash text-4xl text-emerald-600 opacity-60"></i>
                                </div>
                                <h3 class="text-xl font-bold text-slate-800 mb-2 uppercase tracking-wide">Tidak Ada Antrian Pasien</h3>
                                <p class="text-slate-500 text-base max-w-sm">
                                    Silakan periksa kembali filter pencarian Anda atau hubungi bagian pendaftaran loket depan.
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
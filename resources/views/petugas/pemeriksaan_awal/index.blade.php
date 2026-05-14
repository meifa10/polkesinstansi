@extends('layouts.petugas')

@section('content')

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    .table-container {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(226, 232, 240, 0.8);
    }
    .btn-glass {
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    .btn-glass:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3);
    }
</style>

<div class="min-h-screen bg-[#f8fafc] p-4 lg:p-8">

    {{-- HEADER & SEARCH --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                Pemeriksaan <span class="text-emerald-600">Awal</span>
            </h1>
            <p class="text-slate-500 font-medium mt-1">
                Kelola data vital sign dan antrean awal pasien.
            </p>
        </div>

        {{-- SEARCH BOX (Visual Only) --}}
        <div class="relative w-full md:w-72">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input type="text" placeholder="Cari nama pasien..." 
                class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none text-sm shadow-sm">
        </div>
    </div>

    {{-- TABLE SECTION --}}
    <div class="table-container rounded-[2.5rem] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-900">
                        <th class="px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em] text-slate-400">No</th>
                        <th class="px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em] text-slate-400">Informasi Pasien</th>
                        <th class="px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em] text-slate-400">Poliklinik Tujuan</th>
                        <th class="px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em] text-slate-400">Status</th>
                        <th class="px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($pasien as $item)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-8 py-6 text-sm font-bold text-slate-400">
                            {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </td>
                        
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                                    {{ strtoupper(substr($item->nama_pasien, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 leading-tight group-hover:text-emerald-700 transition-colors">
                                        {{ $item->nama_pasien }}
                                    </p>
                                    <p class="text-xs text-slate-400 mt-1 uppercase font-medium tracking-tighter">
                                        ID: #PX-{{ $item->id + 1000 }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="px-8 py-6">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                                <span class="text-sm font-semibold text-slate-700">{{ $item->poli }}</span>
                            </div>
                        </td>

                        <td class="px-8 py-6">
                            @if($item->status == 'menunggu_petugas')
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-50 text-amber-600 text-[10px] font-black uppercase border border-amber-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-2 animate-pulse"></span>
                                    Menunggu
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-[10px] font-black uppercase border border-blue-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2"></span>
                                    {{ str_replace('_', ' ', $item->status) }}
                                </span>
                            @endif
                        </td>

                        <td class="px-8 py-6 text-center">
                            <a href="{{ route('petugas.pemeriksaan_awal.edit', $item->id) }}"
                                class="btn-glass inline-flex items-center gap-2 text-white px-5 py-2.5 rounded-xl text-xs font-bold shadow-lg shadow-emerald-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Input Pemeriksaan
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <p class="text-slate-400 font-bold">Tidak ada antrean pasien saat ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- PAGINATION (Visual Only) --}}
        <div class="px-8 py-5 bg-slate-50/50 border-t border-slate-100 flex justify-between items-center">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                Menampilkan {{ $pasien->count() }} Pasien
            </p>
            <div class="flex gap-2">
                <button class="p-2 rounded-lg border border-slate-200 bg-white text-slate-400 hover:text-emerald-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button class="p-2 rounded-lg border border-slate-200 bg-white text-slate-400 hover:text-emerald-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
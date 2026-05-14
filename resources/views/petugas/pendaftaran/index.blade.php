@extends('layouts.petugas')

@section('content')

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    body { 
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #1e293b;
    }
    .premium-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 24px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
</style>

<div class="min-h-screen bg-slate-50 p-4 md:p-8">

    {{-- HEADER SECTION --}}
    <div class="mb-10 border-b border-slate-200 pb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <span class="inline-block px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-widest mb-3">
                    Sistem Arsip Medis
                </span>
                <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight mb-2">
                    Riwayat <span class="text-emerald-600">Pendaftaran</span>
                </h1>
                <p class="text-slate-500 font-medium italic">
                    Menampilkan seluruh data pemeriksaan awal pasien yang tersimpan di database.
                </p>
            </div>

            <div class="bg-slate-900 px-6 py-4 rounded-2xl shadow-xl shadow-slate-200">
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Total Entri</p>
                <p class="text-3xl font-black text-white leading-none">
                    {{ $pendaftaran->count() }}
                </p>
            </div>
        </div>
    </div>

    {{-- FILTER BOX --}}
    <div class="premium-card p-6 mb-8">
        <form method="GET" action="{{ route('petugas.pendaftaran.index') }}" class="flex flex-col md:flex-row gap-4">
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="q" value="{{ request('q') }}" 
                    placeholder="Cari nama pasien atau NIK..." 
                    class="w-full pl-12 pr-6 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none text-sm font-bold text-slate-700 transition-all">
            </div>

            <select name="status" onchange="this.form.submit()" 
                class="md:w-64 px-4 py-3.5 bg-white border border-slate-200 rounded-xl outline-none text-sm font-bold text-slate-700 shadow-sm">
                <option value="">Semua Status</option>
                <option value="menunggu_petugas" {{ request('status') == 'menunggu_petugas' ? 'selected' : '' }}>Menunggu Petugas</option>
                <option value="menunggu_admin" {{ request('status') == 'menunggu_admin' ? 'selected' : '' }}>Menunggu Admin</option>
                <option value="diproses_dokter" {{ request('status') == 'diproses_dokter' ? 'selected' : '' }}>Diproses Dokter</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>

            <button type="submit" class="px-8 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-emerald-200">
                Filter
            </button>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="premium-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-900">
                        <th class="px-6 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-white">No</th>
                        <th class="px-6 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-white border-l border-slate-800">Biodata Pasien</th>
                        <th class="px-6 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-white border-l border-slate-800">Unit Layanan</th>
                        <th class="px-6 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-white border-l border-slate-800 text-center">Tanda Vital</th>
                        <th class="px-6 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-white border-l border-slate-800 text-center">Status alur</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($pendaftaran as $item)
                    <tr class="hover:bg-emerald-50/30 transition-colors">
                        {{-- NOMOR URUT - SEKARANG LEBIH KONTRAS --}}
                        <td class="px-6 py-6">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-black border border-emerald-100 shadow-sm">
                                {{ $loop->iteration }}
                            </span>
                        </td>
                        
                        <td class="px-6 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg bg-slate-100 border border-slate-200 text-slate-700 flex items-center justify-center font-black">
                                    {{ strtoupper(substr($item->nama_pasien, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-extrabold text-slate-900 leading-none mb-1 uppercase tracking-tight">{{ $item->nama_pasien }}</p>
                                    <p class="text-[11px] font-bold text-emerald-600">{{ $item->no_identitas }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-lg text-[11px] font-black uppercase border border-slate-200">
                                {{ $item->poli }}
                            </span>
                        </td>
                        <td class="px-6 py-6">
                            <div class="flex justify-center gap-4 text-center">
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Berat</p>
                                    <p class="text-sm font-bold text-slate-800">{{ $item->berat_badan ?? '-' }}kg</p>
                                </div>
                                <div class="w-px h-8 bg-slate-100"></div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Tinggi</p>
                                    <p class="text-sm font-bold text-slate-800">{{ $item->tinggi_badan ?? '-' }}cm</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6 text-center">
                            @php
                                $statusClasses = [
                                    'menunggu_petugas' => 'bg-amber-100 text-amber-700 border-amber-200',
                                    'menunggu_admin'   => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'diproses_dokter'  => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    'selesai'          => 'bg-slate-200 text-slate-600 border-slate-300'
                                ];
                                $class = $statusClasses[$item->status] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                            @endphp
                            <span class="inline-block px-4 py-1.5 rounded-full border {{ $class }} text-[10px] font-black uppercase tracking-widest shadow-sm">
                                {{ str_replace('_', ' ', $item->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center font-bold text-slate-400 italic">
                            Data pendaftaran tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
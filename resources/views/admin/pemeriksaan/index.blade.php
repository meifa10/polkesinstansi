@extends('layouts.admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=500;600;700;800&display=swap" rel="stylesheet">
<script src="https://unpkg.com/@phosphor-icons/web"></script>

<div class="p-6 bg-slate-100 min-h-screen font-['Plus_Jakarta_Sans']">

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-6">
        <div>
            <nav class="flex items-center gap-1 text-[11px] uppercase tracking-wider font-bold text-slate-500 mb-1">
                <span>Admin</span> / <span class="text-emerald-600">Rekam Medis</span>
            </nav>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                Laporan <span class="text-emerald-600">Pemeriksaan</span>
            </h1>
        </div>

        {{-- Form Export - Mengirimkan filter aktif --}}
        <form method="GET" action="{{ route('admin.pemeriksaan') }}">
            <input type="hidden" name="q" value="{{ request('q') }}">
            <input type="hidden" name="poli" value="{{ request('poli') }}">
            <input type="hidden" name="tanggal" value="{{ request('tanggal') }}">
            
            <button type="submit" name="download" value="1" 
                class="flex items-center gap-3 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3.5 rounded-2xl shadow-lg shadow-emerald-100 transition-all font-bold active:scale-95">
                <i class="ph-bold ph-microsoft-excel-logo text-xl"></i>
                Export ke Excel
            </button>
        </form>
    </div>

    {{-- FILTER BOX --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('admin.pemeriksaan') }}" class="grid grid-cols-1 lg:grid-cols-12 gap-4">
            
            {{-- Search --}}
            <div class="lg:col-span-5 relative">
                <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Nama Pasien atau Diagnosis..."
                    class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-300 bg-slate-50 text-sm font-semibold focus:border-emerald-500 focus:bg-white outline-none transition">
            </div>

            {{-- Filter Poli --}}
            <div class="lg:col-span-3 relative">
                <select name="poli" onchange="this.form.submit()" class="w-full pl-4 pr-10 py-3 rounded-xl border border-slate-300 bg-white text-sm font-bold outline-none focus:border-emerald-500 appearance-none">
                    <option value="">Semua Poliklinik</option>
                    <option value="Poli Umum" {{ request('poli') == 'Poli Umum' ? 'selected' : '' }}>Poli Umum</option>
                    <option value="Poli Gigi" {{ request('poli') == 'Poli Gigi' ? 'selected' : '' }}>Poli Gigi</option>
                    <option value="Poli KIA & KB" {{ request('poli') == 'Poli KIA & KB' ? 'selected' : '' }}>Poli KIA & KB</option>
                </select>
                <i class="ph ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
            </div>

            {{-- Single Date Filter --}}
            <div class="lg:col-span-4 flex gap-2">
                <div class="relative w-full">
                    <i class="ph ph-calendar absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}" 
                        class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-300 text-sm font-semibold outline-none focus:border-emerald-500">
                </div>
                <button type="submit" class="bg-slate-900 text-white px-6 rounded-xl font-bold text-sm hover:bg-slate-800 transition-colors">
                    Cari
                </button>
            </div>
        </form>
    </div>

    {{-- DATA TABLE --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden text-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1400px]">
                <thead>
                    <tr class="bg-slate-900 text-white text-[11px] uppercase font-black tracking-widest">
                        <th class="px-6 py-5 text-center w-16">No</th>
                        <th class="px-6 py-5 text-left">Informasi Pasien</th>
                        <th class="px-6 py-5 text-left">Keluhan Utama</th>
                        <th class="px-6 py-5 text-left">Diagnosis & Tindakan</th>
                        <th class="px-6 py-5 text-left">Resep Obat (Item)</th>
                        <th class="px-6 py-5 text-right">Waktu Periksa</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pemeriksaan as $item)
                    <tr class="hover:bg-emerald-50/50 transition-all group">
                        <td class="px-6 py-5 text-center font-bold text-slate-400 group-hover:text-emerald-600">
                            {{ $loop->iteration }}
                        </td>
                        
                        <td class="px-6 py-5">
                            <p class="font-black text-slate-900 uppercase leading-none">{{ $item->pendaftaran->nama_pasien ?? '-' }}</p>
                            <p class="text-[10px] text-slate-400 font-bold mt-1.5 font-mono tracking-tighter">{{ $item->pendaftaran->no_identitas ?? '-' }}</p>
                            <span class="mt-2 inline-block text-[9px] font-black px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded border border-emerald-100 uppercase tracking-wider">
                                {{ $item->pendaftaran->poli ?? '-' }}
                            </span>
                        </td>

                        <td class="px-6 py-5">
                            <div class="max-w-[220px] text-xs font-medium text-slate-600 leading-relaxed italic border-l-2 border-emerald-200 pl-3">
                                "{{ $item->keluhan ?? 'Tidak ada keluhan' }}"
                            </div>
                        </td>

                        <td class="px-6 py-5">
                            <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 max-w-[300px]">
                                <p class="text-[10px] font-black text-emerald-600 uppercase mb-1 tracking-widest">Diagnosis</p>
                                <p class="text-xs font-bold text-slate-800 leading-tight mb-2">{{ $item->diagnosis }}</p>
                                <p class="text-[10px] font-black text-slate-400 uppercase mb-1 tracking-widest border-t border-slate-200 pt-1">Tindakan</p>
                                <p class="text-[11px] font-semibold text-slate-600">{{ $item->tindakan }}</p>
                            </div>
                        </td>

                        <td class="px-6 py-5">
                            @if($item->resep)
                                <div class="flex flex-col gap-2">
                                    @php
                                        $obatArray = preg_split('/[\n,]+/', $item->resep);
                                    @endphp
                                    @foreach($obatArray as $obat)
                                        @if(trim($obat) !== "")
                                        <div class="flex items-start gap-2">
                                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-1.5 shrink-0"></div>
                                            <span class="text-[10px] font-bold text-slate-700 bg-white border border-slate-200 px-2.5 py-1 rounded-md shadow-sm uppercase italic leading-none">
                                                {{ trim($obat) }}
                                            </span>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <span class="text-slate-300 italic text-xs tracking-wide">Tanpa Resep</span>
                            @endif
                        </td>

                        <td class="px-6 py-5 text-right">
                            <p class="font-black text-slate-900 leading-none">{{ $item->created_at->translatedFormat('d M Y') }}</p>
                            <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-widest">{{ $item->created_at->format('H:i') }} WIB</p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-32 text-center text-slate-400 uppercase font-black tracking-widest">
                            Data tidak ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
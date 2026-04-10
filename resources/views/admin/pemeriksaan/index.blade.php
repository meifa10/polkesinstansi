@extends('layouts.admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
<script src="https://unpkg.com/@phosphor-icons/web"></script>

<div class="p-6 bg-slate-100 min-h-screen font-['Plus_Jakarta_Sans']">

    {{-- ================= HEADER SECTION ================= --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-6">
        <div>
            <nav class="flex text-[11px] text-slate-500 mb-1 gap-1 font-bold uppercase tracking-wider">
                <span>Admin</span>
                <span class="text-slate-400">/</span>
                <span class="text-emerald-700">Arsip Medis</span>
            </nav>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                Rekam <span class="text-emerald-600">Medis Pasien</span>
            </h1>
            <p class="text-slate-500 text-sm font-bold mt-1 italic">Database riwayat kesehatan dan diagnosis klinis.</p>
        </div>

        <div class="flex items-center gap-3 bg-slate-900 px-5 py-2.5 rounded-xl shadow-lg border-b-4 border-emerald-500 shrink-0">
            <div class="text-right">
                <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold leading-none">Total Arsip</p>
                <p class="text-xl font-extrabold text-white leading-tight mt-1">
                    {{ number_format($pemeriksaan->count()) }} <span class="text-xs font-medium text-emerald-400">DATA</span>
                </p>
            </div>
        </div>
    </div>

    {{-- ================= FILTER & SEARCH BAR ================= --}}
    <div class="bg-white p-5 rounded-2xl border border-slate-300 shadow-sm mb-6">
        <form method="GET" action="{{ route('admin.pemeriksaan') }}" class="flex flex-col gap-4">
            
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                {{-- Search Input --}}
                <div class="md:col-span-6 relative group">
                    <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors"></i>
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari Nama Pasien, Diagnosis, atau Dokter..."
                        class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-50 border border-slate-300 focus:border-slate-900 focus:bg-white outline-none text-sm font-bold text-slate-900 transition-all"
                    >
                </div>

                {{-- Poliklinik Filter --}}
                <div class="md:col-span-3 relative">
                    <select name="poli" onchange="this.form.submit()" 
                        class="w-full pl-4 pr-10 py-3 rounded-xl border border-slate-300 bg-white text-sm font-bold text-slate-900 outline-none focus:border-slate-900 cursor-pointer appearance-none">
                        <option value="">Semua Poliklinik</option>
                        <option value="Poli Umum" {{ request('poli') == 'Poli Umum' ? 'selected' : '' }}>Poli Umum</option>
                        <option value="Poli Gigi" {{ request('poli') == 'Poli Gigi' ? 'selected' : '' }}>Poli Gigi</option>
                        <option value="Poli KIA & KB" {{ request('poli') == 'Poli KIA & KB' ? 'selected' : '' }}>Poli KIA & KB</option>
                    </select>
                    <i class="ph ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                </div>

                {{-- Single Date Filter --}}
                <div class="md:col-span-3 relative">
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}" onchange="this.form.submit()"
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 text-sm font-bold text-slate-900 outline-none focus:border-slate-900 bg-white cursor-pointer">
                    <i class="ph ph-calendar absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                </div>
            </div>

            {{-- Reset Button --}}
            @if(request('q') || request('poli') || request('tanggal'))
            <div class="flex justify-end pt-2 border-t border-slate-100">
                <a href="{{ route('admin.pemeriksaan') }}"
                   class="flex items-center gap-2 px-4 py-2 bg-slate-200 text-slate-900 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-slate-300 transition-all border border-slate-400">
                    <i class="ph ph-arrows-counter-clockwise"></i> Atur Ulang Filter
                </a>
            </div>
            @endif
        </form>
    </div>

    {{-- ================= DATA TABLE SECTION ================= --}}
    <div class="bg-white rounded-2xl shadow-md border border-slate-300 overflow-hidden">
        {{-- Table Container with Horizontal Scroll --}}
        <div class="overflow-x-auto overflow-y-hidden custom-scrollbar">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead>
                    <tr class="bg-slate-900">
                        <th class="px-5 py-4 text-center w-16 text-emerald-400 font-black text-xs uppercase tracking-tighter">No</th>
                        <th class="px-5 py-4 text-white font-bold text-xs uppercase w-64">Identitas Pasien</th>
                        <th class="px-5 py-4 text-white font-bold text-xs uppercase w-72">Hasil Diagnosis</th>
                        <th class="px-5 py-4 text-white font-bold text-xs uppercase w-48">Tindakan</th>
                        <th class="px-5 py-4 text-white font-bold text-xs uppercase w-64">Resep Obat</th>
                        <th class="px-5 py-4 text-right text-white font-bold text-xs uppercase w-48">Waktu Periksa</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($pemeriksaan as $item)
                    <tr class="hover:bg-emerald-50/50 transition-colors group">
                        <td class="px-5 py-4 text-center">
                            <span class="text-sm font-bold text-slate-900">#{{ $loop->iteration }}</span>
                        </td>

                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 shrink-0 rounded-lg bg-emerald-600 flex items-center justify-center text-white text-sm font-black shadow-sm">
                                    {{ strtoupper(substr($item->pendaftaran->nama_pasien ?? '-', 0, 1)) }}
                                </div>
                                <div class="leading-tight">
                                    <p class="text-[14px] font-bold text-slate-950 uppercase line-clamp-1">{{ $item->pendaftaran->nama_pasien ?? '-' }}</p>
                                    <span class="text-[9px] font-black px-1.5 py-0.5 rounded bg-slate-200 text-slate-700 mt-1 inline-block uppercase tracking-wider border border-slate-300">
                                        {{ $item->pendaftaran->poli ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        <td class="px-5 py-4 text-sm font-bold text-slate-800 leading-relaxed italic">
                            <div class="line-clamp-2" title="{{ $item->diagnosis }}">"{{ $item->diagnosis }}"</div>
                        </td>

                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-3 py-1.5 bg-slate-100 text-slate-900 rounded-md text-[10px] font-black uppercase border border-slate-300 shadow-sm leading-none">
                                {{ $item->tindakan }}
                            </span>
                        </td>

                        <td class="px-5 py-4">
                            @if($item->resep)
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach(preg_split('/[\n,]+/', $item->resep) as $obat)
                                        <span class="px-2 py-0.5 bg-white border border-slate-300 text-[10px] font-black text-slate-800 rounded uppercase shadow-sm whitespace-nowrap">
                                            {{ trim($obat) }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-slate-400 font-bold text-[10px] uppercase">-- Nihil --</span>
                            @endif
                        </td>

                        <td class="px-5 py-4 text-right">
                            <div class="flex flex-col items-end leading-none">
                                <span class="text-sm font-black text-slate-900 tracking-tighter whitespace-nowrap">
                                    {{ $item->created_at->translatedFormat('d M Y') }}
                                </span>
                                <span class="text-[10px] font-bold text-slate-400 mt-1 uppercase whitespace-nowrap">
                                    Pukul {{ $item->created_at->format('H:i') }} WIB
                                </span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-20 text-center bg-slate-50">
                            <div class="flex flex-col items-center">
                                <i class="ph ph-folder-open text-5xl text-slate-300 mb-2"></i>
                                <p class="text-slate-500 font-extrabold text-lg uppercase tracking-widest opacity-50 italic">Data tidak ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-slate-50 px-6 py-4 border-t border-slate-300">
            <p class="text-[10px] text-slate-600 font-bold italic uppercase tracking-wider">
                * Rekam medis divalidasi secara real-time berdasarkan laporan unit poliklinik Polkes Jombang.
            </p>
        </div>
    </div>
</div>

<style>
    body { background-color: #f1f5f9; color: #0f172a; }
    * {
        -webkit-font-smoothing: antialiased;
        text-rendering: optimizeLegibility;
    }
    
    /* Mencegah teks overlap pada diagnosis */
    .line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

    /* Custom Scrollbar untuk Table agar terlihat jelas bisa digeser */
    .custom-scrollbar::-webkit-scrollbar {
        height: 8px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Memadatkan baris tabel */
    td {
        padding-top: 1.25rem !important;
        padding-bottom: 1.25rem !important;
        vertical-align: middle;
    }
</style>
@endsection
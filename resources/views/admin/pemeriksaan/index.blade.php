@extends('layouts.admin')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
</style>

<div class="p-6 lg:p-8 bg-slate-50 min-h-screen font-sans">

    {{-- HEADER SECTION --}}
    <div class="mb-8">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-sm font-bold tracking-wide uppercase mb-3 border border-emerald-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
            </svg>
            Admin / Rekam Medis
        </div>
        <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight">
            Laporan <span class="text-emerald-600">Pemeriksaan Pasien</span>
        </h1>
        <p class="text-slate-600 font-medium mt-3 text-base lg:text-lg">
            Pilih pasien di bawah ini untuk melihat jejak riwayat pemeriksaan rekam medis secara menyeluruh.
        </p>
    </div>

    {{-- FILTER BOX --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 lg:p-8 mb-8">
        <form method="GET" action="{{ route('admin.pemeriksaan') }}" class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
            
            {{-- Search --}}
            <div class="md:col-span-6 relative">
                <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Cari Pasien</label>
                <div class="absolute inset-y-0 bottom-0 left-0 pl-4 flex items-center pointer-events-none" style="top: 28px;">
                    <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Masukkan Nama Pasien atau NIK..."
                    class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none">
            </div>

            {{-- Filter Poli --}}
            <div class="md:col-span-4 relative">
                <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Poliklinik Asal</label>
                <select name="poli" onchange="this.form.submit()" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none appearance-none cursor-pointer">
                    <option value="">Semua Poliklinik</option>
                    <option value="Poli Umum" {{ request('poli') == 'Poli Umum' ? 'selected' : '' }}>Poli Umum</option>
                    <option value="Poli Gigi" {{ request('poli') == 'Poli Gigi' ? 'selected' : '' }}>Poli Gigi</option>
                    <option value="Poli KIA & KB" {{ request('poli') == 'Poli KIA & KB' ? 'selected' : '' }}>Poli KIA & KB</option>
                </select>
                <div class="absolute inset-y-0 bottom-0 right-0 flex items-center pr-4 pointer-events-none" style="top: 28px;">
                    <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 bg-slate-800 text-white py-3.5 rounded-xl font-bold text-base hover:bg-slate-900 transition-colors shadow-md flex items-center justify-center cursor-pointer">
                    Cari
                </button>
                @if(request('q') || request('poli'))
                    <a href="{{ route('admin.pemeriksaan') }}" class="px-4 py-3.5 bg-slate-100 text-slate-600 rounded-xl text-base font-bold hover:bg-slate-200 transition-colors border border-slate-300 flex items-center justify-center" title="Reset Filter">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- DATA TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead>
                    <tr class="bg-emerald-900 text-white text-sm uppercase tracking-widest font-bold">
                        <th class="py-5 px-6 w-20 text-center rounded-tl-xl">No</th>
                        <th class="py-5 px-6 min-w-[300px]">Nama Pasien / NIK</th>
                        <th class="py-5 px-6 min-w-[200px]">Poliklinik Terakhir</th>
                        <th class="py-5 px-6 text-center min-w-[180px]">Total Kunjungan</th>
                        <th class="py-5 px-6 text-center min-w-[200px] rounded-tr-xl">Aksi</th>
                    </tr>
                </thead>

                <tbody class="text-base divide-y divide-slate-200">
                    @forelse($pasien as $p)
                    @php
                        // Menghitung jumlah kunjungan nyata rekam medis berdasarkan nama pasien yang sama
                        $hitungKunjungan = \App\Models\RekamMedis::whereHas('pendaftaran', function($q) use ($p) {
                            $q->where('nama_pasien', $p->nama_pasien);
                        })->count();
                    @endphp
                    <tr class="hover:bg-emerald-50/60 transition-colors group">
                        <td class="py-5 px-6 text-center font-extrabold text-slate-500">{{ ($pasien->currentPage() - 1) * $pasien->perPage() + $loop->iteration }}</td>
                        <td class="py-5 px-6">
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 rounded-xl bg-slate-900 text-white flex items-center justify-center font-black text-lg shadow-sm flex-shrink-0">
                                    {{ strtoupper(substr($p->nama_pasien ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <a href="{{ route('admin.pemeriksaan.show', $p->id) }}" class="font-extrabold text-slate-800 text-lg hover:text-emerald-600 transition-colors uppercase leading-tight block">
                                        {{ $p->nama_pasien ?? '-' }}
                                    </a>
                                    <p class="text-xs text-slate-400 font-bold tracking-wider font-mono mt-0.5">NIK: {{ $p->no_identitas ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-6 align-middle">
                            <span class="inline-flex px-3 py-1 rounded-md bg-slate-100 text-slate-700 text-xs font-black uppercase tracking-wider border border-slate-200">
                                {{ $p->poli ?? '-' }}
                            </span>
                        </td>
                        <td class="py-5 px-6 text-center align-middle">
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full font-extrabold border border-emerald-200 text-sm">
                                {{ $hitungKunjungan }} Kali Periksa
                            </span>
                        </td>
                        <td class="py-5 px-6 text-center align-middle">
                            <a href="{{ route('admin.pemeriksaan.show', $p->id) }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl text-xs font-extrabold uppercase tracking-wider transition-colors shadow-sm cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Lihat Riwayat
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-24 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="bg-slate-100 p-5 rounded-full text-slate-400 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-slate-800 mb-1 uppercase tracking-wide">Data Pasien Tidak Ditemukan</h3>
                                <p class="text-slate-500 text-sm max-w-sm">Ganti kata kunci pencarian atau filter poliklinik asal pasien.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pasien->hasPages())
        <div class="p-6 border-t border-slate-200">
            {{ $pasien->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

@endsection
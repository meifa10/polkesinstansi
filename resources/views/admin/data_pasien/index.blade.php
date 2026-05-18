@extends('layouts.admin')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    body { 
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    /* Memperbaiki jarak pagination tailwind bawaan laravel */
    nav[role="navigation"] { margin-top: 0.5rem; }
</style>

<div class="p-6 lg:p-8 bg-slate-50 min-h-screen font-sans">
    
    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-8 gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-sm font-bold tracking-wide uppercase mb-3 border border-emerald-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                </svg>
                Admin / Manajemen Data Pasien
            </div>
            <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight">
                Data <span class="text-emerald-600">Pasien</span>
            </h1>
            <p class="text-slate-600 font-medium mt-3 text-base lg:text-lg">
                Kelola rekam medis, pantau riwayat kunjungan, dan status administrasi pasien.
            </p>
        </div>

        {{-- METRIC CARD --}}
        <div class="bg-slate-900 px-8 py-4 rounded-2xl shadow-md border-b-4 border-emerald-500 min-w-[220px]">
            <p class="text-[10px] uppercase tracking-widest text-slate-400 font-black">
                Total Pasien Terdaftar
            </p>
            <p class="text-3xl font-black text-white mt-1 leading-none flex items-baseline gap-2">
                {{ number_format($pasien->total()) }}
                <span class="text-xs text-emerald-400 font-bold tracking-wider">ORANG</span>
            </p>
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
            <h2 class="text-xl font-bold text-slate-800">Pencarian & Filter Data</h2>
        </div>

        <form method="GET" action="{{ route('admin.data_pasien.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
            
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

            {{-- Tipe Pasien Select --}}
            <div class="md:col-span-3 relative">
                <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Jaminan</label>
                <select name="jenis" onchange="this.form.submit()" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none appearance-none cursor-pointer">
                    <option value="">Semua Kategori</option>
                    <option value="umum" {{ request('jenis') == 'umum' ? 'selected' : '' }}>Pasien Umum</option>
                    <option value="jkn" {{ request('jenis') == 'jkn' ? 'selected' : '' }}>Pasien JKN/BPJS</option>
                </select>
                <div class="absolute inset-y-0 bottom-0 right-0 flex items-center pr-4 pointer-events-none" style="top: 28px;">
                    <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            {{-- Filter Tanggal (PERUBAHAN BARU) --}}
            <div class="md:col-span-3 relative">
                <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" onchange="this.form.submit()"
                    class="w-full px-5 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none cursor-pointer">
            </div>
            
            {{-- Action Buttons --}}
            <div class="md:col-span-2 flex gap-3">
                <button type="submit" class="flex-1 bg-slate-800 text-white py-3.5 rounded-xl text-base font-bold hover:bg-slate-900 transition-colors shadow-md">
                    Filter
                </button>
                @if(request('q') || request('jenis') || request('tanggal'))
                    <a href="{{ route('admin.data_pasien.index') }}" class="px-5 py-3.5 bg-slate-100 text-slate-600 rounded-xl text-base font-bold hover:bg-slate-200 transition-colors border border-slate-300 flex items-center justify-center" title="Reset">
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
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-emerald-900 text-white text-sm uppercase tracking-widest font-bold">
                        <th class="py-5 px-6 w-20 text-center rounded-tl-xl">No</th>
                        <th class="py-5 px-6 min-w-[280px]">Profil Pasien</th>
                        <th class="py-5 px-6 min-w-[180px]">Identitas & Riwayat</th>
                        <th class="py-5 px-6 min-w-[130px] text-center">Kunjungan</th>
                        <th class="py-5 px-6 min-w-[180px] text-center">Administrasi</th>
                        <th class="py-5 px-6 min-w-[160px] text-center rounded-tr-xl">Aksi</th>
                    </tr>
                </thead>

                <tbody class="text-base divide-y divide-slate-200">
                    @forelse($pasien as $index => $p)
                    <tr class="hover:bg-emerald-50/60 transition-colors">
                        
                        {{-- NO (Diubah agar nomor urut berlanjut di halaman berikutnya) --}}
                        <td class="py-5 px-6 text-center align-middle">
                            <div class="font-extrabold text-slate-500 text-lg">#{{ $pasien->firstItem() + $index }}</div>
                        </td>

                        {{-- PROFIL PASIEN --}}
                        <td class="py-5 px-6 align-middle">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-slate-900 text-white flex items-center justify-center font-black text-xl shadow-md flex-shrink-0">
                                    {{ strtoupper(substr($p->nama_pasien, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-extrabold text-slate-800 text-lg uppercase tracking-tight leading-tight mb-1">{{ $p->nama_pasien }}</p>
                                    @if(strtolower($p->jenis_pasien) == 'jkn' || strtolower($p->jenis_pasien) == 'bpjs')
                                        <span class="inline-flex px-2.5 py-0.5 rounded-md bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-wider border border-emerald-300 shadow-sm">
                                            {{ $p->jenis_pasien }}
                                        </span>
                                    @else
                                        <span class="inline-flex px-2.5 py-0.5 rounded-md bg-blue-100 text-blue-700 text-[10px] font-black uppercase tracking-wider border border-blue-300 shadow-sm">
                                            {{ $p->jenis_pasien }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- IDENTITAS & RIWAYAT --}}
                        <td class="py-5 px-6 align-middle">
                            <div class="flex items-center gap-2 mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                </svg>
                                <p class="text-sm font-extrabold text-slate-700 tracking-wide font-mono">{{ $p->no_identitas }}</p>
                            </div>
                            <div class="flex items-center gap-2 text-[11px] text-slate-500 font-bold uppercase tracking-wide">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                                @if($p->terakhir_kunjungan)
                                    Terakhir: {{ \Carbon\Carbon::parse($p->terakhir_kunjungan)->translatedFormat('d M Y') }}
                                @else
                                    Belum Ada Kunjungan
                                @endif
                            </div>
                        </td>

                        {{-- KUNJUNGAN --}}
                        <td class="py-5 px-6 align-middle text-center">
                            <div class="inline-flex items-center justify-center min-w-[3rem] px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 text-base font-black border border-slate-300 shadow-inner">
                                {{ $p->total_kunjungan ?? 0 }}x
                            </div>
                        </td>

                        {{-- ADMINISTRASI / STATUS PEMBAYARAN --}}
                        <td class="py-5 px-6 align-middle text-center">
                            @if($p->status_admin === 'lunas')
                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-100 border border-emerald-300 text-emerald-800 text-[11px] font-extrabold shadow-sm uppercase tracking-wider">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    Lunas
                                </div>
                            @elseif($p->status_admin === 'belum_lunas')
                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-rose-100 border border-rose-300 text-rose-800 text-[11px] font-extrabold shadow-sm uppercase tracking-wider animate-pulse">
                                    <span class="flex h-2 w-2 relative">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-600"></span>
                                    </span>
                                    Belum Lunas
                                </div>
                            @else
                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-100 border border-slate-300 text-slate-500 text-[11px] font-extrabold shadow-sm uppercase tracking-wider">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    Tidak Ada
                                </div>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td class="py-5 px-6 align-middle text-center">
                            <a href="{{ route('admin.data_pasien.detail', $p->no_identitas) }}"
                               class="group inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 text-white hover:bg-emerald-600 font-bold rounded-xl transition-all shadow-md text-xs tracking-wide uppercase border border-slate-800 hover:border-emerald-600">
                                Detail
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:translate-x-1 transition-transform" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-24 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="bg-emerald-50 p-6 rounded-full mb-5 border border-emerald-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v11m0 5l4.879-4.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-slate-800 mb-2 uppercase tracking-wide">Data Pasien Tidak Ditemukan</h3>
                                <p class="text-slate-500 text-base max-w-sm">
                                    Silakan periksa kembali kata kunci pencarian Anda atau pastikan data pasien sudah terdaftar pada tanggal tersebut.
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- PAGINATION LINKS (Tambahan Baru) --}}
        @if($pasien->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 bg-white">
            {{ $pasien->links() }}
        </div>
        @endif
        
    </div>

    {{-- FOOTER / INFO TABLE --}}
    <div class="mt-4 flex items-center justify-between">
        <p class="text-[11px] text-slate-500 font-extrabold italic uppercase tracking-wider flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Menampilkan data pasien yang terdaftar di sistem pusat.
        </p>
    </div>
</div>

@endsection
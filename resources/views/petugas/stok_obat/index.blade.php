@extends('layouts.petugas')

@section('content')

<div class="p-6 lg:p-8 bg-slate-50 min-h-screen font-sans">

    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-8 gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold tracking-wide uppercase mb-3 border border-emerald-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                </svg>
                Instalasi Farmasi
            </div>
            <h1 class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">
                Manajemen <span class="text-emerald-600">Stok Obat</span>
            </h1>
            <p class="text-slate-500 font-medium mt-2 text-sm lg:text-base">
                Kelola data obat, pantau ketersediaan, dan perbarui harga dengan mudah.
            </p>
        </div>

        {{-- METRIC CARD --}}
        <div class="bg-white px-6 py-4 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-5 min-w-[200px]">
            <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600 border border-emerald-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <div>
                <p class="text-xs uppercase font-bold text-slate-400 tracking-wider">Total Item</p>
                <h2 class="text-3xl font-black text-slate-800 leading-none mt-1">{{ $obat->count() }}</h2>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
        {{-- FORM TAMBAH OBAT --}}
        <div class="lg:col-span-8 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-2 mb-5 pb-4 border-b border-slate-100">
                <div class="bg-emerald-100 p-1.5 rounded-lg text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-800">Tambah Obat Baru</h2>
            </div>
            <form action="{{ route('petugas.stok_obat.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                @csrf
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Nama Obat</label>
                    <input type="text" name="nama_obat" placeholder="Contoh: Paracetamol 500mg" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Harga (Rp)</label>
                    <input type="number" name="harga" placeholder="0" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Stok Awal</label>
                    <div class="flex gap-2">
                        <input type="number" name="stok" placeholder="0" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none">
                        <button type="submit" class="bg-emerald-600 text-white p-3 rounded-xl font-bold hover:bg-emerald-700 shadow-md shadow-emerald-500/30 transition-all flex items-center justify-center min-w-[3rem]" title="Simpan Obat Baru">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- SEARCH BOX --}}
        <div class="lg:col-span-4 bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col justify-center">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Pencarian Cepat</label>
            <form action="{{ route('petugas.stok_obat.index') }}" method="GET" class="flex flex-col gap-3">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama obat..."
                        class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-slate-800 text-white py-3 rounded-xl text-sm font-bold hover:bg-slate-900 transition-colors shadow-md">
                        Cari Obat
                    </button>
                    @if(request('q'))
                        <a href="{{ route('petugas.stok_obat.index') }}" class="px-5 py-3 bg-slate-100 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-200 transition-colors border border-slate-200 flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- DATA TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-emerald-900 text-white text-xs uppercase tracking-wider font-semibold">
                        <th class="p-4 w-12 text-center rounded-tl-xl">No</th>
                        <th class="p-4 min-w-[220px]">Edit Nama Obat</th>
                        <th class="p-4 min-w-[150px]">Edit Harga</th>
                        <th class="p-4 min-w-[120px]">Edit Stok</th>
                        <th class="p-4 min-w-[140px] rounded-tr-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100">
                    @forelse($obat as $item)
                    <tr class="hover:bg-emerald-50/50 transition-colors">
                        <td class="p-4 text-center text-slate-500 font-bold align-middle">
                            {{ $loop->iteration }}
                        </td>
                        
                        {{-- QUICK EDIT FORM (Mencakup 3 Kolom + Tombol Simpan) --}}
                        <td class="p-4 align-middle" colspan="4">
                            <div class="flex flex-wrap items-center gap-4 w-full">
                                <form action="{{ route('petugas.stok_obat.update', $item->id) }}" method="POST" class="flex flex-1 items-center gap-4">
                                    @csrf
                                    @method('PUT')
                                    
                                    {{-- Input Nama --}}
                                    <div class="flex-1 min-w-[200px]">
                                        <input type="text" name="nama_obat" value="{{ $item->nama_obat }}" 
                                            class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none font-semibold text-slate-700 shadow-sm" required>
                                    </div>

                                    {{-- Input Harga --}}
                                    <div class="w-[130px]">
                                        <div class="relative">
                                            <span class="absolute left-3 top-2 text-slate-400 text-sm">Rp</span>
                                            <input type="number" name="harga" value="{{ $item->harga }}" 
                                                class="w-full pl-8 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none shadow-sm" required>
                                        </div>
                                    </div>

                                    {{-- Input Stok --}}
                                    <div class="w-[100px] flex items-center gap-2">
                                        <input type="number" name="stok" value="{{ $item->stok }}" 
                                            class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none shadow-sm {{ $item->stok <= 5 ? 'border-rose-400 bg-rose-50 text-rose-700' : '' }}" required>
                                        
                                        {{-- Indikator Kritis --}}
                                        @if($item->stok <= 5)
                                            <span class="flex h-3 w-3 relative" title="Stok Kritis!">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Tombol Update --}}
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-100 text-emerald-700 hover:bg-emerald-600 hover:text-white font-bold rounded-lg transition-colors border border-emerald-200 hover:border-emerald-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293zM9 4a1 1 0 012 0v2H9V4z" />
                                        </svg>
                                        Update
                                    </button>
                                </form>

                                {{-- DELETE FORM (Terpisah dari form update) --}}
                                <form action="{{ route('petugas.stok_obat.destroy', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus obat {{ $item->nama_obat }}?')" 
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white font-bold rounded-lg transition-colors border border-rose-200 hover:border-rose-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="bg-emerald-50 p-4 rounded-full mb-4 border border-emerald-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-slate-700 mb-1">Data Obat Tidak Ditemukan</h3>
                                <p class="text-slate-500 text-sm max-w-sm">
                                    Belum ada data obat yang terdaftar atau pencarian Anda tidak membuahkan hasil.
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
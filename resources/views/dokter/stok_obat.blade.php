@extends('layouts.dokter')

@section('content')
<div class="p-6 lg:p-8 bg-slate-50 min-h-screen font-sans">

    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-8 gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-sm font-bold tracking-wide uppercase mb-3 border border-emerald-200">
                <i class="fa-solid fa-prescription-bottle-medical"></i> Data Apotek / Farmasi
            </div>
            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">
                Informasi <span class="text-emerald-600">Stok Obat</span>
            </h1>
            <p class="text-slate-600 font-medium mt-2 text-sm lg:text-base">
                Data real-time ketersediaan obat dan harga penunjang resep pasien.
            </p>
        </div>

        {{-- METRIC CARD --}}
        <div class="bg-white px-6 py-4 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4 min-w-[200px]">
            <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600 border border-emerald-100">
                <i class="fa-solid fa-boxes-stacked text-2xl"></i>
            </div>
            <div>
                <p class="text-xs uppercase font-bold text-slate-400 tracking-wider">Total Jenis Obat</p>
                <h2 class="text-3xl font-black text-slate-800 leading-none mt-1">{{ $obat->total() }}</h2>
            </div>
        </div>
    </div>

    {{-- SEARCH BOX --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
        <form action="{{ route('dokter.data_obat') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center">
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                </div>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari ketersediaan resep obat..."
                    class="w-full pl-12 pr-5 py-3 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none">
            </div>
            <div class="flex gap-2 w-full md:w-auto">
                <button type="submit" class="flex-1 md:flex-initial bg-slate-880 bg-slate-800 text-white px-6 py-3 rounded-xl text-base font-bold hover:bg-slate-900 transition-colors shadow-md">
                    Cari
                </button>
                @if(request('q'))
                    <a href="{{ route('dokter.data_obat') }}" class="px-5 py-3 bg-slate-100 text-slate-600 rounded-xl text-base font-bold hover:bg-slate-200 transition-colors border border-slate-300 flex items-center justify-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- DATA TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-emerald-900 text-white text-sm uppercase tracking-widest font-bold">
                        <th class="py-4 px-6 w-16 text-center">No</th>
                        <th class="py-4 px-6">Nama Obat</th>
                        <th class="py-4 px-6">Harga Satuan</th>
                        <th class="py-4 px-6">Ketersediaan Stok</th>
                    </tr>
                </thead>
                <tbody class="text-base divide-y divide-slate-200">
                    @forelse($obat as $item)
                    <tr class="hover:bg-emerald-50/40 transition-colors">
                        <td class="py-4 px-6 text-center text-slate-500 font-bold align-middle">
                            {{-- Menggunakan firstItem agar nomor urut berlanjut di halaman 2 (6, 7, 8...) --}}
                            {{ $obat->firstItem() + $loop->index }}
                        </td>
                        <td class="py-4 px-6 align-middle">
                            <span class="font-bold text-slate-800 text-lg block">{{ $item->nama_obat }}</span>
                        </td>
                        <td class="py-4 px-6 text-slate-700 font-bold align-middle">
                            Rp {{ number_format($item->harga, 0, ',', '.') }}
                        </td>
                        <td class="py-4 px-6 align-middle">
                            @if($item->stok <= 5)
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-100 border border-rose-200 text-rose-800 text-xs font-extrabold">
                                    <span class="flex h-2 w-2 relative">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-600"></span>
                                    </span>
                                    {{ $item->stok }} (Stok Kritis)
                                </div>
                            @else
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 border border-emerald-200 text-emerald-800 text-xs font-extrabold">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    {{ $item->stok }} Tersedia
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-16 text-center">
                            <div class="text-slate-400 text-lg font-medium">Data obat tidak ditemukan atau apotek kosong.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- NAVIGASI TOMBOL NEXT / PREVIOUS (PAGINATION LINKS) --}}
    <div class="mt-4 px-2">
        {!! $obat->links() !!}
    </div>

</div>
@endsection
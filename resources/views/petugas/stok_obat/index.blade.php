@extends('layouts.petugas')

@section('content')

<div class="p-6 bg-slate-100 min-h-screen">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900">
                Stok <span class="text-emerald-600">Obat</span>
            </h1>
            <p class="text-slate-500 font-medium mt-1">
                Kelola data obat klinik
            </p>
        </div>

        <div class="bg-white px-5 py-3 rounded-2xl shadow border">
            <p class="text-xs uppercase font-bold text-slate-400">
                Total Obat
            </p>
            <h2 class="text-2xl font-extrabold text-slate-900">
                {{ $obat->count() }}
            </h2>
        </div>
    </div>

    {{-- FORM TAMBAH --}}
    <div class="bg-white rounded-3xl shadow border p-6 mb-6">
        <form action="{{ route('petugas.stok_obat.store') }}"
            method="POST"
            class="grid md:grid-cols-4 gap-4">
            @csrf
            <input
                type="text"
                name="nama_obat"
                placeholder="Nama Obat"
                class="px-4 py-3 rounded-xl border focus:outline-emerald-500"
                required
            >
            <input
                type="number"
                name="harga"
                placeholder="Harga"
                class="px-4 py-3 rounded-xl border focus:outline-emerald-500"
                required
            >
            <input
                type="number"
                name="stok"
                placeholder="Stok"
                class="px-4 py-3 rounded-xl border focus:outline-emerald-500"
                required
            >
            <button
                type="submit"
                class="bg-emerald-600 text-white rounded-xl font-bold hover:bg-emerald-700 transition">
                Tambah Obat
            </button>
        </form>
    </div>

    {{-- SEARCH (DIPERBAIKI) --}}
    <div class="bg-white rounded-3xl shadow border p-4 mb-6">
        {{-- Gunakan method GET dan action ke route index --}}
        <form action="{{ route('petugas.stok_obat.index') }}" method="GET" class="flex gap-2">
            <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                placeholder="Cari nama obat..."
                class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-emerald-500 outline-none"
            >
            <button type="submit" class="bg-slate-800 text-white px-6 rounded-xl font-bold hover:bg-slate-900 transition">
                Cari
            </button>
            @if(request('q'))
                <a href="{{ route('petugas.stok_obat.index') }}" class="bg-slate-200 text-slate-700 px-6 py-3 rounded-xl font-bold hover:bg-slate-300 transition flex items-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-3xl shadow border overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-900 text-white">
                <tr>
                    <th class="p-4 text-left">No</th>
                    <th class="p-4 text-left">Nama Obat</th>
                    <th class="p-4 text-left">Harga</th>
                    <th class="p-4 text-left">Stok</th>
                    <th class="p-4 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($obat as $item)
                <tr class="border-b hover:bg-slate-50 transition">
                    <td class="p-4 text-center">
                        {{ $loop->iteration }}
                    </td>
                    <td class="p-4 font-bold text-slate-700">
                        {{ $item->nama_obat }}
                    </td>
                    <td class="p-4">
                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                    </td>
                    <td class="p-4">
                        @if($item->stok <= 5)
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">
                                {{ $item->stok }} (Kritis)
                            </span>
                        @else
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">
                                {{ $item->stok }}
                            </span>
                        @endif
                    </td>
                    <td class="p-4">
                        <div class="flex gap-2">
                            {{-- EDIT --}}
                            <form action="{{ route('petugas.stok_obat.update', $item->id) }}"
                                method="POST"
                                class="flex gap-2">
                                @csrf
                                @method('PUT')
                                <input
                                    type="text"
                                    name="nama_obat"
                                    value="{{ $item->nama_obat }}"
                                    class="border rounded px-2 py-1 text-sm focus:border-blue-500 outline-none"
                                    required
                                >
                                <input
                                    type="number"
                                    name="harga"
                                    value="{{ $item->harga }}"
                                    class="border rounded px-2 py-1 text-sm w-28 focus:border-blue-500 outline-none"
                                    required
                                >
                                <input
                                    type="number"
                                    name="stok"
                                    value="{{ $item->stok }}"
                                    class="border rounded px-2 py-1 text-sm w-20 focus:border-blue-500 outline-none"
                                    required
                                >
                                <button
                                    type="submit"
                                    class="bg-blue-600 text-white px-3 py-1 rounded text-sm font-bold hover:bg-blue-700">
                                    Update
                                </button>
                            </form>

                            {{-- DELETE --}}
                            <form action="{{ route('petugas.stok_obat.destroy', $item->id) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus obat ini?')"
                                    class="bg-red-600 text-white px-3 py-1 rounded text-sm font-bold hover:bg-red-700">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-12 text-slate-400">
                        <div class="flex flex-col items-center">
                            <span class="text-4xl mb-2">🔍</span>
                            <p class="font-bold">Obat tidak ditemukan</p>
                            <p class="text-sm">Coba kata kunci lain atau bersihkan pencarian.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
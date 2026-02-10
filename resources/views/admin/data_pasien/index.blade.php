@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-4">Data Pasien</h1>

{{-- SEARCH --}}
<form method="GET" action="{{ route('admin.data_pasien.index') }}" class="mb-4 flex gap-3">
    <input
        type="text"
        name="q"
        value="{{ request('q') }}"
        placeholder="Cari nama / no identitas / jenis pasien"
        class="border rounded-lg px-4 py-2 w-80 focus:outline-none focus:ring focus:ring-blue-200"
    >

    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">
        Cari
    </button>

    @if(request('q'))
        <a href="{{ route('admin.data_pasien.index') }}"
           class="bg-gray-200 px-4 py-2 rounded-lg">
            Reset
        </a>
    @endif
</form>

<div class="bg-white rounded-xl shadow p-6 overflow-x-auto">
    <table class="w-full border-collapse text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3 text-left">Nama Pasien</th>
                <th class="p-3 text-left">No Identitas</th>
                <th class="p-3 text-left">Jenis</th>
                <th class="p-3 text-center">Total Kunjungan</th>
                <th class="p-3 text-left">Terakhir Berobat</th>
                <th class="p-3 text-center">Status Admin</th>
                <th class="p-3 text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
        @forelse($pasien as $p)
            <tr class="border-b hover:bg-gray-50 transition">
                {{-- NAMA --}}
                <td class="p-3 font-medium text-gray-800">
                    {{ $p->nama_pasien }}
                </td>

                {{-- IDENTITAS --}}
                <td class="p-3">
                    {{ $p->no_identitas }}
                </td>

                {{-- JENIS --}}
                <td class="p-3 uppercase text-xs font-semibold">
                    {{ $p->jenis_pasien }}
                </td>

                {{-- TOTAL KUNJUNGAN --}}
                <td class="p-3 text-center">
                    {{ $p->total_kunjungan }}x
                </td>

                {{-- TERAKHIR --}}
                <td class="p-3">
                    {{ \Carbon\Carbon::parse($p->terakhir_kunjungan)->format('d M Y') }}
                </td>

                {{-- STATUS ADMIN --}}
                <td class="p-3 text-center">
                    @if($p->status_admin === 'lunas')
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            LUNAS
                        </span>
                    @elseif($p->status_admin === 'belum_lunas')
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                            BELUM LUNAS
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                            BELUM ADA TAGIHAN
                        </span>
                    @endif
                </td>

                {{-- AKSI --}}
                <td class="p-3 text-center">
                    <a href="{{ route('admin.data_pasien.detail', $p->no_identitas) }}"
                       class="inline-block px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs">
                        Detail
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="p-6 text-center text-gray-500">
                    Data pasien tidak ditemukan
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection

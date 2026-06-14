@extends('layouts.petugas')

@section('content')
<div class="p-6 lg:p-8 bg-slate-50 min-h-screen">
    <div class="mb-8">
        <h1 class="text-4xl font-extrabold text-slate-900">Laporan Pemeriksaan Pasien</h1>
        <p class="text-slate-600 font-medium mt-2">Pilih pasien untuk melihat riwayat medis.</p>
    </div>

    {{-- Form Filter --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-8">
        <form method="GET" action="{{ route('petugas.laporan.diagnosa') }}" class="flex gap-4">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama pasien..." class="w-full px-4 py-3 border rounded-xl">
            <button type="submit" class="bg-emerald-600 text-white px-6 py-3 rounded-xl font-bold">Cari</button>
        </form>
    </div>

    {{-- Tabel Pasien --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left">
            <tr class="bg-emerald-900 text-white uppercase text-xs font-bold">
                <th class="py-4 px-6">No</th>
                <th class="py-4 px-6">Nama Pasien / NIK</th>
                <th class="py-4 px-6">Poli Terakhir</th>
                <th class="py-4 px-6 text-center">Aksi</th>
            </tr>
            @foreach($pasien as $p)
            <tr class="border-b hover:bg-emerald-50">
                <td class="py-4 px-6">{{ $loop->iteration }}</td>
                <td class="py-4 px-6 font-bold">{{ $p->nama_pasien }}<br><span class="text-xs text-slate-400">NIK: {{ $p->no_identitas }}</span></td>
                <td class="py-4 px-6">{{ $p->poli }}</td>
                <td class="py-4 px-6 text-center">
                    <a href="{{ route('petugas.laporan.show', $p->id) }}" class="bg-emerald-600 text-white px-4 py-2 rounded-xl text-xs font-bold">Lihat Riwayat</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection
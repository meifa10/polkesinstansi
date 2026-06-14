@extends('layouts.petugas')
@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="mb-8">
        <h1 class="text-4xl font-extrabold text-slate-900">Daftar Pasien</h1>
        <p class="text-slate-600">Pilih pasien untuk melihat riwayat medis lengkap.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-emerald-900 text-white">
                <tr>
                    <th class="py-5 px-6">No</th>
                    <th class="py-5 px-6">Nama Pasien / NIK</th>
                    <th class="py-5 px-6">Poliklinik</th>
                    <th class="py-5 px-6">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($laporan as $item)
                <tr class="hover:bg-slate-50">
                    <td class="py-4 px-6">{{ $loop->iteration }}</td>
                    <td class="py-4 px-6 font-bold text-slate-800">{{ $item->nama_pasien }}<br><span class="text-xs font-normal text-slate-500">NIK: {{ $item->nik }}</span></td>
                    <td class="py-4 px-6"><span class="bg-slate-100 px-3 py-1 rounded-full text-sm font-semibold">{{ $item->poli }}</span></td>
                    <td class="py-4 px-6">
                        <a href="{{ route('petugas.laporan.riwayat', $item->nik) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-xl text-sm font-bold transition-all">LIHAT RIWAYAT</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $laporan->links() }}</div>
</div>
@endsection
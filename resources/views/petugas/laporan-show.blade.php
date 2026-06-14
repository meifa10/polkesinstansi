@extends('layouts.petugas')

@section('content')
<div class="p-6 lg:p-8 bg-slate-50 min-h-screen">
    {{-- Tombol Kembali --}}
    <a href="{{ route('petugas.laporan.diagnosa') }}" class="mb-6 inline-block bg-white border px-4 py-2 rounded-xl font-bold text-slate-700">« Kembali</a>

    {{-- Header Kartu Identitas --}}
    <div class="bg-gradient-to-r from-emerald-900 to-slate-900 text-white p-8 rounded-3xl mb-8">
        <h1 class="text-3xl font-black uppercase">{{ $pasien->nama_pasien }}</h1>
        <p class="text-emerald-300">NIK: {{ $pasien->no_identitas }} | Poli: {{ $pasien->poli }}</p>
    </div>

    {{-- Tabel Riwayat --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-900 text-white text-xs uppercase font-black">
                    <th class="py-5 px-6">Waktu</th>
                    <th class="py-5 px-6">Keluhan</th>
                    <th class="py-5 px-6">Diagnosis</th>
                    <th class="py-5 px-6">Dokter</th>
                </tr>
            </thead>
            <tbody>
                @foreach($riwayat as $item)
                <tr class="border-b">
                    <td class="py-4 px-6">{{ $item->created_at->format('d M Y') }}</td>
                    <td class="py-4 px-6">{{ $item->keluhan ?? '-' }}</td>
                    <td class="py-4 px-6 font-bold text-rose-700">{{ $item->rekamMedis->diagnosis ?? '-' }}</td>
                    <td class="py-4 px-6">{{ $item->dokter->name ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
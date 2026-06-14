@extends('layouts.petugas')
@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('petugas.laporan.index') }}" class="text-slate-500 hover:text-emerald-600 font-bold">&larr; Kembali</a>
        <h1 class="text-3xl font-bold">Riwayat Medis: {{ $namaPasien }}</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-800 text-white text-xs uppercase tracking-wider">
                    <th class="p-4">Waktu</th>
                    <th class="p-4">Keluhan</th>
                    <th class="p-4">Tanda Vital</th>
                    <th class="p-4">Diagnosis</th>
                    <th class="p-4">Tindakan</th>
                    <th class="p-4">Resep</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($riwayat as $item)
                <tr>
                    <td class="p-4 font-bold text-sm">{{ $item->created_at->format('d/m/Y') }}<br>{{ $item->created_at->format('H:i') }}</td>
                    <td class="p-4">{{ $item->rekamMedis->keluhan ?? '-' }}</td>
                    <td class="p-4 text-xs">
                        T: {{ $item->rekamMedis->tensi ?? '-' }}<br>
                        B: {{ $item->rekamMedis->berat ?? '-' }}kg
                    </td>
                    <td class="p-4 text-rose-700 font-bold">{{ $item->rekamMedis->diagnosis ?? '-' }}</td>
                    <td class="p-4 text-sm">{{ $item->rekamMedis->tindakan ?? '-' }}</td>
                    <td class="p-4 text-sm bg-amber-50">{{ $item->rekamMedis->resep ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
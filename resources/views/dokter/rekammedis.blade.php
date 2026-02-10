@extends('layouts.dokter')

@section('content')
<h1 class="text-xl font-bold mb-4">Rekam Medis Pasien</h1>

<div class="bg-white rounded-xl shadow p-4">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b bg-gray-50">
                <th class="p-2 text-left">Nama Pasien</th>
                <th class="p-2 text-left">Poli</th>
                <th class="p-2 text-left">Keluhan</th>
                <th class="p-2 text-left">Diagnosis</th>
                <th class="p-2 text-left">Tindakan</th>
                <th class="p-2 text-left">Resep</th>
                <th class="p-2 text-left">Tanggal</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($data as $item)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-2">
                    {{ $item->pendaftaran->nama_pasien ?? '-' }}
                </td>
                <td class="p-2">
                    {{ $item->pendaftaran->poli ?? '-' }}
                </td>
                <td class="p-2">
                    {{ $item->keluhan }}
                </td>
                <td class="p-2">
                    {{ $item->diagnosis }}
                </td>
                <td class="p-2">
                    {{ $item->tindakan }}
                </td>
                <td class="p-2">
                    {{ $item->resep ?? '-' }}
                </td>
                <td class="p-2">
                    {{ $item->created_at->format('d-m-Y') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="p-4 text-center text-gray-500">
                    Belum ada rekam medis
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

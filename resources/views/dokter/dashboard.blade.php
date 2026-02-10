@extends('layouts.dokter')

@section('content')
<div class="p-6">

    <h1 class="text-2xl font-bold mb-6">Pasien Siap Diperiksa</h1>

    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">No</th>
                    <th>Nama Pasien</th>
                    <th>Jenis</th>
                    <th>Poli</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($pasien as $item)
                <tr class="border-b">
                    <td class="p-3">{{ $loop->iteration }}</td>
                    <td>{{ $item->nama_pasien }}</td>
                    <td class="uppercase">{{ $item->jenis_pasien }}</td>
                    <td>{{ $item->poli }}</td>
                    <td>
                        <a href="{{ route('dokter.pemeriksaan.show', $item->id) }}"
                           class="bg-blue-500 text-white px-3 py-1 rounded">
                           Periksa
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-5 text-center text-gray-500">
                        Belum ada pasien
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection

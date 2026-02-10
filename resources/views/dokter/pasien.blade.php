@extends('layouts.dokter')

@section('content')
<h1 class="text-xl font-bold mb-4">Daftar Pasien</h1>

<table class="bg-white w-full rounded shadow text-sm">
    <tr class="border-b">
        <th class="p-2 text-left">Nama</th>
        <th class="p-2">Poli</th>
        <th class="p-2">Aksi</th>
    </tr>

    @foreach($pasien as $p)
    <tr class="border-b">
        <td class="p-2">{{ $p->nama_pasien }}</td>
        <td class="p-2">{{ $p->poli }}</td>
        <td class="p-2">
            <a href="{{ route('dokter.pemeriksaan.show',$p->id) }}"
               class="bg-emerald-600 text-white px-3 py-1 rounded">
                Periksa
            </a>
        </td>
    </tr>
    @endforeach
</table>
@endsection

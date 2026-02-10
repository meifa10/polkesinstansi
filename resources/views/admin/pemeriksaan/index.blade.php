@extends('layouts.admin')

@section('content')
<h1 class="text-xl font-bold mb-4">Pemeriksaan Pasien</h1>

{{-- SEARCH --}}
<form method="GET"
      action="{{ route('admin.pemeriksaan') }}"
      class="mb-4 flex gap-3">

    <input
        type="text"
        name="q"
        value="{{ request('q') }}"
        placeholder="Cari pasien / poli / dokter / diagnosis"
        class="border rounded-lg px-4 py-2 w-96
               focus:outline-none focus:ring focus:ring-blue-200"
    >

    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">
        Cari
    </button>

    @if(request('q'))
        <a href="{{ route('admin.pemeriksaan') }}"
           class="bg-gray-200 px-4 py-2 rounded-lg">
            Reset
        </a>
    @endif
</form>

<div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-3 text-left">Nama Pasien</th>
                <th class="px-4 py-3 text-left">Poli</th>
                <th class="px-4 py-3 text-left">Dokter</th>
                <th class="px-4 py-3 text-left">Diagnosis</th>
                <th class="px-4 py-3 text-left">Tindakan</th>
                <th class="px-4 py-3 text-left">Resep</th>
                <th class="px-4 py-3 text-left">Tanggal</th>
            </tr>
        </thead>

        <tbody class="divide-y">
            @forelse ($pemeriksaan as $item)
                <tr>
                    <td class="px-4 py-3 font-medium">
                        {{ $item->pendaftaran->nama_pasien ?? '-' }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $item->pendaftaran->poli ?? '-' }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $item->dokter->name ?? '-' }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $item->diagnosis }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $item->tindakan }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $item->resep ?? '-' }}
                    </td>

                    <td class="px-4 py-3 text-gray-500">
                        {{ $item->created_at->format('d M Y') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7"
                        class="px-4 py-6 text-center text-gray-500">
                        Data pemeriksaan tidak ditemukan
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

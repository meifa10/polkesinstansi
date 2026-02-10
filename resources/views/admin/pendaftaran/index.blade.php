@extends('layouts.admin')

@section('content')
<div class="p-6">

    <h1 class="text-2xl font-bold mb-6">Pendaftaran Pasien</h1>

    {{-- SEARCH --}}
    <form method="GET" action="{{ route('admin.pendaftaran.index') }}" class="mb-4 flex gap-3">
        <input
            type="text"
            name="q"
            value="{{ request('q') }}"
            placeholder="Cari nama / no identitas / poli"
            class="border rounded-lg px-4 py-2 w-72 focus:outline-none focus:ring focus:ring-blue-200"
        >

        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            Cari
        </button>

        @if(request('q'))
            <a href="{{ route('admin.pendaftaran.index') }}"
               class="bg-gray-200 px-4 py-2 rounded-lg">
                Reset
            </a>
        @endif
    </form>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="p-3">No</th>
                    <th>Jenis</th>
                    <th>Nama</th>
                    <th>Identitas</th>
                    <th>Poli</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($pendaftaran as $item)
                <tr class="border-b">
                    <td class="p-3">{{ $loop->iteration }}</td>
                    <td class="uppercase">{{ $item->jenis_pasien }}</td>
                    <td>{{ $item->nama_pasien }}</td>
                    <td>{{ $item->no_identitas }}</td>
                    <td>{{ $item->poli }}</td>

                    <td>
                        <span class="px-2 py-1 rounded text-xs
                            {{ $item->status == 'menunggu' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $item->status == 'diproses' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $item->status == 'selesai' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $item->status == 'ditolak' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ strtoupper($item->status) }}
                        </span>
                    </td>

                    <td>
                        <form method="POST"
                              action="{{ route('admin.pendaftaran.status', $item->id) }}"
                              class="flex gap-2">
                            @csrf
                            <select name="status" class="border rounded px-2 py-1 text-sm">
                                <option value="menunggu" {{ $item->status=='menunggu'?'selected':'' }}>Menunggu</option>
                                <option value="diproses" {{ $item->status=='diproses'?'selected':'' }}>Diproses</option>
                                <option value="selesai" {{ $item->status=='selesai'?'selected':'' }}>Selesai</option>
                                <option value="ditolak" {{ $item->status=='ditolak'?'selected':'' }}>Ditolak</option>
                            </select>
                            <button class="bg-emerald-500 text-white px-3 rounded">
                                Simpan
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach

                @if($pendaftaran->count() == 0)
                <tr>
                    <td colspan="7" class="p-5 text-center text-gray-500">
                        Data tidak ditemukan
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

</div>
@endsection

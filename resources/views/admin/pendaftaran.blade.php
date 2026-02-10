@extends('layouts.admin')

@section('content')
<h1 class="text-xl font-bold mb-6">Pendaftaran Pasien</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3 text-left">No</th>
                <th class="p-3 text-left">Jenis</th>
                <th class="p-3 text-left">Nama</th>
                <th class="p-3 text-left">Identitas</th>
                <th class="p-3 text-left">Poli</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $item)
            <tr class="border-t">
                <td class="p-3">{{ $loop->iteration }}</td>
                <td class="p-3 capitalize">{{ $item->jenis_pasien }}</td>
                <td class="p-3">{{ $item->nama_pasien }}</td>
                <td class="p-3">{{ $item->no_identitas }}</td>
                <td class="p-3">{{ $item->poli }}</td>
                <td class="p-3">
                    <span class="
                        px-2 py-1 rounded text-xs
                        @if($item->status=='menunggu') bg-yellow-100 text-yellow-700
                        @elseif($item->status=='diperiksa') bg-blue-100 text-blue-700
                        @else bg-green-100 text-green-700 @endif
                    ">
                        {{ ucfirst($item->status) }}
                    </span>
                </td>
                <td class="p-3">
                    <form method="POST" action="{{ route('admin.pendaftaran.status', $item->id) }}">
                        @csrf
                        <select name="status" class="border rounded px-2 py-1 text-sm">
                            <option value="menunggu" {{ $item->status=='menunggu'?'selected':'' }}>Menunggu</option>
                            <option value="diperiksa" {{ $item->status=='diperiksa'?'selected':'' }}>Diperiksa</option>
                            <option value="selesai" {{ $item->status=='selesai'?'selected':'' }}>Selesai</option>
                        </select>
                        <button class="ml-2 bg-emerald-600 text-white px-3 py-1 rounded text-xs">
                            Simpan
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="p-6 text-center text-gray-500">
                    Belum ada pendaftaran
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

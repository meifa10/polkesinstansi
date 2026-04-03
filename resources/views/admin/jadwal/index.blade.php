@extends('layouts.admin')

@section('content')

<div class="p-10 space-y-10">

    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Jadwal Praktik Dokter
            </h1>
            <p class="text-gray-500">
                Kelola jadwal layanan dokter
            </p>
        </div>

        <button onclick="document.getElementById('modal').classList.remove('hidden')"
            class="bg-emerald-600 hover:bg-emerald-700
                   text-white px-5 py-2 rounded-xl shadow">
            + Tambah Jadwal
        </button>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">

        @foreach($jadwal as $j)

        <div class="bg-white rounded-3xl shadow-2xl p-8">

            @if($j->buka_hari_ini)
                <div class="bg-emerald-500 text-white px-3 py-1 rounded-full text-xs w-fit mb-3">
                    BUKA HARI INI
                </div>
            @endif

            <h2 class="font-bold text-lg">
                {{ $j->dokter->name }}
            </h2>

            <p class="text-emerald-600">
                {{ $j->poli }}
            </p>

            <p class="mt-3 text-sm text-gray-600">
                📅 {{ $j->hari }}
            </p>

            <p class="text-sm text-gray-600">
                ⏰ {{ substr($j->jam_mulai,0,5) }} - {{ substr($j->jam_selesai,0,5) }}
            </p>

            <div class="mt-4">
                <form method="POST" action="{{ route('admin.jadwal_dokter.toggle',$j->id) }}">
                    @csrf
                    <button class="w-full py-2 rounded-xl
                        {{ $j->status == 'aktif'
                            ? 'bg-red-500 hover:bg-red-600'
                            : 'bg-green-500 hover:bg-green-600' }}
                        text-white text-sm">
                        {{ $j->status == 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                </form>
            </div>

        </div>

        @endforeach

    </div>

</div>


{{-- MODAL TAMBAH --}}
<div id="modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center">

<form method="POST" action="{{ route('admin.jadwal_dokter.store') }}"
      class="bg-white p-6 rounded-2xl w-full max-w-md space-y-4">
    @csrf

    <h2 class="font-bold text-lg">Tambah Jadwal</h2>

    <select name="dokter_id" class="w-full border p-2 rounded">
        @foreach($dokter as $d)
            <option value="{{ $d->id }}">{{ $d->name }}</option>
        @endforeach
    </select>

    <input type="text" name="poli" placeholder="Poli"
           class="w-full border p-2 rounded" required>

    <input type="text" name="hari" placeholder="Contoh: Senin,Jumat"
           class="w-full border p-2 rounded" required>

    <input type="time" name="jam_mulai"
           class="w-full border p-2 rounded" required>

    <input type="time" name="jam_selesai"
           class="w-full border p-2 rounded" required>

    <button class="w-full bg-emerald-600 text-white py-2 rounded">
        Simpan
    </button>
</form>

</div>

@endsection
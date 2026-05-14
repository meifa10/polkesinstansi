@extends('petugas.petugas')

@section('content')

<h1 class="text-2xl font-bold mb-6">
    Pemeriksaan Awal
</h1>

<div class="bg-white p-6 rounded-xl shadow">

    <form method="POST"
        action="{{ route('petugas.pemeriksaan_awal.update', $pasien->id) }}">

        @csrf

        <div class="mb-4">

            <label class="font-semibold">
                Nama Pasien
            </label>

            <input type="text"
                value="{{ $pasien->nama_pasien }}"
                readonly
                class="w-full border rounded p-3 bg-gray-100">

        </div>

        <div class="mb-4">

            <label class="font-semibold">
                Berat Badan (BB)
            </label>

            <input type="text"
                name="bb"
                required
                class="w-full border rounded p-3">

        </div>

        <div class="mb-4">

            <label class="font-semibold">
                Tinggi Badan (TB)
            </label>

            <input type="text"
                name="tb"
                required
                class="w-full border rounded p-3">

        </div>

        <div class="mb-4">

            <label class="font-semibold">
                Keluhan
            </label>

            <textarea
                name="keluhan"
                required
                class="w-full border rounded p-3"></textarea>

        </div>

        <button class="bg-emerald-600 text-white px-6 py-3 rounded">
            Simpan Pemeriksaan
        </button>

    </form>

</div>

@endsection
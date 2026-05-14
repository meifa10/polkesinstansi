@extends('layouts.petugas')

@section('content')

<h1 class="text-2xl font-bold mb-6">
    Input Pemeriksaan Awal
</h1>

<div class="bg-white rounded-xl shadow p-6 max-w-2xl">

    <form method="POST"
        action="{{ route('petugas.pemeriksaan_awal.update', $pasien->id) }}">

        @csrf

        <div class="mb-4">
            <label class="block mb-2 font-semibold">
                Nama Pasien
            </label>

            <input type="text"
                value="{{ $pasien->nama_pasien }}"
                readonly
                class="w-full border rounded-lg p-3 bg-gray-100">
        </div>

        <div class="mb-4">
            <label class="block mb-2 font-semibold">
                Poli
            </label>

            <input type="text"
                value="{{ $pasien->poli }}"
                readonly
                class="w-full border rounded-lg p-3 bg-gray-100">
        </div>

        <div class="mb-4">
            <label class="block mb-2 font-semibold">
                Berat Badan (kg)
            </label>

            <input type="number"
                name="berat_badan"
                required
                class="w-full border rounded-lg p-3">
        </div>

        <div class="mb-4">
            <label class="block mb-2 font-semibold">
                Tinggi Badan (cm)
            </label>

            <input type="number"
                name="tinggi_badan"
                required
                class="w-full border rounded-lg p-3">
        </div>

        <div class="mb-6">
            <label class="block mb-2 font-semibold">
                Keluhan Pasien
            </label>

            <textarea
                name="keluhan"
                rows="4"
                required
                class="w-full border rounded-lg p-3"></textarea>
        </div>

        <button type="submit"
            class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-lg">

            Simpan Pemeriksaan

        </button>

    </form>

</div>

@endsection
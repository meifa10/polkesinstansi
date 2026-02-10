@extends('layouts.dokter')

@section('content')
<div class="p-6 max-w-3xl mx-auto">

    <h2 class="text-xl font-bold mb-4">
        Pemeriksaan Pasien: {{ $pasien->nama_pasien }}
    </h2>

    <form method="POST" action="{{ route('dokter.pemeriksaan.store', $pasien->id) }}">
        @csrf

        <div class="mb-4">
            <label>Keluhan</label>
            <textarea name="keluhan" class="w-full border rounded p-2" required></textarea>
        </div>

        <div class="mb-4">
            <label>Diagnosis</label>
            <textarea name="diagnosis" class="w-full border rounded p-2" required></textarea>
        </div>

        <div class="mb-4">
            <label>Tindakan</label>
            <textarea name="tindakan" class="w-full border rounded p-2" required></textarea>
        </div>

        <div class="mb-4">
            <label>Resep Obat</label>
            <textarea name="resep" class="w-full border rounded p-2"></textarea>
        </div>

        <button class="bg-emerald-500 text-white px-6 py-2 rounded">
            Simpan Pemeriksaan
        </button>
    </form>

</div>
@endsection

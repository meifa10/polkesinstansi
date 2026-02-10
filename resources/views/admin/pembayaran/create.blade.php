@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-3xl mx-auto space-y-6">

    {{-- ================= HEADER ================= --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            Buat Pembayaran Pasien
        </h1>
        <p class="text-gray-500">
            Input pembayaran berdasarkan hasil pemeriksaan dokter
        </p>
    </div>

    {{-- ================= ALERT ERROR ================= --}}
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded-lg text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ================= DATA PASIEN ================= --}}
    <div class="bg-white rounded-xl shadow p-6 text-sm space-y-1">
        <p><strong>Nama Pasien:</strong> {{ $pendaftaran->nama_pasien }}</p>
        <p><strong>No Identitas:</strong> {{ $pendaftaran->no_identitas }}</p>
        <p><strong>Jenis Pasien:</strong> {{ strtoupper($pendaftaran->jenis_pasien) }}</p>
        <p><strong>Poli:</strong> {{ $pendaftaran->poli }}</p>
        <p><strong>Tanggal Kunjungan:</strong>
            {{ \Carbon\Carbon::parse($pendaftaran->created_at)->format('d M Y') }}
        </p>
    </div>

    {{-- ================= RINGKASAN MEDIS ================= --}}
    <div class="bg-white rounded-xl shadow p-6 text-sm">
        <h3 class="font-semibold mb-3">Ringkasan Pemeriksaan</h3>

        @if($pendaftaran->rekamMedis)
            <p><strong>Keluhan:</strong> {{ $pendaftaran->rekamMedis->keluhan }}</p>
            <p><strong>Diagnosis:</strong> {{ $pendaftaran->rekamMedis->diagnosis }}</p>
            <p><strong>Tindakan:</strong> {{ $pendaftaran->rekamMedis->tindakan }}</p>
            <p><strong>Resep:</strong> {{ $pendaftaran->rekamMedis->resep }}</p>
        @else
            <p class="text-yellow-600">
                ⚠ Rekam medis belum tersedia.
            </p>
        @endif
    </div>

    {{-- ================= FORM PEMBAYARAN ================= --}}
    <form method="POST"
          action="{{ route('admin.pembayaran.store') }}"
          class="bg-white rounded-xl shadow p-6 space-y-4">
        @csrf

        <input type="hidden" name="pendaftaran_id" value="{{ $pendaftaran->id }}">

        {{-- METODE --}}
        <div>
            <label class="block text-sm font-semibold mb-1">
                Metode Pembayaran
            </label>
            <select id="metode"
                    name="metode"
                    class="w-full border rounded-lg p-2"
                    required>
                <option value="">-- Pilih Metode --</option>
                <option value="tunai">Tunai</option>
                <option value="bpjs"
                    {{ $pendaftaran->jenis_pasien === 'jkn' ? 'selected' : '' }}>
                    BPJS
                </option>
            </select>
        </div>

        {{-- TOTAL BIAYA --}}
        <div>
            <label class="block text-sm font-semibold mb-1">
                Total Biaya
            </label>

            {{-- INPUT TAMPILAN --}}
            <input type="text"
                   id="biaya_display"
                   placeholder="Rp 0"
                   class="w-full border rounded-lg p-2">

            {{-- INPUT ASLI KE DB --}}
            <input type="hidden"
                   name="total_biaya"
                   id="total_biaya">

            <p class="text-xs text-gray-500 mt-1">
                * BPJS otomatis Rp 0 dan tidak dapat diubah
            </p>
        </div>

        {{-- AKSI --}}
        <div class="flex justify-end gap-2 pt-4">
            <a href="{{ route('admin.data_pasien.detail', $pendaftaran->no_identitas) }}"
               class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-50">
                Batal
            </a>

            <button type="submit"
                    class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700
                           text-white rounded-lg font-semibold">
                Simpan Pembayaran
            </button>
        </div>
    </form>
</div>

{{-- ================= SCRIPT FORMAT RUPIAH + BPJS ================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const metode  = document.getElementById('metode');
    const display = document.getElementById('biaya_display');
    const hidden  = document.getElementById('total_biaya');

    function formatRupiah(value) {
        return 'Rp ' + Number(value).toLocaleString('id-ID');
    }

    display.addEventListener('input', function () {
        let raw = this.value.replace(/[^0-9]/g, '');
        hidden.value = raw;
        this.value = raw ? formatRupiah(raw) : '';
    });

    function toggleBPJS() {
        if (metode.value === 'bpjs') {
            hidden.value = 0;
            display.value = 'Rp 0';
            display.setAttribute('readonly', true);
            display.classList.add('bg-gray-100');
        } else {
            display.removeAttribute('readonly');
            display.classList.remove('bg-gray-100');
            display.value = '';
            hidden.value = '';
        }
    }

    metode.addEventListener('change', toggleBPJS);
    toggleBPJS(); // auto run saat load
});
</script>
@endsection

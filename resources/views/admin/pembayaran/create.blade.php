@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-3xl mx-auto space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="border-b pb-4">
        <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">
            Buat Pembayaran Pasien
        </h1>
        <p class="text-gray-500 mt-1">
            Input detail tagihan berdasarkan hasil pemeriksaan dokter secara akurat.
        </p>
    </div>

    {{-- ================= ALERT ERROR ================= --}}
    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg shadow-sm">
            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-bold">Terjadi Kesalahan!</span>
            </div>
            <ul class="list-disc pl-8 space-y-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- ================= DATA PASIEN ================= --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-600">Informasi Pasien</h3>
            <div class="text-sm space-y-2">
                <p class="flex justify-between"><span class="text-gray-500">Nama:</span> <span class="font-semibold text-gray-800">{{ $pendaftaran->nama_pasien }}</span></p>
                <p class="flex justify-between"><span class="text-gray-500">No Identitas:</span> <span class="font-mono">{{ $pendaftaran->no_identitas }}</span></p>
                <p class="flex justify-between"><span class="text-gray-500">Jenis:</span> <span class="px-2 py-0.5 bg-gray-100 rounded text-xs font-bold">{{ strtoupper($pendaftaran->jenis_pasien) }}</span></p>
                <p class="flex justify-between"><span class="text-gray-500">Poli:</span> <span class="font-semibold">{{ $pendaftaran->poli }}</span></p>
            </div>
        </div>

        {{-- ================= RINGKASAN MEDIS ================= --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-3">Ringkasan Medis</h3>
            @if($pendaftaran->rekamMedis)
                <div class="text-sm space-y-2">
                    <p class="line-clamp-1"><strong class="text-gray-700">Diagnosis:</strong> {{ $pendaftaran->rekamMedis->diagnosis }}</p>
                    <p class="line-clamp-2"><strong class="text-gray-700">Tindakan:</strong> {{ $pendaftaran->rekamMedis->tindakan }}</p>
                    <p class="line-clamp-1 text-emerald-700 italic"><strong class="text-gray-700 italic-none">Resep:</strong> {{ $pendaftaran->rekamMedis->resep }}</p>
                </div>
            @else
                <div class="flex flex-col items-center justify-center h-full py-4 text-center">
                    <span class="text-yellow-500 text-2xl">⚠️</span>
                    <p class="text-xs text-gray-400 mt-1 italic">Rekam medis belum diinput</p>
                </div>
            @endif
        </div>
    </div>

    {{-- ================= FORM PEMBAYARAN ================= --}}
    <form method="POST" action="{{ route('admin.pembayaran.store') }}" class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        @csrf
        <div class="p-8 space-y-6">
            <input type="hidden" name="pendaftaran_id" value="{{ $pendaftaran->id }}">

            {{-- METODE PEMBAYARAN --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Metode Pembayaran</label>
                <select id="metode" name="metode" 
                        class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none" 
                        required>
                    <option value="">-- Pilih Metode --</option>
                    <option value="tunai">Tunai</option>
                    <option value="transfer">Transfer</option>
                    <option value="bpjs" {{ $pendaftaran->jenis_pasien === 'jkn' ? 'selected' : '' }}>BPJS Kesehatan</option>
                </select>
            </div>

            {{-- TOTAL BIAYA --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Total Biaya (IDR)</label>
                <div class="relative">
                    <input type="text" id="biaya_display" placeholder="Rp 0"
                           class="w-full bg-gray-50 border-gray-200 rounded-xl p-4 text-lg font-bold text-gray-800 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none">
                    <input type="hidden" name="total_biaya" id="total_biaya">
                </div>
                <p id="helper-text" class="text-[11px] text-gray-400 mt-2 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Input nominal angka saja, sistem akan memformat otomatis.
                </p>
            </div>
        </div>

        {{-- AKSI (FOOTER FORM) --}}
        <div class="bg-gray-50 px-8 py-5 flex items-center justify-between border-t border-gray-100">
            {{-- TOMBOL BATAL AESTHETIC --}}
            <a href="{{ route('admin.data_pasien.detail', $pendaftaran->no_identitas ?? 'TEMP-'.$pendaftaran->id) }}" 
               class="group flex items-center text-sm font-medium text-gray-500 hover:text-red-600 transition-colors duration-200">
                <svg class="w-5 h-5 mr-1.5 transform group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali & Batal
            </a>

            <button type="submit" 
                    class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white rounded-xl font-bold shadow-lg shadow-emerald-200 transition-all duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
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
    const helper  = document.getElementById('helper-text');

    function formatRupiah(value) {
        if (!value) return '';
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
            display.classList.replace('bg-gray-50', 'bg-gray-200');
            display.classList.add('cursor-not-allowed', 'text-gray-500');
            helper.innerHTML = `<span class="text-emerald-600 font-medium font-semibold italic underline">✓ Mode BPJS: Biaya ditanggung sistem (Rp 0)</span>`;
        } else {
            display.removeAttribute('readonly');
            display.classList.replace('bg-gray-200', 'bg-gray-50');
            display.classList.remove('cursor-not-allowed', 'text-gray-500');
            helper.innerHTML = `<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Input nominal angka saja, sistem akan memformat otomatis.`;

            if (metode.value === '') {
                display.value = '';
                hidden.value = '';
            }
        }
    }

    metode.addEventListener('change', toggleBPJS);
    toggleBPJS(); 
});
</script>
@endsection
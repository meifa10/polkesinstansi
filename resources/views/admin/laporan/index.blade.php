@extends('layouts.admin')

@section('content')
<div class="p-6 space-y-8 bg-gray-50 min-h-screen">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        {{-- TITLE --}}
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">
                📊 Laporan Pasien
            </h1>
            <p class="text-gray-500 mt-1">
                Ringkasan data pelayanan dan pembayaran pasien
            </p>
        </div>

        {{-- FILTER + EXPORT --}}
        <div class="flex flex-wrap items-center gap-3 bg-white p-3 rounded-xl shadow">

            {{-- FILTER FORM --}}
            <form method="GET" class="flex items-center gap-3">
                <select name="bulan"
                        class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ sprintf('%02d',$i) }}"
                            {{ $bulan == sprintf('%02d',$i) ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>

                <input type="number"
                       name="tahun"
                       value="{{ $tahun }}"
                       class="border rounded-lg px-3 py-2 w-24 text-sm">

                <button type="submit"
                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700
                               text-white rounded-lg font-semibold text-sm">
                    Tampilkan
                </button>
            </form>

            {{-- EXPORT PDF --}}
            <a href="{{ route('admin.laporan.pdf', ['bulan'=>$bulan,'tahun'=>$tahun]) }}"
               class="px-4 py-2 bg-red-600 hover:bg-red-700
                      text-white rounded-lg font-semibold text-sm">
                Export PDF
            </a>

        </div>
    </div>

    {{-- ================= KPI CARDS ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- TOTAL KUNJUNGAN --}}
        <div class="rounded-2xl p-6 bg-gradient-to-br from-emerald-500 to-emerald-700 text-white shadow-lg">
            <p class="text-sm opacity-90">Total Kunjungan</p>
            <h2 class="text-4xl font-extrabold mt-2">
                {{ $totalKunjungan }}
            </h2>
            <div class="mt-4 text-sm">
                <p>BPJS: <strong>{{ $bpjs }}</strong></p>
                <p>Umum: <strong>{{ $umum }}</strong></p>
            </div>
        </div>

        {{-- TOTAL PEMASUKAN --}}
        <div class="rounded-2xl p-6 bg-gradient-to-br from-indigo-500 to-indigo-700 text-white shadow-lg">
            <p class="text-sm opacity-90">Total Pemasukan</p>
            <h2 class="text-3xl font-extrabold mt-2">
                Rp {{ number_format($totalPemasukan) }}
            </h2>
            <div class="mt-4 text-sm">
                <p>Lunas: <strong>{{ $lunas }}</strong></p>
                <p>Belum Lunas: <strong>{{ $belumLunas }}</strong></p>
            </div>
        </div>

        {{-- TOTAL PEMERIKSAAN --}}
        <div class="rounded-2xl p-6 bg-gradient-to-br from-amber-400 to-amber-600 text-white shadow-lg">
            <p class="text-sm opacity-90">Total Pemeriksaan</p>
            <h2 class="text-4xl font-extrabold mt-2">
                {{ $totalPemeriksaan }}
            </h2>
            <p class="mt-4 text-sm opacity-90">
                Pemeriksaan dokter tercatat
            </p>
        </div>

    </div>

    {{-- ================= DETAIL LAPORAN ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- KUNJUNGAN PER POLI --}}
        <div class="bg-white rounded-2xl shadow p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                🏥 Kunjungan per Poli
            </h3>

            <ul class="space-y-2 text-sm">
                @forelse($kunjunganPerPoli as $p)
                    <li class="flex justify-between border-b pb-2">
                        <span class="text-gray-600">{{ $p->poli }}</span>
                        <span class="font-semibold text-gray-800">
                            {{ $p->total }} pasien
                        </span>
                    </li>
                @empty
                    <li class="text-gray-400 italic">
                        Belum ada data kunjungan
                    </li>
                @endforelse
            </ul>
        </div>

        {{-- METODE PEMBAYARAN --}}
        <div class="bg-white rounded-2xl shadow p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                💳 Metode Pembayaran
            </h3>

            <ul class="space-y-2 text-sm">
                @forelse($metodePembayaran as $m)
                    <li class="flex justify-between border-b pb-2">
                        <span class="uppercase text-gray-600">
                            {{ $m->paid_by ?? '-' }}
                        </span>
                        <span class="font-semibold text-gray-800">
                            {{ $m->total }} transaksi
                        </span>
                    </li>
                @empty
                    <li class="text-gray-400 italic">
                        Belum ada data pembayaran
                    </li>
                @endforelse
            </ul>
        </div>

    </div>

</div>
@endsection

@extends('layouts.dokter')

@section('content')
<div class="p-6 lg:p-10 bg-slate-50 min-h-screen">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">

        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">
                Dashboard <span class="text-emerald-600">Dokter</span>
            </h1>

            <p class="text-slate-500 font-medium">
                Selamat datang kembali! Berikut ringkasan pasien hari ini.
            </p>
        </div>

        <div class="flex items-center gap-3 bg-white px-5 py-3 rounded-2xl shadow-sm border border-slate-100">

            <div class="p-2 bg-emerald-50 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 text-emerald-600"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />

                </svg>
            </div>

            <span class="text-sm font-bold text-slate-700">
                {{ now()->translatedFormat('l, d F Y') }}
            </span>

        </div>
    </div>

    {{-- STATISTIK --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">

        {{-- CHART --}}
        <div class="lg:col-span-2 bg-white rounded-[2.5rem] p-8 shadow-xl border border-slate-100 relative overflow-hidden">

            <h3 class="text-xl font-bold text-slate-800 mb-8 flex items-center gap-2">

                <span class="w-2 h-8 bg-emerald-500 rounded-full"></span>

                Statistik Pasien Hari Ini

            </h3>

            <div class="flex flex-col md:flex-row items-center justify-around gap-12">

                <div class="relative w-56 h-56">

                    <canvas id="donutChart"></canvas>

                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">

                        <span class="text-4xl font-black text-slate-800">
                            {{ $totalPasienHariIni }}
                        </span>

                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                            Total Antrean
                        </span>

                    </div>

                </div>

                {{-- KETERANGAN --}}
                <div class="grid grid-cols-1 gap-4 w-full md:w-auto">

                    <div class="bg-emerald-50 p-4 rounded-2xl border border-emerald-100 flex items-center justify-between min-w-[200px]">

                        <div class="flex items-center gap-3">

                            <div class="w-3 h-3 rounded-full bg-emerald-500"></div>

                            <span class="font-semibold text-slate-600">
                                Pasien Umum
                            </span>

                        </div>

                        <span class="text-lg font-bold text-emerald-700">
                            {{ $totalPasienUmum }}
                        </span>

                    </div>

                    <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100 flex items-center justify-between min-w-[200px]">

                        <div class="flex items-center gap-3">

                            <div class="w-3 h-3 rounded-full bg-blue-500"></div>

                            <span class="font-semibold text-slate-600">
                                Pasien JKN
                            </span>

                        </div>

                        <span class="text-lg font-bold text-blue-700">
                            {{ $totalPasienJKN }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

        {{-- CARD SAMPING --}}
        <div class="flex flex-col gap-6">

            <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-[2rem] p-6 text-white shadow-lg">

                <p class="text-emerald-100 font-medium">
                    Status Antrean
                </p>

                <h2 class="text-2xl font-bold mt-1">
                    Sistem Siaga
                </h2>

                <p class="text-xs text-emerald-200 mt-2 opacity-80">
                    Menampilkan pasien yang sudah diverifikasi admin.
                </p>

            </div>

            <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-xl flex items-center gap-5">

                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-7 w-7"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />

                    </svg>

                </div>

                <div>

                    <p class="text-slate-400 text-sm font-medium">
                        Total Rekam Medis
                    </p>

                    <h2 class="text-2xl font-bold text-slate-800">
                        {{ $totalRekamMedis }}
                    </h2>

                </div>

            </div>

        </div>

    </div>

    {{-- TABLE PASIEN --}}
    <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-50 overflow-hidden">

        <div class="p-8 border-b border-slate-50 flex justify-between items-center">

            <h3 class="text-lg font-bold text-slate-800">
                Pasien Siap Diperiksa
            </h3>

        </div>

        <div class="overflow-x-auto p-4">

            <table class="w-full text-left border-separate border-spacing-y-3">

                <thead>

                    <tr class="text-slate-400 text-sm uppercase tracking-wider">

                        <th class="px-6 py-4 font-semibold">
                            Nama Pasien
                        </th>

                        <th class="px-6 py-4 font-semibold">
                            Jenis
                        </th>

                        <th class="px-6 py-4 font-semibold">
                            Poli
                        </th>

                        <th class="px-6 py-4 font-semibold text-center">
                            Status
                        </th>

                        <th class="px-6 py-4 font-semibold text-right">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($pasien as $item)

                    <tr class="bg-white hover:bg-slate-50 transition-all">

                        {{-- NAMA --}}
                        <td class="px-6 py-4 rounded-l-2xl border border-slate-50">

                            <div class="flex items-center gap-3">

                                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center font-bold text-emerald-700">

                                    {{ substr($item->nama_pasien, 0, 1) }}

                                </div>

                                <span class="font-bold text-slate-800">
                                    {{ $item->nama_pasien }}
                                </span>

                            </div>

                        </td>

                        {{-- JENIS --}}
                        <td class="px-6 py-4 border-y border-slate-50">

                            <span class="px-3 py-1 rounded-lg text-xs font-bold
                            {{ strtolower($item->jenis_pasien) == 'jkn'
                                ? 'bg-blue-50 text-blue-600'
                                : 'bg-emerald-50 text-emerald-600' }}">

                                {{ strtoupper($item->jenis_pasien) }}

                            </span>

                        </td>

                        {{-- POLI --}}
                        <td class="px-6 py-4 border-y border-slate-50 font-bold text-slate-700">

                            {{ $item->poli }}

                        </td>

                        {{-- STATUS --}}
                        <td class="px-6 py-4 border-y border-slate-50 text-center">

                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-xs font-bold border border-emerald-100">

                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>

                                Siap Diperiksa

                            </span>

                        </td>

                        {{-- AKSI --}}
                        <td class="px-6 py-4 rounded-r-2xl border border-slate-50 text-right">

                            <a href="{{ route('dokter.pemeriksaan.show', $item->id) }}"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white rounded-xl text-sm font-bold hover:bg-emerald-700 transition-all">

                                Periksa

                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="5" class="py-20 text-center">

                            <p class="text-slate-400">
                                Belum ada pasien yang siap diperiksa.
                            </p>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

{{-- CHART --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const donutCtx = document.getElementById('donutChart').getContext('2d');

    const dataUmum = {{ $totalPasienUmum ?? 0 }};
    const dataJKN = {{ $totalPasienJKN ?? 0 }};

    new Chart(donutCtx, {

        type: 'doughnut',

        data: {

            labels: ['Umum', 'JKN'],

            datasets: [{

                data: [dataUmum, dataJKN],

                backgroundColor: ['#10b981', '#3b82f6'],

                borderWidth: 0

            }]
        },

        options: {

            cutout: '82%',

            responsive: true,

            plugins: {
                legend: {
                    display: false
                }
            }

        }

    });

});
</script>

@endsection
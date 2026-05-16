@extends('layouts.dokter')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    body { 
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
</style>

<div class="p-4 md:p-6 lg:p-8 bg-slate-50 min-h-screen font-sans">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold tracking-wide uppercase mb-2 border border-emerald-200 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Portal Medis
            </div>
            <h1 class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">
                Dashboard <span class="text-emerald-600">Dokter</span>
            </h1>
            <p class="text-slate-500 font-medium mt-1.5 text-sm md:text-base">
                Selamat datang kembali! Berikut adalah ringkasan antrean pasien Anda hari ini.
            </p>
        </div>

        <div class="flex items-center gap-3 bg-white px-5 py-3 rounded-xl shadow-sm border border-slate-200">
            <div class="p-2 bg-emerald-50 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <span class="text-sm font-bold text-slate-700">
                {{ now()->translatedFormat('l, d F Y') }}
            </span>
        </div>
    </div>

    {{-- ================= STATISTIK ================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        {{-- CHART SECTION --}}
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 lg:p-8 shadow-sm border border-slate-200 relative overflow-hidden flex flex-col">
            <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                <span class="w-2 h-6 bg-emerald-500 rounded-full"></span>
                Statistik Pasien Hari Ini
            </h3>

            <div class="flex flex-col md:flex-row items-center justify-center gap-8 lg:gap-16 flex-grow">
                {{-- Donut Chart --}}
                <div class="relative w-48 h-48 lg:w-56 lg:h-56 flex-shrink-0">
                    <canvas id="donutChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-3xl lg:text-4xl font-black text-slate-800 leading-none">
                            {{ $totalPasienHariIni }}
                        </span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                            Antrean
                        </span>
                    </div>
                </div>

                {{-- Legend & Details --}}
                <div class="flex flex-col gap-3 w-full md:w-auto">
                    {{-- Legend Umum --}}
                    <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-100 flex items-center justify-between gap-6 min-w-[200px]">
                        <div class="flex items-center gap-2.5">
                            <div class="w-3 h-3 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50"></div>
                            <span class="text-sm font-bold text-emerald-800">Pasien Umum</span>
                        </div>
                        <span class="text-xl font-black text-emerald-700">{{ $totalPasienUmum }}</span>
                    </div>

                    {{-- Legend JKN --}}
                    <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 flex items-center justify-between gap-6 min-w-[200px]">
                        <div class="flex items-center gap-2.5">
                            <div class="w-3 h-3 rounded-full bg-blue-500 shadow-sm shadow-blue-500/50"></div>
                            <span class="text-sm font-bold text-blue-800">Pasien JKN</span>
                        </div>
                        <span class="text-xl font-black text-blue-700">{{ $totalPasienJKN }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- SIDE CARDS --}}
        <div class="flex flex-col gap-6">
            {{-- Status Card --}}
            <div class="bg-gradient-to-br from-emerald-600 to-teal-800 rounded-2xl p-6 text-white shadow-md relative overflow-hidden flex-1 flex flex-col justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-white/10 absolute -right-4 -bottom-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="relative z-10">
                    <p class="text-emerald-100 text-xs font-bold uppercase tracking-wider mb-1">Status Antrean</p>
                    <h2 class="text-3xl font-black mb-2">Sistem Siaga</h2>
                    <p class="text-xs text-emerald-100 opacity-90 leading-relaxed">
                        Menampilkan daftar pasien terverifikasi admin dan siap untuk pemeriksaan medis.
                    </p>
                </div>
            </div>

            {{-- Total Record Card --}}
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center gap-4 group hover:border-emerald-300 transition-colors">
                <div class="w-14 h-14 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-slate-400 text-[11px] font-bold uppercase tracking-wider mb-0.5">Total Rekam Medis</p>
                    <h2 class="text-2xl font-black text-slate-800">{{ $totalRekamMedis }}</h2>
                </div>
            </div>
        </div>

    </div>

    {{-- ================= TABEL PASIEN ================= --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Pasien Siap Diperiksa
            </h3>
            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full border border-emerald-200">
                {{ $pasien->count() ?? 0 }} Pasien
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-white border-b border-slate-200 text-slate-400 text-[11px] uppercase tracking-widest font-bold">
                        <th class="px-6 py-4">Nama Pasien</th>
                        <th class="px-6 py-4">Jenis Layanan</th>
                        <th class="px-6 py-4">Poliklinik</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($pasien as $item)
                    <tr class="hover:bg-emerald-50/40 transition-colors group">
                        
                        {{-- NAMA --}}
                        <td class="px-6 py-4 align-middle">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-black text-lg border border-slate-200 group-hover:bg-emerald-100 group-hover:text-emerald-700 group-hover:border-emerald-200 transition-colors flex-shrink-0">
                                    {{ strtoupper(substr($item->nama_pasien, 0, 1)) }}
                                </div>
                                <span class="font-extrabold text-slate-800 uppercase tracking-tight">
                                    {{ $item->nama_pasien }}
                                </span>
                            </div>
                        </td>

                        {{-- JENIS --}}
                        <td class="px-6 py-4 align-middle">
                            <span class="inline-flex items-center px-2.5 py-1 rounded border text-[10px] font-black uppercase tracking-wider
                                {{ strtolower($item->jenis_pasien) == 'jkn' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200' }}">
                                {{ $item->jenis_pasien }}
                            </span>
                        </td>

                        {{-- POLI --}}
                        <td class="px-6 py-4 align-middle font-bold text-slate-600">
                            {{ $item->poli }}
                        </td>

                        {{-- STATUS --}}
                        <td class="px-6 py-4 align-middle text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-700 rounded-full text-[10px] font-black uppercase tracking-wider border border-amber-200">
                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                                Menunggu
                            </span>
                        </td>

                        {{-- AKSI --}}
                        <td class="px-6 py-4 align-middle text-center">
                            <a href="{{ route('dokter.pemeriksaan.show', $item->id) }}"
                                class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-emerald-600 text-white rounded-lg text-[11px] font-bold uppercase tracking-wider hover:bg-emerald-700 transition-all active:scale-95 shadow-sm border border-emerald-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Periksa
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="bg-slate-50 p-4 rounded-full mb-3 border border-slate-100 text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                </div>
                                <h4 class="text-sm font-bold text-slate-700 mb-0.5 uppercase tracking-wide">Antrean Kosong</h4>
                                <p class="text-xs text-slate-500">Belum ada pasien yang siap diperiksa saat ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ================= SCRIPT CHART ================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const donutCtx = document.getElementById('donutChart').getContext('2d');
    const dataUmum = {{ $totalPasienUmum ?? 0 }};
    const dataJKN = {{ $totalPasienJKN ?? 0 }};

    new Chart(donutCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pasien Umum', 'Pasien JKN'],
            datasets: [{
                data: [dataUmum, dataJKN],
                backgroundColor: ['#10b981', '#3b82f6'],
                hoverBackgroundColor: ['#059669', '#2563eb'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            cutout: '80%',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false // Legend dimatikan karena sudah dibuat custom di HTML
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { family: 'Plus Jakarta Sans', size: 13 },
                    bodyFont: { family: 'Plus Jakarta Sans', size: 12, weight: 'bold' },
                    displayColors: true,
                    cornerRadius: 8,
                }
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    });
});
</script>

@endsection
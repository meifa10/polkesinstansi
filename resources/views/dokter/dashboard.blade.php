@extends('layouts.dokter')

@section('content')
<div class="p-6 lg:p-10 bg-slate-50 min-h-screen">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">
                Dashboard <span class="text-emerald-600">Dokter</span>
            </h1>
            <p class="text-slate-500 font-medium">Selamat datang kembali! Berikut ringkasan pasien Anda hari ini.</p>
        </div>
        <div class="flex items-center gap-3 bg-white px-5 py-3 rounded-2xl shadow-sm border border-slate-100">
            <div class="p-2 bg-emerald-50 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <span class="text-sm font-bold text-slate-700">{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        
        <div class="lg:col-span-2 bg-white/80 backdrop-blur-md rounded-[2.5rem] p-8 shadow-xl shadow-slate-200/50 border border-white relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-xl font-bold text-slate-800 mb-8 flex items-center gap-2">
                    <span class="w-2 h-8 bg-emerald-500 rounded-full"></span>
                    Statistik Pasien Hari Ini
                </h3>
                
                <div class="flex flex-col md:flex-row items-center justify-around gap-12">
                    <div class="relative w-56 h-56 transition-transform hover:scale-105 duration-500">
                        <canvas id="donutChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span class="text-4xl font-black text-slate-800">{{ $totalPasienHariIni }}</span>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 w-full md:w-auto">
                        <div class="bg-emerald-50/50 p-4 rounded-2xl border border-emerald-100 flex items-center justify-between min-w-[200px]">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
                                <span class="font-semibold text-slate-600">Pasien Umum</span>
                            </div>
                            <span class="text-lg font-bold text-emerald-700">{{ $totalPasienUmum }}</span>
                        </div>
                        <div class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100 flex items-center justify-between min-w-[200px]">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.5)]"></div>
                                <span class="font-semibold text-slate-600">Pasien BPJS</span>
                            </div>
                            <span class="text-lg font-bold text-blue-700">{{ $totalPasienBPJS }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-6">
            <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-[2rem] p-6 text-white shadow-lg">
                <p class="text-emerald-100 font-medium">Rata-rata Menunggu</p>
                <h2 class="text-3xl font-bold mt-1">20 <span class="text-lg font-normal">Menit</span></h2>
            </div>

            <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-xl flex items-center gap-5">
                <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <div>
                    <p class="text-slate-400 text-sm font-medium">Pasien Baru</p>
                    <h2 class="text-2xl font-bold text-slate-800">{{ $totalPasienBaru }}</h2>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-xl flex items-center gap-5">
                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-slate-400 text-sm font-medium">Total Rekam Medis</p>
                    <h2 class="text-2xl font-bold text-slate-800">{{ $totalRekamMedis }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-50 overflow-hidden">
        <div class="p-8 border-b flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-800">Daftar Tunggu Pasien</h3>
        </div>
        <div class="overflow-x-auto p-4">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-slate-400 text-sm uppercase">
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">Jenis</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pasien as $item)
                    <tr class="border-b">
                        <td class="px-6 py-4 font-bold">{{ $item->nama_pasien }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-lg text-xs font-bold {{ strtolower($item->jenis_pasien) == 'bpjs' ? 'bg-blue-50 text-blue-600' : 'bg-emerald-50 text-emerald-600' }}">
                                {{ strtoupper($item->jenis_pasien) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-xs font-bold">Menunggu</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('dokter.pemeriksaan.show', $item->id) }}" class="bg-emerald-600 text-white px-4 py-2 rounded-xl text-sm font-bold">Periksa</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-10 text-center text-slate-400">Tidak ada pasien hari ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const donutCtx = document.getElementById('donutChart').getContext('2d');
    new Chart(donutCtx, {
        type: 'doughnut',
        data: {
            labels: ['Umum', 'BPJS'],
            datasets: [{
                data: [{{ $totalPasienUmum }}, {{ $totalPasienBPJS }}],
                backgroundColor: ['#10b981', '#3b82f6'],
                borderWidth: 0,
            }]
        },
        options: {
            cutout: '82%',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });
});
</script>
@endsection
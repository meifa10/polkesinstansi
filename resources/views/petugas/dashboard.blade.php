@extends('layouts.petugas')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
</style>

<div class="p-6 lg:p-8 bg-slate-50 min-h-screen">

    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
        <div class="relative pl-4">
            <div class="absolute left-0 top-0 w-1.5 h-full bg-emerald-600 rounded-full"></div>
            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">
                Polkes 05.09.15 <span class="text-emerald-600">Jombang</span>
            </h1>
            <p class="text-slate-600 font-medium mt-2 flex items-center gap-2 text-base">
                <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse"></span>
                Sistem Monitoring Aktivitas Pasien Real-time
            </p>
        </div>

        <div class="flex items-center gap-4 bg-white px-5 py-3 rounded-2xl shadow-sm border border-slate-200">
            <div class="bg-emerald-50 text-emerald-600 p-3 rounded-xl border border-emerald-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] uppercase font-black text-slate-400 tracking-wider">Hari Operasional</p>
                <p class="text-base font-extrabold text-slate-800 mt-0.5">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>
    </div>

    {{-- STATS GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @php
            $stats = [
                ['label' => 'Total Pasien', 'value' => $totalPasien, 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-100', 'icon' => 'M17 20h5V4H2v16h5m10 0v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6m10 0H7'],
                ['label' => 'Menunggu', 'value' => $menungguPetugas, 'bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'border' => 'border-amber-100', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Tervalidasi', 'value' => $sudahDiperiksa, 'bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-100', 'icon' => 'M9 12l2 2 4-4m5-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Poli Aktif', 'value' => '3', 'bg' => 'bg-violet-50', 'text' => 'text-violet-600', 'border' => 'border-violet-100', 'icon' => 'M19 11H5m14-4H5m14 8H5m14 4H5'],
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:translate-y-[-4px] transition-transform duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl {{ $stat['bg'] }} {{ $stat['text'] }} {{ $stat['border'] }} border flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}" />
                    </svg>
                </div>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-wider border border-slate-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>Live
                </span>
            </div>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">{{ $stat['label'] }}</p>
            <h3 class="text-3xl font-black text-slate-800 mt-1 leading-none">{{ $stat['value'] }}</h3>
        </div>
        @endforeach
    </div>

    {{-- TOP ROW: CHART & SUMMARY (2 COLUMNS) --}}
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start mb-8">
        
        {{-- BEBAN ANTREAN CHART --}}
        <div class="xl:col-span-8 bg-white border border-slate-200 rounded-2xl p-6 lg:p-8 shadow-sm">
            <div class="mb-6">
                <h3 class="text-xl font-bold text-slate-800">Beban Antrean Poli</h3>
                <p class="text-sm text-slate-500 font-medium mt-1">Visualisasi distribusi beban antrean pasien aktif per unit layanan</p>
            </div>
            <div class="h-[320px] relative flex justify-center">
                <canvas id="pasienChart"></canvas>
            </div>
        </div>

        {{-- LOKET & SUMMARY SIDEBAR --}}
        <div class="xl:col-span-4 space-y-8">
            {{-- SUMMARY CARD --}}
            <div class="bg-slate-900 rounded-2xl p-6 lg:p-8 text-white shadow-lg border border-slate-800 relative overflow-hidden">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-48 h-48 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <h3 class="text-lg font-bold mb-6 relative z-10 text-emerald-400 uppercase tracking-widest text-xs">Ringkasan Aktivitas</h3>
                
                <div class="space-y-6 relative z-10">
                    <div class="flex gap-4 items-center">
                        <div class="w-1.5 bg-emerald-500 rounded-full h-10"></div>
                        <div>
                            <p class="text-3xl font-black leading-none text-white">{{ $totalPasien }}</p>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Pasien Terdaftar</p>
                        </div>
                    </div>
                    <div class="flex gap-4 items-center">
                        <div class="w-1.5 bg-sky-400 rounded-full h-10"></div>
                        <div>
                            <p class="text-3xl font-black leading-none text-white">{{ $sudahDiperiksa }}</p>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Selesai Periksa</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 p-4 bg-slate-800/60 rounded-xl border border-slate-700/60">
                    <p class="text-xs font-medium text-slate-300 italic leading-relaxed">"Pastikan pemeriksaan tanda-tanda vital dilakukan dengan teliti sebelum dikirim ke meja dokter."</p>
                </div>
            </div>

            {{-- PETUGAS INFO CARD --}}
            <div class="bg-white rounded-2xl p-6 lg:p-8 shadow-sm border border-slate-200">
                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    Informasi Loket Petugas
                </h3>
                <div class="space-y-4">
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Shift Aktif</p>
                        <p class="text-sm font-extrabold text-slate-700">Senin - Jum'at (07:00 - 15:30)</p>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Lokasi Counter</p>
                        <p class="text-sm font-extrabold text-slate-700">Poli Terpadu - Meja 1</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- BOTTOM ROW: FULL WIDTH TABLE PASIEN ANTREAN (MENTOK KANAN-KIRI) --}}
    <div class="w-full bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 lg:p-8 border-b border-slate-200 flex justify-between items-center bg-slate-50/50">
            <h3 class="text-xl font-bold text-slate-800">Antrean Pasien <span class="text-emerald-600">Terbaru</span></h3>
            <a href="{{ route('petugas.pemeriksaan_awal.index') }}" class="inline-flex items-center text-sm font-bold text-emerald-600 hover:text-emerald-700 transition-colors">
                Lihat Semua Antrean
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-emerald-900 text-white text-xs uppercase tracking-widest font-bold">
                        <th class="py-4 px-6">Identitas Pasien</th>
                        <th class="py-4 px-6">Unit Layanan</th>
                        <th class="py-4 px-6 text-center">Status Alur</th>
                    </tr>
                </thead>
                <tbody class="text-base divide-y divide-slate-200">
                    @forelse($pasienTerbaru as $item)
                    <tr class="hover:bg-emerald-50/40 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-black text-lg border border-emerald-200 shadow-sm flex-shrink-0">
                                    {{ strtoupper(substr($item->nama_pasien,0,1)) }}
                                </div>
                                <div>
                                    <p class="font-extrabold text-slate-800 text-base uppercase tracking-tight">{{ $item->nama_pasien }}</p>
                                    <p class="text-xs text-slate-400 font-bold mt-0.5 tracking-wider">ID: #PX-{{ $item->id + 1000 }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 align-middle">
                            <div class="flex flex-col">
                                <span class="text-sm font-extrabold text-slate-700 uppercase">{{ $item->poli }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mt-0.5">{{ $item->jenis_pasien ?? 'Umum' }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 align-middle text-center">
                            @php
                                $statusStyles = [
                                    'menunggu_petugas' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'border' => 'border-amber-300', 'dot' => 'bg-amber-500', 'label' => 'Menunggu'],
                                    'menunggu_admin' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'border' => 'border-blue-300', 'dot' => 'bg-blue-500', 'label' => 'Verifikasi'],
                                    'diproses_dokter' => ['bg' => 'bg-violet-100', 'text' => 'text-violet-800', 'border' => 'border-violet-300', 'dot' => 'bg-violet-500', 'label' => 'Ke Dokter']
                                ];
                                $style = $statusStyles[$item->status] ?? ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'border' => 'border-emerald-300', 'dot' => 'bg-emerald-500', 'label' => 'Selesai'];
                            @endphp
                            <div class="inline-flex items-center justify-center gap-1.5 px-3 py-1 rounded-full {{ $style['bg'] }} {{ $style['border'] }} {{ $style['text'] }} text-xs font-black uppercase border shadow-sm">
                                <span class="w-1.5 h-1.5 rounded-full {{ $style['dot'] }}"></span>
                                {{ $style['label'] }}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-16 text-center text-slate-400 font-bold italic">Data antrean kosong hari ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- SCRIPT CHART.JS --}}
<script>
const ctx = document.getElementById('pasienChart');

new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($dataPoli) !!}, 
        datasets: [{
            data: {!! json_encode($jumlahPasienPoli) !!},
            backgroundColor: [
                '#10b981', // Emerald (Poli KIA & KB)
                '#8b5cf6', // Violet (Poli Umum)
                '#0ea5e9', // Sky Blue (Poli Gigi)
                '#f59e0b', // Amber
                '#f43f5e'  // Rose
            ],
            hoverOffset: 15,
            borderWidth: 4,
            borderColor: '#ffffff',
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '70%',
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    pointStyle: 'circle',
                    padding: 24,
                    font: {
                        family: "'Plus Jakarta Sans'",
                        size: 13,
                        weight: '700'
                    },
                    color: '#334155'
                }
            }
        },
        animation: {
            animateScale: true,
            animateRotate: true
        }
    }
});
</script>

@endsection
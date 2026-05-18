@extends('layouts.petugas')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    /* Custom styling untuk memastikan pagination bawaan laravel (tailwind) rapi */
    nav[role="navigation"] { margin-top: 1rem; }
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

    {{-- BOTTOM ROW: DETAILED TABLE PASIEN ANTREAN --}}
    <div class="w-full bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
        <div class="p-6 border-b border-slate-200 flex justify-between items-center bg-slate-50/50">
            <div>
                <h3 class="text-xl font-bold text-slate-800">Antrean Pasien <span class="text-emerald-600">Terbaru</span></h3>
                <p class="text-xs text-slate-500 mt-1 font-medium">Detail informasi klinis dan status alur pasien saat ini.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    {{-- DIUBAH: Background header menggunakan warna hijau Emerald (bg-emerald-600) --}}
                    <tr class="bg-emerald-600 text-white text-[11px] uppercase tracking-widest font-bold shadow-sm">
                        <th class="py-4 px-4 text-center">No</th>
                        <th class="py-4 px-4">Waktu Masuk</th>
                        <th class="py-4 px-4">Biodata / NIK</th>
                        <th class="py-4 px-4">Keluhan Utama</th>
                        <th class="py-4 px-4">Unit Layanan & Dokter</th>
                        <th class="py-4 px-4">Tanda-Tanda Vital</th>
                        <th class="py-4 px-4 text-center">Status Alur</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-200">
                    @forelse($pasienTerbaru as $index => $item)
                    {{-- DIUBAH: Hover row diganti menjadi tint emerald tipis agar lebih senada --}}
                    <tr class="hover:bg-emerald-50/40 transition-colors">
                        {{-- 1. No --}}
                        <td class="py-4 px-4 text-center font-bold text-slate-500">
                            {{ $pasienTerbaru->firstItem() + $index }}
                        </td>

                        {{-- 2. Waktu Masuk --}}
                        <td class="py-4 px-4">
                            <div class="font-extrabold text-slate-800">{{ $item->created_at->format('H:i') }} WIB</div>
                            <div class="text-[10px] text-slate-400 font-bold uppercase">{{ $item->created_at->format('d M Y') }}</div>
                        </td>

                        {{-- 3. Biodata Pasien / NIK --}}
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-black text-sm border border-emerald-200 flex-shrink-0">
                                    {{ strtoupper(substr($item->nama_pasien, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-extrabold text-slate-800 text-sm uppercase tracking-tight">{{ $item->nama_pasien }}</p>
                                    <p class="text-[10px] text-slate-500 font-bold mt-0.5 tracking-wider">NIK: {{ $item->nik ?? 'Tidak Tersedia' }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- 4. Keluhan Utama --}}
                        <td class="py-4 px-4">
                            <div class="max-w-[200px] whitespace-normal">
                                <p class="text-xs font-semibold text-slate-600 leading-tight">
                                    {{ $item->keluhan ?? 'Belum ada keluhan yang diinputkan' }}
                                </p>
                            </div>
                        </td>

                        {{-- 5. Unit Layanan & Dokter --}}
                        <td class="py-4 px-4">
                            <p class="text-sm font-extrabold text-slate-800 uppercase">{{ $item->poli }}</p>
                            <p class="text-[10px] font-bold text-emerald-600 uppercase mt-0.5">
                                {{-- PERBAIKAN: Jika nama dokter diinputkan sejak awal, ini akan langsung menampilkannya.
                                     Jika di database kolom Anda bernama lain, silakan ganti '$item->nama_dokter' dengan nama kolom yang sesuai.
                                     Contoh: $item->dokter (jika string langsung) atau $item->dokter->nama (jika relasi table) --}}
                                Dr. {{ $item->nama_dokter ?? $item->dokter ?? 'Belum Ditentukan' }}
                            </p>
                        </td>

                        {{-- 6. Tanda-Tanda Vital (Ultra-Jelas) --}}
                        <td class="py-4 px-4">
                            <div class="grid grid-cols-2 gap-1.5 min-w-[160px]">
                                <div class="bg-red-50/80 text-red-700 px-2 py-1 rounded border border-red-100 text-[10px] font-black flex justify-between">
                                    <span>TD</span> <span>{{ $item->td ?? '-' }} <span class="font-medium text-[9px]">mmHg</span></span>
                                </div>
                                <div class="bg-orange-50/80 text-orange-700 px-2 py-1 rounded border border-orange-100 text-[10px] font-black flex justify-between">
                                    <span>T</span> <span>{{ $item->suhu ?? '-' }} <span class="font-medium text-[9px]">°C</span></span>
                                </div>
                                <div class="bg-blue-50/80 text-blue-700 px-2 py-1 rounded border border-blue-100 text-[10px] font-black flex justify-between">
                                    <span>HR</span> <span>{{ $item->nadi ?? '-' }} <span class="font-medium text-[9px]">bpm</span></span>
                                </div>
                                <div class="bg-emerald-50/80 text-emerald-700 px-2 py-1 rounded border border-emerald-100 text-[10px] font-black flex justify-between">
                                    <span>BB</span> <span>{{ $item->bb ?? '-' }} <span class="font-medium text-[9px]">kg</span></span>
                                </div>
                            </div>
                        </td>

                        {{-- 7. Status Alur --}}
                        <td class="py-4 px-4 text-center">
                            @php
                                $statusStyles = [
                                    'menunggu_petugas' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'border' => 'border-amber-300', 'dot' => 'bg-amber-500', 'label' => 'Menunggu'],
                                    'menunggu_admin' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'border' => 'border-blue-300', 'dot' => 'bg-blue-500', 'label' => 'Verifikasi'],
                                    'diproses_dokter' => ['bg' => 'bg-violet-100', 'text' => 'text-violet-800', 'border' => 'border-violet-300', 'dot' => 'bg-violet-500', 'label' => 'Ke Dokter']
                                ];
                                $style = $statusStyles[$item->status] ?? ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'border' => 'border-emerald-300', 'dot' => 'bg-emerald-500', 'label' => 'Selesai'];
                            @endphp
                            <div class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-md {{ $style['bg'] }} {{ $style['border'] }} {{ $style['text'] }} text-[10px] font-black uppercase border shadow-sm w-full max-w-[110px]">
                                <span class="w-1.5 h-1.5 rounded-full {{ $style['dot'] }} animate-pulse"></span>
                                {{ $style['label'] }}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-16 text-center text-slate-400 font-bold italic">
                            Belum ada data antrean terdaftar hari ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- PAGINATION LINKS --}}
        @if($pasienTerbaru->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 bg-white">
            {{ $pasienTerbaru->links() }}
        </div>
        @endif
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
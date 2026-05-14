@extends('layouts.petugas')

@section('content')

<!-- Google Fonts: Inter & Plus Jakarta Sans -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    .glass-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .glass-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.05), 0 8px 10px -6px rgb(0 0 0 / 0.05);
    }
    .medical-gradient {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    }
    .status-badge {
        font-family: 'Inter', sans-serif;
        letter-spacing: 0.025em;
    }
    /* Custom Scrollbar */
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: #f1f5f9; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>

<div class="min-h-screen bg-[#f8fafc] p-4 lg:p-8">

    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
        <div class="relative">
            <div class="absolute -left-4 top-0 w-1 h-12 bg-emerald-500 rounded-full"></div>
            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">
                Polkes <span class="text-emerald-600">Jombang</span>
            </h1>
            <p class="text-slate-500 font-medium mt-1 flex items-center gap-2">
                <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                Sistem Monitoring Aktivitas Pasien Real-time
            </p>
        </div>

        <div class="flex items-center gap-4 bg-white p-2 rounded-2xl shadow-sm border border-slate-100">
            <div class="bg-emerald-50 text-emerald-600 p-3 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="pr-4">
                <p class="text-[10px] uppercase font-bold text-slate-400 leading-none">Hari Operasional</p>
                <p class="text-sm font-extrabold text-slate-800">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>
    </div>

    {{-- STATS GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        @php
            $stats = [
                ['label' => 'Total Pasien', 'value' => $totalPasien, 'color' => 'emerald', 'icon' => 'M17 20h5V4H2v16h5m10 0v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6m10 0H7'],
                ['label' => 'Menunggu', 'value' => $menungguPetugas, 'color' => 'amber', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Tervalidasi', 'value' => $sudahDiperiksa, 'color' => 'blue', 'icon' => 'M9 12l2 2 4-4m5-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Poli Aktif', 'value' => '3', 'color' => 'indigo', 'icon' => 'M19 11H5m14-4H5m14 8H5m14 4H5'],
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="glass-card rounded-[2rem] p-6 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-{{ $stat['color'] }}-100 text-{{ $stat['color'] }}-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-slate-300 uppercase tracking-tighter">Live</span>
            </div>
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">{{ $stat['label'] }}</p>
            <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $stat['value'] }}</h3>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        {{-- LEFT COLUMN: CHART --}}
        <div class="xl:col-span-2 space-y-8">
            <div class="glass-card rounded-[2.5rem] p-8 shadow-sm">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-900">Beban Antrean Poli</h3>
                        <p class="text-sm text-slate-500 font-medium">Visualisasi distribusi pasien per unit layanan</p>
                    </div>
                    {{-- Legend Dinamis (Opsional jika ingin manual, tapi Chart.js bisa handle) --}}
                    <div id="chart-legend" class="flex flex-wrap gap-3">
                        <!-- Legend will be injected or you can keep it simple -->
                    </div>
                </div>
                <div class="h-[350px] relative">
                    <canvas id="pasienChart"></canvas>
                </div>
            </div>

            {{-- TABLE PASIEN --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                    <h3 class="text-xl font-extrabold text-slate-900">Antrean Pasien <span class="text-emerald-500">Terbaru</span></h3>
                    <button class="text-xs font-bold text-emerald-600 hover:text-emerald-700 transition">Lihat Semua →</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Identitas Pasien</th>
                                <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Unit Layanan</th>
                                <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">Status Alur</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($pasienTerbaru as $item)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex items-center justify-center font-bold shadow-lg shadow-emerald-200">
                                            {{ strtoupper(substr($item->nama_pasien,0,1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800 leading-tight">{{ $item->nama_pasien }}</p>
                                            <p class="text-xs text-slate-400 font-medium mt-1">{{ $item->no_identitas }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-700">{{ $item->poli }}</span>
                                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-tighter">{{ $item->jenis_pasien }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex justify-center">
                                        @if($item->status == 'menunggu_petugas')
                                            <span class="status-badge px-4 py-1.5 rounded-xl bg-amber-50 text-amber-600 text-[10px] font-black uppercase border border-amber-100">Menunggu</span>
                                        @elseif($item->status == 'menunggu_admin')
                                            <span class="status-badge px-4 py-1.5 rounded-xl bg-blue-50 text-blue-600 text-[10px] font-black uppercase border border-blue-100">Verifikasi</span>
                                        @else
                                            <span class="status-badge px-4 py-1.5 rounded-xl bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase border border-emerald-100">Ke Dokter</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-20 text-center text-slate-400 font-medium">Data antrean kosong hari ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: ACTIVITY & INFO --}}
        <div class="space-y-8">
            <div class="medical-gradient rounded-[2.5rem] p-8 text-white shadow-xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-all"></div>
                
                <h3 class="text-xl font-bold mb-6 relative z-10">Ringkasan Aktivitas</h3>
                
                <div class="space-y-6 relative z-10">
                    <div class="flex gap-4">
                        <div class="w-1 bg-emerald-400 rounded-full h-12"></div>
                        <div>
                            <p class="text-2xl font-black">{{ $totalPasien }}</p>
                            <p class="text-xs text-slate-300 font-medium uppercase tracking-widest">Pasien Terdaftar</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-1 bg-blue-400 rounded-full h-12"></div>
                        <div>
                            <p class="text-2xl font-black">{{ $sudahDiperiksa }}</p>
                            <p class="text-xs text-slate-300 font-medium uppercase tracking-widest">Selesai Periksa</p>
                        </div>
                    </div>
                </div>

                <div class="mt-10 p-4 bg-white/10 rounded-2xl border border-white/10">
                    <p class="text-xs font-medium text-slate-200 italic">"Pastikan pemeriksaan tanda-tanda vital dilakukan dengan teliti."</p>
                </div>
            </div>

            <div class="glass-card rounded-[2.5rem] p-8">
                <h3 class="text-lg font-extrabold text-slate-900 mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    Informasi Petugas
                </h3>
                <div class="space-y-4">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Shift Sekarang</p>
                        <p class="text-sm font-bold text-slate-700">Pagi (08:00 - 14:00)</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Lokasi Counter</p>
                        <p class="text-sm font-bold text-slate-700">Poli Terpadu - Meja 1</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const ctx = document.getElementById('pasienChart');

/**
 * Pastikan variabel di bawah ini dikirim dari Controller:
 * $dataPoli = ['Poli Umum', 'Poli Gigi', 'Poli Anak']
 * $jumlahPasienPoli = [10, 5, 8]
 */
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($dataPoli) !!}, 
        datasets: [{
            data: {!! json_encode($jumlahPasienPoli) !!},
            backgroundColor: [
                '#10b981', // Emerald
                '#3b82f6', // Blue
                '#6366f1', // Indigo
                '#f59e0b', // Amber
                '#ec4899'  // Pink
            ],
            hoverOffset: 25,
            borderWidth: 0,
            borderRadius: 12
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '75%',
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    padding: 20,
                    font: {
                        family: "'Plus Jakarta Sans'",
                        size: 12,
                        weight: '600'
                    }
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
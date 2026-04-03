@extends('layouts.admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<div class="dashboard-wrapper font-['Plus_Jakarta_Sans'] space-y-8 p-6 md:p-10 bg-[#f8fafc]">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">
                Dashboard <span class="text-emerald-600">Polkes</span>
            </h1>
            <p class="text-slate-500 font-medium flex items-center gap-2 mt-1">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Monitoring Real-time Layanan Kesehatan
            </p>
        </div>

        <div class="flex items-center gap-4">
            <div class="bg-white px-4 py-2 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Status Server</span>
                <span class="px-2 py-1 rounded-lg bg-emerald-100 text-emerald-600 text-[10px] font-black">ONLINE</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="modern-card group">
            <div class="icon bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div>
                <p class="text-slate-400 font-bold text-[10px] uppercase tracking-widest">Antrian Hari Ini</p>
                <h2 class="text-3xl font-black text-slate-800 tracking-tight">{{ $pendaftaranHariIni }}</h2>
            </div>
        </div>

        <div class="modern-card group">
            <div class="icon bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
            <div>
                <p class="text-slate-400 font-bold text-[10px] uppercase tracking-widest">Total Pasien</p>
                <h2 class="text-3xl font-black text-slate-800 tracking-tight">{{ $totalPasien }}</h2>
            </div>
        </div>

        <div class="modern-card group">
            <div class="icon bg-purple-50 text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <p class="text-slate-400 font-bold text-[10px] uppercase tracking-widest">Dokter Aktif</p>
                <h2 class="text-3xl font-black text-slate-800 tracking-tight">{{ $dokterAktif }}</h2>
            </div>
        </div>

        <div class="modern-card group">
            <div class="icon bg-orange-50 text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-slate-400 font-bold text-[10px] uppercase tracking-widest">Pemeriksaan</p>
                <h2 class="text-3xl font-black text-slate-800 tracking-tight">{{ $totalPemeriksaan }}</h2>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 bg-white p-8 rounded-[35px] border border-slate-100 shadow-sm">
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h3 class="text-xl font-black text-slate-800 tracking-tight">Statistik Layanan</h3>
                    <p class="text-sm text-slate-400 font-medium">Perbandingan Kunjungan & Pemeriksaan</p>
                </div>
                
                <select id="filterPeriode" onchange="updateChart(this.value)" 
                        class="bg-slate-50 border-none rounded-2xl px-5 py-3 text-sm font-bold text-slate-600 outline-none focus:ring-2 focus:ring-emerald-500/20 cursor-pointer transition-all">
                    <option value="6">6 Bulan Terakhir</option>
                    <option value="12">1 Tahun Terakhir</option>
                </select>
            </div>

            <div class="h-[380px] relative">
                <canvas id="dashboardChart"></canvas>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-slate-900 p-8 rounded-[35px] text-white shadow-2xl relative overflow-hidden group">
                <div class="absolute -top-20 -right-20 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/20 transition-all duration-700"></div>
                
                <h4 class="text-emerald-400 font-black uppercase tracking-[0.2em] text-[10px] mb-6">Visi Instansi</h4>
                <p class="text-xl font-bold leading-relaxed italic relative z-10">
                    "Menuju Masyarakat Jombang Sehat, Mandiri, dan Berkualitas."
                </p>
                <div class="mt-8 pt-8 border-t border-white/10 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 flex items-center justify-center text-emerald-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-slate-400">Terakreditasi Paripurna Kemenkes RI</span>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[35px] border border-slate-100 shadow-sm">
                <h4 class="font-black text-slate-800 mb-6 text-sm uppercase tracking-widest flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                    Misi Utama
                </h4>
                <div class="space-y-5">
                    <div class="flex gap-4">
                        <div class="text-emerald-500 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <p class="text-sm text-slate-500 font-medium leading-relaxed">Pelayanan kesehatan cepat, tepat, dan terjangkau.</p>
                    </div>
                    <div class="flex gap-4">
                        <div class="text-emerald-500 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <p class="text-sm text-slate-500 font-medium leading-relaxed">Peningkatan keterampilan tenaga medis secara berkala.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
let myChart; // Variabel global untuk menyimpan instance chart

function initChart(labels, kunjungan, pemeriksaan) {
    const ctx = document.getElementById('dashboardChart').getContext('2d');
    
    // Gradient Warna Mewah
    const gradEmerald = ctx.createLinearGradient(0, 0, 0, 400);
    gradEmerald.addColorStop(0, '#10b981');
    gradEmerald.addColorStop(1, 'rgba(16, 185, 129, 0.1)');

    const gradOrange = ctx.createLinearGradient(0, 0, 0, 400);
    gradOrange.addColorStop(0, '#f97316');
    gradOrange.addColorStop(1, 'rgba(249, 115, 22, 0.1)');

    // Hancurkan chart lama jika ada sebelum buat baru
    if (myChart) {
        myChart.destroy();
    }

    myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Kunjungan',
                    data: kunjungan,
                    backgroundColor: gradEmerald,
                    borderRadius: 10,
                    hoverBackgroundColor: '#059669'
                },
                {
                    label: 'Pemeriksaan',
                    data: pemeriksaan,
                    backgroundColor: gradOrange,
                    borderRadius: 10,
                    hoverBackgroundColor: '#ea580c'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', labels: { font: { weight: 'bold' }, usePointStyle: true } }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                x: { grid: { display: false } }
            }
        }
    });
}

// FUNGSI UPDATE DATA (BERFUNGSI!)
function updateChart(bulan) {
    console.log("Mengambil data untuk " + bulan + " bulan terakhir...");
    
    // Tampilkan Loading (opsional)
    // Di sini kamu biasanya memanggil API Laravel, contoh:
    // fetch(`/admin/api/statistik?limit=${bulan}`)
    //    .then(res => res.json())
    //    .then(data => initChart(data.labels, data.kunjungan, data.pemeriksaan));

    // Demo Simulasi Perubahan Data
    if(bulan == '12') {
        initChart(['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'], 
                  [50, 60, 70, 80, 90, 100, 110, 120, 130, 140, 150, 160], 
                  [30, 40, 50, 60, 70, 80, 90, 100, 110, 120, 130, 140]);
    } else {
        initChart(@json($bulan), @json($dataKunjungan), @json($dataPemeriksaan));
    }
}

// Jalankan chart pertama kali saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
    initChart(@json($bulan), @json($dataKunjungan), @json($dataPemeriksaan));
});
</script>

<style>
.modern-card {
    background: white;
    padding: 30px;
    border-radius: 35px;
    border: 1px solid #f1f5f9;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.modern-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.05);
    border-color: #e2e8f0;
}

.icon {
    width: 65px;
    height: 65px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 22px;
}
</style>
@endsection
@extends('layouts.admin')

@section('content')
<div class="space-y-12">

    <!-- HEADER -->
    <div>
        <h1 class="text-4xl font-extrabold text-gray-800">
            Dashboard Polkes
        </h1>
        <p class="text-gray-500 mt-1">
            Monitoring layanan kesehatan {{ date('Y') }}
        </p>
    </div>

    <!-- STAT CARD -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

        <div class="stat-card from-emerald-500 to-emerald-700">
            <span>👥</span>
            <p>Pendaftaran Hari Ini</p>
            <h2>{{ $pendaftaranHariIni }}</h2>
        </div>

        <div class="stat-card from-blue-500 to-blue-700">
            <span>🧑‍⚕️</span>
            <p>Total Pasien</p>
            <h2>{{ $totalPasien }}</h2>
        </div>

        <div class="stat-card from-purple-500 to-purple-700">
            <span>🩺</span>
            <p>Dokter Aktif</p>
            <h2>{{ $dokterAktif }}</h2>
        </div>

        <div class="stat-card from-orange-500 to-orange-700">
            <span>📋</span>
            <p>Total Pemeriksaan</p>
            <h2>{{ $totalPemeriksaan }}</h2>
        </div>

    </div>

    <!-- GRAFIK -->
    <div class="bg-white rounded-2xl shadow-xl p-8">
        <h3 class="text-xl font-bold text-gray-800 mb-1">
            Statistik Layanan Polkes
        </h3>
        <p class="text-sm text-gray-500 mb-6">
            Kunjungan, Pemeriksaan & Dokter Aktif
        </p>

        <div class="h-[360px]">
            <canvas id="dashboardChart"></canvas>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('dashboardChart');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($bulan),
        datasets: [
            {
                label: 'Kunjungan Pasien',
                data: @json($dataKunjungan),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16,185,129,0.25)',
                tension: 0.45,
                fill: true
            },
            {
                label: 'Pemeriksaan',
                data: @json($dataPemeriksaan),
                borderColor: '#f97316',
                backgroundColor: 'rgba(249,115,22,0.25)',
                tension: 0.45,
                fill: true
            },
            {
                label: 'Dokter Aktif',
                data: @json($dataDokter),
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99,102,241,0.25)',
                tension: 0.45,
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            mode: 'index',
            intersect: false
        },
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    usePointStyle: true,
                    boxWidth: 10
                }
            },
            tooltip: {
                backgroundColor: '#111827',
                padding: 12,
                cornerRadius: 10
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#e5e7eb' }
            },
            x: {
                grid: { display: false }
            }
        }
    }
});
</script>

<style>
.stat-card {
    background-image: linear-gradient(135deg, var(--tw-gradient-from), var(--tw-gradient-to));
    padding: 26px;
    border-radius: 22px;
    color: white;
    position: relative;
    overflow: hidden;
    transition: 0.35s ease;
    cursor: pointer;
    box-shadow: 0 12px 28px rgba(0,0,0,0.2);
}

.stat-card:hover {
    transform: translateY(-10px) scale(1.03);
    box-shadow: 0 30px 60px rgba(0,0,0,0.35);
}

.stat-card span {
    position: absolute;
    right: 20px;
    top: 10px;
    font-size: 64px;
    opacity: 0.25;
}

.stat-card p {
    font-size: 14px;
    opacity: 0.9;
}

.stat-card h2 {
    font-size: 38px;
    font-weight: 800;
    margin-top: 6px;
}
</style>
@endsection

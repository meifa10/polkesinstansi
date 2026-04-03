@extends('layouts.dokter')

@section('content')

<div class="dokter-wrapper space-y-10">

    <!-- HEADER -->
    <div>
        <h1 class="text-3xl font-bold text-emerald-900">
            Dashboard Dokter
        </h1>
        <p class="text-emerald-700 text-sm">
            Monitoring Pasien & Layanan Hari Ini
        </p>
    </div>

    <!-- TOP GRID -->
    <div class="grid xl:grid-cols-3 gap-8">

        <!-- DONUT + TOTAL -->
        <div class="glass-card p-8 xl:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-semibold text-lg text-emerald-900">
                    Total Pasien Hari Ini
                </h3>
                <span class="text-sm text-emerald-600">
                    {{ now()->translatedFormat('d F Y') }}
                </span>
            </div>

            <div class="flex flex-col md:flex-row items-center gap-10">

                <div class="w-60 h-60">
                    <canvas id="donutChart"></canvas>
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Total Pasien</p>
                        <h2 class="text-4xl font-bold text-emerald-800">
                            {{ $pasien->count() }}
                        </h2>
                    </div>

                    <div class="space-y-2 text-sm">
                        <p>
                            <span class="badge-green"></span>
                            Umum :
                            <strong>{{ $pasien->where('jenis_pasien','umum')->count() }}</strong>
                        </p>
                        <p>
                            <span class="badge-blue"></span>
                            BPJS :
                            <strong>{{ $pasien->where('jenis_pasien','bpjs')->count() }}</strong>
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <!-- MINI INFO -->
        <div class="space-y-6">

            <div class="mini-card">
                <p>Rata Waktu Tunggu</p>
                <h2>20 m</h2>
            </div>

            <div class="mini-card">
                <p>Pasien Baru</p>
                <h2>{{ $pasien->where('jenis_pasien','baru')->count() ?? 0 }}</h2>
            </div>

        <div class="mini-card">
            <p>Total Rekam Medis</p>
            <h2>{{ $totalRekamMedis }}</h2>
        </div>

        </div>

    </div>


    <!-- TABLE BOOKING -->
    <div class="glass-card p-8">

        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-emerald-900">
                Pasien Booking Online
            </h3>
            <a href="#" class="text-emerald-600 text-sm hover:underline">
                Lihat Semua
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">

                <thead>
                    <tr class="border-b text-left text-emerald-800">
                        <th class="pb-3">Nama</th>
                        <th>Jenis</th>
                        <th>Poli</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($pasien as $item)
                    <tr class="border-b hover:bg-emerald-50 transition">

                        <td class="py-3 font-medium">
                            {{ $item->nama_pasien }}
                        </td>

                        <td>
                            {{ strtoupper($item->jenis_pasien) }}
                        </td>

                        <td>{{ $item->poli }}</td>

                        <td>
                            <span class="status-badge">
                                Siap Diperiksa
                            </span>
                        </td>

                        <td>
                            <a href="{{ route('dokter.pemeriksaan.show', $item->id) }}"
                               class="btn-periksa">
                                Periksa
                            </a>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-10 text-center text-gray-500">
                            Tidak ada pasien hari ini
                        </td>
                    </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const donutCtx = document.getElementById('donutChart');

new Chart(donutCtx, {
    type: 'doughnut',
    data: {
        labels: ['Umum', 'BPJS'],
        datasets: [{
            data: [
                {{ $pasien->where('jenis_pasien','umum')->count() }},
                {{ $pasien->where('jenis_pasien','bpjs')->count() }}
            ],
            backgroundColor: ['#10b981', '#3b82f6']
        }]
    },
    options: {
        cutout: '70%',
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});
</script>


<style>

/* Wrapper */
.dokter-wrapper {
    padding: 20px;
}

/* Glass Card */
.glass-card {
    background: rgba(255,255,255,0.9);
    border-radius: 28px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.06);
}

/* Mini Card */
.mini-card {
    background: rgba(255,255,255,0.85);
    padding: 24px;
    border-radius: 22px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.06);
    text-align: center;
}

.mini-card p {
    font-size: 14px;
    color: #6b7280;
}

.mini-card h2 {
    font-size: 26px;
    font-weight: bold;
    color: #065f46;
}

/* Badge */
.badge-green {
    display:inline-block;
    width:10px;
    height:10px;
    background:#10b981;
    border-radius:50%;
    margin-right:6px;
}

.badge-blue {
    display:inline-block;
    width:10px;
    height:10px;
    background:#3b82f6;
    border-radius:50%;
    margin-right:6px;
}

/* Status */
.status-badge {
    background:#dcfce7;
    color:#15803d;
    padding:4px 10px;
    border-radius:999px;
    font-size:11px;
    font-weight:600;
}

/* Button */
.btn-periksa {
    background: linear-gradient(135deg, #059669, #047857);
    color:white;
    padding:6px 14px;
    border-radius:999px;
    font-size:13px;
    font-weight:600;
    transition:0.3s ease;
}

.btn-periksa:hover {
    transform: scale(1.05);
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
}

</style>

@endsection
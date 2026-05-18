@extends('layouts.dokter')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/xlsx-js-style@1.2.0/dist/xlsx.bundle.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
</style>

<div class="p-4 md:p-6 lg:p-8 bg-slate-50 min-h-screen text-slate-900">
    <div class="max-w-7xl mx-auto">

        {{-- ================= HEADER SECTION ================= --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wide mb-2.5 border border-emerald-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Sistem Rekam Medis
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                    Data <span class="text-emerald-600">Rekam Medis</span>
                </h1>
                <p class="text-slate-500 font-medium text-xs md:text-sm mt-1">
                    Arsip lengkap riwayat pemeriksaan pasien Polkes Jombang.
                </p>
            </div>

            <button id="exportExcelBtn" class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-all active:scale-95 shadow-md shadow-emerald-600/10 self-start sm:self-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Excel
            </button>
        </div>

        {{-- ================= SEARCH & FILTER ================= --}}
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm mb-6 flex flex-col md:flex-row gap-3">
            {{-- Search Input --}}
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" id="searchInput" placeholder="Cari nama pasien atau diagnosis..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-lg bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-emerald-500/10 focus:border-emerald-500 focus:bg-white outline-none font-medium text-sm text-slate-900 transition-all placeholder:text-slate-400">
            </div>

            {{-- Select Filter Poli --}}
            <div class="relative md:w-60 flex-shrink-0">
                <select id="poliFilter" class="w-full pl-4 pr-10 py-2.5 rounded-lg bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-emerald-500/10 focus:border-emerald-500 focus:bg-white outline-none font-bold text-xs uppercase tracking-wider appearance-none cursor-pointer text-slate-700 transition-all">
                    <option value="ALL">Semua Poliklinik</option>
                    <option value="Poli Umum">Poli Umum</option>
                    <option value="Poli Gigi">Poli Gigi</option>
                    <option value="Poli KIA & KB">Poli KIA & KB</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- ================= CARDS CONTAINER ================= --}}
        <div id="cardsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($data as $index => $item)
            <div class="medis-card bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all flex flex-col overflow-hidden group" 
                 data-poli="{{ $item->pendaftaran->poli ?? '-' }}"
                 data-search-content="{{ strtolower(($item->pendaftaran->nama_pasien ?? '').' '.($item->diagnosis ?? '')) }}">
                
                {{-- Card Header --}}
                <div class="p-4 border-b border-slate-100 bg-slate-50/70 flex items-start justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="card-number inline-flex items-center justify-center px-2 py-0.5 rounded text-[11px] font-extrabold bg-slate-200 text-slate-700 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                {{ $index + 1 }}
                            </span>
                            <span class="px-2 py-0.5 rounded bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-bold uppercase tracking-wider">
                                {{ $item->pendaftaran->poli ?? '-' }}
                            </span>
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-base uppercase tracking-wide">
                            {{ $item->pendaftaran->nama_pasien ?? '-' }}
                        </h3>
                        <p class="text-[11px] text-slate-500 font-semibold mt-0.5">
                            No Antrean: <span class="text-emerald-600 font-bold">{{ $item->pendaftaran->nomor_antrian ?? '-' }}</span>
                        </p>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold uppercase">
                        Selesai
                    </span>
                </div>

                {{-- Card Body --}}
                <div class="p-4 flex-grow flex flex-col gap-3.5">
                    {{-- Waktu Registrasi & Periksa --}}
                    <div class="grid grid-cols-2 gap-2 bg-slate-50 p-2.5 rounded-lg border border-slate-100 text-[11px]">
                        <div>
                            <span class="block text-slate-400 font-medium uppercase tracking-wide text-[9px]">Waktu Daftar</span>
                            <span class="font-bold text-slate-700 daftar-time-val">
                                {{ $item->pendaftaran->created_at ? $item->pendaftaran->created_at->format('d/m/Y H:i') : '-' }} WIB
                            </span>
                        </div>
                        <div class="border-l border-slate-200 pl-2.5">
                            <span class="block text-slate-400 font-medium uppercase tracking-wide text-[9px]">Waktu Periksa</span>
                            <span class="font-bold text-slate-700 periksa-time-val">
                                {{ $item->created_at->format('d/m/Y H:i') }} WIB
                            </span>
                        </div>
                    </div>

                    {{-- Vitals --}}
                    <div class="flex flex-wrap gap-x-4 gap-y-1.5 text-xs font-bold text-slate-700 bg-slate-50/50 p-2 rounded-lg border border-dashed border-slate-200">
                        <span class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>BB: <span class="text-slate-900 bb-val">{{ $item->pendaftaran->berat_badan ?? '-' }}</span> KG</span>
                        <span class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>TB: <span class="text-slate-900 tb-val">{{ $item->pendaftaran->tinggi_badan ?? '-' }}</span> CM</span>
                        <span class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>Tensi: <span class="text-slate-900 tensi-val">{{ $item->pendaftaran->tensi ?? '-' }}</span> mmHg</span>
                    </div>

                    {{-- Keluhan --}}
                    <div>
                        <span class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-0.5">Keluhan Awal</span>
                        <p class="text-xs font-semibold text-slate-700 bg-slate-50 px-2.5 py-2 rounded-md line-clamp-2 keluhan-val">{{ $item->pendaftaran->keluhan ?? '-' }}</p>
                    </div>

                    {{-- Diagnosis & Tindakan --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <span class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-0.5">Diagnosis</span>
                            <p class="text-xs font-bold text-slate-900 line-clamp-2 diagnosis-val">{{ $item->diagnosis }}</p>
                        </div>
                        <div>
                            <span class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-0.5">Tindakan</span>
                            <p class="text-xs font-bold text-slate-800 uppercase line-clamp-2 tindakan-val">{{ $item->tindakan }}</p>
                        </div>
                    </div>

                    {{-- Resep Obat --}}
                    <div class="mt-auto pt-2 border-t border-slate-100">
                        <span class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-0.5">Resep Obat</span>
                        <p class="text-xs font-medium text-slate-600 italic line-clamp-2 resep-val">
                            {{ $item->resep ?? 'Tidak ada resep' }}
                        </p>
                    </div>
                </div>

            </div>
            @empty
            {{-- Empty State --}}
            <div id="emptyState" class="col-span-full py-20 text-center bg-white rounded-xl border border-slate-200 shadow-sm">
                <div class="flex flex-col items-center opacity-60">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-slate-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-500">
                        Belum Ada Rekam Medis
                    </p>
                </div>
            </div>
            @endforelse
        </div>

        {{-- Empty Search Trigger --}}
        <div id="searchEmptyState" class="hidden text-center bg-white rounded-xl border border-slate-200 shadow-sm py-20">
            <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Tidak ada rekam medis yang cocok dengan pencarian.</p>
        </div>

    </div>
</div>

{{-- ================= JAVASCRIPT: FILTER & EXPORT ================= --}}
<script>
    // FUNGSI PENCARIAN & FILTER CARD
    function filterCards() {
        let count = 1;
        let visibleCards = 0;
        const search = document.getElementById('searchInput').value.toLowerCase();
        const filter = document.getElementById('poliFilter').value;
        const cards = document.querySelectorAll('.medis-card');
        const searchEmptyState = document.getElementById('searchEmptyState');
        const emptyState = document.getElementById('emptyState');

        cards.forEach(card => {
            const poli = card.getAttribute('data-poli');
            const searchContent = card.getAttribute('data-search-content');

            if (searchContent.includes(search) && (filter === 'ALL' || poli === filter)) {
                card.style.display = 'flex';
                const numberTag = card.querySelector('.card-number');
                if (numberTag) numberTag.innerText = count++;
                visibleCards++;
            } else {
                card.style.display = 'none';
            }
        });

        // Tampilkan feedback jika hasil filter/pencarian kosong
        if (cards.length > 0) {
            if (visibleCards === 0) {
                searchEmptyState.classList.remove('hidden');
            } else {
                searchEmptyState.classList.add('hidden');
            }
        }
    }

    document.getElementById('searchInput').addEventListener('keyup', filterCards);
    document.getElementById('poliFilter').addEventListener('change', filterCards);
    window.addEventListener('DOMContentLoaded', filterCards);

    // FUNGSI EXPORT EXCEL (Disesuaikan dari DOM Card)
    document.getElementById('exportExcelBtn').addEventListener('click', function () {
        const wb = XLSX.utils.book_new();
        let excelData = [
            ["DATA REKAM MEDIS PASIEN POLKES JOMBANG"],
            [""], 
            ["NO", "NAMA PASIEN", "POLIKLINIK", "KELUHAN", "VITALS (BB/TB/TENSI)", "DIAGNOSIS", "TINDAKAN", "RESEP OBAT", "WAKTU DAFTAR", "WAKTU PERIKSA"]
        ];

        document.querySelectorAll('.medis-card').forEach(card => {
            if (card.style.display !== 'none') {
                const no = card.querySelector('.card-number').innerText;
                const nama = card.querySelector('h3').innerText.trim();
                const poli = card.getAttribute('data-poli');
                const keluhan = card.querySelector('.keluhan-val').innerText.trim();
                
                const bb = card.querySelector('.bb-val').innerText.trim();
                const tb = card.querySelector('.tb-val').innerText.trim();
                const tensi = card.querySelector('.tensi-val').innerText.trim();
                const vitals = `BB: ${bb} KG, TB: ${tb} CM, Tensi: ${tensi} mmHg`;

                const diagnosis = card.querySelector('.diagnosis-val').innerText.trim();
                const tindakan = card.querySelector('.tindakan-val').innerText.trim();
                const resep = card.querySelector('.resep-val').innerText.trim();
                
                const waktuDaftar = card.querySelector('.daftar-time-val').innerText.trim();
                const waktuPeriksa = card.querySelector('.periksa-time-val').innerText.trim();

                excelData.push([no, nama, poli, keluhan, vitals, diagnosis, tindakan, resep, waktuDaftar, waktuPeriksa]);
            }
        });

        const ws = XLSX.utils.aoa_to_sheet(excelData);
        
        ws['!cols'] = [
            {wch: 5},   // No
            {wch: 30},  // Nama
            {wch: 15},  // Poli
            {wch: 35},  // Keluhan
            {wch: 30},  // Vitals
            {wch: 40},  // Diagnosis
            {wch: 30},  // Tindakan
            {wch: 40},  // Resep
            {wch: 22},  // Waktu Daftar
            {wch: 22}   // Waktu Periksa
        ];

        XLSX.utils.book_append_sheet(wb, ws, "Rekam Medis");
        XLSX.writeFile(wb, "Arsip_Rekam_Medis_Polkes.xlsx");
    });
</script>

@endsection
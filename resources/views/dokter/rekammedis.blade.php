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
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-slate-200 text-slate-800 text-[10px] font-bold uppercase tracking-wider mb-2 border border-slate-300">
                    Sistem Rekam Medis
                </div>
                <h1 class="text-2xl md:text-3xl font-black tracking-tight text-slate-900">
                    Arsip <span class="text-emerald-600">Rekam Medis</span>
                </h1>
                <p class="text-slate-500 font-medium text-xs mt-0.5">
                    Data riwayat pemeriksaan klinis pasien Polkes Jombang.
                </p>
            </div>

            <button id="exportExcelBtn" class="inline-flex items-center justify-center gap-2 bg-slate-900 hover:bg-slate-800 text-white px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all active:scale-95 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Excel
            </button>
        </div>

        {{-- ================= SEARCH & FILTER ================= --}}
        <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm mb-4 flex flex-col sm:flex-row gap-3">
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" id="searchInput" placeholder="Cari nama pasien, keluhan, diagnosis..."
                       class="w-full pl-9 pr-4 py-2 rounded-lg bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-emerald-500/10 focus:border-emerald-500 focus:bg-white outline-none font-medium text-xs text-slate-900 transition-all">
            </div>

            <div class="relative sm:w-56 flex-shrink-0">
                <select id="poliFilter" class="w-full pl-3 pr-10 py-2 rounded-lg bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-emerald-500/10 focus:border-emerald-500 focus:bg-white outline-none font-bold text-xs uppercase tracking-wider appearance-none cursor-pointer text-slate-700 transition-all">
                    <option value="ALL">Semua Poliklinik</option>
                    <option value="Poli Umum">Poli Umum</option>
                    <option value="Poli Gigi">Poli Gigi</option>
                    <option value="Poli KIA & KB">Poli KIA & KB</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- ================= MEDICAL TABLE RECORD ================= --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse table-fixed min-w-[1000px]" id="medisTable">
                    <thead>
                        <tr class="bg-slate-900 text-slate-200 text-[10px] uppercase tracking-wider font-bold border-b border-slate-800">
                            <th class="py-3 px-4 text-center w-12">No</th>
                            <th class="py-3 px-4 w-1/5">Identitas Pasien</th>
                            <th class="py-3 px-4 w-1/4">Anamnesis & Vitals</th>
                            <th class="py-3 px-4 w-1/4">Diagnosis & Tindakan</th>
                            <th class="py-3 px-4 w-1/5">Resep Obat</th>
                            <th class="py-3 px-4 text-right w-36">Timeline</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 text-xs">
                        @forelse($data as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors group" data-poli="{{ $item->pendaftaran->poli ?? '-' }}">
                            
                            {{-- NO INDEX --}}
                            <td class="py-3.5 px-4 text-center align-top">
                                <span class="row-number font-mono font-bold text-slate-400 group-hover:text-slate-900"></span>
                            </td>

                            {{-- IDENTITAS PASIEN --}}
                            <td class="py-3.5 px-4 align-top">
                                <div class="flex flex-col gap-0.5">
                                    <span class="font-extrabold text-slate-900 uppercase tracking-wide nama-pasien-val">
                                        {{ $item->pendaftaran->nama_pasien ?? '-' }}
                                    </span>
                                    <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                        <span class="px-1.5 py-0.5 rounded bg-slate-100 border border-slate-300 text-slate-700 text-[9px] font-bold uppercase tracking-wider">
                                            {{ $item->pendaftaran->poli ?? '-' }}
                                        </span>
                                        <span class="text-[10px] font-semibold text-slate-500">
                                            Antrean: <span class="text-slate-800 font-bold font-mono">{{ $item->pendaftaran->nomor_antrian ?? '-' }}</span>
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- ANAMNESIS & VITALS --}}
                            <td class="py-3.5 px-4 align-top">
                                <div class="flex flex-col gap-1.5">
                                    <div>
                                        <span class="text-[9px] uppercase font-bold tracking-wider text-slate-400 block">Keluhan</span>
                                        <p class="text-slate-700 font-medium leading-relaxed keluhan-val">{{ $item->pendaftaran->keluhan ?? '-' }}</p>
                                    </div>
                                    <div class="flex items-center gap-3 text-[10px] font-bold text-slate-600 bg-slate-50 px-2 py-1 rounded border border-slate-200 w-fit">
                                        <span>BB: <span class="text-slate-900 font-mono bb-val">{{ $item->pendaftaran->berat_badan ?? '-' }}</span><span class="text-[9px] text-slate-400 font-normal">kg</span></span>
                                        <span class="border-l border-slate-300 h-3"></span>
                                        <span>TB: <span class="text-slate-900 font-mono tb-val">{{ $item->pendaftaran->tinggi_badan ?? '-' }}</span><span class="text-[9px] text-slate-400 font-normal">cm</span></span>
                                        <span class="border-l border-slate-300 h-3"></span>
                                        <span>TD: <span class="text-slate-900 font-mono tensi-val">{{ $item->pendaftaran->tensi ?? '-' }}</span><span class="text-[9px] text-slate-400 font-normal">mmHg</span></span>
                                    </div>
                                </div>
                            </td>

                            {{-- DIAGNOSIS & TINDAKAN --}}
                            <td class="py-3.5 px-4 align-top">
                                <div class="flex flex-col gap-1.5">
                                    <div>
                                        <span class="text-[9px] uppercase font-bold tracking-wider text-slate-400 block">Diagnosis Hasil</span>
                                        <p class="text-slate-950 font-bold text-xs diagnosis-val">{{ $item->diagnosis }}</p>
                                    </div>
                                    <div>
                                        <span class="text-[9px] uppercase font-bold tracking-wider text-slate-400 block">Tindakan</span>
                                        <p class="text-slate-700 font-semibold uppercase tracking-wide tindakan-val text-[11px]">{{ $item->tindakan }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- RESEP OBAT --}}
                            <td class="py-3.5 px-4 align-top">
                                <span class="text-[9px] uppercase font-bold tracking-wider text-slate-400 block mb-0.5">Terapi / Resep</span>
                                <p class="text-slate-600 font-medium italic leading-relaxed resep-val">
                                    {{ $item->resep ?? 'Tidak ada instruksi resep obat' }}
                                </p>
                            </td>

                            {{-- TIMELINE REGISTER & EXAM --}}
                            <td class="py-3.5 px-4 align-top text-right">
                                <div class="flex flex-col gap-1.5 font-mono text-[10px]">
                                    <div>
                                        <span class="text-[8px] uppercase font-bold font-sans tracking-wider text-slate-400 block">Daftar</span>
                                        <span class="text-slate-600 font-medium daftar-time-val">
                                            {{ $item->pendaftaran->created_at ? $item->pendaftaran->created_at->format('d/m/Y H:i') : '-' }}
                                        </span>
                                    </div>
                                    <div class="pt-1 border-t border-slate-100">
                                        <span class="text-[8px] uppercase font-bold font-sans tracking-wider text-emerald-600 block">Periksa</span>
                                        <span class="text-slate-900 font-bold periksa-time-val">
                                            {{ $item->created_at->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center bg-slate-50/50">
                                <div class="flex flex-col items-center opacity-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Belum Ada Rekam Medis</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

{{-- ================= JAVASCRIPT: FILTER & EXPORT ================= --}}
<script>
    // FUNGSI PENCARIAN & FILTER TABEL
    function filterTable() {
        let count = 1;
        const search = document.getElementById('searchInput').value.toLowerCase();
        const filter = document.getElementById('poliFilter').value;
        const rows = document.querySelectorAll('#medisTable tbody tr');

        rows.forEach(row => {
            if (row.cells.length === 1) return; // Lewati baris empty state

            const poli = row.getAttribute('data-poli');
            const textContent = row.innerText.toLowerCase();

            if (textContent.includes(search) && (filter === 'ALL' || poli === filter)) {
                row.style.display = '';
                const numberCell = row.querySelector('.row-number');
                if (numberCell) numberCell.innerText = count++;
            } else {
                row.style.display = 'none';
            }
        });
    }

    document.getElementById('searchInput').addEventListener('keyup', filterTable);
    document.getElementById('poliFilter').addEventListener('change', filterTable);
    window.addEventListener('DOMContentLoaded', filterTable);

    // FUNGSI EXPORT EXCEL
    document.getElementById('exportExcelBtn').addEventListener('click', function () {
        const wb = XLSX.utils.book_new();
        let excelData = [
            ["DATA REKAM MEDIS PASIEN POLKES JOMBANG"],
            [""], 
            ["NO", "NAMA PASIEN", "POLIKLINIK", "KELUHAN", "VITALS (BB/TB/TENSI)", "DIAGNOSIS", "TINDAKAN", "RESEP OBAT", "WAKTU DAFTAR", "WAKTU PERIKSA"]
        ];

        document.querySelectorAll('#medisTable tbody tr').forEach(row => {
            if (row.style.display !== 'none' && row.cells.length > 1) {
                const no = row.querySelector('.row-number').innerText;
                const nama = row.querySelector('.nama-pasien-val').innerText.trim();
                const poli = row.getAttribute('data-poli');
                const keluhan = row.querySelector('.keluhan-val').innerText.trim();
                
                const bb = row.querySelector('.bb-val').innerText.trim();
                const tb = row.querySelector('.tb-val').innerText.trim();
                const tensi = row.querySelector('.tensi-val').innerText.trim();
                const vitals = `BB: ${bb} KG, TB: ${tb} CM, Tensi: ${tensi} mmHg`;

                const diagnosis = row.querySelector('.diagnosis-val').innerText.trim();
                const tindakan = row.querySelector('.tindakan-val').innerText.trim();
                const resep = row.querySelector('.resep-val').innerText.trim();
                
                const waktuDaftar = row.querySelector('.daftar-time-val').innerText.trim() + " WIB";
                const waktuPeriksa = row.querySelector('.periksa-time-val').innerText.trim() + " WIB";

                excelData.push([no, nama, poli, keluhan, vitals, diagnosis, tindakan, resep, waktuDaftar, waktuPeriksa]);
            }
        });

        const ws = XLSX.utils.aoa_to_sheet(excelData);
        
        ws['!cols'] = [
            {wch: 5},   // No
            {wch: 30},  // Nama
            {wch: 15},  // Poli
            {wch: 35},  // Keluhan
            {wch: 25},  // Vitals
            {wch: 40},  // Diagnosis
            {wch: 30},  // Tindakan
            {wch: 40},  // Resep
            {wch: 20},  // Waktu Daftar
            {wch: 20}   // Waktu Periksa
        ];

        XLSX.utils.book_append_sheet(wb, ws, "Rekam Medis");
        XLSX.writeFile(wb, "Arsip_Rekam_Medis_Polkes.xlsx");
    });
</script>

@endsection
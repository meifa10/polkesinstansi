@extends('layouts.dokter')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/xlsx-js-style@1.2.0/dist/xlsx.bundle.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    /* Custom Scrollbar untuk Tabel Horizontal */
    .custom-scrollbar::-webkit-scrollbar {
        height: 8px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f8fafc; 
        border-radius: 8px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1; 
        border-radius: 8px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8; 
    }
</style>

<div class="p-4 md:p-6 lg:p-8 bg-slate-50 min-h-screen font-sans text-slate-900">
    
    <div class="max-w-[1600px] mx-auto">

        {{-- ================= HEADER SECTION ================= --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wide mb-3 border border-emerald-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Sistem Rekam Medis
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">
                    Data <span class="text-emerald-600">Rekam Medis</span>
                </h1>
                <p class="text-slate-500 font-medium mt-1.5 text-sm">
                    Arsip lengkap riwayat pemeriksaan pasien Polkes Jombang.
                </p>
            </div>

            <button id="exportExcelBtn" class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3.5 rounded-xl text-sm font-bold uppercase tracking-widest transition-all active:scale-95 shadow-md shadow-emerald-600/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Excel
            </button>
        </div>

        {{-- ================= SEARCH & FILTER ================= --}}
        <div class="bg-white p-4 rounded-2xl border border-slate-300 shadow-sm mb-6 flex flex-col md:flex-row gap-4">
            
            {{-- Search Input --}}
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" id="searchInput" placeholder="Cari nama pasien..."
                       class="w-full pl-11 pr-4 py-3 rounded-xl bg-slate-50 border border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none font-semibold text-sm text-slate-900 transition-all placeholder:text-slate-400">
            </div>

            {{-- Select Filter Poli --}}
            <div class="relative md:w-64 flex-shrink-0">
                <select id="poliFilter" class="w-full pl-5 pr-10 py-3 rounded-xl bg-slate-50 border border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none font-bold text-xs uppercase tracking-wider appearance-none cursor-pointer text-slate-800 transition-all">
                    <option value="ALL">Semua Poliklinik</option>
                    <option value="Poli Umum">Poli Umum</option>
                    <option value="Poli Gigi">Poli Gigi</option>
                    <option value="Poli KIA & KB">Poli KIA & KB</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- ================= DATA TABLE ================= --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-300 overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse min-w-[1300px]" id="medisTable">
                    <thead>
                        {{-- MENGGUNAKAN WARNA EMERALD GELAP SESUAI PERMINTAAN --}}
                        <tr class="bg-emerald-900 text-white text-[11px] uppercase tracking-widest font-bold">
                            <th class="py-4 px-5 text-center w-16">No</th>
                            <th class="py-4 px-5 min-w-[200px]">Data Pasien</th>
                            <th class="py-4 px-5 min-w-[140px]">Poliklinik</th>
                            <th class="py-4 px-5 min-w-[200px]">Keluhan Awal</th>
                            <th class="py-4 px-5 min-w-[120px]">Vitals</th>
                            <th class="py-4 px-5 min-w-[220px]">Hasil Diagnosis</th>
                            <th class="py-4 px-5 min-w-[180px]">Tindakan</th>
                            <th class="py-4 px-5 min-w-[200px]">Resep Obat</th>
                            <th class="py-4 px-5 min-w-[120px]">Status</th>
                            <th class="py-4 px-5 text-right min-w-[120px]">Waktu</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 text-sm">
                        @forelse($data as $item)
                        <tr class="hover:bg-emerald-50/50 transition-colors group" data-poli="{{ $item->pendaftaran->poli ?? '-' }}">
                            
                            {{-- NO URUT --}}
                            <td class="py-4 px-5 text-center align-middle">
                                <div class="row-number w-8 h-8 rounded-lg bg-slate-100 text-slate-700 border border-slate-200 flex items-center justify-center text-xs font-black mx-auto group-hover:bg-emerald-100 group-hover:text-emerald-700 transition-colors"></div>
                            </td>

                            {{-- DATA PASIEN --}}
                            <td class="py-4 px-5 align-middle">
                                <div class="flex flex-col">
                                    <span class="font-extrabold text-slate-900 text-sm uppercase">
                                        {{ $item->pendaftaran->nama_pasien ?? '-' }}
                                    </span>
                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mt-0.5">
                                        No Antrean: <span class="text-emerald-600">{{ $item->pendaftaran->nomor_antrian ?? '-' }}</span>
                                    </span>
                                </div>
                            </td>

                            {{-- POLIKLINIK --}}
                            <td class="py-4 px-5 align-middle">
                                <span class="px-2.5 py-1 rounded-md bg-slate-100 border border-slate-300 text-slate-800 text-[10px] font-black uppercase tracking-wider">
                                    {{ $item->pendaftaran->poli ?? '-' }}
                                </span>
                            </td>

                            {{-- KELUHAN --}}
                            <td class="py-4 px-5 align-middle">
                                <p class="text-xs font-semibold text-slate-700 leading-relaxed line-clamp-2">
                                    {{ $item->pendaftaran->keluhan ?? '-' }}
                                </p>
                            </td>

                            {{-- VITALS (BB/TB) --}}
                            <td class="py-4 px-5 align-middle">
                                <div class="flex flex-col gap-1 text-[11px] font-bold">
                                    <span class="text-slate-800 flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> BB: {{ $item->pendaftaran->berat_badan ?? '-' }} KG
                                    </span>
                                    <span class="text-slate-800 flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> TB: {{ $item->pendaftaran->tinggi_badan ?? '-' }} CM
                                    </span>
                                </div>
                            </td>

                            {{-- DIAGNOSIS --}}
                            <td class="py-4 px-5 align-middle">
                                <p class="text-sm font-bold text-slate-900 line-clamp-2">
                                    {{ $item->diagnosis }}
                                </p>
                            </td>

                            {{-- TINDAKAN --}}
                            <td class="py-4 px-5 align-middle">
                                <span class="text-xs font-bold text-slate-700 uppercase">
                                    {{ $item->tindakan }}
                                </span>
                            </td>

                            {{-- RESEP --}}
                            <td class="py-4 px-5 align-middle">
                                <p class="text-xs font-medium text-slate-600 italic line-clamp-2">
                                    {{ $item->resep ?? 'Tidak ada resep' }}
                                </p>
                            </td>

                            {{-- STATUS --}}
                            <td class="py-4 px-5 align-middle">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-black uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                    Selesai
                                </span>
                            </td>

                            {{-- WAKTU --}}
                            <td class="py-4 px-5 align-middle text-right">
                                <span class="block text-xs font-black text-slate-900 date-val">
                                    {{ $item->created_at->format('d/m/Y') }}
                                </span>
                                <span class="block text-[10px] font-bold text-slate-500 mt-0.5 hour-val">
                                    {{ $item->created_at->format('H:i') }} WIB
                                </span>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="py-20 text-center bg-slate-50/50">
                                <div class="flex flex-col items-center opacity-60">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-slate-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <p class="text-sm font-black uppercase tracking-widest text-slate-500">
                                        Belum Ada Rekam Medis
                                    </p>
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
    // FUNGSI PENCARIAN & FILTER
    function filterTable() {
        let count = 1;
        const search = document.getElementById('searchInput').value.toLowerCase();
        const filter = document.getElementById('poliFilter').value;
        const rows = document.querySelectorAll('#medisTable tbody tr');

        rows.forEach(row => {
            // Lewati baris "Belum Ada Rekam Medis"
            if (row.cells.length === 1) return; 

            const poli = row.getAttribute('data-poli');
            const textContent = row.innerText.toLowerCase();

            if (textContent.includes(search) && (filter === 'ALL' || poli === filter)) {
                row.style.display = '';
                const numberCell = row.querySelector('.row-number');
                if(numberCell) numberCell.innerText = count++;
            } else {
                row.style.display = 'none';
            }
        });
    }

    document.getElementById('searchInput').addEventListener('keyup', filterTable);
    document.getElementById('poliFilter').addEventListener('change', filterTable);
    window.addEventListener('DOMContentLoaded', filterTable);

    // FUNGSI EXPORT EXCEL (Bug Mapped Column Diperbaiki)
    document.getElementById('exportExcelBtn').addEventListener('click', function () {
        const wb = XLSX.utils.book_new();
        let excelData = [
            ["DATA REKAM MEDIS PASIEN POLKES JOMBANG"],
            [""], // Spasi Kosong
            // Header Excel
            ["NO", "NAMA PASIEN", "POLIKLINIK", "KELUHAN", "VITALS (BB/TB)", "DIAGNOSIS", "TINDAKAN", "RESEP OBAT", "STATUS", "WAKTU"]
        ];

        document.querySelectorAll('#medisTable tbody tr').forEach(row => {
            // Hanya ambil baris yang sedang tampil dan bukan baris kosong
            if (row.style.display !== 'none' && row.cells.length > 1) {
                excelData.push([
                    row.querySelector('.row-number').innerText,               // 0: NO
                    row.cells[1].innerText.trim().replace(/\n/g, ' - '),      // 1: NAMA
                    row.cells[2].innerText.trim(),                            // 2: POLI
                    row.cells[3].innerText.trim(),                            // 3: KELUHAN
                    row.cells[4].innerText.trim().replace(/\n/g, ', '),       // 4: BB/TB
                    row.cells[5].innerText.trim(),                            // 5: DIAGNOSIS
                    row.cells[6].innerText.trim(),                            // 6: TINDAKAN
                    row.cells[7].innerText.trim(),                            // 7: RESEP
                    row.cells[8].innerText.trim().replace(/\n/g, ''),         // 8: STATUS
                    row.cells[9].innerText.trim().replace(/\n/g, ' ')         // 9: WAKTU
                ]);
            }
        });

        const ws = XLSX.utils.aoa_to_sheet(excelData);
        
        // Mengatur Lebar Kolom Excel
        ws['!cols'] = [
            {wch: 5},   // No
            {wch: 30},  // Nama
            {wch: 15},  // Poli
            {wch: 35},  // Keluhan
            {wch: 20},  // BB/TB
            {wch: 40},  // Diagnosis
            {wch: 30},  // Tindakan
            {wch: 40},  // Resep
            {wch: 15},  // Status
            {wch: 20}   // Waktu
        ];

        XLSX.utils.book_append_sheet(wb, ws, "Rekam Medis");
        XLSX.writeFile(wb, "Arsip_Rekam_Medis_Polkes.xlsx");
    });
</script>

@endsection
@extends('layouts.dokter')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/xlsx-js-style@1.2.0/dist/xlsx.bundle.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
</style>

<div class="p-6 lg:p-8 bg-slate-50 min-h-screen font-sans">

    {{-- HEADER SECTION --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-sm font-bold tracking-wide uppercase mb-3 border border-emerald-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                </svg>
                Dokter / Rekam Medis
            </div>
            <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight">
                Data <span class="text-emerald-600">Rekam Medis Pasien</span>
            </h1>
            <p class="text-slate-600 font-medium mt-3 text-base lg:text-lg">
                Arsip lengkap riwayat pemeriksaan klinis, tindakan medis, dan terapi resep obat.
            </p>
        </div>

        <button id="exportExcelBtn" class="inline-flex items-center justify-center gap-2.5 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-4 rounded-2xl text-base font-extrabold uppercase tracking-wider transition-all active:scale-95 shadow-lg shadow-emerald-600/10 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Export Excel
        </button>
    </div>

    {{-- FILTER BOX (Live Filter JavaScript) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8 flex flex-col md:flex-row gap-5">
        {{-- Search Input --}}
        <div class="flex-grow relative">
            <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Cari Pasien / Diagnosis</label>
            <div class="absolute inset-y-0 bottom-0 left-0 pl-4 flex items-center pointer-events-none" style="top: 28px;">
                <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <input type="text" id="searchInput" placeholder="Masukkan nama pasien, keluhan, atau diagnosis utama..."
                class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none">
        </div>

        {{-- Filter Poli --}}
        <div class="md:w-72 relative">
            <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Poliklinik</label>
            <select id="poliFilter" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none appearance-none cursor-pointer">
                <option value="ALL">Semua Poliklinik</option>
                <option value="Poli Umum">Poli Umum</option>
                <option value="Poli Gigi">Poli Gigi</option>
                <option value="Poli KIA & KB">Poli KIA & KB</option>
            </select>
            <div class="absolute inset-y-0 bottom-0 right-0 flex items-center pr-4 pointer-events-none" style="top: 28px;">
                <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>
    </div>

    {{-- DATA TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1300px]" id="medisTable">
                <thead>
                    <tr class="bg-slate-900 text-white text-xs uppercase tracking-widest font-black">
                        <th class="py-5 px-6 w-16 text-center rounded-tl-2xl">No</th>
                        <th class="py-5 px-6 min-w-[280px]">Data Pasien</th>
                        <th class="py-5 px-6 min-w-[260px]">Keluhan & Vitals</th>
                        <th class="py-5 px-6 min-w-[340px]">Diagnosis & Tindakan Klinis</th>
                        <th class="py-5 px-6 min-w-[260px]">Resep Obat</th>
                        <th class="py-5 px-6 w-52 text-right rounded-tr-2xl">Timeline Kegiatan</th>
                    </tr>
                </thead>

                <tbody class="text-base divide-y divide-slate-200">
                    @forelse($data as $item)
                    <tr class="hover:bg-slate-50 transition-colors group" data-poli="{{ $item->pendaftaran->poli ?? '-' }}">
                        
                        {{-- NO URUT --}}
                        <td class="py-6 px-6 align-top text-center font-bold text-slate-500">
                            <span class="row-number"></span>
                        </td>

                        {{-- DATA PASIEN --}}
                        <td class="py-6 px-6 align-top">
                            <div class="flex items-start gap-4">
                                <div class="w-11 h-11 rounded-xl bg-slate-900 text-white flex items-center justify-center font-black text-lg shadow-sm flex-shrink-0 group-hover:bg-emerald-600 transition-colors">
                                    {{ strtoupper(substr($item->pendaftaran->nama_pasien ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <span class="font-extrabold text-slate-800 text-lg uppercase leading-tight block nama-pasien-val">
                                        {{ $item->pendaftaran->nama_pasien ?? '-' }}
                                    </span>
                                    <div class="flex flex-wrap items-center gap-2 mt-2">
                                        <span class="inline-flex px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-xs font-black uppercase tracking-wider border border-slate-200">
                                            {{ $item->pendaftaran->poli ?? '-' }}
                                        </span>
                                        <p class="text-xs text-slate-500 font-bold tracking-wider">
                                            No Antrean: <span class="text-emerald-600 font-mono font-black">{{ $item->pendaftaran->nomor_antrian ?? '-' }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- KELUHAN & VITALS --}}
                        <td class="py-6 px-6 align-top">
                            <div class="space-y-3">
                                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 text-slate-800 font-medium text-sm leading-relaxed shadow-sm keluhan-val">
                                    {{ $item->pendaftaran->keluhan ?? 'Tidak ada catatan keluhan awal' }}
                                </div>
                                
                                <div class="grid grid-cols-3 gap-2 text-center text-xs font-bold text-slate-700">
                                    <div class="border border-slate-200 p-2 rounded-lg bg-white shadow-sm">
                                        <span class="block text-[10px] text-slate-400 uppercase tracking-wide mb-0.5">Berat</span>
                                        <span class="font-mono font-bold text-slate-900"><span class="bb-val">{{ $item->pendaftaran->berat_badan ?? '-' }}</span> kg</span>
                                    </div>
                                    <div class="border border-slate-200 p-2 rounded-lg bg-white shadow-sm">
                                        <span class="block text-[10px] text-slate-400 uppercase tracking-wide mb-0.5">Tinggi</span>
                                        <span class="font-mono font-bold text-slate-900"><span class="tb-val">{{ $item->pendaftaran->tinggi_badan ?? '-' }}</span> cm</span>
                                    </div>
                                    <div class="border border-slate-200 p-2 rounded-lg bg-white shadow-sm">
                                        <span class="block text-[10px] text-slate-400 uppercase tracking-wide mb-0.5">Tensi</span>
                                        <span class="font-mono font-bold text-slate-900 text-[11px]"><span class="tensi-val">{{ $item->pendaftaran->tensi ?? '-' }}</span> mmHg</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- DIAGNOSIS & TINDAKAN --}}
                        <td class="py-6 px-6 align-top">
                            <div class="space-y-3">
                                <div class="p-3.5 rounded-xl bg-rose-50/80 border border-rose-200 text-sm shadow-sm">
                                    <span class="text-[10px] font-black text-rose-700 block uppercase tracking-widest mb-1">Diagnosis</span>
                                    <p class="font-bold text-slate-900 leading-relaxed text-base diagnosis-val">{{ $item->diagnosis }}</p>
                                </div>
                                <div class="p-3.5 rounded-xl bg-blue-50/80 border border-blue-200 text-sm shadow-sm">
                                    <span class="text-[10px] font-black text-blue-700 block uppercase tracking-widest mb-1">Tindakan Klinis</span>
                                    <p class="font-semibold text-slate-800 uppercase tracking-wide tindakan-val text-sm">{{ $item->tindakan }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- RESEP OBAT --}}
                        <td class="py-6 px-6 align-top">
                            @if($item->resep)
                                <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-sm font-semibold text-slate-900 whitespace-pre-line leading-relaxed shadow-sm resep-val">
                                    {{ $item->resep }}
                                </div>
                            @else
                                <span class="text-sm text-slate-500 font-semibold italic bg-slate-100 px-4 py-3 rounded-xl border border-slate-200 block text-center resep-val">
                                    Tidak ada resep obat
                                </span>
                            @endif
                        </td>

                        {{-- TIMELINE (DAFTAR & PERIKSA) --}}
                        <td class="py-6 px-6 align-top text-right">
                            <div class="space-y-3 font-mono text-xs">
                                <div>
                                    <span class="text-[10px] uppercase font-sans font-black tracking-wider text-slate-400 block mb-0.5">Waktu Daftar</span>
                                    <p class="font-bold text-slate-600 text-sm tracking-tight daftar-date-val">
                                        {{ $item->pendaftaran->created_at ? $item->pendaftaran->created_at->format('d/m/Y') : '-' }}
                                    </p>
                                    <span class="text-xs font-semibold text-slate-400 mt-0.5 block daftar-hour-val">
                                        {{ $item->pendaftaran->created_at ? $item->pendaftaran->created_at->format('H:i') : '-' }} WIB
                                    </span>
                                </div>
                                <div class="pt-2.5 border-t border-slate-100">
                                    <span class="text-[10px] uppercase font-sans font-black tracking-wider text-emerald-600 block mb-0.5">Selesai Periksa</span>
                                    <p class="font-black text-slate-900 text-sm tracking-tight periksa-date-val">
                                        {{ $item->created_at->format('d/m/Y') }}
                                    </p>
                                    <span class="inline-flex items-center gap-1 text-[11px] font-black text-emerald-700 mt-1 bg-emerald-100/60 rounded-md py-0.5 px-1.5 border border-emerald-200 periksa-hour-val">
                                        {{ $item->created_at->format('H:i') }} WIB
                                    </span>
                                </div>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-24 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="bg-slate-100 p-5 rounded-full text-slate-400 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-slate-800 mb-1 uppercase tracking-wide">Belum Ada Rekam Medis</h3>
                                <p class="text-slate-500 text-sm max-w-sm">Data rekam medis pemeriksaan pasien belum tersedia dalam database.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ================= JAVASCRIPT: FILTER & EXPORT ================= --}}
<script>
    // FUNGSI LIVE PENCARIAN & FILTER KATA KUNCI
    function filterTable() {
        let count = 1;
        const search = document.getElementById('searchInput').value.toLowerCase();
        const filter = document.getElementById('poliFilter').value;
        const rows = document.querySelectorAll('#medisTable tbody tr');

        rows.forEach(row => {
            if (row.cells.length === 1) return; // Lewati baris data kosong (empty state)

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

    // FUNGSI EXPORT DATA KE EXCEL
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
                
                const waktuDaftar = row.querySelector('.daftar-date-val').innerText.trim() + ' ' + row.querySelector('.daftar-hour-val').innerText.trim();
                const waktuPeriksa = row.querySelector('.periksa-date-val').innerText.trim() + ' ' + row.querySelector('.periksa-hour-val').innerText.trim();

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
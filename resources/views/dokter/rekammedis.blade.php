@extends('layouts.dokter')

@section('content')

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/xlsx-js-style@1.2.0/dist/xlsx.bundle.js"></script>

<style>

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #f8fafc;
        overflow-x: hidden;
    }

    .table-medis th {
        padding: 15px;
        background: #0f172a;
        color: white;
        font-size: 10px;
        text-transform: uppercase;
        font-weight: 800;
        letter-spacing: .1em;
        white-space: nowrap;
    }

    .table-medis td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .card-main {
        border-radius: 1.5rem;
        background: white;
        border: 1px solid #f1f5f9;
        box-shadow: 0 5px 25px rgba(0,0,0,.04);
    }

    .row-number {
        width: 26px;
        height: 26px;
        border-radius: 8px;
        background: #f1f5f9;
        color: #334155;
        font-size: 10px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: auto;
    }

    .custom-scrollbar::-webkit-scrollbar {
        height: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 999px;
    }

</style>


<div class="p-4 md:p-6 max-w-[1800px] mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">

        <div>

            <h1 class="text-3xl font-black text-slate-900 tracking-tight">
                Rekam <span class="text-emerald-600">Medis</span>
            </h1>

            <p class="text-[10px] uppercase tracking-[0.25em] font-black text-slate-400 mt-1">
                Polkes Jombang Medical Record System
            </p>

        </div>

        <button
            id="exportExcelBtn"
            class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition active:scale-95 shadow-lg shadow-emerald-100"
        >

            <i class="fa-solid fa-file-excel"></i>

            Export Excel

        </button>

    </div>


    {{-- FILTER --}}
    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">

        {{-- SEARCH --}}
        <div class="md:col-span-8 relative">

            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>

            <input
                type="text"
                id="searchInput"
                placeholder="Cari nama pasien..."
                class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-50 border border-transparent focus:border-emerald-500 outline-none text-sm font-bold"
            >

        </div>


        {{-- FILTER POLI --}}
        <div class="md:col-span-4 relative">

            <select
                id="poliFilter"
                class="w-full pl-4 pr-10 py-3 rounded-xl bg-slate-50 border border-transparent focus:border-emerald-500 outline-none appearance-none text-[11px] font-black uppercase tracking-wider"
            >

                <option value="ALL">Semua Poli</option>

                <option value="Poli Umum">Poli Umum</option>

                <option value="Poli Gigi">Poli Gigi</option>

                <option value="Poli KIA & KB">Poli KIA & KB</option>

            </select>

            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>

        </div>

    </div>


    {{-- TABLE --}}
    <div class="card-main overflow-hidden">

        <div class="overflow-x-auto custom-scrollbar">

            <table class="w-full table-medis" id="medisTable">

                <thead>

                    <tr>

                        <th class="text-center">No</th>

                        <th>Nama Pasien</th>

                        <th>Poli</th>

                        <th>Keluhan</th>

                        <th>BB/TB</th>

                        <th>Diagnosis</th>

                        <th>Tindakan</th>

                        <th>Resep</th>

                        <th>Status</th>

                        <th class="text-right">Waktu</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($data as $item)

                    <tr data-poli="{{ $item->pendaftaran->poli ?? '-' }}">

                        {{-- NO --}}
                        <td class="text-center">

                            <div class="row-number"></div>

                        </td>


                        {{-- NAMA --}}
                        <td>

                            <div class="flex flex-col">

                                <span class="font-black text-slate-800 uppercase text-xs">

                                    {{ $item->pendaftaran->nama_pasien ?? '-' }}

                                </span>

                                <span class="text-[9px] uppercase font-bold text-slate-400">

                                    No Antrean :
                                    {{ $item->pendaftaran->nomor_antrian ?? '-' }}

                                </span>

                            </div>

                        </td>


                        {{-- POLI --}}
                        <td>

                            <span class="px-3 py-1 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-700 text-[10px] font-black uppercase">

                                {{ $item->pendaftaran->poli ?? '-' }}

                            </span>

                        </td>


                        {{-- KELUHAN --}}
                        <td class="text-xs font-bold text-slate-700 uppercase">

                            {{ $item->pendaftaran->keluhan ?? '-' }}

                        </td>


                        {{-- BB TB --}}
                        <td>

                            <div class="flex flex-col text-[10px] font-black uppercase">

                                <span class="text-emerald-700">

                                    BB :
                                    {{ $item->pendaftaran->berat_badan ?? '-' }} KG

                                </span>

                                <span class="text-blue-700">

                                    TB :
                                    {{ $item->pendaftaran->tinggi_badan ?? '-' }} CM

                                </span>

                            </div>

                        </td>


                        {{-- DIAGNOSIS --}}
                        <td class="text-xs text-slate-700 font-bold uppercase">

                            {{ $item->diagnosis }}

                        </td>


                        {{-- TINDAKAN --}}
                        <td class="text-xs text-slate-700 font-bold uppercase">

                            {{ $item->tindakan }}

                        </td>


                        {{-- RESEP --}}
                        <td class="text-[10px] text-slate-500 italic uppercase">

                            {{ $item->resep ?? '-' }}

                        </td>


                        {{-- STATUS --}}
                        <td>

                            <span class="inline-flex items-center px-3 py-1 rounded-lg bg-emerald-100 border border-emerald-200 text-emerald-700 text-[10px] font-black uppercase">

                                <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span>

                                Selesai

                            </span>

                        </td>


                        {{-- WAKTU --}}
                        <td class="text-right">

                            <span class="block text-[10px] font-black text-slate-700 date-val">

                                {{ $item->created_at->format('d/m/Y') }}

                            </span>

                            <span class="block text-[9px] text-slate-400 hour-val">

                                {{ $item->created_at->format('H:i') }} WIB

                            </span>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="10" class="py-24 text-center">

                            <div class="flex flex-col items-center opacity-25">

                                <i class="fa-solid fa-folder-open text-6xl mb-4"></i>

                                <p class="text-[10px] uppercase tracking-[0.4em] font-black">

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


<script>

    function filterTable()
    {
        let count = 1;

        document.querySelectorAll('#medisTable tbody tr').forEach(row => {

            const search = document.getElementById('searchInput').value.toLowerCase();

            const filter = document.getElementById('poliFilter').value;

            const poli = row.getAttribute('data-poli');

            if (
                row.innerText.toLowerCase().includes(search)
                &&
                (filter === 'ALL' || poli === filter)
            ) {

                row.style.display = '';

                row.querySelector('.row-number').innerText = count++;

            } else {

                row.style.display = 'none';

            }

        });
    }

    document.getElementById('searchInput').addEventListener('keyup', filterTable);

    document.getElementById('poliFilter').addEventListener('change', filterTable);

    window.onload = filterTable;


    // EXPORT EXCEL
    document.getElementById('exportExcelBtn').addEventListener('click', function () {

        const wb = XLSX.utils.book_new();

        let excelData = [

            ["REKAM MEDIS PASIEN POLKES JOMBANG"],

            [""],

            ["NO","NAMA","POLI","KELUHAN","BB","TB","DIAGNOSIS","TINDAKAN","RESEP","TANGGAL"]

        ];

        document.querySelectorAll('#medisTable tbody tr').forEach(row => {

            if (row.style.display !== 'none')
            {
                excelData.push([

                    row.querySelector('.row-number').innerText,

                    row.cells[1].innerText.trim(),

                    row.cells[2].innerText.trim(),

                    row.cells[3].innerText.trim(),

                    row.cells[4].innerText.trim(),

                    row.cells[5].innerText.trim(),

                    row.cells[6].innerText.trim(),

                    row.cells[7].innerText.trim(),

                    row.cells[8].innerText.trim(),

                    row.querySelector('.date-val').innerText

                ]);
            }

        });

        const ws = XLSX.utils.aoa_to_sheet(excelData);

        ws['!cols'] = [

            {wch:5},
            {wch:25},
            {wch:20},
            {wch:30},
            {wch:15},
            {wch:15},
            {wch:30},
            {wch:30},
            {wch:30},
            {wch:15}

        ];

        XLSX.utils.book_append_sheet(wb, ws, "Rekam Medis");

        XLSX.writeFile(wb, "rekam_medis.xlsx");

    });

</script>

@endsection
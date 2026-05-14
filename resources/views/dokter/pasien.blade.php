@extends('layouts.dokter')

@section('content')

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #f8fafc;
    }

    .table-antrean th {
        padding: 16px;
        background: #0f172a;
        color: #fff;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .1em;
        font-weight: 800;
    }

    .table-antrean td {
        padding: 18px;
        vertical-align: middle;
    }

    .card-antrean {
        border-radius: 2rem;
        background: white;
        border: 1px solid #f1f5f9;
        box-shadow: 0 10px 30px rgba(0,0,0,.04);
    }

    .btn-periksa {
        background: #10b981;
        transition: .3s;
    }

    .btn-periksa:hover {
        background: #059669;
        transform: translateY(-2px);
    }

    .row-number {
        width: 30px;
        height: 30px;
        background: #f1f5f9;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 800;
        color: #334155;
        margin: auto;
    }

</style>

<div class="p-8 max-w-[1300px] mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-end md:items-center gap-4">

        <div>

            <div class="flex items-center gap-2 mb-2">

                <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>

                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">
                    Dashboard Pemeriksaan Dokter
                </p>

            </div>

            <h1 class="text-3xl font-black text-slate-900 tracking-tight">
                Daftar <span class="text-emerald-600">Pasien</span>
            </h1>

        </div>

        <div class="bg-white border border-slate-200 rounded-2xl px-6 py-4 shadow-sm flex items-center gap-4">

            <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600">
                <i class="fa-solid fa-user-doctor"></i>
            </div>

            <div>

                <p class="text-[10px] uppercase font-black tracking-widest text-slate-400">
                    Total Pasien
                </p>

                <h2 class="text-2xl font-black text-slate-900">
                    {{ $pasien->count() }}
                </h2>

            </div>

        </div>

    </div>


    {{-- SEARCH --}}
    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">

        <div class="md:col-span-8 relative">

            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>

            <input
                type="text"
                id="searchInput"
                placeholder="Cari nama pasien..."
                class="w-full pl-11 pr-4 py-3 rounded-xl bg-slate-50 border border-transparent focus:border-emerald-500 outline-none font-bold text-sm"
            >

        </div>

        <div class="md:col-span-4 relative">

            <select
                id="poliFilter"
                class="w-full pl-5 pr-10 py-3 rounded-xl bg-slate-50 border border-transparent focus:border-emerald-500 outline-none font-black text-[11px] uppercase tracking-wider appearance-none"
            >

                <option value="ALL">Semua Poli</option>

                <option value="Poli Umum">Poli Umum</option>

                <option value="Poli Gigi">Poli Gigi</option>

                <option value="Poli KIA & KB">Poli KIA & KB</option>

            </select>

            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>

        </div>

    </div>


    {{-- TABLE --}}
    <div class="card-antrean overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full table-antrean" id="pasienTable">

                <thead>

                    <tr>

                        <th class="text-center w-16 rounded-tl-3xl">
                            No
                        </th>

                        <th>
                            Pasien
                        </th>

                        <th>
                            Keluhan
                        </th>

                        <th>
                            BB / TB
                        </th>

                        <th>
                            Poli
                        </th>

                        <th>
                            Status
                        </th>

                        <th class="text-center rounded-tr-3xl">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($pasien as $p)

                    <tr
                        class="hover:bg-emerald-50/40 transition"
                        data-poli="{{ $p->poli }}"
                    >

                        {{-- NO --}}
                        <td class="text-center">

                            <div class="row-number"></div>

                        </td>


                        {{-- PASIEN --}}
                        <td>

                            <div class="flex items-center gap-4">

                                <div class="w-11 h-11 rounded-full bg-slate-100 flex items-center justify-center text-slate-500">

                                    <i class="fa-solid fa-user"></i>

                                </div>

                                <div>

                                    <h2 class="font-black text-slate-800 uppercase text-sm">
                                        {{ $p->nama_pasien }}
                                    </h2>

                                    <p class="text-[10px] uppercase font-bold text-slate-400">
                                        No Antrean :
                                        {{ $p->nomor_antrian }}
                                    </p>

                                </div>

                            </div>

                        </td>


                        {{-- KELUHAN --}}
                        <td>

                            <div class="max-w-[250px]">

                                <p class="text-sm font-bold text-slate-700 line-clamp-2">

                                    {{ $p->keluhan }}

                                </p>

                            </div>

                        </td>


                        {{-- BB/TB --}}
                        <td>

                            <div class="flex flex-col gap-1">

                                <span class="text-xs font-black text-emerald-700">
                                    BB : {{ $p->berat_badan }} KG
                                </span>

                                <span class="text-xs font-black text-blue-700">
                                    TB : {{ $p->tinggi_badan }} CM
                                </span>

                            </div>

                        </td>


                        {{-- POLI --}}
                        <td>

                            <span class="px-3 py-1 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-700 text-[10px] font-black uppercase">

                                {{ $p->poli }}

                            </span>

                        </td>


                        {{-- STATUS --}}
                        <td>

                            <span class="inline-flex items-center px-3 py-1 rounded-lg bg-blue-100 border border-blue-200 text-blue-700 text-[10px] font-black uppercase">

                                <span class="w-2 h-2 bg-blue-500 rounded-full mr-2 animate-pulse"></span>

                                Siap Diperiksa

                            </span>

                        </td>


                        {{-- AKSI --}}
                        <td class="text-center">

                            <a
                                href="{{ route('dokter.pemeriksaan.show', $p->id) }}"
                                class="btn-periksa inline-flex items-center gap-2 px-5 py-3 rounded-xl text-white text-[10px] font-black uppercase tracking-widest"
                            >

                                <i class="fa-solid fa-stethoscope"></i>

                                Periksa

                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="7" class="py-24 text-center">

                            <div class="flex flex-col items-center opacity-30">

                                <i class="fa-solid fa-folder-open text-6xl mb-4"></i>

                                <p class="text-[11px] font-black uppercase tracking-[0.4em]">
                                    Tidak Ada Pasien
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

    const searchInput = document.getElementById('searchInput');

    const poliFilter = document.getElementById('poliFilter');

    const tableRows = document.querySelectorAll('#pasienTable tbody tr');

    function filterTable()
    {
        const searchTerm = searchInput.value.toLowerCase();

        const selectedPoli = poliFilter.value;

        let visibleCount = 1;

        tableRows.forEach(row => {

            if (row.cells.length === 1) return;

            const poliValue = row.getAttribute('data-poli');

            const rowText = row.innerText.toLowerCase();

            const matchesSearch = rowText.includes(searchTerm);

            const matchesPoli = selectedPoli === 'ALL' || poliValue === selectedPoli;

            if (matchesSearch && matchesPoli) {

                row.style.display = '';

                const number = row.querySelector('.row-number');

                if(number)
                {
                    number.innerText = visibleCount++;
                }

            } else {

                row.style.display = 'none';

            }

        });
    }

    searchInput.addEventListener('keyup', filterTable);

    poliFilter.addEventListener('change', filterTable);

    document.addEventListener('DOMContentLoaded', filterTable);

</script>

@endsection
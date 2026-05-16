@extends('layouts.dokter')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
</style>

<div class="p-4 md:p-6 lg:p-8 bg-slate-50 min-h-screen font-sans">

    {{-- ================= HEADER SECTION ================= --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-8 gap-6">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold tracking-wide uppercase mb-2 border border-emerald-200 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Dashboard Pemeriksaan
            </div>
            <h1 class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">
                Daftar <span class="text-emerald-600">Antrean Pasien</span>
            </h1>
            <p class="text-slate-500 font-medium mt-1.5 text-sm md:text-base">
                Pilih pasien dari daftar antrean di bawah untuk memulai pemeriksaan medis.
            </p>
        </div>

        {{-- Card Total Pasien --}}
        <div class="bg-white border border-slate-200 rounded-2xl px-6 py-4 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] uppercase font-black tracking-widest text-slate-400 mb-0.5">Total Antrean</p>
                <h2 class="text-2xl font-black text-slate-900 leading-none">{{ $pasien->count() }}</h2>
            </div>
        </div>
    </div>

    {{-- ================= SEARCH & FILTER ================= --}}
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-6 flex flex-col md:flex-row gap-4">
        
        {{-- Search Input --}}
        <div class="relative flex-grow">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <input type="text" id="searchInput" placeholder="Cari nama pasien..."
                   class="w-full pl-11 pr-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none font-semibold text-sm text-slate-800 transition-all">
        </div>

        {{-- Select Filter Poli --}}
        <div class="relative md:w-64 flex-shrink-0">
            <select id="poliFilter" class="w-full pl-5 pr-10 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none font-bold text-[11px] uppercase tracking-wider appearance-none cursor-pointer text-slate-700 transition-all">
                <option value="ALL">Semua Poliklinik</option>
                <option value="Poli Umum">Poli Umum</option>
                <option value="Poli Gigi">Poli Gigi</option>
                <option value="Poli KIA & KB">Poli KIA & KB</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>
    </div>

    {{-- ================= DATA TABLE ================= --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1000px]" id="pasienTable">
                <thead>
                    <tr class="bg-slate-900 text-white text-[11px] uppercase tracking-widest font-bold">
                        <th class="py-4 px-6 text-center w-16">No</th>
                        <th class="py-4 px-6 min-w-[200px]">Data Pasien</th>
                        <th class="py-4 px-6 min-w-[250px]">Keluhan Utama</th>
                        <th class="py-4 px-6 min-w-[150px]">Vitals (BB/TB)</th>
                        <th class="py-4 px-6 min-w-[140px]">Poliklinik</th>
                        <th class="py-4 px-6 min-w-[130px]">Status</th>
                        <th class="py-4 px-6 text-center min-w-[120px]">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($pasien as $p)
                    <tr class="hover:bg-emerald-50/50 transition-colors group" data-poli="{{ $p->poli }}">
                        
                        {{-- NO URUT --}}
                        <td class="py-4 px-6 text-center align-middle">
                            <div class="row-number w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-xs font-black mx-auto group-hover:bg-emerald-100 group-hover:text-emerald-700 transition-colors"></div>
                        </td>

                        {{-- DATA PASIEN --}}
                        <td class="py-4 px-6 align-middle">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center flex-shrink-0 group-hover:bg-emerald-100 group-hover:text-emerald-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="font-extrabold text-slate-800 uppercase text-sm leading-tight">
                                        {{ $p->nama_pasien }}
                                    </h2>
                                    <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mt-0.5 font-mono">
                                        Antrean: <span class="text-emerald-600">{{ $p->nomor_antrian }}</span>
                                    </p>
                                </div>
                            </div>
                        </td>

                        {{-- KELUHAN --}}
                        <td class="py-4 px-6 align-middle">
                            <div class="bg-slate-50 p-3 rounded-lg border border-slate-100 relative">
                                <p class="text-xs font-bold text-slate-600 italic line-clamp-2 leading-relaxed">
                                    "{{ $p->keluhan }}"
                                </p>
                            </div>
                        </td>

                        {{-- VITALS (BB/TB) --}}
                        <td class="py-4 px-6 align-middle">
                            <div class="flex flex-col gap-1.5">
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    BB: <span class="font-black text-slate-900">{{ $p->berat_badan }} KG</span>
                                </span>
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                    TB: <span class="font-black text-slate-900">{{ $p->tinggi_badan }} CM</span>
                                </span>
                            </div>
                        </td>

                        {{-- POLIKLINIK --}}
                        <td class="py-4 px-6 align-middle">
                            <span class="px-2.5 py-1 rounded-md bg-slate-100 border border-slate-200 text-slate-700 text-[10px] font-black uppercase tracking-wider">
                                {{ $p->poli }}
                            </span>
                        </td>

                        {{-- STATUS --}}
                        <td class="py-4 px-6 align-middle">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 border border-blue-200 text-blue-700 text-[10px] font-black uppercase tracking-wider">
                                <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></span>
                                Siap Periksa
                            </span>
                        </td>

                        {{-- AKSI --}}
                        <td class="py-4 px-6 align-middle text-center">
                            <a href="{{ route('dokter.pemeriksaan.show', $p->id) }}"
                               class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-emerald-600 text-white rounded-lg text-[11px] font-bold uppercase tracking-wider hover:bg-emerald-700 transition-all active:scale-95 shadow-sm border border-emerald-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Periksa
                            </a>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-20 text-center bg-slate-50/50">
                            <div class="flex flex-col items-center opacity-60">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-slate-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="text-sm font-black uppercase tracking-widest text-slate-500">
                                    Tidak Ada Pasien
                                </p>
                                <p class="text-xs font-medium text-slate-400 mt-1">Saat ini belum ada pasien yang menunggu di antrean.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- FOOTER INFO --}}
        <div class="bg-slate-50 px-6 py-4 border-t border-slate-200">
            <p class="text-[10px] text-slate-500 font-extrabold italic uppercase tracking-wider flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Menampilkan daftar antrean hari ini berdasarkan layanan poliklinik.
            </p>
        </div>
    </div>
</div>

{{-- ================= JAVASCRIPT UNTUK FILTER ================= --}}
<script>
    const searchInput = document.getElementById('searchInput');
    const poliFilter = document.getElementById('poliFilter');
    const tableRows = document.querySelectorAll('#pasienTable tbody tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedPoli = poliFilter.value;
        let visibleCount = 1;

        tableRows.forEach(row => {
            // Abaikan baris "Tidak Ada Pasien" (colspan=7)
            if (row.cells.length === 1) return;

            const poliValue = row.getAttribute('data-poli');
            const rowText = row.innerText.toLowerCase();

            const matchesSearch = rowText.includes(searchTerm);
            const matchesPoli = selectedPoli === 'ALL' || poliValue === selectedPoli;

            if (matchesSearch && matchesPoli) {
                row.style.display = '';
                // Update nomor urut
                const numberCell = row.querySelector('.row-number');
                if(numberCell) {
                    numberCell.innerText = visibleCount++;
                }
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Jalankan filter setiap kali ada input teks atau perubahan dropdown
    searchInput.addEventListener('keyup', filterTable);
    poliFilter.addEventListener('change', filterTable);

    // Inisialisasi penomoran saat halaman pertama kali dimuat
    document.addEventListener('DOMContentLoaded', filterTable);
</script>

@endsection
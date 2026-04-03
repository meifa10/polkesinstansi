@extends('layouts.dokter')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; }
    
    .table-antrean th { 
        padding: 16px; 
        background: #0f172a; /* Slate 900 */
        color: #f8fafc;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-weight: 800;
    }
    .table-antrean td { padding: 16px; vertical-align: middle; }
    
    .card-antrean { 
        border-radius: 2rem; 
        background: white; 
        border: 1px solid #f1f5f9; 
        box-shadow: 0 10px 30px -10px rgba(0,0,0,0.04); 
    }

    .btn-periksa {
        background: #10b981;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }
    .btn-periksa:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
    }

    .row-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        background: #f1f5f9;
        color: #475569;
        border-radius: 8px;
        font-weight: 800;
        font-size: 11px;
    }
</style>

<div class="p-8 max-w-[1200px] mx-auto space-y-6">
    
    <div class="flex flex-col md:flex-row justify-between items-end md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.3em]">Antrean Pasien Hari Ini</p>
            </div>
            <h1 class="text-3xl font-[800] text-slate-900 tracking-tight">Daftar <span class="text-emerald-600">Pemeriksaan</span></h1>
        </div>

        <div class="bg-white border border-slate-200 px-6 py-3 rounded-2xl flex items-center gap-4 shadow-sm">
            <i class="fa-solid fa-clipboard-list text-emerald-500 text-xl"></i>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Total Antrean</p>
                <p class="text-xl font-black text-slate-900 leading-none">{{ $pasien->count() }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 bg-white p-3 rounded-2xl border border-slate-100 shadow-sm">
        <div class="md:col-span-8 relative group">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs transition-colors group-focus-within:text-emerald-500"></i>
            <input type="text" id="searchInput" placeholder="Cari nama pasien..." 
                class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-transparent rounded-xl text-sm font-bold text-slate-700 focus:bg-white focus:ring-2 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none">
        </div>

        <div class="md:col-span-4 relative">
            <select id="poliFilter" class="w-full pl-5 pr-10 py-3 bg-slate-50 border border-transparent rounded-xl text-[11px] font-black uppercase tracking-wider text-slate-600 appearance-none focus:bg-white focus:ring-2 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none cursor-pointer">
                <option value="ALL">Semua Poli</option>
                <option value="Poli Umum">Poli Umum</option>
                <option value="Poli Gigi">Poli Gigi</option>
                <option value="Poli KIA & KB">Poli KIA & KB</option>
            </select>
            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 text-[10px] pointer-events-none"></i>
        </div>
    </div>

    <div class="card-antrean overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full table-antrean" id="pasienTable">
                <thead>
                    <tr>
                        <th class="text-center w-20 rounded-tl-3xl">No</th>
                        <th class="text-left">Identitas Pasien</th>
                        <th class="text-left">Unit Pelayanan</th>
                        <th class="text-center w-40 rounded-tr-3xl">Aksi Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($pasien as $p)
                    <tr class="hover:bg-emerald-50/40 transition-all duration-300 group" data-poli="{{ $p->poli }}">
                        <td class="text-center">
                            <span class="row-number"></span>
                        </td>
                        <td>
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                                    <i class="fa-solid fa-user-check text-sm"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-800 text-sm uppercase tracking-tight">{{ $p->nama_pasien }}</span>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">Pasien Terdaftar</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="px-3 py-1 bg-white border border-slate-100 rounded-lg text-[10px] font-black uppercase text-emerald-700 shadow-sm inline-block">
                                {{ $p->poli }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('dokter.pemeriksaan.show',$p->id) }}" 
                               class="btn-periksa inline-flex items-center gap-2 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest active:scale-95">
                                <i class="fa-solid fa-stethoscope text-sm"></i>
                                Periksa
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-24 text-center">
                            <div class="flex flex-col items-center opacity-20">
                                <i class="fa-solid fa-hourglass-empty text-5xl mb-4"></i>
                                <p class="text-[10px] font-black uppercase tracking-[0.4em]">Belum ada antrean pasien</p>
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

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedPoli = poliFilter.value;
        let visibleCount = 1;

        tableRows.forEach(row => {
            if (row.cells.length === 1) return; // Skip empty row

            const poliValue = row.getAttribute('data-poli');
            const rowText = row.innerText.toLowerCase();

            const matchesSearch = rowText.includes(searchTerm);
            const matchesPoli = (selectedPoli === 'ALL' || poliValue === selectedPoli);

            if (matchesSearch && matchesPoli) {
                row.style.display = '';
                const numCell = row.querySelector('.row-number');
                if(numCell) numCell.innerText = visibleCount++;
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
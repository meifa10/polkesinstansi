@extends('layouts.dokter')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; }
    
    /* Styling Tabel Ramping & Aesthetic */
    .table-medis th { 
        padding: 16px; 
        background: #1e293b; /* Warna Slate 900 untuk Header */
        color: #f8fafc;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-weight: 800;
    }
    .table-medis td { 
        padding: 14px 16px; 
        vertical-align: middle;
    }
    .card-main { 
        border-radius: 2rem; 
        background: white; 
        border: 1px solid #f1f5f9; 
        box-shadow: 0 10px 30px -10px rgba(0,0,0,0.04); 
    }
    
    /* Custom Scrollbar */
    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

    /* Badge Nomor Urut */
    .row-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        background: #f1f5f9;
        color: #64748b;
        border-radius: 6px;
        font-weight: 800;
        font-size: 11px;
    }
</style>

<div class="p-8 max-w-[1440px] mx-auto space-y-6">
    
    <div class="flex flex-col sm:flex-row justify-between items-end sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-[800] text-slate-900 tracking-tight">Rekam Medis Pasien</h1>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.3em]">Polkes Jombang Integrated System</p>
        </div>

        <div class="flex items-center gap-3">
            <div class="bg-emerald-50 px-5 py-2.5 rounded-2xl border border-emerald-100 flex items-center gap-3 shadow-sm">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <p class="text-[11px] font-black text-emerald-700 uppercase tracking-widest">Live: {{ $data->count() }} Records</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 bg-white p-4 rounded-[1.5rem] border border-slate-100 shadow-sm">
        <div class="md:col-span-6 relative group">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs transition-colors group-focus-within:text-emerald-500"></i>
            <input type="text" id="searchInput" placeholder="Cari nama pasien, diagnosis, atau tindakan..." 
                class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-transparent rounded-xl text-sm font-bold text-slate-700 focus:bg-white focus:ring-2 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none">
        </div>

        <div class="md:col-span-4 relative">
            <select id="poliFilter" class="w-full pl-5 pr-10 py-3 bg-slate-50 border border-transparent rounded-xl text-[11px] font-black uppercase tracking-wider text-slate-600 appearance-none focus:bg-white focus:ring-2 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none cursor-pointer transition-all">
                <option value="ALL">Semua Unit Poli</option>
                <option value="Poli Umum">Poli Umum</option>
                <option value="Poli Gigi">Poli Gigi</option>
                <option value="Poli KIA & KB">Poli KIA & KB</option>
            </select>
            <i class="fa-solid fa-filter absolute right-4 top-1/2 -translate-y-1/2 text-emerald-500 text-[10px] pointer-events-none"></i>
        </div>

        <button id="exportBtn" class="md:col-span-2 flex items-center justify-center gap-2 bg-slate-900 hover:bg-black text-white py-3 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] transition-all active:scale-95 shadow-lg shadow-slate-200">
            <i class="fa-solid fa-file-csv text-emerald-400"></i> Export
        </button>
    </div>

    <div class="card-main overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full table-medis" id="medisTable">
                <thead>
                    <tr>
                        <th class="text-center rounded-tl-3xl">No</th>
                        <th class="text-left">Nama Pasien</th>
                        <th class="text-left">Poli</th>
                        <th class="text-left">Analisis Medis</th>
                        <th class="text-left">Tindakan</th>
                        <th class="text-left">Resep</th>
                        <th class="text-right rounded-tr-3xl">Tanggal Periksa</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($data as $item)
                    <tr class="hover:bg-emerald-50/40 transition-colors" data-poli="{{ $item->pendaftaran->poli ?? '-' }}">
                        <td class="text-center">
                            <span class="row-number"></span>
                        </td>
                        
                        <td>
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-800 text-sm tracking-tight uppercase">{{ $item->pendaftaran->nama_pasien ?? '-' }}</span>
                                <span class="text-[9px] text-slate-400 font-bold tracking-widest">RM-{{ $item->id }}</span>
                            </div>
                        </td>

                        <td>
                            <span class="px-3 py-1 bg-white border border-slate-100 rounded-lg text-[10px] font-black uppercase text-emerald-700 shadow-sm">
                                {{ $item->pendaftaran->poli ?? '-' }}
                            </span>
                        </td>

                        <td class="max-w-[220px]">
                            <p class="text-[10px] font-black text-rose-500 uppercase tracking-tighter italic leading-none mb-1">Diagnosis:</p>
                            <p class="text-xs text-slate-700 font-bold truncate leading-tight">{{ $item->diagnosis }}</p>
                        </td>

                        <td>
                            <span class="text-xs text-slate-600 font-semibold flex items-center gap-2">
                                <i class="fa-solid fa-kit-medical text-[10px] text-slate-300"></i>
                                {{ $item->tindakan }}
                            </span>
                        </td>

                        <td>
                            <span class="text-xs text-slate-400 italic font-medium">
                                {{ $item->resep ?? '-' }}
                            </span>
                        </td>

                        <td class="text-right">
                            <p class="text-xs font-black text-slate-700 tracking-tighter leading-none mb-1">{{ $item->created_at->format('d/m/Y') }}</p>
                            <p class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">{{ $item->created_at->format('H:i') }} WIB</p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-20 text-center">
                            <div class="flex flex-col items-center opacity-20">
                                <i class="fa-solid fa-folder-open text-5xl mb-3"></i>
                                <p class="text-[10px] font-black uppercase tracking-widest">Belum ada data rekam medis</p>
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
    const tableRows = document.querySelectorAll('#medisTable tbody tr');
    const exportBtn = document.getElementById('exportBtn');

    // FUNGSI 1: FILTER & AUTO-NUMBERING
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedPoli = poliFilter.value;
        let visibleCount = 1;

        tableRows.forEach(row => {
            if (row.cells.length === 1) return; // Abaikan baris "Data Kosong"

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

    // FUNGSI 2: EXPORT CSV (Hanya data yang sedang tampil)
    exportBtn.addEventListener('click', function() {
        let csv = [];
        const header = ["No", "Nama Pasien", "Poli", "Diagnosis", "Tindakan", "Resep", "Tanggal"];
        csv.push(header.join(","));

        tableRows.forEach(row => {
            if (row.style.display !== 'none' && row.cells.length > 1) {
                let rowData = [
                    row.querySelector('.row-number').innerText,
                    '"' + row.cells[1].innerText.split('\n')[0].trim() + '"', // Ambil Nama saja
                    '"' + row.cells[2].innerText.trim() + '"',
                    '"' + row.cells[3].querySelector('p:last-child').innerText.trim() + '"',
                    '"' + row.cells[4].innerText.trim() + '"',
                    '"' + row.cells[5].innerText.trim() + '"',
                    '"' + row.cells[6].querySelector('p:first-child').innerText.trim() + '"'
                ];
                csv.push(rowData.join(","));
            }
        });

        const csvContent = "data:text/csv;charset=utf-8," + csv.join("\n");
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "Export_RekamMedis_" + new Date().toISOString().slice(0,10) + ".csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });

    // Event Listeners
    searchInput.addEventListener('keyup', filterTable);
    poliFilter.addEventListener('change', filterTable);
    document.addEventListener('DOMContentLoaded', filterTable);
</script>
@endsection
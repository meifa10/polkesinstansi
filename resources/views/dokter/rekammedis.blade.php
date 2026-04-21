@extends('layouts.dokter')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/xlsx-js-style@1.2.0/dist/xlsx.bundle.js"></script>

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; overflow-x: hidden; }
    
    .table-medis th { padding: 14px 16px; background: #0f172a; color: #f8fafc; font-size: 10px; text-transform: uppercase; font-weight: 800; white-space: nowrap; }
    .table-medis td { padding: 12px 16px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; white-space: nowrap; }
    
    .card-main { border-radius: 1.5rem; background: white; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px -10px rgba(0,0,0,0.05); }
    
    /* Efek Kilau Halus saat Hover */
    .btn-export { position: relative; overflow: hidden; transition: all 0.3s ease; }
    .btn-export::after {
        content: ""; position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    }
    .btn-export:hover::after { left: 100%; transition: all 0.6s ease; }

    .custom-scrollbar::-webkit-scrollbar { height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    
    .row-number { display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; background: #f1f5f9; color: #475569; border-radius: 6px; font-weight: 800; font-size: 10px; }
</style>

<div class="p-4 md:p-6 max-w-[1600px] mx-auto space-y-6">
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-[800] text-slate-900 tracking-tight">Rekam <span class="text-emerald-600">Medis Pasien</span></h1>
            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.2em]">Polkes Jombang Integrated System</p>
        </div>

        <button id="exportExcelBtn" class="btn-export group flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-wider shadow-md shadow-emerald-100 active:scale-95">
            <i class="fa-solid fa-file-excel"></i>
            <span>Export Report</span>
        </button>
    </div>

    {{-- Filter Bar --}}
    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 bg-white p-3 rounded-2xl border border-slate-100 shadow-sm">
        <div class="md:col-span-8 relative">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
            <input type="text" id="searchInput" placeholder="Cari nama pasien..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-medium focus:bg-white outline-none">
        </div>
        <div class="md:col-span-4 relative">
            <select id="poliFilter" class="w-full pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-[10px] font-bold uppercase outline-none appearance-none cursor-pointer">
                <option value="ALL">Semua Unit Poli</option>
                <option value="Poli Umum">Poli Umum</option>
                <option value="Poli Gigi">Poli Gigi</option>
                <option value="Poli KIA & KB">Poli KIA & KB</option>
            </select>
            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-[9px]"></i>
        </div>
    </div>

    <div class="card-main overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full table-medis" id="medisTable">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-left">Nama Pasien</th>
                        <th class="text-left">Poli</th>
                        <th class="text-left">Diagnosis</th>
                        <th class="text-left">Tindakan</th>
                        <th class="text-left">Resep</th>
                        <th class="text-right">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                    <tr data-poli="{{ $item->pendaftaran->poli ?? '-' }}">
                        <td class="text-center"><span class="row-number"></span></td>
                        <td class="font-bold text-slate-800 uppercase text-xs">{{ $item->pendaftaran->nama_pasien ?? '-' }}</td>
                        <td><span class="text-[9px] font-black text-emerald-600 uppercase">{{ $item->pendaftaran->poli ?? '-' }}</span></td>
                        <td class="text-xs text-slate-600 uppercase">{{ $item->diagnosis }}</td>
                        <td class="text-xs text-slate-600 uppercase">{{ $item->tindakan }}</td>
                        <td class="text-[10px] italic text-slate-400 uppercase">{{ $item->resep ?? '-' }}</td>
                        <td class="text-right">
                            <span class="block text-[10px] font-bold text-slate-700 date-val">{{ $item->created_at->format('d/m/Y') }}</span>
                            <span class="block text-[9px] text-slate-400 hour-val">{{ $item->created_at->format('H:i') }} WIB</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function filterTable() {
        let count = 1;
        document.querySelectorAll('#medisTable tbody tr').forEach(row => {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const filter = document.getElementById('poliFilter').value;
            const poli = row.getAttribute('data-poli');
            if (row.innerText.toLowerCase().includes(search) && (filter === 'ALL' || poli === filter)) {
                row.style.display = '';
                row.querySelector('.row-number').innerText = count++;
            } else { row.style.display = 'none'; }
        });
    }
    document.getElementById('searchInput').addEventListener('keyup', filterTable);
    document.getElementById('poliFilter').addEventListener('change', filterTable);
    window.onload = filterTable;

    document.getElementById('exportExcelBtn').addEventListener('click', function() {
        const wb = XLSX.utils.book_new();
        const selectedPoli = document.getElementById('poliFilter').value;
        
        // Logika Judul Dinamis Aesthetic
        let displayTitle = selectedPoli === 'ALL' 
            ? "LAPORAN REKAM MEDIS - SEMUA UNIT POLI" 
            : `LAPORAN REKAM MEDIS - ${selectedPoli.toUpperCase()}`;

        let excelData = [
            [{ v: "POLKES 05.09.15 JOMBANG", t: 's' }],
            [{ v: "Jl. KH. Wahid Hasyim No.28 B, Jombang - Jawa Timur", t: 's' }],
            [{ v: "Email: jombangposkes@gmail.com | Telp: 0877-7723-5386", t: 's' }],
            [""],
            [{ v: displayTitle, t: 's' }], // Judul Dinamis
            [""],
            ["NO", "NAMA PASIEN", "UNIT POLI", "DIAGNOSIS KLINIS", "TINDAKAN MEDIS", "RESEP OBAT", "TANGGAL", "JAM"]
        ];

        document.querySelectorAll('#medisTable tbody tr').forEach(row => {
            if (row.style.display !== 'none') {
                excelData.push([
                    row.querySelector('.row-number').innerText,
                    row.cells[1].innerText.trim().toUpperCase(),
                    row.cells[2].innerText.trim().toUpperCase(),
                    row.cells[3].innerText.trim().toUpperCase(),
                    row.cells[4].innerText.trim().toUpperCase(),
                    row.cells[5].innerText.trim().toUpperCase(),
                    row.querySelector('.date-val').innerText,
                    row.querySelector('.hour-val').innerText
                ]);
            }
        });

        const ws = XLSX.utils.aoa_to_sheet(excelData);

        // Styling Excel
        const headerStyle = {
            fill: { fgColor: { rgb: "059669" } },
            font: { color: { rgb: "FFFFFF" }, bold: true },
            alignment: { horizontal: "center", vertical: "center" },
            border: { top: {style:"thin"}, bottom: {style:"thin"}, left: {style:"thin"}, right: {style:"thin"} }
        };

        const range = XLSX.utils.decode_range(ws['!ref']);
        for (let C = range.s.c; C <= range.e.c; ++C) {
            const addr = XLSX.utils.encode_cell({ r: 6, c: C });
            if (ws[addr]) ws[addr].s = headerStyle;
        }

        ws['A1'].s = { font: { bold: true, sz: 14, color: { rgb: "064E3B" } }, alignment: { horizontal: "center" } };
        ws['A2'].s = ws['A3'].s = { font: { sz: 10 }, alignment: { horizontal: "center" } };
        ws['A5'].s = { font: { bold: true, sz: 12, color: { rgb: "059669" } }, alignment: { horizontal: "center" } };

        ws['!merges'] = [
            { s: {r: 0, c: 0}, e: {r: 0, c: 7} },
            { s: {r: 1, c: 0}, e: {r: 1, c: 7} },
            { s: {r: 2, c: 0}, e: {r: 2, c: 7} },
            { s: {r: 4, c: 0}, e: {r: 4, c: 7} }
        ];

        ws['!cols'] = [{wch: 5}, {wch: 25}, {wch: 15}, {wch: 35}, {wch: 25}, {wch: 25}, {wch: 12}, {wch: 10}];

        XLSX.utils.book_append_sheet(wb, ws, "Rekam Medis");
        XLSX.writeFile(wb, `Laporan_Medis_${selectedPoli.replace(/\s/g, '_')}.xlsx`);
    });
</script>
@endsection
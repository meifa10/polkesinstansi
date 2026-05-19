@extends('layouts.dokter')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/xlsx-js-style@1.2.0/dist/xlsx.bundle.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
</style>

<div class="p-6 lg:p-8 bg-slate-50 min-h-screen">
    
    {{-- BACK BUTTON --}}
    <div class="mb-4 flex justify-between items-center">
        <a href="{{ route('dokter.rekammedis') }}" class="inline-flex items-center gap-2 bg-white border border-slate-300 px-4 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors shadow-sm">
            ← Kembali ke Daftar Laporan Utama
        </a>
        <span class="text-xs font-bold bg-slate-200 text-slate-600 px-3 py-1 rounded-md">STATUS: AKTIF</span>
    </div>

    {{-- CARD IDENTITAS PASIEN --}}
    <div class="bg-emerald-950 text-white rounded-2xl p-6 mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 shadow-xl">
        <div>
            <span class="text-xs font-black uppercase tracking-widest bg-emerald-800 text-emerald-200 px-2.5 py-1 rounded mb-2 inline-block">POLI TERAKHIR: {{ $sampelPendaftaran->poli ?? '-' }}</span>
            <h2 class="text-3xl font-black uppercase tracking-tight" id="namaPasienHeader">{{ $namaPasien }}</h2>
            <p class="text-emerald-300 font-medium text-sm mt-1">Nomor Identitas NIK: <span class="font-mono bg-emerald-900 px-2 py-0.5 rounded text-white text-xs">{{ $sampelPendaftaran->nik ?? '-' }}</span></p>
        </div>
        <button id="exportExcelBtn" class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-3 rounded-xl text-sm font-extrabold uppercase tracking-wide transition-all active:scale-95 shadow-lg shadow-emerald-500/20 cursor-pointer">
            Export Riwayat Pasien (Excel)
        </button>
    </div>

    {{-- FILTER TANGGAL --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 mb-6">
        <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-3 max-w-md">
            <div class="flex-1">
                <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-1">Saring Riwayat Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-800 outline-none focus:border-emerald-500 focus:bg-white transition-all">
            </div>
            <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2.5 rounded-xl font-bold text-sm uppercase self-end h-[46px]">Filter</button>
            @if(request('tanggal'))
                <a href="{{ url()->current() }}" class="bg-slate-100 border border-slate-300 hover:bg-slate-200 text-slate-600 px-3 rounded-xl flex items-center justify-center h-[46px] self-end" title="Reset">✕</a>
            @endif
        </form>
    </div>

    {{-- TIMELINE REKAM MEDIS TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1200px]" id="medisTable">
                <thead>
                    <tr class="bg-slate-900 text-white text-xs uppercase tracking-widest font-black">
                        <th class="py-4 px-4 w-12 text-center">NO</th>
                        <th class="py-4 px-6 w-44">WAKTU KUNJUNGAN</th>
                        <th class="py-4 px-6 w-56">KELUHAN UTAMA</th>
                        <th class="py-4 px-6 w-52">TANDA-TANDA VITAL</th>
                        <th class="py-4 px-6">DIAGNOSIS & TINDAKAN KLINIS</th>
                        <th class="py-4 px-6">RESEP OBAT</th>
                        <th class="py-4 px-6 w-48">DOKTER</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-200">
                    @forelse($dataRiwayat as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-5 px-4 text-center font-bold text-slate-400 row-number">
                            {{ ($dataRiwayat->currentPage() - 1) * $dataRiwayat->perPage() + $loop->iteration }}
                        </td>
                        <td class="py-5 px-6 font-mono text-xs">
                            <span class="font-bold text-slate-800 text-sm block">{{ $item->created_at->format('d M Y') }}</span>
                            <span class="inline-block mt-1 px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800 font-bold font-sans">{{ $item->created_at->format('H:i') }} WIB</span>
                        </td>
                        <td class="py-5 px-6">
                            <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 leading-relaxed font-medium keluhan-val">
                                {{ $item->pendaftaran->keluhan ?? '-' }}
                            </div>
                        </td>
                        <td class="py-5 px-6">
                            <div class="space-y-1.5 text-xs font-bold text-slate-700">
                                <div class="flex justify-between border-b pb-1">
                                    <span class="text-slate-400 uppercase tracking-wide">Tensi:</span>
                                    <span class="font-mono text-slate-900"><span class="tensi-val">{{ $item->pendaftaran->tensi ?? '-' }}</span> mmHg</span>
                                </div>
                                <div class="flex justify-between border-b pb-1">
                                    <span class="text-slate-400 uppercase tracking-wide">Berat:</span>
                                    <span class="font-mono text-slate-900"><span class="bb-val">{{ $item->pendaftaran->berat_badan ?? '-' }}</span> kg</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400 uppercase tracking-wide">Tinggi:</span>
                                    <span class="font-mono text-slate-900"><span class="tb-val">{{ $item->pendaftaran->tinggi_badan ?? '-' }}</span> cm</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-6 space-y-2">
                            <div class="p-3 bg-rose-50/70 border border-rose-200 rounded-xl">
                                <span class="text-[10px] font-black text-rose-700 block uppercase tracking-widest mb-0.5">Diagnosis Utama</span>
                                <p class="font-bold text-slate-900 text-sm diagnosis-val">{{ $item->diagnosis }}</p>
                            </div>
                            <div class="p-3 bg-blue-50/70 border border-blue-200 rounded-xl">
                                <span class="text-[10px] font-black text-blue-700 block uppercase tracking-widest mb-0.5">Tindakan Klinis</span>
                                <p class="font-semibold text-slate-800 text-xs uppercase tracking-wide tindakan-val">{{ $item->tindakan }}</p>
                            </div>
                        </td>
                        <td class="py-5 px-6">
                            <div class="p-3 bg-amber-50/80 border border-amber-200 rounded-xl text-slate-900 font-semibold leading-relaxed whitespace-pre-line text-xs resep-val">
                                {{ $item->resep ?? 'Tidak ada resep obat' }}
                            </div>
                        </td>
                        <td class="py-5 px-6">
                            <span class="inline-block px-3 py-1.5 border border-slate-300 bg-white rounded-lg font-bold text-slate-700 text-xs text-center min-w-full dokter-val">
                                {{ $item->dokter->name ?? '-' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-20 text-center font-semibold text-slate-500 uppercase tracking-wider">Tidak ditemukan riwayat rekam medis pada tanggal ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION LINK (MAKSIMAL 5 DATA PER HALAMAN) --}}
        @if($dataRiwayat->hasPages())
        <div class="p-4 border-t border-slate-200 bg-slate-50">
            {{ $dataRiwayat->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    document.getElementById('exportExcelBtn').addEventListener('click', function () {
        // Ambil data dinamis dari tampilan komponen untuk disematkan ke lembar judul atas excel
        const namaPasien = document.getElementById('namaPasienHeader').innerText.trim();
        const tglHariIni = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        
        const wb = XLSX.utils.book_new();
        
        // Pengemasan Susunan Judul Dokumen Excel yang Jauh Lebih Lengkap & Formal
        let excelData = [
            ["LAPORAN JEJAK RIWAYAT REKAM MEDIS PASIEN - POLKES JOMBANG"],
            [`Nama Lengkap Pasien : ${namaPasien}`],
            [`Tanggal Unduh Dokumen: ${tglHariIni}`],
            ["Klasifikasi Dokumen : Dokumen Arsip Klinis Internal Sah"],
            [""], // Ruang kosong jeda baris antara judul laporan dengan tabel utama
            ["NO", "TANGGAL PERIKSA", "KELUHAN PASIEN", "TANDA VITAL (BB/TB/TENSI)", "DIAGNOSIS KLINIS", "TINDAKAN MEDIS", "RESEP OBAT", "DOKTER PEMERIKSA"]
        ];

        document.querySelectorAll('#medisTable tbody tr').forEach(row => {
            if (row.cells.length > 1) {
                const no = row.querySelector('.row-number').innerText.trim();
                const tanggal = row.cells[1].querySelector('span').innerText.trim();
                const keluhan = row.querySelector('.keluhan-val').innerText.trim();
                
                const bb = row.querySelector('.bb-val').innerText.trim();
                const tb = row.querySelector('.tb-val').innerText.trim();
                const tensi = row.querySelector('.tensi-val').innerText.trim();
                const vitals = `BB: ${bb} kg, TB: ${tb} cm, Tensi: ${tensi} mmHg`;

                const diagnosis = row.querySelector('.diagnosis-val').innerText.trim();
                const tindakan = row.querySelector('.tindakan-val').innerText.trim();
                const resep = row.querySelector('.resep-val').innerText.trim();
                
                // Menarik data nama dokter secara real-time dari setiap sel baris tabel HTML
                const dokter = row.querySelector('.dokter-val').innerText.trim();

                excelData.push([no, tanggal, keluhan, vitals, diagnosis, tindakan, resep, dokter]);
            }
        });

        const ws = XLSX.utils.aoa_to_sheet(excelData);
        
        ws['!cols'] = [
            {wch: 6},  // NO
            {wch: 18}, // TANGGAL PERIKSA
            {wch: 35}, // KELUHAN PASIEN
            {wch: 30}, // TANDA VITAL (BB/TB/TENSI)
            {wch: 35}, // DIAGNOSIS KLINIS
            {wch: 28}, // TINDAKAN MEDIS
            {wch: 45}, // RESEP OBAT
            {wch: 32}  // DOKTER PEMERIKSA (Lebar kolom disesuaikan untuk gelar nama dokter)
        ];

        XLSX.utils.book_append_sheet(wb, ws, "Riwayat Medis");
        XLSX.writeFile(wb, `Laporan_Rekam_Medis_${namaPasien.replace(/\s+/g, '_')}.xlsx`);
    });
</script>
@endsection
@extends('layouts.dokter')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
</style>

<div class="p-6 lg:p-8 bg-slate-50 min-h-screen">

    {{-- HEADER --}}
    <div class="mb-8">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-sm font-bold uppercase mb-3 border border-emerald-200">
            DOKTER / REKAM MEDIS
        </div>
        <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">
            Laporan <span class="text-emerald-600">Pemeriksaan Pasien</span>
        </h1>
        <p class="text-slate-600 font-medium mt-2 text-base">
            Pilih pasien di bawah ini untuk melihat jejak riwayat pemeriksaan rekam medis secara menyeluruh.
        </p>
    </div>

    {{-- FILTER BOX --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
            <div class="md:col-span-6 relative">
                <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Cari Pasien</label>
                <div class="absolute inset-y-0 bottom-0 left-0 pl-4 flex items-center pointer-events-none" style="top: 28px;">
                    <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" id="searchInput" placeholder="Masukkan Nama Pasien atau NIK..."
                    class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none">
            </div>

            <div class="md:col-span-4 relative">
                <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Poliklinik Terakhir</label>
                <select id="poliFilter" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none appearance-none cursor-pointer">
                    <option value="ALL">Semua Poliklinik</option>
                    <option value="Poli Umum">Poli Umum</option>
                    <option value="Poli Gigi">Poli Gigi</option>
                    <option value="Poli KIA & KB">Poli KIA & KB</option>
                </select>
            </div>
            
            <div class="md:col-span-2">
                <button onclick="filterTable()" class="w-full bg-slate-900 text-white font-bold py-4 rounded-xl transition-all hover:bg-slate-800 uppercase tracking-wider text-sm">Cari</button>
            </div>
        </div>
    </div>

    {{-- PASIEN DATA TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="pasienTable">
                <thead>
                    <tr class="bg-emerald-950 text-white text-xs uppercase tracking-widest font-black">
                        <th class="py-4 px-6 w-16 text-center">NO</th>
                        <th class="py-4 px-6">NAMA PASIEN / NIK</th>
                        <th class="py-4 px-6">POLIKLINIK TERAKHIR</th>
                        <th class="py-4 px-6 text-center">TOTAL KUNJUNGAN</th>
                        <th class="py-4 px-6 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="text-base divide-y divide-slate-200">
                    @forelse($pasienList as $index => $item)
                    <tr class="hover:bg-slate-50 transition-colors group row-pasien" data-poli="{{ $item->poli_terakhir ?? '-' }}">
                        <td class="py-5 px-6 text-center font-bold text-slate-500">
                            {{ $index + 1 }}
                        </td>
                        <td class="py-5 px-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-black text-base uppercase group-hover:bg-emerald-600 transition-colors">
                                    {{ strtoupper(substr($item->nama_pasien ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <span class="font-extrabold text-slate-800 block uppercase nama-pasien-text">
                                        {{ $item->nama_pasien ?? '-' }}
                                    </span>
                                    <span class="text-xs text-slate-400 font-bold tracking-wider">NIK: {{ $item->nik ?? '-' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-6">
                            <span class="inline-flex px-3 py-1 rounded bg-slate-100 text-slate-700 text-xs font-bold uppercase border border-slate-200">
                                {{ $item->poli_terakhir ?? '-' }}
                            </span>
                        </td>
                        <td class="py-5 px-6 text-center">
                            <span class="inline-flex px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-extrabold border border-emerald-200">
                                {{ $item->total_kunjungan }} Kali Periksa
                            </span>
                        </td>
                        <td class="py-5 px-6 text-center">
                            <a href="{{ route('dokter.rekammedis.riwayat', \Illuminate\Support\Facades\Crypt::encryptString($item->nama_pasien)) }}" 
                               class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wide transition-all active:scale-95 shadow-md shadow-emerald-600/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Lihat Riwayat
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-16 text-center text-slate-500 font-medium uppercase tracking-wide text-sm">Belum ada pasien yang diperiksa.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Live Search Client Side
    function filterTable() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const filter = document.getElementById('poliFilter').value;
        const rows = document.querySelectorAll('.row-pasien');

        rows.forEach(row => {
            const poli = row.getAttribute('data-poli');
            const text = row.innerText.toLowerCase();

            if (text.includes(search) && (filter === 'ALL' || poli === filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    document.getElementById('searchInput').addEventListener('keyup', filterTable);
    document.getElementById('poliFilter').addEventListener('change', filterTable);
</script>
@endsection
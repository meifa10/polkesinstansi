@extends('layouts.petugas')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="p-6 bg-gray-50 min-h-screen font-sans">
    <div class="max-w-7xl mx-auto space-y-6">
        
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Laporan Kunjungan Pasien</h1>
                <p class="text-sm text-gray-500 font-semibold mt-1">Rekapitulasi data kunjungan pasien berdasarkan diagnosa medis ICD-10</p>
            </div>
            
            <button onclick="window.print()" class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-sm print:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Laporan
            </button>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm print:hidden">
            <form method="GET" action="{{ route('petugas.laporan.diagnosa') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Tanggal Mulai</label>
                    <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai', date('Y-m-d')) }}" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-sm font-semibold text-gray-900 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Tanggal Selesai</label>
                    <input type="date" name="tgl_selesai" value="{{ request('tgl_selesai', date('Y-m-d')) }}" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-sm font-semibold text-gray-900 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Poliklinik</label>
                    <select name="poli" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-sm font-semibold text-gray-900 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition appearance-none cursor-pointer">
                        <option value="">Semua Poli</option>
                        <option value="Poli Umum" {{ request('poli') == 'Poli Umum' ? 'selected' : '' }}>Poli Umum</option>
                        <option value="Poli Gigi" {{ request('poli') == 'Poli Gigi' ? 'selected' : '' }}>Poli Gigi</option>
                        <option value="Poli KIA & KB" {{ request('poli') == 'Poli KIA & KB' ? 'selected' : '' }}>Poli KIA & KB</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="w-full inline-flex items-center justify-center bg-gray-900 hover:bg-gray-800 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-sm">
                        Filter Data
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <th class="px-6 py-4 text-center w-16">No</th>
                            <th class="px-6 py-4">Tanggal Kunjungan</th>
                            <th class="px-6 py-4">Nama Pasien</th>
                            <th class="px-6 py-4">Poliklinik</th>
                            <th class="px-6 py-4">Dokter Pemeriksa</th>
                            <th class="px-6 py-4">Diagnosis Medis (ICD-10)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm font-medium text-gray-900">
                        @forelse($laporan as $index => $item)
                            <tr class="hover:bg-gray-50/70 transition">
                                <td class="px-6 py-4 text-center font-mono text-gray-500">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
                                    <span class="block text-xs text-gray-400 font-semibold mt-0.5">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB</span>
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900">
                                    {{ $item->nama_pasien }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-800 text-xs font-bold">
                                        {{ $item->poli }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    {{ $item->nama_dokter ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-rose-700 font-semibold">
                                    {{ $item->rekamMedis->diagnosis ?? 'Belum Diisi Dokter' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400 font-bold">
                                    Tidak ada data kunjungan pasien untuk filter yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<style>
    @print {
        body {
            background-color: #ffffff !important;
        }
        main {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        aside {
            display: none !important;
        }
        .print\:hidden {
            display: none !important;
        }
        .bg-white {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endsection
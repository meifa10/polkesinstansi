@extends('layouts.petugas')

@section('content')

<div class="p-6 lg:p-8 bg-slate-50 min-h-screen font-sans">

    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-8 gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-sm font-bold tracking-wide uppercase mb-3 border border-emerald-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                </svg>
                Rekam Medis Instansi
            </div>
            <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight">
                Laporan <span class="text-emerald-600">Kunjungan Pasien</span>
            </h1>
            <p class="text-slate-600 font-medium mt-3 text-base lg:text-lg">
                Rekapitulasi dan pemantauan data kunjungan pasien berdasarkan hasil diagnosa medis.
            </p>
        </div>

        {{-- METRIC CARD --}}
        <div class="bg-white px-8 py-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-6 min-w-[220px]">
            <div class="p-4 bg-emerald-50 rounded-xl text-emerald-600 border border-emerald-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm uppercase font-bold text-slate-400 tracking-wider">Total Kunjungan</p>
                <h2 class="text-4xl font-black text-slate-800 leading-none mt-1">{{ $laporan->total() }}</h2>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8 print:hidden">
        {{-- FORM FILTER LAPORAN --}}
        <div class="lg:col-span-12 bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            <div class="flex items-center justify-between border-b border-slate-100 pb-5 mb-6 gap-4">
                <div class="flex items-center gap-3">
                    <div class="bg-emerald-100 p-2 rounded-lg text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">Penyaringan Data Laporan</h2>
                </div>

                <button onclick="window.print()" class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-3 rounded-xl text-base font-bold transition-all shadow-lg shadow-emerald-500/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak Dokumen
                </button>
            </div>

            <form method="GET" action="{{ route('petugas.laporan.diagnosa') }}" class="grid grid-cols-1 md:grid-cols-4 gap-5 items-end">
                <div>
                    <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Tanggal Mulai</label>
                    <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai', date('Y-m-d')) }}"
                        class="w-full px-5 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Tanggal Selesai</label>
                    <input type="date" name="tgl_selesai" value="{{ request('tgl_selesai', date('Y-m-d')) }}"
                        class="w-full px-5 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Poliklinik</label>
                    <div class="relative">
                        <select name="poli" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none appearance-none cursor-pointer">
                            <option value="">Semua Poliklinik</option>
                            <option value="Poli Umum" {{ request('poli') == 'Poli Umum' ? 'selected' : '' }}>Poli Umum</option>
                            <option value="Poli Gigi" {{ request('poli') == 'Poli Gigi' ? 'selected' : '' }}>Poli Gigi</option>
                            <option value="Poli KIA & KB" {{ request('poli') == 'Poli KIA & KB' ? 'selected' : '' }}>Poli KIA & KB</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-500">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="flex gap-2">
                        <button type="submit" class="w-full bg-slate-800 text-white px-5 py-3.5 rounded-xl font-bold text-base hover:bg-slate-900 transition-colors shadow-md">
                            Tampilkan Data
                        </button>
                        @if(request('tgl_mulai') || request('tgl_selesai') || request('poli'))
                            <a href="{{ route('petugas.laporan.diagnosa') }}" class="px-5 py-3.5 bg-slate-100 text-slate-600 rounded-xl text-base font-bold hover:bg-slate-200 transition-colors border border-slate-300 flex items-center justify-center">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- DATA TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-emerald-900 text-white text-sm uppercase tracking-widest font-bold">
                        <th class="py-5 px-6 w-16 text-center rounded-tl-xl">No</th>
                        <th class="py-5 px-6 min-w-[180px]">Tanggal Kunjungan</th>
                        <th class="py-5 px-6 min-w-[220px]">Nama Pasien</th>
                        <th class="py-5 px-6 min-w-[150px]">Poliklinik</th>
                        <th class="py-5 px-6 min-w-[200px]">Dokter Pemeriksa</th>
                        <th class="py-5 px-6 min-w-[250px] rounded-tr-xl">Diagnosis Medis (ICD-10)</th>
                    </tr>
                </thead>
                <tbody class="text-base divide-y divide-slate-200">
                    @forelse($laporan as $item)
                        <tr class="hover:bg-emerald-50/60 transition-colors">
                            <td class="py-5 px-6 text-center text-slate-500 font-bold align-middle text-lg">
                                {{ ($laporan->currentPage() - 1) * $laporan->perPage() + $loop->iteration }}
                            </td>
                            <td class="py-5 px-6 align-middle text-slate-700 font-medium">
                                <span class="font-bold block text-slate-800">{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}</span>
                                <span class="block text-xs text-slate-400 font-bold mt-0.5">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB</span>
                            </td>
                            <td class="py-5 px-6 align-middle">
                                <span class="font-extrabold text-slate-800 text-lg">{{ $item->nama_pasien }}</span>
                            </td>
                            <td class="py-5 px-6 align-middle">
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-slate-100 border border-slate-300 text-slate-800 text-sm font-extrabold">
                                    {{ $item->poli }}
                                </span>
                            </td>
                            <td class="py-5 px-6 align-middle text-slate-700 font-bold">
                                {{ $item->nama_dokter ?? '-' }}
                            </td>
                            <td class="py-5 px-6 align-middle text-rose-700 font-extrabold text-lg">
                                {{ $item->rekamMedis->diagnosis ?? 'Belum Diisi Dokter' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-20 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="bg-emerald-50 p-6 rounded-full mb-5 border border-emerald-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-800 mb-2">Data Laporan Kunjungan Kosong</h3>
                                    <p class="text-slate-500 text-base max-w-md">
                                        Tidak ada rekaman data kunjungan pasien yang sesuai dengan parameter penyaringan yang Anda tentukan.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- NAVIGATION PAGINATION --}}
    <div class="mt-6 print:hidden">
        {!! $laporan->links() !!}
    </div>

</div>

<style>
    @media print {
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
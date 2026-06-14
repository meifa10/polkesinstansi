@extends('layouts.petugas')

@section('content')

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
</style>

<div class="p-6 lg:p-8 bg-slate-50 min-h-screen">

    {{-- Header Navigation --}}
    <div class="mb-6 flex items-center justify-between border-b border-slate-200 pb-5">
        <a href="{{ route('petugas.laporan.diagnosa') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-emerald-700 font-bold hover:bg-emerald-50 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>

        <span class="text-xs font-bold text-slate-500 uppercase bg-slate-200 px-3 py-1 rounded">
            ID Pasien #{{ $pasien->id }}
        </span>
    </div>

    {{-- Info Pasien Section --}}
    <div class="bg-gradient-to-r from-emerald-900 to-slate-900 text-white rounded-3xl p-8 mb-8 shadow-xl">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <div class="inline-flex px-3 py-1 rounded bg-emerald-500/20 text-emerald-300 text-xs font-bold uppercase mb-3">
                    {{ $pasien->poli }}
                </div>
                <h1 class="text-3xl lg:text-4xl font-black uppercase">
                    {{ $pasien->nama_pasien }}
                </h1>
                <p class="mt-3 text-emerald-100">
                    NIK : <span class="font-bold text-white">{{ $pasien->no_identitas }}</span>
                </p>
            </div>

            <form method="GET" action="{{ route('petugas.laporan.diagnosa.show', $pasien->id) }}">
                <input type="hidden" name="tanggal" value="{{ request('tanggal') }}">
                <button type="submit" name="download" value="excel"
                        class="inline-flex items-center gap-2 px-6 py-4 bg-emerald-500 hover:bg-emerald-600 rounded-2xl font-extrabold text-white shadow-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export Excel
                </button>
            </form>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5 mb-8 shadow-sm">
        <form method="GET" action="{{ route('petugas.laporan.diagnosa.show', $pasien->id) }}" class="flex flex-col md:flex-row gap-3 items-end">
            <div>
                <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Filter Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>
            <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold transition">
                Filter
            </button>
            @if(request('tanggal'))
                <a href="{{ route('petugas.laporan.diagnosa.show', $pasien->id) }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 border border-slate-300 rounded-xl font-bold transition">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Table Section --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-black text-slate-800">Riwayat Rekam Medis</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-900 text-white uppercase text-xs text-left">
                        <th class="px-6 py-4 text-center">No</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Keluhan</th>
                        <th class="px-6 py-4">Diagnosis</th>
                        <th class="px-6 py-4">Tindakan</th>
                        <th class="px-6 py-4">Resep</th>
                        <th class="px-6 py-4">Dokter</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($riwayat as $item)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-5 text-center font-bold">
                                {{ ($riwayat->currentPage()-1) * $riwayat->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-5">
                                <div class="font-bold">{{ $item->created_at->translatedFormat('d F Y') }}</div>
                                <div class="text-xs text-slate-500">{{ $item->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td class="px-6 py-5">{{ $item->rekamMedis?->keluhan ?? '-' }}</td>
                            <td class="px-6 py-5">{{ $item->rekamMedis?->diagnosis ?? '-' }}</td>
                            <td class="px-6 py-5">{{ $item->rekamMedis?->tindakan ?? '-' }}</td>
                            <td class="px-6 py-5 whitespace-pre-line">{{ $item->rekamMedis?->resep ?? '-' }}</td>
                            <td class="px-6 py-5">{{ $item->dokter?->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-16 text-slate-500">Belum ada riwayat rekam medis.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($riwayat->hasPages())
            <div class="p-5 border-t border-slate-200">
                {{ $riwayat->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
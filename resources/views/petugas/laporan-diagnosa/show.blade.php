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
            Kembali
        </a>
        <span class="text-xs font-bold text-slate-500 uppercase bg-slate-200 px-3 py-1 rounded">
            ID Pasien #{{ $pasien->id }}
        </span>
    </div>

    {{-- Info Pasien --}}
    <div class="bg-gradient-to-r from-emerald-900 to-slate-900 text-white rounded-3xl p-8 mb-8 shadow-xl">
        <h1 class="text-3xl lg:text-4xl font-black uppercase">{{ $pasien->nama_pasien }}</h1>
        <p class="mt-3 text-emerald-100">NIK : <span class="font-bold text-white">{{ $pasien->no_identitas }}</span></p>
    </div>

    {{-- Tabel Riwayat --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-slate-200 flex justify-between items-center">
            <h2 class="text-xl font-black text-slate-800">Riwayat Rekam Medis</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] tracking-widest">
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Keluhan</th>
                        <th class="px-6 py-4">Vital Sign</th>
                        <th class="px-6 py-4">Diagnosis & Tindakan</th>
                        <th class="px-6 py-4">Resep</th>
                        <th class="px-6 py-4">Dokter</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($riwayat as $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800">{{ $item->created_at->format('d M Y') }}</div>
                            <div class="text-xs text-slate-400">{{ $item->created_at->format('H:i') }} WIB</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded-lg text-xs font-medium">
                                {{ $item->keluhan ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1 text-[11px] font-bold text-slate-600">
                                {{-- Mengambil langsung dari $item (tabel pendaftaran_poli) --}}
                                <span>❤️ Tensi: {{ $item->tensi ?? '-' }}</span>
                                <span>⚖️ BB: {{ $item->berat_badan ?? '-' }} kg</span>
                                <span>📏 TB: {{ $item->tinggi_badan ?? '-' }} cm</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 max-w-[200px]">
                            <div class="font-bold text-emerald-700 text-xs">{{ $item->rekamMedis?->diagnosis ?? '-' }}</div>
                            <div class="text-xs text-slate-500 mt-1 italic">{{ $item->rekamMedis?->tindakan ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-slate-600 bg-amber-50 p-2 rounded-lg border border-amber-100 italic">
                                {{ $item->rekamMedis?->resep ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-slate-700 font-semibold">{{ $item->dokter?->name ?? '-' }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-16 text-slate-400 italic">Belum ada riwayat rekam medis.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
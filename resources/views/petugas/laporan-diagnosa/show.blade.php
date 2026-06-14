@extends('layouts.petugas')

@section('content')
<style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>

<div class="p-6 lg:p-8 bg-slate-50 min-h-screen">
    {{-- Header & Export --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 uppercase">Riwayat Rekam Medis</h1>
            <p class="text-slate-500 font-medium">{{ $pasien->nama_pasien }} • NIK: {{ $pasien->no_identitas }}</p>
        </div>
        <a href="{{ route('petugas.laporan.diagnosa.show', [$pasien->id, 'download' => 'excel']) }}" 
           class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl font-bold transition shadow-lg shadow-emerald-200">
            Export Excel
        </a>
    </div>

    {{-- Tabel Utama --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-widest border-b border-slate-100">
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Keluhan</th>
                        <th class="px-6 py-4">Vital Sign</th>
                        <th class="px-6 py-4">Diagnosis & Tindakan</th>
                        <th class="px-6 py-4">Resep Obat</th>
                        <th class="px-6 py-4">Dokter</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($riwayat as $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800">{{ $item->created_at->format('d M Y') }}</div>
                            <div class="text-[11px] text-slate-400">{{ $item->created_at->format('H:i') }} WIB</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                {{ $item->keluhan ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1 text-[11px] font-bold text-slate-700">
                                <span class="text-rose-500">❤️ Tensi: {{ $item->tensi ?? '-' }}</span>
                                <span class="text-sky-600">⚖️ BB: {{ $item->berat_badan ?? '-' }} kg</span>
                                <span class="text-amber-600">📏 TB: {{ $item->tinggi_badan ?? '-' }} cm</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-emerald-700 text-xs">{{ $item->rekamMedis?->diagnosis ?? '-' }}</div>
                            <div class="text-[11px] text-slate-500 mt-1 italic">{{ $item->rekamMedis?->tindakan ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-slate-600 bg-amber-50 p-3 rounded-xl border border-amber-100 italic">
                                {{ $item->rekamMedis?->resep ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs font-semibold text-slate-700">
                            {{ $item->dokter?->name ?? 'Admin' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-16 text-slate-400 italic">Belum ada riwayat medis tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($riwayat->hasPages())
            <div class="p-6 border-t border-slate-100">
                {{ $riwayat->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
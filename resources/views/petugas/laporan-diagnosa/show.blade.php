@extends('layouts.petugas')

@section('content')
<style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>

<div class="p-6 lg:p-8 bg-slate-50 min-h-screen">
    
    {{-- Navigasi Kembali --}}
    <div class="mb-6">
        <a href="{{ route('petugas.laporan.diagnosa') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-emerald-700 transition">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar Pasien
        </a>
    </div>

    {{-- Header Laporan yang Aesthetic --}}
    <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl p-8 mb-8 text-white shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500 rounded-full mix-blend-overlay filter blur-3xl opacity-20 -mr-16 -mt-16"></div>
        
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 relative z-10">
            <div>
                <div class="flex items-center gap-2 text-emerald-400 text-xs font-black uppercase tracking-widest mb-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                    Laporan Rekam Medis Pasien
                </div>
                <h1 class="text-4xl font-black uppercase tracking-tight">{{ $pasien->nama_pasien }}</h1>
                <p class="text-slate-300 font-medium mt-1">NIK: {{ $pasien->no_identitas }} | Poli: {{ $pasien->poli }}</p>
            </div>
            
            <div class="flex gap-3">
                <div class="text-right">
                    <p class="text-[10px] text-slate-400 uppercase font-bold">Total Kunjungan</p>
                    <p class="text-2xl font-black text-emerald-400">{{ $riwayat->total() }}</p>
                </div>
                <a href="{{ route('petugas.laporan.diagnosa.show', [$pasien->id, 'download' => 'excel']) }}" 
                   class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 px-6 py-4 rounded-2xl font-black text-xs transition shadow-lg shadow-emerald-900/50">
                    UNDUH EXCEL
                </a>
            </div>
        </div>
    </div>

    {{-- Tabel Riwayat --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-bold text-slate-800">Riwayat Perawatan Terakhir</h2>
            <form method="GET" action="{{ route('petugas.laporan.diagnosa.show', $pasien->id) }}" class="flex gap-2">
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="text-xs bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 outline-none">
                <button type="submit" class="bg-slate-900 text-white text-xs font-bold px-4 py-2 rounded-lg">FILTER</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-[10px] uppercase tracking-widest">
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Keluhan</th>
                        <th class="px-6 py-4">Vital Sign (Tensi/BB/TB)</th>
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
                            <div class="text-[10px] text-slate-400 font-bold">{{ $item->created_at->format('H:i') }} WIB</div>
                        </td>
                        <td class="px-6 py-4"><span class="text-xs bg-slate-100 px-2 py-1 rounded-md">{{ $item->keluhan ?? '-' }}</span></td>
                        <td class="px-6 py-4">
                            <div class="text-[10px] font-bold space-y-1">
                                <div><span class="text-rose-500">T:</span> {{ $item->tensi ?? '-' }}</div>
                                <div><span class="text-sky-500">BB:</span> {{ $item->berat_badan ?? '-' }}kg</div>
                                <div><span class="text-amber-500">TB:</span> {{ $item->tinggi_badan ?? '-' }}cm</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs font-bold text-emerald-700">{{ $item->rekamMedis?->diagnosis ?? '-' }}</div>
                            <div class="text-[10px] text-slate-500 mt-0.5 italic">{{ $item->rekamMedis?->tindakan ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-[11px] text-slate-600 bg-amber-50 rounded italic">{{ $item->rekamMedis?->resep ?? '-' }}</td>
                        <td class="px-6 py-4 text-xs font-bold text-slate-700">{{ $item->dokter?->name ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-10 text-slate-400 italic">Data tidak ditemukan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
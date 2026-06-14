@extends('layouts.petugas')

@section('content')
<div class="p-4 md:p-8 bg-slate-50 min-h-screen">
    
    {{-- Tombol Kembali --}}
    <div class="mb-6">
        <a href="{{ route('petugas.laporan.diagnosa') }}" class="inline-flex items-center text-sm font-semibold text-slate-600 hover:text-emerald-700 transition">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar Laporan Utama
        </a>
    </div>

    {{-- Card Header Pasien --}}
    <div class="bg-[#0f2d26] rounded-3xl p-8 mb-6 text-white shadow-xl relative overflow-hidden">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <span class="px-3 py-1 bg-emerald-500/20 text-emerald-300 text-[10px] font-bold uppercase tracking-wider rounded-full">POLI ASAL: {{ $pasien->poli }}</span>
                <h1 class="text-3xl font-black mt-3 uppercase">{{ $pasien->nama_pasien }}</h1>
                <p class="text-emerald-100/80 font-medium">Nomor Identitas NIK: {{ $pasien->no_identitas }}</p>
            </div>
            
            {{-- Tombol Export Excel --}}
            <a href="{{ route('petugas.laporan.diagnosa.show', [$pasien->id, 'download' => 'excel']) }}" 
               class="flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 px-6 py-3 rounded-2xl font-bold text-sm transition shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export Riwayat Pasien (Excel)
            </a>
        </div>
        <div class="absolute top-0 right-0 p-4 bg-white/10 rounded-bl-3xl text-xs font-bold">ID PASIEN: #{{ $pasien->id }}</div>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-3xl border border-slate-200 p-6 mb-6 shadow-sm">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Saring Riwayat Tanggal</p>
        <form method="GET" action="{{ route('petugas.laporan.diagnosa.show', $pasien->id) }}" class="flex gap-3">
            <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-8 py-2.5 rounded-2xl font-bold text-sm transition">FILTER</button>
        </form>
    </div>

    {{-- Tabel Riwayat --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-900 text-white text-[10px] uppercase tracking-widest">
                        <th class="px-6 py-5 w-20">NO</th>
                        <th class="px-6 py-5">WAKTU KUNJUNGAN</th>
                        <th class="px-6 py-5">KELUHAN UTAMA</th>
                        <th class="px-6 py-5">TANDA-TANDA VITAL</th>
                        <th class="px-6 py-5">DIAGNOSIS & TINDAKAN</th>
                        <th class="px-6 py-5">RESEP OBAT</th>
                        <th class="px-6 py-5">DOKTER</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($riwayat as $index => $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-6 font-bold text-slate-400">{{ $index + 1 }}</td>
                        <td class="px-6 py-6">
                            <div class="font-bold text-slate-800">{{ $item->created_at->format('d M Y') }}</div>
                            <div class="text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md text-[10px] inline-block font-bold mt-1">
                                {{ $item->created_at->format('H:i') }} WIB
                            </div>
                        </td>
                        <td class="px-6 py-6 text-sm text-slate-600 italic">
                            {{ $item->keluhan ?? 'Tidak ada keluhan tertulis' }}
                        </td>
                        <td class="px-6 py-6">
                            <div class="space-y-1.5">
                                <div class="bg-slate-50 border rounded-lg px-3 py-1.5 text-[11px] font-bold text-slate-600">TENSI: <span class="text-emerald-700">{{ $item->tensi ?? '-' }} mmHg</span></div>
                                <div class="bg-slate-50 border rounded-lg px-3 py-1.5 text-[11px] font-bold text-slate-600">BERAT: <span class="text-emerald-700">{{ $item->berat_badan ?? '-' }} kg</span></div>
                                <div class="bg-slate-50 border rounded-lg px-3 py-1.5 text-[11px] font-bold text-slate-600">TINGGI: <span class="text-emerald-700">{{ $item->tinggi_badan ?? '-' }} cm</span></div>
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <div class="bg-rose-50 border border-rose-100 rounded-xl p-3 mb-2">
                                <p class="text-[9px] font-bold text-rose-800 uppercase">Diagnosis Utama</p>
                                <p class="text-xs font-bold text-rose-900">{{ $item->rekamMedis?->diagnosis ?? '-' }}</p>
                            </div>
                            <div class="bg-blue-50 border border-blue-100 rounded-xl p-3">
                                <p class="text-[9px] font-bold text-blue-800 uppercase">Tindakan Klinis</p>
                                <p class="text-xs font-bold text-blue-900">{{ $item->rekamMedis?->tindakan ?? '-' }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-6 text-xs text-slate-500 italic bg-slate-50 rounded-xl">
                            {{ $item->rekamMedis?->resep ?? 'Tidak ada resep obat' }}
                        </td>
                        <td class="px-6 py-6 text-xs font-bold text-slate-700">
                            {{ $item->dokter?->name ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-10 text-slate-400">Data tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
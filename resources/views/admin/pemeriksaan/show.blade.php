@extends('layouts.admin')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
</style>

<div class="p-6 lg:p-8 bg-slate-50 min-h-screen font-sans">

    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-8 gap-6">
        <div>
            <a href="{{ route('admin.pemeriksaan') }}" class="inline-flex items-center gap-2 text-sm font-bold text-emerald-600 hover:text-emerald-700 transition-colors mb-4 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar Laporan
            </a>
            <h1 class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">
                Riwayat Medis: <span class="text-emerald-600 uppercase">{{ $pasien->nama_pasien }}</span>
            </h1>
            <p class="text-slate-500 font-medium mt-2 text-base">
                NIK Pasien: <span class="font-mono font-bold text-slate-700">{{ $pasien->no_identitas }}</span> | Poliklinik: <span class="font-bold text-slate-700">{{ $pasien->poli }}</span>
            </p>
        </div>

        {{-- Download Excel Khusus Pasien Terpilih --}}
        <form method="GET" action="{{ route('admin.pemeriksaan.show', $pasien->id) }}">
            <input type="hidden" name="tanggal" value="{{ request('tanggal') }}">
            <button type="submit" name="download" value="1" 
                class="w-full md:w-auto flex items-center justify-center gap-2.5 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3.5 rounded-xl shadow-md transition-all font-bold text-base active:scale-95 border border-emerald-700 group cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:-translate-y-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Riwayat (Excel)
            </button>
        </form>
    </div>

    {{-- FILTER TANGGAL RIWAYAT --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
        <form method="GET" action="{{ route('admin.pemeriksaan.show', $pasien->id) }}" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:w-64">
                <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Cari Berdasarkan Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" 
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none cursor-pointer">
            </div>
            <div class="flex gap-2 w-full md:w-auto">
                <button type="submit" class="flex-1 md:flex-none bg-slate-800 text-white px-6 py-3 rounded-xl font-bold hover:bg-slate-900 transition-colors cursor-pointer">
                    Filter
                </button>
                @if(request('tanggal'))
                    <a href="{{ route('admin.pemeriksaan.show', $pasien->id) }}" class="px-4 py-3 bg-slate-100 text-slate-600 rounded-xl border border-slate-300 flex items-center justify-center hover:bg-slate-200" title="Clear Filter Tanggal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- KRONOLOGIS TABLE REKAM MEDIS PASIEN --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1200px]">
                <thead>
                    <tr class="bg-slate-900 text-white text-sm uppercase tracking-widest font-bold">
                        <th class="py-5 px-6 w-48 text-center rounded-tl-xl">Waktu Periksa</th>
                        <th class="py-5 px-6 min-w-[200px]">Keluhan Utama</th>
                        <th class="py-5 px-6 min-w-[180px] text-center">Tanda-Tanda Vital</th>
                        <th class="py-5 px-6 min-w-[280px]">Diagnosis & Tindakan</th>
                        <th class="py-5 px-6 min-w-[220px]">Resep Obat</th>
                        <th class="py-5 px-6 min-w-[150px] rounded-tr-xl">Dokter Pemeriksa</th>
                    </tr>
                </thead>
                <tbody class="text-base divide-y divide-slate-200">
                    @forelse($riwayat as $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        
                        {{-- WAKTU PERIKSA --}}
                        <td class="py-6 px-6 align-middle text-center">
                            <p class="font-extrabold text-slate-800">{{ $item->created_at->translatedFormat('d M Y') }}</p>
                            <span class="text-xs font-bold font-mono text-slate-400 block mt-1">{{ $item->created_at->format('H:i') }} WIB</span>
                        </td>
                        
                        {{-- KELUHAN --}}
                        <td class="py-6 px-6 align-middle italic text-slate-600 font-medium">
                            "{{ $item->keluhan ?? 'Tidak ada keluhan' }}"
                        </td>
                        
                        {{-- TANDA VITAL (TENSI, BB, TB) --}}
                        <td class="py-6 px-6 align-middle">
                            <div class="grid grid-cols-1 gap-2">
                                <div class="flex items-center justify-between text-xs font-bold border border-slate-200 p-1.5 rounded-lg bg-slate-50">
                                    <span class="text-slate-500 uppercase tracking-wider">Tensi:</span>
                                    <span class="text-slate-800 font-mono font-black">
                                        {{ $item->tensi ?? '-' }} <span class="text-[10px] text-slate-400 font-normal">mmHg</span>
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-xs font-bold border border-slate-200 p-1.5 rounded-lg bg-slate-50">
                                    <span class="text-slate-500 uppercase tracking-wider">Berat:</span>
                                    <span class="text-slate-800 font-mono font-black">
                                        {{ $item->bb ?? '-' }} <span class="text-[10px] text-slate-400 font-normal">kg</span>
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-xs font-bold border border-slate-200 p-1.5 rounded-lg bg-slate-50">
                                    <span class="text-slate-500 uppercase tracking-wider">Tinggi:</span>
                                    <span class="text-slate-800 font-mono font-black">
                                        {{ $item->tb ?? '-' }} <span class="text-[10px] text-slate-400 font-normal">cm</span>
                                    </span>
                                </div>
                            </div>
                        </td>
                        
                        {{-- DIAGNOSIS & TINDAKAN --}}
                        <td class="py-6 px-6 align-middle">
                            <div class="space-y-2">
                                <div class="p-2.5 rounded-lg bg-rose-50 border border-rose-100 text-sm">
                                    <span class="text-[10px] font-black text-rose-600 block uppercase tracking-wider mb-0.5">Diagnosis</span>
                                    <p class="font-extrabold text-slate-800">{{ $item->diagnosis }}</p>
                                </div>
                                <div class="p-2.5 rounded-lg bg-blue-50 border border-blue-100 text-sm">
                                    <span class="text-[10px] font-black text-blue-600 block uppercase tracking-wider mb-0.5">Tindakan</span>
                                    <p class="font-semibold text-slate-700">{{ $item->tindakan }}</p>
                                </div>
                            </div>
                        </td>
                        
                        {{-- RESEP OBAT --}}
                        <td class="py-6 px-6 align-middle">
                            @if($item->resep)
                                <div class="flex flex-wrap gap-1.5">
                                    @php $obatArray = preg_split('/[\n,]+/', $item->resep); @endphp
                                    @foreach($obatArray as $obat)
                                        @if(trim($obat) !== "")
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 border border-slate-300 text-xs font-extrabold text-slate-700 rounded-md uppercase tracking-wide">
                                                {{ trim($obat) }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <span class="text-xs text-slate-400 font-bold italic">Tanpa Resep</span>
                            @endif
                        </td>
                        
                        {{-- DOKTER --}}
                        <td class="py-6 px-6 align-middle font-bold text-slate-700">
                            Dr. {{ $item->dokter->name ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-20 text-center text-slate-400 font-medium italic">
                            Belum ada riwayat rekam medis tercatat pada parameter tanggal ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
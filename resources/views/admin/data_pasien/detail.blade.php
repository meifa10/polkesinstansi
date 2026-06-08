@extends('layouts.admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    summary { list-style: none; }
    summary::-webkit-details-marker { display: none; }
    details[open] summary .accordion-icon { transform: rotate(180deg); }
</style>

<div class="p-6 lg:p-8 bg-slate-50 min-h-screen">
    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-8 gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-sm font-bold uppercase mb-3 border border-emerald-300">
                <a href="{{ route('admin.data_pasien.index') }}" class="hover:text-emerald-600">Data Pasien</a>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                Profil Lengkap
            </div>
            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">Detail <span class="text-emerald-600">Pasien</span></h1>
        </div>
        <a href="{{ route('admin.data_pasien.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-slate-700 border-2 border-slate-300 rounded-xl text-sm font-bold hover:bg-slate-100 transition-all active:scale-95">Kembali</a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
        {{-- KIRI: PROFIL & TOTAL --}}
        <div class="xl:col-span-4 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border p-8">
                <h2 class="text-2xl font-extrabold text-slate-900 uppercase">{{ $pasien->nama_pasien }}</h2>
                <div class="mt-4 space-y-4">
                    <p class="text-sm text-slate-500 font-bold">NO. IDENTITAS: {{ $pasien->no_identitas }}</p>
                    <p class="text-sm text-slate-500 font-bold">TGL LAHIR: {{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->translatedFormat('d F Y') }}</p>
                </div>
            </div>
            <div class="bg-slate-900 rounded-2xl p-8 text-white">
                <p class="text-xs font-black text-slate-400 uppercase">Total Kunjungan</p>
                <h3 class="text-5xl font-black">{{ $kunjungan->count() }} <span class="text-lg text-emerald-400">KALI</span></h3>
            </div>
        </div>

        {{-- KANAN: RIWAYAT KUNJUNGAN & MEDIS --}}
        <div class="xl:col-span-8 space-y-8">
            
            {{-- 1. RIWAYAT KUNJUNGAN --}}
            <section class="bg-white rounded-2xl shadow-sm border p-6">
                <h2 class="text-xl font-bold mb-6 border-b pb-4">Status Transaksi & Kunjungan</h2>
                <div class="space-y-4">
                    @forelse($kunjungan as $k)
                    <details class="group bg-slate-50 border rounded-xl overflow-hidden">
                        <summary class="p-5 flex justify-between cursor-pointer hover:bg-slate-100">
                            <div><p class="font-bold">{{ $k->created_at->translatedFormat('d M Y') }}</p><span class="text-[10px] font-black bg-slate-200 px-2 rounded">{{ $k->poli }}</span></div>
                            <div class="accordion-icon transition-transform"><svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" /></svg></div>
                        </summary>
                        <div class="p-5 border-t bg-white">Status: {{ strtoupper($k->pembayaran->status ?? 'Belum Ada') }}</div>
                    </details>
                    @empty
                    <p class="text-center py-4 text-slate-400">Belum ada kunjungan.</p>
                    @endforelse
                </div>
            </section>

            {{-- 2. RIWAYAT KLINIS & REKAM MEDIS --}}
            <section class="bg-white rounded-2xl shadow-sm border p-6">
                <h2 class="text-xl font-bold mb-6 border-b pb-4">Riwayat Klinis & Rekam Medis</h2>
                <div class="space-y-4">
                    @forelse($rekamMedis as $rm)
                    <details class="group bg-blue-50 border border-blue-100 rounded-xl overflow-hidden">
                        <summary class="p-5 flex justify-between cursor-pointer hover:bg-blue-100">
                            <div>
                                <p class="font-bold text-blue-900">{{ $rm->created_at->translatedFormat('d M Y') }}</p>
                                <p class="text-[10px] font-black text-blue-600 uppercase">Dokter: {{ optional(optional($rm->pendaftaran)->dokter)->name ?: 'Tidak Diketahui' }}</p>
                            </div>
                            <div class="accordion-icon transition-transform text-blue-600"><svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" /></svg></div>
                        </summary>
                        <div class="p-6 bg-white border-t space-y-4">
                            <p class="text-sm font-bold italic text-slate-700">Keluhan: "{{ $rm->keluhan ?? optional($rm->pendaftaran)->keluhan ?? '-' }}"</p>
                            <div class="grid grid-cols-3 gap-2">
                                <div class="bg-slate-50 p-2 rounded text-center"><p class="text-[10px] font-bold text-slate-400">BB</p><p class="font-bold">{{ optional($rm->pendaftaran)->bb ?? '-' }} kg</p></div>
                                <div class="bg-slate-50 p-2 rounded text-center"><p class="text-[10px] font-bold text-slate-400">TB</p><p class="font-bold">{{ optional($rm->pendaftaran)->tb ?? '-' }} cm</p></div>
                                <div class="bg-slate-50 p-2 rounded text-center"><p class="text-[10px] font-bold text-slate-400">Tensi</p><p class="font-bold">{{ optional($rm->pendaftaran)->tensi ?? '-' }}</p></div>
                            </div>
                            <p class="text-sm"><strong class="text-rose-600">Diagnosis:</strong> {{ $rm->diagnosis }}</p>
                            <p class="text-sm"><strong class="text-blue-600">Tindakan:</strong> {{ $rm->tindakan }}</p>
                        </div>
                    </details>
                    @empty
                    <div class="py-10 text-center border-2 border-dashed rounded-xl">Belum ada rekam medis.</div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
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
    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-8 gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-sm font-bold uppercase mb-3 border border-emerald-300">
                <a href="{{ route('admin.data_pasien.index') }}" class="hover:text-emerald-600">Data Pasien</a>
                <span>/</span> Profil Lengkap
            </div>
            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">Detail <span class="text-emerald-600">Pasien</span></h1>
        </div>
        <a href="{{ route('admin.data_pasien.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-slate-700 border-2 border-slate-300 rounded-xl text-sm font-bold hover:bg-slate-100 transition-all">Kembali</a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
        {{-- KIRI --}}
        <div class="xl:col-span-4 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border p-8">
                <h2 class="text-2xl font-extrabold text-slate-900 uppercase">{{ $pasien->nama_pasien }}</h2>
                <div class="mt-4 space-y-2 text-sm text-slate-600">
                    <p><span class="font-bold text-slate-400">IDENTITAS:</span> {{ $pasien->no_identitas }}</p>
                    <p><span class="font-bold text-slate-400">LAHIR:</span> {{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->translatedFormat('d F Y') }}</p>
                </div>
            </div>
            <div class="bg-slate-900 rounded-2xl p-8 text-white">
                <p class="text-xs font-black text-slate-400 uppercase">Total Kunjungan</p>
                <h3 class="text-5xl font-black">{{ $kunjungan->count() }} <span class="text-lg text-emerald-400">KALI</span></h3>
            </div>
        </div>

        {{-- KANAN --}}
        <div class="xl:col-span-8 space-y-8">
            {{-- SEKSI REKAM MEDIS --}}
            <section class="bg-white rounded-2xl shadow-sm border p-6">
                <h2 class="text-xl font-bold mb-6 border-b pb-4">Riwayat Klinis & Rekam Medis</h2>
                <div class="space-y-4">
                    @forelse($rekamMedis as $rm)
                    <details class="group bg-slate-50 border rounded-xl overflow-hidden shadow-sm" {{ $loop->first ? 'open' : '' }}>
                        <summary class="p-5 flex justify-between items-center cursor-pointer hover:bg-slate-100">
                            <div>
                                <p class="font-bold text-slate-900">{{ $rm->created_at->translatedFormat('d M Y') }}</p>
                                <span class="text-[10px] font-black text-emerald-600 uppercase">Poli: {{ $rm->pendaftaran->poli ?? '-' }}</span>
                            </div>
                            <div class="accordion-icon text-slate-400"><svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" /></svg></div>
                        </summary>
                        <div class="p-6 bg-white border-t space-y-6">
                            {{-- VITAL SIGNS (PENGAMBILAN DARI PEMERIKSAAN AWAL/PENDAFTARAN) --}}
                            <div class="grid grid-cols-3 gap-3">
                                <div class="bg-slate-50 p-3 rounded-xl border text-center">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase">BB</p>
                                    <p class="font-black text-slate-800">{{ $rm->berat_badan ?? $rm->pendaftaran->berat_badan ?? $rm->pendaftaran->bb ?? '-' }} <span class="text-[10px]">kg</span></p>
                                </div>
                                <div class="bg-slate-50 p-3 rounded-xl border text-center">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase">TB</p>
                                    <p class="font-black text-slate-800">{{ $rm->tinggi_badan ?? $rm->pendaftaran->tinggi_badan ?? $rm->pendaftaran->tb ?? '-' }} <span class="text-[10px]">cm</span></p>
                                </div>
                                <div class="bg-slate-50 p-3 rounded-xl border text-center">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase">Tensi</p>
                                    <p class="font-black text-slate-800">{{ $rm->tekanan_darah ?? $rm->pendaftaran->tekanan_darah ?? $rm->pendaftaran->tensi ?? '-' }}</p>
                                </div>
                            </div>
                            
                            {{-- KELUHAN & DIAGNOSA --}}
                            <div class="space-y-4">
                                <div><p class="text-xs font-bold text-slate-400 uppercase">Keluhan</p><p class="text-sm font-medium italic">"{{ $rm->keluhan ?? $rm->pendaftaran->keluhan ?? '-' }}"</p></div>
                                <div><p class="text-xs font-bold text-slate-400 uppercase">Diagnosis</p><p class="text-sm font-bold text-rose-600">{{ $rm->diagnosis ?? '-' }}</p></div>
                            </div>
                        </div>
                    </details>
                    @empty
                    <p class="text-center py-10 text-slate-400 italic">Belum ada riwayat rekam medis.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
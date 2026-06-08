@extends('layouts.admin')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    summary { list-style: none; }
    summary::-webkit-details-marker { display: none; }
    details[open] summary .accordion-icon { transform: rotate(180deg); }
</style>

<div class="p-6 lg:p-8 bg-slate-50 min-h-screen font-sans">

    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-8 gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-sm font-bold tracking-wide uppercase mb-3 border border-emerald-300">
                <a href="{{ route('admin.data_pasien.index') }}" class="hover:text-emerald-600 transition-colors">Data Pasien</a>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                Profil Lengkap
            </div>
            <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight">
                Detail <span class="text-emerald-600">Pasien</span>
            </h1>
        </div>

        <a href="{{ route('admin.data_pasien.index') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-white text-slate-700 border-2 border-slate-300 rounded-xl text-sm font-bold hover:bg-slate-100 transition-all shadow-sm active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
        <div class="xl:col-span-4 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden relative">
                <div class="h-32 bg-slate-900 w-full relative">
                    <div class="absolute -bottom-12 left-8">
                        <div class="w-24 h-24 bg-emerald-100 text-emerald-700 rounded-2xl border-4 border-white shadow-sm flex items-center justify-center text-4xl font-black">
                            {{ strtoupper(substr($pasien->nama_pasien, 0, 1)) }}
                        </div>
                    </div>
                </div>
                <div class="px-8 pt-16 pb-8">
                    <h2 class="text-2xl font-extrabold text-slate-900 uppercase tracking-tight">{{ $pasien->nama_pasien }}</h2>
                    <span class="inline-flex px-3 py-1 mt-2 rounded-md {{ strtolower($pasien->jenis_pasien) == 'jkn' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }} text-xs font-black uppercase tracking-wider border shadow-sm">
                        Pasien {{ $pasien->jenis_pasien }}
                    </span>
                    <div class="mt-8 space-y-6 border-t border-slate-100 pt-6">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest leading-none mb-1">Nomor Identitas</p>
                        <p class="text-base font-extrabold text-slate-800 font-mono tracking-tight">{{ $pasien->no_identitas }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="xl:col-span-8 space-y-8">
            <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
                <h2 class="text-xl font-bold text-slate-800 mb-6 pb-5 border-b border-slate-100">Riwayat Klinis & Rekam Medis</h2>
                <div class="space-y-4">
                    @forelse($kunjungan as $k)
                    @php
                        // Mengambil biaya dari record pembayaran jika ada
                        $biayaDokter = (int)($k->pembayaran->biaya_dokter ?? 0);
                        $biayaAdmin = (int)($k->pembayaran->biaya_admin ?? 0);
                        $totalObat = (int)($k->pembayaran->total_obat ?? 0);
                        $totalBersih = $biayaDokter + $biayaAdmin + $totalObat;
                    @endphp
                    <details class="group bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm" {{ $loop->first ? 'open' : '' }}>
                        <summary class="flex justify-between items-center cursor-pointer p-5 bg-slate-50 hover:bg-slate-100 transition-colors">
                            <div>
                                <p class="text-base font-extrabold text-slate-900">{{ $k->created_at->translatedFormat('d M Y') }}</p>
                                <span class="text-[10px] font-black uppercase text-slate-500">{{ $k->poli }}</span>
                            </div>
                            <div class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-200 text-slate-500 accordion-icon transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </summary>
                        <div class="p-6 border-t border-slate-200 space-y-4">
                            <p class="text-sm font-bold text-slate-700">Diagnosis: {{ $k->rekamMedis->diagnosis ?? '-' }}</p>
                            <p class="text-sm text-slate-600">Tindakan: {{ $k->rekamMedis->tindakan ?? '-' }}</p>
                            
                            <div class="bg-slate-50 p-4 rounded-lg border border-slate-200 text-xs font-bold text-slate-800">
                                <div class="flex justify-between mb-1"><span>Jasa Dokter:</span> <span>Rp {{ number_format($biayaDokter, 0, ',', '.') }}</span></div>
                                <div class="flex justify-between mb-1"><span>Administrasi:</span> <span>Rp {{ number_format($biayaAdmin, 0, ',', '.') }}</span></div>
                                <div class="flex justify-between border-t border-slate-200 pt-1 mt-1"><span>TOTAL:</span> <span>Rp {{ number_format($totalBersih, 0, ',', '.') }}</span></div>
                            </div>
                        </div>
                    </details>
                    @empty
                    <p class="text-slate-500 text-center py-6">Tidak ada riwayat medis.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
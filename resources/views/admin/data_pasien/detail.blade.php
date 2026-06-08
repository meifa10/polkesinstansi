@extends('layouts.admin')

@section('content')
<div class="p-4 lg:p-8 bg-slate-50 min-h-screen font-sans">

    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8 gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold tracking-wide uppercase mb-3 border border-emerald-300">
                <a href="{{ route('admin.data_pasien.index') }}" class="hover:text-emerald-600">Data Pasien</a>
                <span>/</span>
                <span>Profil Lengkap</span>
            </div>
            <h1 class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">
                Detail <span class="text-emerald-600">Pasien</span>
            </h1>
        </div>
        <a href="{{ route('admin.data_pasien.index') }}" 
           class="flex items-center justify-center gap-2 px-6 py-2.5 bg-white text-slate-700 border border-slate-300 rounded-xl text-sm font-bold hover:bg-slate-50 transition-all shadow-sm">
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        
        {{-- KIRI: PROFIL UTAMA --}}
        <div class="xl:col-span-4 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="h-24 bg-slate-900"></div>
                <div class="px-6 pb-6">
                    <div class="-mt-12 mb-4">
                        <div class="w-20 h-20 bg-emerald-500 text-white rounded-2xl border-4 border-white flex items-center justify-center text-3xl font-black shadow-md">
                            {{ strtoupper(substr($pasien->nama_pasien, 0, 1)) }}
                        </div>
                    </div>
                    <h2 class="text-xl font-extrabold text-slate-900 uppercase">{{ $pasien->nama_pasien }}</h2>
                    <span class="inline-block mt-2 px-3 py-1 rounded-md bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase border border-emerald-200">
                        Pasien {{ $pasien->jenis_pasien }}
                    </span>

                    <div class="mt-6 space-y-4 pt-6 border-t border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="text-slate-400"><i class="fas fa-id-card"></i></div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">No. Identitas</p>
                                <p class="text-sm font-bold text-slate-800">{{ $pasien->no_identitas }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="text-slate-400"><i class="fas fa-calendar"></i></div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Tanggal Lahir</p>
                                <p class="text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->translatedFormat('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KANAN: DATA RIWAYAT --}}
        <div class="xl:col-span-8 space-y-6">
            
            {{-- SEKSI KUNJUNGAN --}}
            <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-50 text-emerald-600"><i class="fas fa-history"></i></span>
                    Riwayat Kunjungan
                </h2>
                <div class="space-y-3">
                    @forelse($kunjungan as $k)
                    <details class="group border border-slate-200 rounded-xl overflow-hidden">
                        <summary class="flex justify-between items-center cursor-pointer p-4 bg-slate-50 hover:bg-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="text-emerald-600 font-bold text-sm">{{ $k->created_at->translatedFormat('d M Y') }}</span>
                                <span class="px-2 py-0.5 rounded bg-slate-200 text-[10px] font-bold">{{ $k->poli }}</span>
                            </div>
                            <span class="text-slate-400 group-open:rotate-180 transition-transform"><i class="fas fa-chevron-down"></i></span>
                        </summary>
                        <div class="p-4 border-t border-slate-100 text-sm text-slate-600">
                            {{-- Konten detail kunjungan --}}
                            Status: <span class="font-bold {{ $k->pembayaran?->status == 'lunas' ? 'text-emerald-600' : 'text-amber-600' }}">{{ strtoupper($k->pembayaran?->status ?? 'Belum Bayar') }}</span>
                        </div>
                    </details>
                    @empty
                    <p class="text-center text-slate-400 py-4">Belum ada riwayat kunjungan.</p>
                    @endforelse
                </div>
            </section>

        </div>
    </div>
</div>
@endsection
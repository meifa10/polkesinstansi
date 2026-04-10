@extends('layouts.admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
<script src="https://unpkg.com/@phosphor-icons/web"></script>

<div class="p-6 bg-slate-100 min-h-screen font-['Plus_Jakarta_Sans']">

    {{-- ================= HEADER & NAVIGASI ================= --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <nav class="flex text-[11px] text-slate-500 mb-1 gap-1 font-bold uppercase tracking-wider">
                <a href="{{ route('admin.data_pasien.index') }}" class="hover:text-emerald-600 transition-colors">Data Pasien</a>
                <span class="text-slate-400">/</span>
                <span class="text-emerald-700 font-black">Profil Lengkap</span>
            </nav>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                Detail <span class="text-emerald-600">Pasien</span>
            </h1>
        </div>

        <a href="{{ route('admin.data_pasien.index') }}" 
           class="flex items-center gap-2 px-5 py-2.5 bg-white text-slate-700 border border-slate-300 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-50 transition-all active:scale-95 shadow-sm">
            <i class="ph-bold ph-arrow-left"></i>
            Kembali
        </a>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        
        {{-- ================= KIRI: INFORMASI UTAMA ================= --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-[2.5rem] shadow-md border border-slate-200 overflow-hidden">
                <div class="h-24 bg-slate-900 w-full relative">
                    <div class="absolute -bottom-10 left-8">
                        <div class="w-20 h-20 bg-emerald-500 rounded-3xl border-4 border-white shadow-lg flex items-center justify-center text-white text-3xl font-black">
                            {{ strtoupper(substr($pasien->nama_pasien, 0, 1)) }}
                        </div>
                    </div>
                </div>
                
                <div class="px-8 pt-14 pb-8">
                    <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">{{ $pasien->nama_pasien }}</h2>
                    <span class="inline-block px-3 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-black rounded-full border border-emerald-100 uppercase tracking-widest mt-2">
                        Pasien {{ $pasien->jenis_pasien }}
                    </span>

                    <div class="mt-8 space-y-5">
                        <div class="flex items-center gap-4">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500">
                                <i class="ph-fill ph-identification-card text-lg"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Nomor Identitas</p>
                                <p class="text-sm font-bold text-slate-800 font-mono tracking-tighter mt-1">{{ $pasien->no_identitas }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500">
                                <i class="ph-fill ph-cake text-lg"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Tanggal Lahir</p>
                                <p class="text-sm font-bold text-slate-800 mt-1">{{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SUMMARY BOX --}}
            <div class="bg-slate-900 rounded-[2rem] p-6 text-white shadow-xl shadow-slate-200/50 relative overflow-hidden group border-b-8 border-emerald-500">
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Kunjungan</p>
                        <h3 class="text-4xl font-black text-emerald-400">{{ $pasien->total_kunjungan ?? $kunjungan->count() }}x</h3>
                    </div>
                    <i class="ph-fill ph-stethoscope text-6xl text-white/10 absolute -right-2 -bottom-2 group-hover:rotate-12 transition-transform duration-500"></i>
                </div>
            </div>
        </div>

        {{-- ================= KANAN: RIWAYAT AKTIVITAS ================= --}}
        <div class="lg:col-span-2 space-y-8">
            
            {{-- SEKSI KUNJUNGAN & PEMBAYARAN --}}
            <section>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                    <h2 class="text-lg font-black text-slate-900 uppercase tracking-tight">Status Kunjungan & Keuangan</h2>
                </div>

                <div class="space-y-4">
                    @forelse($kunjungan as $k)
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 hover:border-emerald-500 transition-all group">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 border border-slate-100 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                                <i class="ph-bold ph-calendar-check text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-black text-slate-900">{{ $k->created_at->translatedFormat('d M Y') }}</p>
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Layanan: <span class="text-slate-700">{{ $k->poli }}</span></p>
                            </div>
                        </div>

                        <div class="flex flex-col items-end gap-2 w-full md:w-auto">
                            @if($k->pembayaran)
                                @if($k->pembayaran->status === 'lunas')
                                    <div class="flex flex-col items-end">
                                        <span class="px-4 py-1.5 bg-emerald-100 text-emerald-700 rounded-lg text-[10px] font-black uppercase border-2 border-emerald-200">
                                            ✔ LUNAS ({{ $k->pembayaran->metode }})
                                        </span>
                                        <p class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-tighter">
                                            Validasi: {{ \Carbon\Carbon::parse($k->pembayaran->tanggal_bayar)->format('d/m/y H:i') }}
                                        </p>
                                    </div>
                                @else
                                    <div class="flex gap-2">
                                        @if($k->pembayaran->metode === 'bpjs' || $k->pembayaran->metode === 'tunai')
                                            <form method="POST" action="{{ route('admin.pembayaran.lunasi', $k->pembayaran->id) }}">
                                                @csrf
                                                <button class="px-4 py-2 bg-slate-900 hover:bg-emerald-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-md active:scale-95">
                                                    Validasi {{ $k->pembayaran->metode }}
                                                </button>
                                            </form>
                                        @else
                                            <span class="px-4 py-2 bg-amber-50 text-amber-600 rounded-xl text-[10px] font-black uppercase border border-amber-200 italic tracking-tighter">
                                                Menunggu Midtrans
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            @else
                                <a href="{{ route('admin.pembayaran.create', $k->id) }}"
                                   class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-100 transition-all active:scale-95 flex items-center gap-2">
                                    <i class="ph-bold ph-plus"></i> Buat Tagihan
                                </a>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="bg-amber-50 border border-amber-200 p-8 rounded-[2rem] text-center">
                        <i class="ph-bold ph-warning-circle text-4xl text-amber-500 mb-2"></i>
                        <p class="text-sm font-bold text-amber-700 uppercase tracking-widest">Belum ada riwayat kunjungan</p>
                    </div>
                    @endforelse
                </div>
            </section>

            {{-- SEKSI REKAM MEDIS --}}
            <section>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-1.5 h-6 bg-blue-500 rounded-full"></div>
                    <h2 class="text-lg font-black text-slate-900 uppercase tracking-tight">Riwayat Klinis / Rekam Medis</h2>
                </div>

                <div class="space-y-4">
                    @forelse($rekamMedis as $rm)
                    <div class="bg-white rounded-[2rem] border border-slate-300 shadow-sm p-8 group">
                        <div class="flex justify-between items-start mb-6 pb-4 border-b border-slate-100">
                            <div>
                                <p class="text-[11px] font-black text-blue-600 uppercase tracking-widest leading-none">Tgl Periksa</p>
                                <h4 class="text-sm font-black text-slate-900 mt-1">{{ $rm->created_at->translatedFormat('d M Y') }}</h4>
                            </div>
                            <span class="text-[10px] font-black text-slate-400 bg-slate-50 px-3 py-1 rounded-md uppercase border border-slate-200">Poli: {{ $rm->pendaftaran->poli ?? '-' }}</span>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Hasil Diagnosis</p>
                                <p class="text-xs font-bold text-slate-800 leading-relaxed italic">"{{ $rm->diagnosis }}"</p>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Tindakan Medis</p>
                                <p class="text-xs font-bold text-slate-800 leading-relaxed">{{ $rm->tindakan }}</p>
                            </div>
                        </div>

                        @if($rm->resep)
                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Instruksi Resep</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach(explode(',', $rm->resep) as $obat)
                                <span class="px-3 py-1 bg-white border border-slate-300 text-[10px] font-black text-slate-700 rounded-lg shadow-sm uppercase italic">
                                    {{ trim($obat) }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @empty
                    <div class="bg-slate-50 border border-slate-200 p-8 rounded-[2rem] text-center">
                        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Tidak ada data rekam medis</p>
                    </div>
                    @endforelse
                </div>
            </section>

        </div>
    </div>
</div>

<style>
    body { background-color: #f1f5f9; }
    * {
        -webkit-font-smoothing: antialiased;
        text-rendering: optimizeLegibility;
    }
</style>
@endsection
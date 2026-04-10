@extends('layouts.admin')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://unpkg.com/@phosphor-icons/web"></script>

<div class="p-6 bg-slate-50 min-h-screen font-['Plus_Jakarta_Sans']">

    {{-- ================= HEADER SECTION ================= --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight uppercase">
                Laporan <span class="text-emerald-600">Analitik Pasien</span>
            </h1>
            <div class="flex items-center gap-2 mt-2 bg-white px-4 py-1.5 rounded-full border border-slate-200 shadow-sm w-fit">
                <i class="ph-fill ph-calendar-blank text-emerald-500 text-lg"></i>
                <span class="text-xs font-black text-slate-600 uppercase tracking-widest">
                    Periode: 
                    @if($bulan == 'semua' || empty($bulan))
                        Semua Bulan
                    @else
                        {{ \Carbon\Carbon::create(null, (int) $bulan, 1)->translatedFormat('F') }}
                    @endif
                    {{ $tahun }}
                </span>
            </div>
        </div>

        {{-- FILTER FORM --}}
        <form method="GET" class="flex flex-wrap items-center gap-3 bg-white p-3 rounded-2xl shadow-sm border border-slate-300">
            <div class="relative group">
                <i class="ph ph-calendar-check absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <select name="bulan" class="pl-10 pr-8 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm font-bold text-slate-900 focus:border-slate-900 outline-none appearance-none cursor-pointer min-w-[160px]" onchange="this.form.submit()">
                    <option value="semua" {{ $bulan == 'semua' || empty($bulan) ? 'selected' : '' }}>Seluruh Bulan</option>
                    @for($i=1;$i<=12;$i++)
                        <option value="{{ sprintf('%02d',$i) }}" {{ $bulan == sprintf('%02d',$i) ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $i, 1)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="relative group">
                <i class="ph ph-hash absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="number" name="tahun" value="{{ $tahun }}" class="pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm font-bold text-slate-900 w-[110px] focus:border-slate-900 outline-none" placeholder="Tahun" onchange="this.form.submit()">
            </div>

            <a href="{{ route('admin.laporan.pdf',['bulan'=>$bulan,'tahun'=>$tahun]) }}" class="flex items-center gap-2 px-6 py-2.5 bg-red-600 text-white rounded-xl text-xs font-black uppercase tracking-[0.15em] hover:bg-red-700 transition-all shadow-md active:scale-95">
                <i class="ph ph-file-pdf text-lg"></i> Cetak PDF
            </a>
        </form>
    </div>

    {{-- ================= KPI CARDS GRID ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        
        <div class="bg-white p-6 rounded-[2.5rem] shadow-md border border-slate-200 group hover:border-emerald-500 transition-all duration-300">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-3xl group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                    <i class="ph-fill ph-users-three"></i>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Kunjungan</p>
                    <h3 class="text-3xl font-black text-slate-900 leading-none tracking-tighter">{{ $totalKunjungan }}</h3>
                </div>
            </div>
            <div class="flex gap-2">
                <span class="px-3 py-1.5 bg-slate-100 text-slate-700 rounded-lg text-[10px] font-black border border-slate-200 flex items-center gap-1.5 uppercase">
                    BPJS: {{ $bpjs }}
                </span>
                <span class="px-3 py-1.5 bg-slate-100 text-slate-700 rounded-lg text-[10px] font-black border border-slate-200 flex items-center gap-1.5 uppercase">
                    UMUM: {{ $umum }}
                </span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2.5rem] shadow-md border border-slate-200 group hover:border-emerald-500 transition-all duration-300">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-3xl group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                    <i class="ph-fill ph-money"></i>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Pemasukan Finansial</p>
                    <h3 class="text-3xl font-black text-slate-900 leading-none tracking-tighter">
                        <span class="text-emerald-600">Rp</span> {{ number_format($totalPemasukan, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
            <div class="flex gap-4 items-center">
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span class="text-[10px] font-black text-slate-700 uppercase">Lunas: {{ $lunas }}</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    <span class="text-[10px] font-black text-slate-700 uppercase">Pending: {{ $belumLunas }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2.5rem] shadow-md border border-slate-200 group hover:border-emerald-500 transition-all duration-300">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-3xl group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                    <i class="ph-fill ph-stethoscope"></i>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Aktivitas Medis</p>
                    <h3 class="text-3xl font-black text-slate-900 leading-none tracking-tighter">{{ $totalPemeriksaan }}</h3>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-emerald-600 font-black text-[10px] uppercase tracking-tighter">
                <i class="ph ph-trend-up"></i>
                <span>Total tindakan medis tercatat</span>
            </div>
        </div>
    </div>

    {{-- ================= DETAILS GRID ================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <div class="bg-white p-8 rounded-[2.5rem] shadow-md border border-slate-300">
            <div class="flex justify-between items-center mb-10 pb-4 border-b border-slate-100">
                <h2 class="text-lg font-black text-slate-900 uppercase tracking-tighter">Distribusi Kunjungan Poli</h2>
                <div class="p-2 bg-slate-50 rounded-xl text-slate-400 border border-slate-200">
                    <i class="ph-fill ph-buildings text-xl"></i>
                </div>
            </div>

            <div class="space-y-6">
                @forelse($kunjunganPerPoli as $p)
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-black text-slate-800 uppercase leading-none">{{ $p->poli }}</span>
                            <span class="text-xs font-black text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">{{ $p->total }} Pasien</span>
                        </div>
                        <div class="w-full h-3 bg-slate-100 rounded-full border border-slate-200 shadow-inner overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full transition-all duration-1000 shadow-sm" style="width: {{ ($p->total / max(1, $totalKunjungan)) * 100 }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="py-10 text-center text-slate-400 font-bold italic uppercase tracking-widest">Data Kosong</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-md border border-slate-300">
            <div class="flex justify-between items-center mb-10 pb-4 border-b border-slate-100">
                <h2 class="text-lg font-black text-slate-900 uppercase tracking-tighter">Statistik Pembayaran</h2>
                <div class="p-2 bg-slate-50 rounded-xl text-slate-400 border border-slate-200">
                    <i class="ph-fill ph-credit-card text-xl"></i>
                </div>
            </div>

            <div class="space-y-6">
                @forelse($metodePembayaran as $m)
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-black text-slate-800 uppercase leading-none">{{ $m->paid_by ?? 'LAINNYA' }}</span>
                            <span class="text-xs font-black text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">{{ $m->total }} Transaksi</span>
                        </div>
                        <div class="w-full h-3 bg-slate-100 rounded-full border border-slate-200 shadow-inner overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full transition-all duration-1000 shadow-sm" style="width: {{ ($m->total / max(1, $totalKunjungan)) * 100 }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="py-10 text-center text-slate-400 font-bold italic uppercase tracking-widest">Data Kosong</p>
                @endforelse
            </div>
        </div>

    </div>
</div>

<style>
    body { background-color: #f1f5f9; color: #0f172a; }
    * {
        -webkit-font-smoothing: antialiased;
        text-rendering: optimizeLegibility;
    }
</style>

@endsection
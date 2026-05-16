@extends('layouts.admin')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    body { 
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
</style>

<div class="p-6 lg:p-8 bg-slate-50 min-h-screen font-sans">

    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-8 gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-sm font-bold tracking-wide uppercase mb-3 border border-emerald-200 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                </svg>
                Admin / Keuangan
            </div>
            <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight">
                Riwayat Transaksi <span class="text-emerald-600">Pasien</span>
            </h1>
            <p class="text-slate-600 font-medium mt-3 text-base lg:text-lg">
                Kelola data tagihan, validasi pembayaran, dan cetak struk transaksi pasien.
            </p>
        </div>
    </div>

    {{-- METRIC CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        {{-- Card 1: Total Pendapatan/Tagihan --}}
        <div class="bg-slate-900 px-8 py-6 rounded-2xl shadow-md border-b-4 border-emerald-500 relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-xs uppercase font-black text-slate-400 tracking-[0.2em] mb-2">Total Nilai Tagihan</p>
                <h2 class="text-3xl lg:text-4xl font-black text-white flex items-baseline gap-1">
                    <span class="text-lg text-emerald-400 font-bold">Rp</span>
                    {{ number_format($data->sum(fn($i) => (int) str_replace(['.', ','], '', $i->total_biaya)), 0, ',', '.') }}
                </h2>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-white/5 absolute -right-4 -bottom-4 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        {{-- Card 2: Sudah Terbayar --}}
        <div class="bg-white px-8 py-6 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between group hover:border-emerald-300 transition-colors">
            <div>
                <p class="text-xs uppercase font-black text-slate-400 tracking-[0.2em] mb-2">Sudah Terbayar</p>
                <h2 class="text-3xl lg:text-4xl font-black text-slate-800 flex items-baseline gap-2">
                    {{ $data->where('status','lunas')->count() }}
                    <span class="text-sm font-bold text-slate-400 uppercase tracking-wider">Pasien</span>
                </h2>
            </div>
            <div class="p-4 bg-emerald-50 rounded-xl text-emerald-600 border border-emerald-100 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        {{-- Card 3: Piutang Pending --}}
        <div class="bg-white px-8 py-6 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between group hover:border-rose-300 transition-colors">
            <div>
                <p class="text-xs uppercase font-black text-slate-400 tracking-[0.2em] mb-2">Piutang Pending</p>
                <h2 class="text-3xl lg:text-4xl font-black text-slate-800 flex items-baseline gap-2">
                    {{ $data->where('status','pending')->count() }}
                    <span class="text-sm font-bold text-slate-400 uppercase tracking-wider">Pasien</span>
                </h2>
            </div>
            <div class="p-4 bg-rose-50 rounded-xl text-rose-600 border border-rose-100 group-hover:bg-rose-600 group-hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- FILTER BOX --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 lg:p-8 mb-8">
        <div class="flex items-center gap-3 mb-6 pb-5 border-b border-slate-100">
            <div class="bg-emerald-100 p-2 rounded-lg text-emerald-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <h2 class="text-xl font-bold text-slate-800">Pencarian & Filter Transaksi</h2>
        </div>

        <form method="GET" action="{{ route('admin.pembayaran') }}" class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
            
            {{-- Search Input --}}
            <div class="md:col-span-6 relative">
                <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Cari Tagihan</label>
                <div class="absolute inset-y-0 bottom-0 left-0 pl-4 flex items-center pointer-events-none" style="top: 28px;">
                    <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Nama Pasien atau No. Invoice..."
                    class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none">
            </div>

            {{-- Layanan/Poli Select --}}
            <div class="md:col-span-4 relative">
                <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Layanan Poli</label>
                <select name="poli" onchange="this.form.submit()" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none appearance-none cursor-pointer">
                    <option value="">Semua Layanan</option>
                    <option value="Poli Umum" {{ request('poli') == 'Poli Umum' ? 'selected' : '' }}>Poli Umum</option>
                    <option value="Poli Gigi" {{ request('poli') == 'Poli Gigi' ? 'selected' : '' }}>Poli Gigi</option>
                    <option value="Poli KIA & KB" {{ request('poli') == 'Poli KIA & KB' ? 'selected' : '' }}>Poli KIA & KB</option>
                </select>
                <div class="absolute inset-y-0 bottom-0 right-0 flex items-center pr-4 pointer-events-none" style="top: 28px;">
                    <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="md:col-span-2 flex gap-3">
                <button type="submit" class="flex-1 bg-slate-800 text-white py-3.5 rounded-xl text-base font-bold hover:bg-slate-900 transition-colors shadow-md">
                    Filter
                </button>
                @if(request('q') || request('poli'))
                    <a href="{{ route('admin.pembayaran') }}" class="px-4 py-3.5 bg-slate-100 text-slate-600 rounded-xl text-base font-bold hover:bg-slate-200 transition-colors border border-slate-300 flex items-center justify-center" title="Reset Pencarian">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- DATA TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1200px]">
                <thead>
                    <tr class="bg-emerald-900 text-white text-sm uppercase tracking-widest font-bold">
                        <th class="py-5 px-6 min-w-[280px] rounded-tl-xl">Informasi Pasien</th>
                        <th class="py-5 px-6 min-w-[180px]">Layanan & Metode</th>
                        <th class="py-5 px-6 min-w-[160px]">Tanggal Tagihan</th>
                        <th class="py-5 px-6 min-w-[180px] text-right">Total Biaya</th>
                        <th class="py-5 px-6 min-w-[150px] text-center">Status</th>
                        <th class="py-5 px-6 min-w-[280px] text-center rounded-tr-xl">Tindakan</th>
                    </tr>
                </thead>

                <tbody class="text-base divide-y divide-slate-200">
                    @forelse($data as $p)
                    <tr class="hover:bg-emerald-50/60 transition-colors group">
                        
                        {{-- INFORMASI PASIEN --}}
                        <td class="py-5 px-6 align-middle">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center border border-slate-300 group-hover:bg-emerald-100 group-hover:text-emerald-600 transition-colors shadow-sm flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-extrabold text-slate-800 text-lg leading-tight uppercase">
                                        {{ $p->pendaftaran->nama_pasien ?? 'Data Pasien Terhapus' }}
                                    </p>
                                    <p class="text-xs font-bold text-slate-500 mt-1 uppercase tracking-widest font-mono">
                                        INV: {{ $p->payment_ref }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        {{-- LAYANAN & METODE --}}
                        <td class="py-5 px-6 align-middle">
                            <div class="flex flex-col items-start gap-2">
                                <span class="inline-flex px-3 py-1 rounded-md bg-slate-100 text-slate-700 text-[11px] font-black uppercase tracking-wider border border-slate-300 shadow-sm">
                                    {{ $p->pendaftaran->poli ?? '-' }}
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-blue-50 text-blue-700 border border-blue-200 text-[11px] font-black uppercase tracking-wider shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $p->metode }}
                                </span>
                            </div>
                        </td>

                        {{-- TANGGAL TAGIHAN --}}
                        <td class="py-5 px-6 align-middle">
                            <p class="text-sm font-extrabold text-slate-700">{{ $p->created_at->translatedFormat('d M Y') }}</p>
                            <p class="text-[11px] font-bold text-slate-400 mt-1 uppercase tracking-widest">{{ $p->created_at->format('H:i') }} WIB</p>
                        </td>

                        {{-- TOTAL BIAYA --}}
                        <td class="py-5 px-6 align-middle text-right">
                            <p class="text-lg font-black text-slate-900 leading-tight">
                                <span class="text-xs text-slate-400 font-bold mr-0.5">Rp</span>{{ number_format((int) str_replace(['.', ','], '', $p->total_biaya), 0, ',', '.') }}
                            </p>
                        </td>

                        {{-- STATUS --}}
                        <td class="py-5 px-6 align-middle text-center">
                            @if($p->status == 'lunas')
                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-100 border border-emerald-300 text-emerald-800 text-[11px] font-extrabold shadow-sm uppercase tracking-wider">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    LUNAS
                                </div>
                            @else
                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-rose-100 border border-rose-300 text-rose-800 text-[11px] font-extrabold shadow-sm uppercase tracking-wider animate-pulse">
                                    <span class="flex h-2 w-2 relative">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-600"></span>
                                    </span>
                                    PENDING
                                </div>
                            @endif
                        </td>

                        {{-- TINDAKAN --}}
                        <td class="py-5 px-6 align-middle text-center">
                            <div class="flex items-center justify-center gap-2">
                                
                                {{-- Tombol Rincian --}}
                                <a href="{{ route('admin.pembayaran.show', $p->id) }}" 
                                   class="inline-flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider border border-slate-300 transition-colors shadow-sm" title="Lihat Rincian">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Rincian
                                </a>

                                @if($p->status != 'lunas')
                                    {{-- Tombol Validasi Bayar --}}
                                    <form method="POST" action="{{ route('admin.pembayaran.lunasi',$p->id) }}" class="inline">
                                        @csrf
                                        <button onclick="return confirm('Apakah Anda yakin ingin memvalidasi pelunasan tagihan ini?')" 
                                                class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all shadow-md active:scale-95 border border-emerald-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Validasi Bayar
                                        </button>
                                    </form>
                                @else
                                    {{-- Tombol Cetak Struk --}}
                                    <a href="{{ route('admin.pembayaran.print', $p->id) }}" target="_blank"
                                       class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all shadow-md active:scale-95 border border-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        Struk
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-24 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="bg-emerald-50 p-6 rounded-full mb-5 border border-emerald-100 text-emerald-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-slate-800 mb-2 uppercase tracking-wide">Tidak Ada Riwayat Pembayaran</h3>
                                <p class="text-slate-500 text-base max-w-md">
                                    Belum ada transaksi pembayaran yang tercatat atau data tidak ditemukan pada pencarian ini.
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- FOOTER INFO --}}
        <div class="bg-slate-50 px-6 py-4 border-t border-slate-200">
            <p class="text-[11px] text-slate-500 font-extrabold italic uppercase tracking-wider flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Menampilkan data tagihan pasien yang terdaftar di sistem.
            </p>
        </div>
    </div>
</div>

@endsection
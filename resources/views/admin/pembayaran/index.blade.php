@extends('layouts.admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

<div class="p-6 bg-slate-100 min-h-screen font-['Plus_Jakarta_Sans']">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-col md:flex-row justify-between items-end mb-6 gap-4">
        <div>
            <nav class="flex text-[11px] text-slate-500 mb-1 gap-1 font-bold uppercase tracking-wider">
                <span>Admin</span>
                <span class="text-slate-400">/</span>
                <span class="text-emerald-700">Keuangan</span>
            </nav>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                Pembayaran <span class="text-emerald-600">Pasien</span>
            </h1>
        </div>

        <a href="{{ route('admin.data_pasien.index') }}"
           class="flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-emerald-200/50 transition-all active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Buat Pembayaran Baru
        </a>
    </div>

    {{-- ================= SUMMARY CARDS ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-slate-900 p-5 rounded-2xl shadow-md border-b-4 border-emerald-500">
            <p class="text-slate-400 text-[10px] uppercase font-bold tracking-[0.2em] mb-1">Total Nilai Tagihan</p>
            <h2 class="text-2xl font-black text-white">
                <span class="text-emerald-400 text-sm font-bold mr-1">Rp</span>{{ number_format($data->sum(fn($i) => (int) str_replace(['.', ','], '', $i->total_biaya)), 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-500 text-[10px] uppercase font-bold tracking-[0.2em] mb-1">Sudah Terbayar</p>
                    <h2 class="text-2xl font-black text-emerald-600 uppercase">{{ $data->where('status','lunas')->count() }} <span class="text-xs text-slate-400">Pasien</span></h2>
                </div>
                <div class="p-2 bg-emerald-50 rounded-lg text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-500 text-[10px] uppercase font-bold tracking-[0.2em] mb-1">Piutang Pending</p>
                    <h2 class="text-2xl font-black text-red-600 uppercase">{{ $data->where('status','belum_lunas')->count() }} <span class="text-xs text-slate-400">Pasien</span></h2>
                </div>
                <div class="p-2 bg-red-50 rounded-lg text-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= FILTER & SEARCH ================= --}}
    <div class="bg-white p-4 rounded-xl border border-slate-300 shadow-sm mb-4">
        <form method="GET" action="{{ route('admin.pembayaran') }}" class="flex flex-col lg:flex-row gap-3">
            
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input 
                    type="text" name="q" value="{{ request('q') }}" 
                    placeholder="Cari Pasien, Nomor Referensi, atau Poli..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-lg bg-slate-50 border border-slate-400 focus:border-slate-900 focus:bg-white outline-none text-sm font-bold text-slate-900 transition-all"
                >
            </div>

            <div class="flex gap-2">
                <select name="poli" onchange="this.form.submit()" 
                        class="pl-4 pr-10 py-2.5 rounded-lg border border-slate-400 bg-white text-sm font-bold text-slate-900 outline-none focus:border-slate-900 cursor-pointer appearance-none min-w-[160px]">
                    <option value="">Semua Layanan Poli</option>
                    <option value="Poli Umum" {{ request('poli') == 'Poli Umum' ? 'selected' : '' }}>Poli Umum</option>
                    <option value="Poli Gigi" {{ request('poli') == 'Poli Gigi' ? 'selected' : '' }}>Poli Gigi</option>
                    <option value="Poli KIA & KB" {{ request('poli') == 'Poli KIA & KB' ? 'selected' : '' }}>Poli KIA & KB</option>
                </select>

                @if(request('q') || request('poli'))
                    <a href="{{ route('admin.pembayaran') }}" 
                       class="px-4 py-2.5 bg-slate-200 text-slate-900 rounded-lg text-sm font-bold border border-slate-400 hover:bg-slate-300">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ================= DATA TABLE ================= --}}
    <div class="bg-white rounded-xl shadow-md border border-slate-300 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900 border-b border-slate-900">
                        <th class="px-5 py-4 text-white font-bold text-[11px] uppercase tracking-wider">Informasi Pasien</th>
                        <th class="px-5 py-4 text-white font-bold text-[11px] uppercase">Layanan</th>
                        <th class="px-5 py-4 text-white font-bold text-[11px] uppercase">Metode</th>
                        <th class="px-5 py-4 text-white font-bold text-[11px] uppercase">Tanggal Tagihan</th>
                        <th class="px-5 py-4 text-right text-white font-bold text-[11px] uppercase">Total Biaya</th>
                        <th class="px-5 py-4 text-center text-white font-bold text-[11px] uppercase">Status</th>
                        <th class="px-5 py-4 text-right text-white font-bold text-[11px] uppercase">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($data as $p)
                    <tr class="hover:bg-emerald-50/50 transition-colors">
                        {{-- PASIEN --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center border border-slate-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    {{-- PERBAIKAN DISINI: Menghindari error null property --}}
                                    <p class="text-[14px] font-bold text-slate-900 leading-tight">
                                        {{ $p->pendaftaran->nama_pasien ?? 'Data Pasien Terhapus' }}
                                    </p>
                                    <p class="text-[10px] font-bold text-slate-400 mt-0.5 tracking-wider uppercase font-mono">
                                        {{ $p->payment_ref }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        {{-- POLI --}}
                        <td class="px-5 py-4">
                            <span class="text-xs font-bold text-slate-700">
                                {{ $p->pendaftaran->poli ?? '-' }}
                            </span>
                        </td>

                        {{-- METODE --}}
                        <td class="px-5 py-4">
                            <span class="px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded text-[10px] font-black uppercase">
                                {{ $p->metode }}
                            </span>
                        </td>

                        {{-- TANGGAL --}}
                        <td class="px-5 py-4 text-xs font-bold text-slate-500">
                            {{ $p->created_at->translatedFormat('d M Y') }}
                        </td>

                        {{-- TOTAL --}}
                        <td class="px-5 py-4 text-right">
                            <p class="text-[14px] font-black text-slate-900 uppercase">
                                <span class="text-[10px] text-slate-400 mr-0.5 font-bold">Rp</span>{{ number_format((int) str_replace(['.', ','], '', $p->total_biaya), 0, ',', '.') }}
                            </p>
                        </td>

                        {{-- STATUS --}}
                        <td class="px-5 py-4 text-center">
                            @if($p->status == 'lunas')
                                <span class="inline-flex items-center px-3 py-1 bg-emerald-100 text-emerald-800 rounded text-[10px] font-black uppercase border-2 border-emerald-200">
                                    LUNAS
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded text-[10px] font-black uppercase border-2 border-red-200">
                                    PENDING
                                </span>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td class="px-5 py-4 text-right">
                            @if($p->status == 'belum_lunas')
                                <form method="POST" action="{{ route('admin.pembayaran.lunasi',$p->id) }}" class="inline">
                                    @csrf
                                    <button onclick="return confirm('Konfirmasi pelunasan tagihan ini?')" 
                                            class="bg-emerald-600 hover:bg-slate-900 text-white px-4 py-2 rounded-lg text-[11px] font-black uppercase tracking-wider transition-all shadow-md shadow-emerald-100 active:scale-95">
                                        Validasi Bayar
                                    </button>
                                </form>
                            @else
                                <div class="flex justify-end items-center gap-1 text-emerald-600">
                                    <span class="text-[10px] font-black uppercase tracking-tighter">Terverifikasi</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-20 bg-slate-50">
                            <div class="opacity-20 flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <p class="text-xl font-black uppercase tracking-widest">Tidak Ada Tagihan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="bg-slate-50 px-6 py-4 border-t border-slate-300">
            <p class="text-[10px] text-slate-500 font-bold italic uppercase tracking-wider">
                * Pastikan nominal pembayaran sudah sesuai sebelum menekan tombol validasi.
            </p>
        </div>
    </div>

</div>

<style>
    body { background-color: #f1f5f9; }
    * {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-rendering: optimizeLegibility;
    }
    td {
        padding-top: 0.75rem !important;
        padding-bottom: 0.75rem !important;
    }
</style>
@endsection
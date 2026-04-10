@extends('layouts.admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

<div class="p-6 bg-slate-100 min-h-screen font-['Plus_Jakarta_Sans']">
    
    <div class="flex flex-col md:flex-row justify-between items-end mb-6 gap-4">
        <div>
            <nav class="flex text-[11px] text-slate-500 mb-1 gap-1 font-bold uppercase tracking-wider">
                <span>Admin</span>
                <span class="text-slate-400">/</span>
                <span class="text-emerald-700">Manajemen Data Pasien</span>
            </nav>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                Data <span class="text-emerald-600">Pasien</span>
            </h1>
        </div>

        <div class="flex items-center gap-3 bg-slate-900 px-5 py-2.5 rounded-xl shadow-lg border-b-4 border-emerald-500">
            <div class="text-right">
                <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold leading-none">Total Terdaftar</p>
                <p class="text-xl font-extrabold text-white leading-tight mt-1">{{ number_format($pasien->count()) }} <span class="text-xs font-medium text-emerald-400">Orang</span></p>
            </div>
        </div>
    </div>

    <div class="bg-white p-5 rounded-xl border border-slate-300 shadow-sm mb-4">
        <form method="GET" action="{{ route('admin.data_pasien.index') }}" class="flex flex-col lg:flex-row gap-4">
            
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-slate-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Cari Nama Pasien atau Nomor Rekam Medis..."
                    class="w-full pl-10 pr-4 py-3 rounded-lg bg-slate-50 border border-slate-400 focus:border-slate-900 focus:bg-white outline-none text-sm font-bold text-slate-900 transition-all placeholder:text-slate-400"
                >
            </div>

            <div class="flex gap-2">
                <div class="relative">
                    <select name="jenis" onchange="this.form.submit()" 
                        class="pl-4 pr-10 py-3 rounded-lg border border-slate-400 bg-white text-sm font-bold text-slate-900 outline-none focus:border-slate-900 cursor-pointer appearance-none min-w-[160px]">
                        <option value="">Semua Kategori</option>
                        <option value="umum" {{ request('jenis') == 'umum' ? 'selected' : '' }}>Pasien Umum</option>
                        <option value="jkn" {{ request('jenis') == 'jkn' ? 'selected' : '' }}>Pasien JKN/BPJS</option>
                    </select>
                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor font-bold">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                @if(request('q') || request('jenis'))
                <a href="{{ route('admin.data_pasien.index') }}"
                   class="flex items-center gap-2 px-5 py-3 bg-slate-200 text-slate-900 rounded-lg text-sm font-bold hover:bg-slate-300 transition-all border border-slate-400">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-slate-300 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900 border-b border-slate-900">
                        <th class="px-4 py-4 text-center w-14 text-emerald-400 font-black text-xs uppercase tracking-tighter">No</th>
                        <th class="px-4 py-4 text-white font-bold text-xs uppercase">Nama Pasien</th>
                        <th class="px-4 py-4 text-white font-bold text-xs uppercase">Nomor Identitas</th>
                        <th class="px-4 py-4 text-center text-white font-bold text-xs uppercase">Kunjungan</th>
                        <th class="px-4 py-4 text-center text-white font-bold text-xs uppercase">Status Pembayaran</th>
                        <th class="px-4 py-4 text-right text-white font-bold text-xs uppercase">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($pasien as $p)
                    <tr class="hover:bg-emerald-50 transition-colors">
                        <td class="px-4 py-4 text-center">
                            <span class="text-sm font-bold text-slate-900">{{ $loop->iteration }}</span>
                        </td>

                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-emerald-600 flex items-center justify-center text-white text-sm font-black shadow-sm">
                                    {{ strtoupper(substr($p->nama_pasien, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-[15px] font-bold text-slate-950 leading-tight">{{ $p->nama_pasien }}</p>
                                    <span class="text-[10px] font-extrabold px-1.5 py-0.5 rounded bg-slate-200 text-slate-700 mt-1 inline-block uppercase tracking-wider border border-slate-300">
                                        {{ $p->jenis_pasien }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        <td class="px-4 py-4">
                            <p class="text-sm font-bold text-slate-900 tracking-tight font-mono">{{ $p->no_identitas }}</p>
                            <p class="text-[10px] text-slate-500 font-bold uppercase mt-1">Terakhir: {{ \Carbon\Carbon::parse($p->terakhir_kunjungan)->translatedFormat('d M Y') }}</p>
                        </td>

                        <td class="px-4 py-4 text-center">
                            <div class="inline-block bg-slate-100 px-3 py-1 rounded-md border border-slate-300">
                                <span class="text-sm font-black text-slate-900">{{ $p->total_kunjungan }}x</span>
                            </div>
                        </td>

                        <td class="px-4 py-4 text-center">
                            @if($p->status_admin === 'lunas')
                                <span class="inline-flex items-center px-4 py-1.5 bg-emerald-100 text-emerald-800 rounded-md text-[11px] font-black uppercase border-2 border-emerald-300">
                                    LUNAS
                                </span>
                            @elseif($p->status_admin === 'belum_lunas')
                                <span class="inline-flex items-center px-4 py-1.5 bg-red-100 text-red-800 rounded-md text-[11px] font-black uppercase border-2 border-red-300">
                                    BELUM LUNAS
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-1.5 bg-slate-100 text-slate-600 rounded-md text-[11px] font-black border border-slate-300 uppercase">
                                    TIDAK ADA TAGIHAN
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-4 text-right">
                            <a href="{{ route('admin.data_pasien.detail', $p->no_identitas) }}"
                               class="inline-flex items-center gap-2 px-5 py-2 bg-slate-900 text-white rounded-lg text-xs font-bold hover:bg-emerald-600 transition-all shadow-md active:scale-95 border border-slate-900">
                                <span>LIHAT DETAIL</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center bg-slate-50">
                            <p class="text-slate-500 font-extrabold text-lg uppercase tracking-widest opacity-50">Data Pasien Tidak Ditemukan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="bg-slate-50 px-6 py-4 border-t border-slate-300">
            <p class="text-[11px] text-slate-600 font-bold italic uppercase tracking-wider">* Menampilkan data pasien yang terdaftar di sistem pusat Polkes.</p>
        </div>
    </div>
</div>

<style>
    /* Mengatur ketajaman font */
    body { 
        background-color: #f1f5f9; 
        color: #0f172a;
    }
    
    /* Menghilangkan transparansi sepenuhnya */
    .bg-slate-50 { background-color: #f8fafc !important; }
    
    /* Memastikan teks sangat jelas terbaca */
    * {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-rendering: optimizeLegibility;
    }

    /* Padding tabel yang lebih rapat */
    td, th {
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
    }
</style>
@endsection
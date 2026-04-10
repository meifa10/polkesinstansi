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
                <span class="text-emerald-700">Manajemen Antrean</span>
            </nav>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                Pendaftaran <span class="text-emerald-600">Pasien</span>
            </h1>
        </div>

        <div class="flex items-center gap-3 bg-slate-900 px-5 py-2.5 rounded-xl shadow-lg border-b-4 border-emerald-500">
            <div class="text-right">
                <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold leading-none">Antrean Aktif</p>
                <p class="text-xl font-extrabold text-white leading-tight mt-1">
                    {{ $pendaftaran->where('status','menunggu')->count() }} <span class="text-xs font-medium text-emerald-400">PASIEN</span>
                </p>
            </div>
        </div>
    </div>

    {{-- ================= FILTER & PENCARIAN ================= --}}
    <div class="bg-white p-4 rounded-xl border border-slate-300 shadow-sm mb-4">
        <form 
            method="GET" 
            action="{{ route('admin.pendaftaran.index') }}" 
            id="filterForm"
            class="flex flex-col lg:flex-row gap-3"
        >
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-slate-900 font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Cari Nama Pasien, NIK, atau Poliklinik..."
                    oninput="submitWithDelay()"
                    class="w-full pl-10 pr-4 py-2.5 rounded-lg bg-slate-50 border border-slate-400 focus:border-slate-900 focus:bg-white outline-none text-sm font-bold text-slate-900 transition-all placeholder:text-slate-400"
                >
            </div>

            <div class="flex gap-2">
                <div class="relative">
                    <select
                        name="poli"
                        onchange="document.getElementById('filterForm').submit()"
                        class="pl-4 pr-10 py-2.5 rounded-lg border border-slate-400 bg-white text-sm font-bold text-slate-900 outline-none focus:border-slate-900 cursor-pointer appearance-none min-w-[180px]"
                    >
                        <option value="">Semua Layanan Poli</option>
                        <option value="Poli Umum" {{ request('poli') == 'Poli Umum' ? 'selected' : '' }}>Poli Umum</option>
                        <option value="Poli Gigi" {{ request('poli') == 'Poli Gigi' ? 'selected' : '' }}>Poli Gigi</option>
                        <option value="Poli KIA & KB" {{ request('poli') == 'Poli KIA & KB' ? 'selected' : '' }}>Poli KIA & KB</option>
                    </select>
                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor font-bold">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                @if(request('q') || request('poli'))
                <a href="{{ route('admin.pendaftaran.index') }}"
                   class="flex items-center gap-2 px-5 py-2.5 bg-slate-200 text-slate-900 rounded-lg text-sm font-bold hover:bg-slate-300 transition-all border border-slate-400">
                    Atur Ulang
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ================= SUCCESS MESSAGE ================= --}}
    @if(session('success'))
    <div class="mb-4 p-4 bg-emerald-100 border-l-4 border-emerald-600 text-emerald-800 rounded shadow-sm flex items-center gap-3 text-sm font-bold italic animate-pulse">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- ================= DATA TABLE ================= --}}
    <div class="bg-white rounded-xl shadow-md border border-slate-300 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900 border-b border-slate-900">
                        <th class="px-5 py-4 text-center w-14 text-emerald-400 font-black text-xs uppercase tracking-tighter">No</th>
                        <th class="px-5 py-4 text-white font-bold text-xs uppercase">Nama Pasien</th>
                        <th class="px-5 py-4 text-white font-bold text-xs uppercase">Kategori / Poli</th>
                        <th class="px-5 py-4 text-center text-white font-bold text-xs uppercase">Status Antrean</th>
                        <th class="px-5 py-4 text-right text-white font-bold text-xs uppercase">Kelola Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($pendaftaran as $item)
                    <tr class="hover:bg-emerald-50/50 transition-colors">
                        {{-- NO --}}
                        <td class="px-5 py-4 text-center">
                            <span class="text-sm font-bold text-slate-900">#{{ $loop->iteration }}</span>
                        </td>

                        {{-- PASIEN --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-slate-800 flex items-center justify-center text-white text-sm font-black shadow-sm">
                                    {{ strtoupper(substr($item->nama_pasien, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-[14px] font-bold text-slate-950 leading-tight">{{ $item->nama_pasien }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-wider">Identitas: {{ $item->no_identitas ?? 'Online' }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- JENIS & POLI --}}
                        <td class="px-5 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="text-xs font-black text-slate-900 uppercase leading-none">{{ $item->poli }}</span>
                                <span class="text-[9px] font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100 w-fit uppercase">
                                    {{ $item->jenis_pasien }}
                                </span>
                            </div>
                        </td>

                        {{-- STATUS PILL --}}
                        <td class="px-5 py-4 text-center">
                            @if($item->status == 'menunggu')
                                <span class="inline-flex items-center px-4 py-1 bg-amber-100 text-amber-800 rounded-md text-[10px] font-black uppercase border border-amber-300">
                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-2 animate-pulse"></span>
                                    MENUNGGU
                                </span>
                            @elseif($item->status == 'diproses')
                                <span class="inline-flex items-center px-4 py-1 bg-blue-100 text-blue-800 rounded-md text-[10px] font-black uppercase border border-blue-300">
                                    <span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-2"></span>
                                    DIPROSES
                                </span>
                            @elseif($item->status == 'selesai')
                                <span class="inline-flex items-center px-4 py-1 bg-emerald-100 text-emerald-800 rounded-md text-[10px] font-black uppercase border border-emerald-300">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-2"></span>
                                    SELESAI
                                </span>
                            @endif
                        </td>

                        {{-- AKSI / SELECT UPDATE --}}
                        <td class="px-5 py-4 text-right">
                            <form id="form-status-{{ $item->id }}" method="POST" action="{{ route('admin.pendaftaran.status', $item->id) }}" class="inline-block">
                                @csrf
                                <div class="relative group">
                                    <select
                                        name="status"
                                        onchange="document.getElementById('form-status-{{ $item->id }}').submit()"
                                        class="pl-3 pr-8 py-1.5 bg-white border-2 border-slate-300 rounded-lg text-[11px] font-black text-slate-900 outline-none focus:border-slate-900 cursor-pointer appearance-none transition-all hover:bg-slate-50 uppercase shadow-sm"
                                    >
                                        <option value="menunggu" {{ $item->status=='menunggu'?'selected':'' }}>⏳ Tunggu</option>
                                        <option value="diproses" {{ $item->status=='diproses'?'selected':'' }}>⚙️ Proses</option>
                                        <option value="selesai" {{ $item->status=='selesai'?'selected':'' }}>✅ Selesai</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-2 flex items-center pointer-events-none text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-16 text-center bg-slate-50">
                            <p class="text-slate-500 font-extrabold text-lg uppercase tracking-widest opacity-50 italic">Antrean Masih Kosong</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="bg-slate-50 px-6 py-4 border-t border-slate-300">
            <p class="text-[10px] text-slate-500 font-bold italic uppercase tracking-wider italic">
                * Harap perbarui status secara berkala untuk menjaga sinkronisasi antrean di dashboard dokter.
            </p>
        </div>
    </div>
</div>

{{-- SCRIPT AUTO SUBMIT --}}
<script>
    let timeout = null;
    function submitWithDelay() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 600);
    }
</script>

<style>
    body { background-color: #f1f5f9; color: #0f172a; }
    * {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-rendering: optimizeLegibility;
    }
    /* Padding tabel yang padat */
    td {
        padding-top: 0.8rem !important;
        padding-bottom: 0.8rem !important;
    }
</style>
@endsection
@extends('layouts.admin')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

<div class="p-6 bg-slate-100 min-h-screen font-['Plus_Jakarta_Sans']">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-end mb-6 gap-4">
        <div>
            <nav class="flex text-[11px] text-slate-500 mb-1 gap-1 font-bold uppercase tracking-wider">
                <span>Admin</span>
                <span class="text-slate-400">/</span>
                <span class="text-emerald-700">Antrian Pasien Hari Ini</span>
            </nav>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                Pendaftaran <span class="text-emerald-600">Pasien</span>
            </h1>
        </div>

        <div class="bg-slate-900 px-6 py-3 rounded-2xl shadow-lg border-b-4 border-emerald-500">
            <p class="text-[10px] uppercase tracking-widest text-slate-400 font-black">
                Total Pasien Hari Ini
            </p>
            <p class="text-2xl font-black text-white">
                {{ $pendaftaran->count() }}
                <span class="text-sm text-emerald-400">PASIEN</span>
            </p>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-5">
        <form method="GET" action="{{ route('admin.pendaftaran.index') }}" class="flex flex-col lg:flex-row gap-3">
            {{-- SEARCH --}}
            <div class="flex-1">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama pasien atau NIK..." 
                    class="w-full px-4 py-3 rounded-xl border border-slate-300 outline-none text-sm font-bold focus:border-emerald-500">
            </div>

            {{-- FILTER POLI --}}
            <div>
                <select name="poli" onchange="this.form.submit()" class="px-4 py-3 rounded-xl border border-slate-300 outline-none text-sm font-bold focus:border-emerald-500">
                    <option value="">Semua Poli</option>
                    <option value="Poli Umum" {{ request('poli') == 'Poli Umum' ? 'selected' : '' }}>Poli Umum</option>
                    <option value="Poli Gigi" {{ request('poli') == 'Poli Gigi' ? 'selected' : '' }}>Poli Gigi</option>
                    <option value="Poli KIA" {{ request('poli') == 'Poli KIA' ? 'selected' : '' }}>Poli KIA & KB</option>
                </select>
            </div>
            
            <button type="submit" class="bg-emerald-600 text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-emerald-700">Filter</button>
        </form>
    </div>

    {{-- SUCCESS ALERT --}}
    @if(session('success'))
    <div class="mb-4 bg-emerald-100 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl font-bold text-sm flex justify-between items-center">
        <span>{{ session('success') }}</span>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-900">&times;</button>
    </div>
    @endif

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-900">
                    <tr>
                        <th class="px-5 py-4 text-white text-xs uppercase text-center w-16">No</th>
                        <th class="px-5 py-4 text-white text-xs uppercase text-left">Pasien & Dokter</th>
                        <th class="px-5 py-4 text-white text-xs uppercase text-center">Unit / Poli</th>
                        <th class="px-5 py-4 text-white text-xs uppercase text-left">Pemeriksaan Awal</th>
                        <th class="px-5 py-4 text-white text-xs uppercase text-center">Status</th>
                        <th class="px-5 py-4 text-white text-xs uppercase text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($pendaftaran as $item)
                    <tr class="hover:bg-emerald-50/40 transition">
                        {{-- NO & ANTREAN --}}
                        <td class="px-5 py-4 text-center">
                            <div class="font-black text-slate-700 text-sm">#{{ $loop->iteration }}</div>
                            <div class="text-[9px] font-black bg-slate-100 text-slate-500 rounded px-1 mt-1">
                                Q-{{ str_pad($item->nomor_antrian, 2, '0', STR_PAD_LEFT) }}
                            </div>
                        </td>

                        {{-- PASIEN & DOKTER --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-black text-sm">
                                    {{ strtoupper(substr($item->nama_pasien,0,1)) }}
                                </div>
                                <div>
                                    <p class="font-black text-slate-800 uppercase text-sm leading-tight">{{ $item->nama_pasien }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase">NIK: {{ $item->no_identitas }}</p>
                                    
                                    {{-- INFO DOKTER --}}
                                    <div class="mt-1 flex items-center gap-1.5">
                                        <i class="fa-solid fa-user-doctor text-[10px] text-emerald-500"></i>
                                        <span class="text-[10px] text-emerald-700 font-black uppercase">
                                            {{ $item->dokter->name ?? 'Dokter Belum Ditentukan' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- POLI --}}
                        <td class="px-5 py-4 text-center">
                            <span class="px-3 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase border border-emerald-200">
                                {{ $item->poli }}
                            </span>
                        </td>

                        {{-- TANDA VITAL --}}
                        <td class="px-5 py-4">
                            <div class="space-y-1">
                                <div class="flex gap-2">
                                    <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded text-[9px] font-black border border-blue-100">
                                        BB: {{ $item->berat_badan ?? '-' }} KG
                                    </span>
                                    <span class="bg-purple-50 text-purple-700 px-2 py-0.5 rounded text-[9px] font-black border border-purple-100">
                                        TB: {{ $item->tinggi_badan ?? '-' }} CM
                                    </span>
                                </div>
                                <p class="text-[10px] text-slate-600 font-bold line-clamp-1 italic">
                                    "{{ $item->keluhan ?? 'Tidak ada keluhan' }}"
                                </p>
                            </div>
                        </td>

                        {{-- STATUS --}}
                        <td class="px-5 py-4 text-center">
                            @if($item->status == 'menunggu_petugas' || $item->status == 'menunggu_admin')
                                <span class="px-4 py-1 rounded-full bg-amber-100 text-amber-700 border border-amber-200 text-[9px] font-black uppercase">
                                    Menunggu Verifikasi
                                </span>
                            @elseif($item->status == 'diproses_dokter' || $item->status == 'proses')
                                <span class="px-4 py-1 rounded-full bg-blue-100 text-blue-700 border border-blue-200 text-[9px] font-black uppercase">
                                    Dalam Pemeriksaan
                                </span>
                            @elseif($item->status == 'selesai')
                                <span class="px-4 py-1 rounded-full bg-emerald-100 text-emerald-700 border border-emerald-200 text-[9px] font-black uppercase">
                                    Selesai
                                </span>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td class="px-5 py-4 text-right">
                            @if($item->status == 'menunggu_petugas' || $item->status == 'menunggu_admin')
                            <form method="POST" action="{{ route('admin.pendaftaran.status', $item->id) }}">
                                @csrf
                                <input type="hidden" name="status" value="diproses_dokter">
                                <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-black uppercase tracking-wider shadow-md transition">
                                    Kirim ke Dokter
                                </button>
                            </form>
                            @else
                                <span class="text-[10px] font-bold text-slate-400 italic">Telah Diproses</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-20 text-center">
                            <div class="flex flex-col items-center opacity-30">
                                <i class="fa-solid fa-folder-open text-4xl mb-3 text-slate-400"></i>
                                <p class="text-sm font-black uppercase tracking-widest text-slate-500">Tidak Ada Antrian Pasien</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
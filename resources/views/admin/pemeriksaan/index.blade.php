@if(request('download') == 1)
    {{-- ========================================================================= --}}
    {{-- LAYOUT KHUSUS EXPORT EXCEL (Akan ter-render saat tombol export ditekan) --}}
    {{-- ========================================================================= --}}
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Export Laporan Pemeriksaan</title>
    </head>
    <body>
        <table style="border-collapse: collapse; width: 100%; font-family: Arial, sans-serif;">
            <thead>
                {{-- KOP SURAT / HEADER EXCEL --}}
                <tr>
                    <th colspan="6" style="background-color: #059669; color: #ffffff; font-size: 16px; font-weight: bold; text-align: center; height: 40px; vertical-align: middle; border: 1px solid #059669;">
                        LAPORAN PEMERIKSAAN POLKES 05.09.15 JOMBANG
                    </th>
                </tr>
                <tr>
                    <th colspan="6" style="background-color: #ecfdf5; color: #064e3b; font-size: 12px; text-align: center; height: 25px; vertical-align: middle; border-left: 1px solid #059669; border-right: 1px solid #059669;">
                        Jl. KH. Wahid Hasyim No.28 B, Jombang, Jawa Timur
                    </th>
                </tr>
                <tr>
                    <th colspan="6" style="background-color: #ecfdf5; color: #064e3b; font-size: 12px; text-align: center; height: 25px; vertical-align: middle; border-bottom: 2px solid #059669; border-left: 1px solid #059669; border-right: 1px solid #059669;">
                        Telp / WA: 0877-7723-5386 | Email: jombangposkes@gmail.com
                    </th>
                </tr>
                <tr>
                    <th colspan="6" style="height: 15px;"></th> <!-- Spacer / Jarak kosong -->
                </tr>
                
                {{-- HEADER TABEL DATA --}}
                <tr>
                    <th style="background-color: #064e3b; color: #ffffff; border: 1px solid #000000; font-weight: bold; text-align: center; height: 35px; vertical-align: middle;">NO</th>
                    <th style="background-color: #064e3b; color: #ffffff; border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle;">NAMA PASIEN & NIK</th>
                    <th style="background-color: #064e3b; color: #ffffff; border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle;">POLIKLINIK</th>
                    <th style="background-color: #064e3b; color: #ffffff; border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle;">KELUHAN & DIAGNOSIS</th>
                    <th style="background-color: #064e3b; color: #ffffff; border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle;">TINDAKAN & RESEP</th>
                    <th style="background-color: #064e3b; color: #ffffff; border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle;">TANGGAL PERIKSA</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pemeriksaan as $index => $item)
                <tr>
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: top;">{{ $index + 1 }}</td>
                    <td style="border: 1px solid #000000; vertical-align: top;">
                        <strong>{{ $item->pendaftaran->nama_pasien ?? '-' }}</strong><br>
                        NIK: {{ $item->pendaftaran->no_identitas ?? '-' }}
                    </td>
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: top;">
                        {{ $item->pendaftaran->poli ?? '-' }}
                    </td>
                    <td style="border: 1px solid #000000; vertical-align: top;">
                        <strong>Keluhan:</strong> {{ $item->keluhan ?? '-' }}<br>
                        <strong>Diagnosis:</strong> {{ $item->diagnosis ?? '-' }}
                    </td>
                    <td style="border: 1px solid #000000; vertical-align: top;">
                        <strong>Tindakan:</strong> {{ $item->tindakan ?? '-' }}<br>
                        <strong>Resep:</strong> {{ $item->resep ?? 'Tanpa Resep' }}
                    </td>
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: top;">
                        {{ $item->created_at->translatedFormat('d F Y H:i') }} WIB
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="border: 1px solid #000000; text-align: center; height: 40px; vertical-align: middle;">Tidak ada data pemeriksaan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </body>
    </html>

@else
    {{-- ========================================================================= --}}
    {{-- LAYOUT UI WEB (NORMAL) TAMPILAN DASHBOARD ADMIN --}}
    {{-- ========================================================================= --}}
    @extends('layouts.admin')

    @section('content')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div class="p-6 bg-slate-100 min-h-screen" style="font-family: 'Plus Jakarta Sans', sans-serif;">

        {{-- HEADER --}}
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-6">
            <div>
                <nav class="flex items-center gap-1 text-[11px] uppercase tracking-wider font-bold text-slate-500 mb-1">
                    <span>Admin</span> <span class="text-slate-300">/</span> <span class="text-emerald-600">Rekam Medis</span>
                </nav>
                <h1 class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Laporan <span class="text-emerald-600">Pemeriksaan</span>
                </h1>
                <p class="text-slate-500 font-medium mt-1.5 text-sm">
                    Pantau rekam medis, keluhan pasien, diagnosis, dan resep obat yang diberikan.
                </p>
            </div>

            {{-- Form Export - Mengirimkan filter aktif beserta trigger download=1 --}}
            <form method="GET" action="{{ route('admin.pemeriksaan') }}">
                <input type="hidden" name="q" value="{{ request('q') }}">
                <input type="hidden" name="poli" value="{{ request('poli') }}">
                <input type="hidden" name="tanggal" value="{{ request('tanggal') }}">
                
                <button type="submit" name="download" value="1" 
                    class="flex items-center gap-3 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3.5 rounded-2xl shadow-lg shadow-emerald-500/30 transition-all font-bold active:scale-95 group">
                    <i class="ph-bold ph-file-xls text-xl group-hover:-translate-y-0.5 transition-transform"></i>
                    Export ke Excel
                </button>
            </form>
        </div>

        {{-- FILTER BOX --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">
            <form method="GET" action="{{ route('admin.pemeriksaan') }}" class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                
                {{-- Search --}}
                <div class="lg:col-span-5 relative">
                    <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Nama Pasien atau Diagnosis..."
                        class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-300 bg-slate-50 text-sm font-semibold focus:border-emerald-500 focus:bg-white outline-none transition">
                </div>

                {{-- Filter Poli --}}
                <div class="lg:col-span-3 relative">
                    <select name="poli" onchange="this.form.submit()" class="w-full pl-4 pr-10 py-3 rounded-xl border border-slate-300 bg-slate-50 text-sm font-semibold outline-none focus:border-emerald-500 focus:bg-white transition appearance-none cursor-pointer">
                        <option value="">Semua Poliklinik</option>
                        <option value="Poli Umum" {{ request('poli') == 'Poli Umum' ? 'selected' : '' }}>Poli Umum</option>
                        <option value="Poli Gigi" {{ request('poli') == 'Poli Gigi' ? 'selected' : '' }}>Poli Gigi</option>
                        <option value="Poli KIA & KB" {{ request('poli') == 'Poli KIA & KB' ? 'selected' : '' }}>Poli KIA & KB</option>
                    </select>
                    <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                </div>

                {{-- Date Filter & Reset --}}
                <div class="lg:col-span-4 flex gap-2">
                    <div class="relative w-full">
                        <input type="date" name="tanggal" value="{{ request('tanggal') }}" 
                            class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-slate-50 text-sm font-semibold outline-none focus:border-emerald-500 focus:bg-white transition cursor-pointer">
                    </div>
                    <button type="submit" class="bg-slate-900 text-white px-6 rounded-xl font-bold text-sm hover:bg-slate-800 transition-colors shadow-md">
                        Cari
                    </button>
                    @if(request('q') || request('poli') || request('tanggal'))
                        <a href="{{ route('admin.pemeriksaan') }}" class="flex items-center justify-center px-4 bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 transition-colors border border-slate-300" title="Reset Filter">
                            <i class="ph-bold ph-arrows-clockwise text-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- DATA TABLE --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden text-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[1200px] text-left">
                    <thead>
                        <tr class="bg-slate-900 text-white text-[11px] uppercase font-black tracking-widest">
                            <th class="px-6 py-5 text-center w-16">No</th>
                            <th class="px-6 py-5">Informasi Pasien</th>
                            <th class="px-6 py-5 min-w-[200px]">Keluhan Utama</th>
                            <th class="px-6 py-5 min-w-[250px]">Diagnosis & Tindakan</th>
                            <th class="px-6 py-5 min-w-[200px]">Resep Obat (Item)</th>
                            <th class="px-6 py-5 text-center">Waktu Periksa</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($pemeriksaan as $item)
                        <tr class="hover:bg-emerald-50/50 transition-all group">
                            <td class="px-6 py-5 text-center font-bold text-slate-400 group-hover:text-emerald-600 transition-colors">
                                {{ $loop->iteration }}
                            </td>
                            
                            <td class="px-6 py-5">
                                <p class="font-black text-slate-900 uppercase leading-none">{{ $item->pendaftaran->nama_pasien ?? '-' }}</p>
                                <p class="text-[10px] text-slate-400 font-bold mt-1.5 font-mono tracking-tighter">NIK: {{ $item->pendaftaran->no_identitas ?? '-' }}</p>
                                <span class="mt-2 inline-flex text-[9px] font-black px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-md border border-emerald-200 uppercase tracking-wider">
                                    {{ $item->pendaftaran->poli ?? '-' }}
                                </span>
                            </td>

                            <td class="px-6 py-5">
                                <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                                    <div class="text-xs font-bold text-slate-600 leading-relaxed italic border-l-2 border-emerald-300 pl-2">
                                        "{{ $item->keluhan ?? 'Tidak ada keluhan' }}"
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-5">
                                <div class="bg-white border border-slate-200 rounded-xl p-3 shadow-sm">
                                    <div class="flex items-center gap-1.5 mb-1">
                                        <i class="ph-fill ph-heartbeat text-rose-500"></i>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Diagnosis</p>
                                    </div>
                                    <p class="text-xs font-bold text-slate-800 leading-tight mb-2">{{ $item->diagnosis }}</p>
                                    
                                    <div class="border-t border-slate-100 pt-2 flex items-center gap-1.5 mb-1">
                                        <i class="ph-fill ph-bandaids text-blue-500"></i>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Tindakan</p>
                                    </div>
                                    <p class="text-[11px] font-semibold text-slate-600">{{ $item->tindakan }}</p>
                                </div>
                            </td>

                            <td class="px-6 py-5">
                                @if($item->resep)
                                    <div class="flex flex-wrap gap-1.5">
                                        @php
                                            $obatArray = preg_split('/[\n,]+/', $item->resep);
                                        @endphp
                                        @foreach($obatArray as $obat)
                                            @if(trim($obat) !== "")
                                            <div class="flex items-center gap-1.5 px-2.5 py-1 bg-slate-50 border border-slate-200 text-slate-700 rounded-lg shadow-sm">
                                                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                                <span class="text-[10px] font-bold uppercase tracking-wide leading-none">
                                                    {{ trim($obat) }}
                                                </span>
                                            </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <div class="inline-flex items-center gap-1.5 text-slate-400 text-xs font-bold italic tracking-wide">
                                        <i class="ph-bold ph-prohibit"></i> Tanpa Resep
                                    </div>
                                @endif
                            </td>

                            <td class="px-6 py-5 text-center">
                                <p class="font-black text-slate-900 leading-none mb-1">{{ $item->created_at->translatedFormat('d M Y') }}</p>
                                <span class="inline-flex items-center justify-center gap-1 px-2.5 py-1 bg-slate-100 border border-slate-200 text-slate-500 rounded-md text-[10px] font-bold tracking-widest uppercase">
                                    <i class="ph-bold ph-clock"></i> {{ $item->created_at->format('H:i') }} WIB
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-24 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="bg-emerald-50 text-emerald-500 p-4 rounded-full mb-3 border border-emerald-100">
                                        <i class="ph-fill ph-file-x text-3xl"></i>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest mb-1">Data Tidak Ditemukan</h3>
                                    <p class="text-xs font-medium text-slate-500">Coba sesuaikan filter pencarian, poliklinik, atau tanggal.</p>
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
@endif
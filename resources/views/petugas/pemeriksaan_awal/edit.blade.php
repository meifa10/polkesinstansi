@extends('layouts.admin')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    body { 
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    /* Menghilangkan panah default pada tag summary */
    summary {
        list-style: none;
    }
    summary::-webkit-details-marker {
        display: none;
    }
    /* Animasi panah custom saat details dibuka */
    details[open] summary .accordion-icon {
        transform: rotate(180deg);
    }
</style>

<div class="p-6 lg:p-8 bg-slate-50 min-h-screen font-sans">

    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-8 gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-sm font-bold tracking-wide uppercase mb-3 border border-emerald-300">
                <span class="font-bold">Pemeriksaan Awal</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                Input Tanda Vital
            </div>
            <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight">
                Detail Pemeriksaan <span class="text-emerald-600">Pasien</span>
            </h1>
            <p class="text-slate-600 font-medium mt-3 text-base lg:text-lg">
                Silakan isi data klinis awal. Riwayat pendaftaran terdahulu pasien dapat dilihat di panel sebelah kanan.
            </p>
        </div>

        <a href="{{ route('petugas.pemeriksaan_awal.index') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-white text-slate-700 border-2 border-slate-300 rounded-xl text-sm font-bold hover:bg-slate-100 hover:text-slate-900 transition-all shadow-sm active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali ke Antrean
        </a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
        
        {{-- ================= KIRI: INFORMASI UTAMA & FORM INPUT ================= --}}
        <div class="xl:col-span-5 space-y-6">
            
            {{-- PROFIL CARD --}}
            <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200 overflow-hidden relative">
                <div class="h-28 bg-slate-900 w-full relative">
                    <div class="absolute -bottom-10 left-8">
                        <div class="w-20 h-20 bg-emerald-100 text-emerald-700 rounded-2xl border-4 border-white shadow-sm flex items-center justify-center text-3xl font-black">
                            {{ strtoupper(substr($pasien->nama_pasien ?? 'P', 0, 1)) }}
                        </div>
                    </div>
                </div>
                
                <div class="px-8 pt-14 pb-6">
                    <h2 class="text-xl font-extrabold text-slate-900 uppercase tracking-tight">{{ $pasien->nama_pasien ?? '-' }}</h2>
                    <div class="mt-2">
                        @if(isset($pasien->jenis_pasien) && (strtolower($pasien->jenis_pasien) == 'jkn' || strtolower($pasien->jenis_pasien) == 'bpjs'))
                            <span class="inline-flex px-2.5 py-1 rounded bg-emerald-100 text-emerald-700 text-xs font-black uppercase tracking-wider border border-emerald-300 shadow-sm">
                                {{ $pasien->jenis_pasien }}
                            </span>
                        @else
                            <span class="inline-flex px-2.5 py-1 rounded bg-blue-100 text-blue-700 text-xs font-black uppercase tracking-wider border border-blue-300 shadow-sm">
                                {{ $pasien->jenis_pasien ?? 'UMUM' }}
                            </span>
                        @endif
                        <span class="inline-flex ml-2 px-2.5 py-1 rounded bg-slate-100 text-slate-700 text-xs font-bold border border-slate-300 shadow-sm">
                            Poli Tujuan: {{ $pasien->poli ?? '-' }}
                        </span>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-4 border-t border-slate-100 pt-4">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">No. Identitas / KTP</p>
                            <p class="text-sm font-bold text-slate-800 font-mono tracking-tight">{{ $pasien->no_identitas ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Tanggal Lahir</p>
                            <p class="text-sm font-bold text-slate-800">
                                {{ isset($pasien->tanggal_lahir) ? \Carbon\Carbon::parse($pasien->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FORM PEMERIKSAAN VITAL SIGN --}}
            <div class="bg-white rounded-2xl shadow-md border-2 border-slate-200 p-6 md:p-8">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-slate-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <h3 class="text-lg font-extrabold text-slate-800">Input Data Klinis Pasien</h3>
                </div>

                <form action="{{ route('petugas.pemeriksaan_awal.update', $pasien->id) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-2 gap-4">
                        {{-- Berat Badan --}}
                        <div>
                            <label for="berat_badan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Berat Badan <span class="text-rose-500">*</span></label>
                            <div class="relative rounded-xl shadow-sm">
                                <input type="number" step="0.1" name="berat_badan" id="berat_badan" required
                                       class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-bold text-slate-800" 
                                       value="{{ old('berat_badan', $pasien->berat_badan) }}" placeholder="0.0">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <span class="text-xs font-bold text-slate-400">kg</span>
                                </div>
                            </div>
                            @error('berat_badan') <p class="text-rose-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        {{-- Tinggi Badan --}}
                        <div>
                            <label for="tinggi_badan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tinggi Badan <span class="text-rose-500">*</span></label>
                            <div class="relative rounded-xl shadow-sm">
                                <input type="number" name="tinggi_badan" id="tinggi_badan" required
                                       class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-bold text-slate-800" 
                                       value="{{ old('tinggi_badan', $pasien->tinggi_badan) }}" placeholder="0">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <span class="text-xs font-bold text-slate-400">cm</span>
                                </div>
                            </div>
                            @error('tinggi_badan') <p class="text-rose-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Tensi Darah --}}
                    <div>
                        <label for="tensi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tekanan Darah / Tensi <span class="text-rose-500">*</span></label>
                        <div class="relative rounded-xl shadow-sm">
                            <input type="text" name="tensi" id="tensi" required
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-bold text-slate-800" 
                                   value="{{ old('tensi', $pasien->tensi) }}" placeholder="Contoh: 120/80">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <span class="text-xs font-bold text-slate-400">mmHg</span>
                            </div>
                        </div>
                        @error('tensi') <p class="text-rose-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Keluhan Utama --}}
                    <div>
                        <label for="keluhan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Keluhan Utama <span class="text-rose-500">*</span></label>
                        <textarea name="keluhan" id="keluhan" rows="4" required
                                  class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-medium text-slate-800 placeholder-slate-400" 
                                  placeholder="Tuliskan alasan utama pasien datang atau keluhan yang dirasakan...">{{ old('keluhan', $pasien->keluhan) }}</textarea>
                        @error('keluhan') <p class="text-rose-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tombol Submit --}}
                    <button type="submit" 
                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-emerald-600 text-white rounded-xl text-sm font-extrabold hover:bg-emerald-700 shadow-md active:scale-[0.98] transition-all border border-emerald-600 mt-2">
                        Simpan & Teruskan ke Dokter
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        {{-- ================= KANAN: RIWAYAT KUNJUNGAN & MEDIS (ACCORDION) ================= --}}
        <div class="xl:col-span-7 space-y-8">
            
            {{-- SEKSI KUNJUNGAN & TRANSAKSI --}}
            <section class="bg-white rounded-2xl shadow-md border-2 border-slate-200 p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6 pb-5 border-b-2 border-slate-100">
                    <div class="bg-emerald-100 p-2 rounded-lg text-emerald-600 border border-emerald-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Riwayat Kunjungan Poliklinik</h2>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">Daftar riwayat pendaftaran poli yang pernah dilakukan pasien</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse($kunjungan ?? [] as $k)
                    {{-- ACCORDION KUNJUNGAN --}}
                    <details class="group bg-white border-2 border-slate-200 rounded-xl overflow-hidden shadow-sm" {{ $loop->first ? 'open' : '' }}>
                        
                        {{-- HEADER ACCORDION --}}
                        <summary class="flex justify-between items-center cursor-pointer p-5 bg-slate-50 hover:bg-slate-100 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-emerald-600 border border-slate-200 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-base font-extrabold text-slate-900">
                                        {{ isset($k->created_at) ? $k->created_at->translatedFormat('d M Y') : '-' }}
                                    </p>
                                    <div class="flex flex-wrap items-center gap-2 mt-1">
                                        <span class="inline-flex px-2 py-0.5 rounded bg-slate-200 text-slate-700 text-[10px] font-black uppercase border border-slate-300">
                                            Poli: {{ $k->poli ?? 'Umum' }}
                                        </span>
                                        @if(isset($k->status))
                                            <span class="text-xs text-slate-500 font-medium">Alur: <b class="text-slate-700">{{ strtoupper(str_replace('_', ' ', $k->status)) }}</b></span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="hidden md:block">
                                    @if(isset($k->status) && ($k->status === 'selesai' || $k->status_pembayaran === 'lunas'))
                                        <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest">Selesai / Lunas</span>
                                    @else
                                        <span class="text-xs font-bold text-amber-600 uppercase tracking-widest animate-pulse">Diproses</span>
                                    @endif
                                </div>
                                <div class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-200 text-slate-500 accordion-icon transition-transform duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </summary>

                        {{-- BODY ACCORDION --}}
                        <div class="p-5 border-t-2 border-slate-200 bg-white space-y-3">
                            <div class="text-sm text-slate-600">
                                Waktu Registrasi: <span class="font-bold text-slate-800">{{ isset($k->created_at) ? $k->created_at->format('H:i') : '-' }} WIB</span>
                            </div>
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Keluhan Tercatat:</p>
                                <p class="text-sm font-medium text-slate-700 italic">"{{ $k->keluhan ?? 'Tidak ada catatan keluhan.' }}"</p>
                            </div>
                            <div class="grid grid-cols-3 gap-2 pt-1 text-center">
                                <div class="p-2 bg-slate-50 rounded-lg border border-slate-100"><span class="block text-[9px] font-bold text-slate-400 uppercase">BB</span><span class="text-xs font-bold text-slate-800">{{ $k->berat_badan ?? '-' }} kg</span></div>
                                <div class="p-2 bg-slate-50 rounded-lg border border-slate-100"><span class="block text-[9px] font-bold text-slate-400 uppercase">TB</span><span class="text-xs font-bold text-slate-800">{{ $k->tinggi_badan ?? '-' }} cm</span></div>
                                <div class="p-2 bg-slate-50 rounded-lg border border-slate-100"><span class="block text-[9px] font-bold text-slate-400 uppercase">Tensi</span><span class="text-xs font-bold text-slate-800">{{ $k->tensi ?? '-' }} mmHg</span></div>
                            </div>
                        </div>
                    </details>
                    @empty
                    <div class="py-12 text-center border-2 border-dashed border-slate-300 rounded-xl bg-slate-50">
                        <div class="flex flex-col items-center justify-center">
                            <div class="bg-emerald-50 p-5 rounded-full mb-4 border border-emerald-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 mb-1 uppercase tracking-wide">Belum Ada Kunjungan</h3>
                            <p class="text-slate-500 text-sm">Pasien ini belum memiliki rekam data kunjungan/pendaftaran sebelumnya.</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </section>

            {{-- SEKSI REKAM MEDIS --}}
            <section class="bg-white rounded-2xl shadow-md border-2 border-slate-200 p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6 pb-5 border-b-2 border-slate-100">
                    <div class="bg-blue-100 p-2 rounded-lg text-blue-600 border border-blue-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">Riwayat Klinis & Rekam Medis</h2>
                </div>

                <div class="space-y-4">
                    @forelse($rekamMedis ?? [] as $rm)
                    {{-- ACCORDION REKAM MEDIS --}}
                    <details class="group bg-white border-2 border-slate-200 rounded-xl overflow-hidden shadow-sm" {{ $loop->first ? 'open' : '' }}>
                        
                        {{-- HEADER ACCORDION --}}
                        <summary class="flex justify-between items-center cursor-pointer p-5 bg-slate-50 hover:bg-slate-100 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-blue-600 border border-slate-200 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-base font-extrabold text-slate-900">
                                        {{ isset($rm->created_at) ? $rm->created_at->translatedFormat('d M Y') : '-' }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="inline-flex px-2 py-0.5 rounded bg-blue-100 text-blue-700 text-[10px] font-black uppercase border border-blue-200">
                                            Diagnosa Akhir
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-200 text-slate-500 accordion-icon transition-transform duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </summary>

                        {{-- BODY ACCORDION --}}
                        <div class="p-6 border-t-2 border-slate-200 bg-white space-y-4">
                            {{-- DIAGNOSIS & TINDAKAN MEDIS --}}
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Diagnosis Dokter</p>
                                    <p class="text-sm font-bold text-slate-800 leading-relaxed italic">"{{ $rm->diagnosis ?? '-' }}"</p>
                                </div>
                                
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tindakan Medis</p>
                                    <p class="text-sm font-medium text-slate-800 leading-relaxed">{{ $rm->tindakan ?? '-' }}</p>
                                </div>
                            </div>

                            {{-- RESEP OBAT --}}
                            @if(!empty($rm->resep))
                            <div class="pt-2">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Resep Obat Terkait</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(explode(',', $rm->resep) as $obat)
                                    <span class="px-2.5 py-1 bg-emerald-50 border border-emerald-200 text-xs font-bold text-emerald-800 rounded shadow-sm uppercase">
                                        {{ trim($obat) }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </details>
                    @empty
                    <div class="py-12 text-center border-2 border-dashed border-slate-300 rounded-xl bg-slate-50">
                        <div class="flex flex-col items-center justify-center">
                            <div class="bg-slate-200 p-5 rounded-full mb-4 border border-slate-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 mb-1 uppercase tracking-wide">Belum Ada Rekam Medis</h3>
                            <p class="text-slate-500 text-sm">Belum ada riwayat diagnosa klinis dari rekam medis dokter.</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</div>

@endsection
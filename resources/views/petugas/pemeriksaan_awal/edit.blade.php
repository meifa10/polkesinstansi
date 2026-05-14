@extends('layouts.petugas')

@section('content')
<!-- Google Fonts: Plus Jakarta Sans -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    .glass-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(226, 232, 240, 0.8);
    }
    .form-input-focus:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    }
    .medical-gradient {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    }
</style>

<div class="min-h-screen p-4 lg:p-8">
    <div class="max-w-4xl mx-auto">
        
        {{-- HEADER SECTION --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                    Pemeriksaan <span class="text-emerald-600">Vital Sign</span>
                </h1>
                <p class="text-slate-500 font-medium mt-1 flex items-center gap-2">
                    <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                    Lengkapi data dasar fisik pasien sebelum konsultasi dokter.
                </p>
            </div>
            
            <a href="{{ route('petugas.pemeriksaan_awal.index') }}" class="flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-emerald-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                KEMBALI KE DAFTAR
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- LEFT COLUMN: INFO PASIEN --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="medical-gradient rounded-[2.5rem] p-8 text-white shadow-xl relative overflow-hidden group">
                    <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-white/10 rounded-full blur-3xl transition-all"></div>
                    
                    <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-400 mb-6 relative z-10">Data Identitas</h3>
                    
                    <div class="space-y-6 relative z-10">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Nama Lengkap</p>
                            <p class="text-xl font-bold leading-tight">{{ $pasien->nama_pasien }}</p>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-1 bg-emerald-400 rounded-full"></div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Unit Layanan</p>
                                <p class="text-sm font-bold">{{ $pasien->poli }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">ID Pendaftaran</p>
                            <p class="text-sm font-bold font-mono">#REG-{{ str_pad($pasien->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>

                    <div class="mt-10 p-4 bg-white/5 rounded-2xl border border-white/10">
                        <p class="text-[10px] font-medium text-slate-300 italic uppercase tracking-wider text-center">Petugas Pemeriksa: {{ auth()->user()->name }}</p>
                    </div>
                </div>

                <div class="glass-card rounded-[2.5rem] p-8">
                    <h4 class="text-sm font-extrabold text-slate-800 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Instruksi Pengisian
                    </h4>
                    <ul class="text-xs text-slate-500 space-y-3 font-medium">
                        <li class="flex gap-2"><span>1.</span> Gunakan timbangan digital untuk BB.</li>
                        <li class="flex gap-2"><span>2.</span> Pastikan pasien berdiri tegak untuk TB.</li>
                        <li class="flex gap-2"><span>3.</span> Tulis keluhan secara spesifik dan padat.</li>
                    </ul>
                </div>
            </div>

            {{-- RIGHT COLUMN: FORM INPUT --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-[2.5rem] p-8 lg:p-12 shadow-sm border border-slate-100">
                    <form method="POST" action="{{ route('petugas.pemeriksaan_awal.update', $pasien->id) }}" class="space-y-8">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- BERAT BADAN --}}
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Berat Badan (kg)</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-300 group-focus-within:text-emerald-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                                        </svg>
                                    </div>
                                    <input type="number" step="0.1" name="berat_badan" required placeholder="0.0"
                                        class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none form-input-focus transition-all text-slate-700 font-bold placeholder:text-slate-300">
                                </div>
                            </div>

                            {{-- TINGGI BADAN --}}
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tinggi Badan (cm)</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-300 group-focus-within:text-emerald-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        </svg>
                                    </div>
                                    <input type="number" name="tinggi_badan" required placeholder="000"
                                        class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none form-input-focus transition-all text-slate-700 font-bold placeholder:text-slate-300">
                                </div>
                            </div>
                        </div>

                        {{-- KELUHAN --}}
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Keluhan Utama Pasien</label>
                            <div class="relative group">
                                <textarea name="keluhan" rows="5" required placeholder="Deskripsikan keluhan pasien secara detail..."
                                    class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-[2rem] outline-none form-input-focus transition-all text-slate-700 font-bold placeholder:font-medium placeholder:text-slate-300 resize-none"></textarea>
                            </div>
                        </div>

                        <div class="pt-6">
                            <button type="submit" 
                                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-5 rounded-[2rem] shadow-lg shadow-emerald-200 flex items-center justify-center gap-3 transition-all hover:scale-[1.02] active:scale-95">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                SIMPAN DATA PEMERIKSAAN
                            </button>
                            <p class="text-center text-[10px] text-slate-400 font-bold uppercase tracking-tighter mt-4">Data yang disimpan akan langsung diteruskan ke bagian verifikasi admin.</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
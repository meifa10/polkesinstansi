@extends('layouts.dokter')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    body { 
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
</style>

<div class="p-4 md:p-6 lg:p-10 bg-slate-50 min-h-screen font-sans">
    
    <div class="max-w-6xl mx-auto">
        
        {{-- ================= HEADER ================= --}}
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-sm font-bold tracking-wide uppercase mb-3 border border-emerald-200 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Pemeriksaan Medis
                </div>
                <h1 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight">
                    Formulir <span class="text-emerald-600">Pemeriksaan</span>
                </h1>
            </div>
            
            <div class="bg-slate-900 text-white px-6 py-4 rounded-2xl flex items-center gap-4 shadow-md">
                <div class="w-12 h-12 bg-slate-800 rounded-xl flex items-center justify-center text-emerald-400 font-black text-2xl uppercase border border-slate-700">
                    {{ substr($pasien->nama_pasien, 0, 1) }}
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-0.5">Pasien Saat Ini</p>
                    <p class="font-black text-lg leading-tight truncate max-w-[200px]">{{ $pasien->nama_pasien }}</p>
                </div>
            </div>
        </div>

        {{-- ================= VALIDATION ALERT ================= --}}
        @if(session('error'))
            <div class="mb-8 p-5 bg-rose-100 border-2 border-rose-300 text-rose-800 rounded-2xl flex items-start gap-3 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-rose-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="text-base font-black uppercase tracking-wide mb-1">Terjadi Kesalahan</p>
                    <p class="text-base font-semibold">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- ================= MAIN FORM CONTAINER ================= --}}
        <div class="bg-white rounded-3xl p-6 md:p-10 border border-slate-200 shadow-xl shadow-slate-200/50">
            
            <form method="POST" action="{{ route('dokter.pemeriksaan.store', $pasien->id) }}" class="space-y-12">
                @csrf

                {{-- 1. DATA VITAL (READ ONLY) --}}
                <div>
                    <h2 class="text-base font-black text-slate-900 uppercase tracking-widest mb-5 flex items-center gap-3 border-b-2 border-slate-100 pb-3">
                        <span class="w-3 h-3 rounded-full bg-blue-500"></span> Informasi Awal & Vitals
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                        {{-- BB & TB --}}
                        <div class="md:col-span-4 grid grid-cols-2 gap-5">
                            <div class="bg-slate-50 p-5 rounded-2xl border-2 border-slate-100 flex flex-col justify-center">
                                <span class="text-xs font-extrabold text-slate-500 uppercase tracking-widest mb-2">Berat Badan</span>
                                <div class="text-3xl font-black text-slate-900 flex items-baseline gap-1.5">
                                    {{ $pasien->berat_badan }} <span class="text-sm text-slate-400 font-bold">KG</span>
                                </div>
                            </div>
                            <div class="bg-slate-50 p-5 rounded-2xl border-2 border-slate-100 flex flex-col justify-center">
                                <span class="text-xs font-extrabold text-slate-500 uppercase tracking-widest mb-2">Tinggi Badan</span>
                                <div class="text-3xl font-black text-slate-900 flex items-baseline gap-1.5">
                                    {{ $pasien->tinggi_badan }} <span class="text-sm text-slate-400 font-bold">CM</span>
                                </div>
                            </div>
                        </div>

                        {{-- Keluhan --}}
                        <div class="md:col-span-8 bg-amber-50 p-6 rounded-2xl border-2 border-amber-100 flex flex-col justify-center">
                            <span class="text-xs font-extrabold text-amber-700 uppercase tracking-widest mb-3 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                                Keluhan Utama Pasien
                            </span>
                            <div class="text-amber-950 font-bold text-base md:text-lg leading-relaxed italic">
                                "{{ $pasien->keluhan }}"
                            </div>
                            <input type="hidden" name="keluhan" value="{{ $pasien->keluhan }}">
                        </div>
                    </div>
                </div>

                {{-- 2. DIAGNOSIS & TINDAKAN --}}
                <div>
                    <h2 class="text-base font-black text-slate-900 uppercase tracking-widest mb-5 flex items-center gap-3 border-b-2 border-slate-100 pb-3">
                        <span class="w-3 h-3 rounded-full bg-rose-500"></span> Diagnosis & Tindakan Medis
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Diagnosis Input --}}
                        <div class="space-y-3">
                            <label class="block text-sm font-extrabold text-slate-800 uppercase tracking-wider ml-1">Hasil Diagnosis Medis</label>
                            <textarea name="diagnosis" rows="4" required placeholder="Tuliskan diagnosa secara lengkap di sini..."
                                      class="w-full px-5 py-4 bg-white border-2 border-slate-200 rounded-2xl text-base font-bold text-slate-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all resize-none placeholder:text-slate-400 placeholder:font-semibold">{{ old('diagnosis') }}</textarea>
                        </div>
                        
                        {{-- Tindakan Select --}}
                        <div class="space-y-3">
                            <label class="block text-sm font-extrabold text-slate-800 uppercase tracking-wider ml-1">Tindakan Medis</label>
                            <div class="relative">
                                <select name="tindakan" required class="w-full px-5 py-4 bg-white border-2 border-slate-200 rounded-2xl text-base font-bold text-slate-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all appearance-none cursor-pointer">
                                    <option value="" disabled selected>-- Pilih Tindakan Medis --</option>
                                    @foreach($tindakan as $t)
                                        <option value="{{ $t }}" {{ old('tindakan') == $t ? 'selected' : '' }}>{{ $t }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3. RESEP OBAT --}}
                <div class="bg-slate-50 p-6 md:p-8 rounded-3xl border-2 border-slate-100">
                    <div class="flex flex-col md:flex-row md:items-center justify-between border-b-2 border-slate-200 pb-4 mb-6 gap-4">
                        <h2 class="text-base font-black text-slate-900 uppercase tracking-widest flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span> Form Resep Obat
                        </h2>
                        <button type="button" onclick="tambahObat()" class="inline-flex items-center justify-center gap-2 bg-emerald-100 text-emerald-800 hover:bg-emerald-600 hover:text-white px-5 py-3 rounded-xl text-sm font-black uppercase tracking-wider transition-colors border border-emerald-200 hover:border-emerald-600 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Baris Obat
                        </button>
                    </div>

                    <div id="obat-wrapper" class="space-y-5">
                        {{-- Baris Obat Pertama --}}
                        <div class="obat-row flex flex-col md:flex-row items-start md:items-end gap-5 p-5 bg-white border-2 border-slate-200 rounded-2xl relative transition-all hover:border-emerald-300">
                            
                            {{-- Pilih Obat (Datalist) --}}
                            <div class="flex-grow w-full md:w-auto space-y-2">
                                <label class="block text-sm font-extrabold text-slate-800 uppercase tracking-wider ml-1">Nama Obat</label>
                                <div class="relative">
                                    <input list="list-obat" class="search-obat w-full pl-5 pr-12 py-3.5 bg-white border-2 border-slate-200 rounded-xl text-base font-bold text-slate-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all placeholder:text-slate-400 placeholder:font-semibold" placeholder="Ketik nama obat..." oninput="syncObatId(this)" required>
                                    <input type="hidden" name="obat_id[]" class="real-id">
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Kuantitas (Qty) --}}
                            <div class="w-full md:w-32 space-y-2">
                                <label class="block text-sm font-extrabold text-slate-800 uppercase tracking-wider ml-1 md:text-center">Jumlah</label>
                                <input type="number" name="qty[]" placeholder="0" class="w-full px-4 py-3.5 bg-white border-2 border-slate-200 rounded-xl text-lg font-black text-slate-900 md:text-center focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all placeholder:text-slate-300" min="1" required>
                            </div>

                            {{-- Aturan Pakai --}}
                            <div class="w-full md:w-[35%] space-y-2">
                                <label class="block text-sm font-extrabold text-slate-800 uppercase tracking-wider ml-1">Aturan Pakai</label>
                                <input type="text" name="aturan_minum[]" placeholder="Contoh: 3 x 1 Sesudah Makan" class="w-full px-5 py-3.5 bg-white border-2 border-slate-200 rounded-xl text-base font-bold text-slate-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all placeholder:text-slate-400 placeholder:font-semibold" required>
                            </div>

                            {{-- Tombol Hapus --}}
                            <button type="button" onclick="hapusObat(this)" class="w-full md:w-auto mt-2 md:mt-0 flex items-center justify-center p-4 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white rounded-xl transition-colors border-2 border-rose-100 hover:border-rose-600" title="Hapus Obat Ini">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span class="md:hidden ml-2 text-base font-extrabold uppercase">Hapus Baris Obat</span>
                            </button>
                        </div>
                    </div>

                    {{-- Datalist Referensi Obat --}}
                    <datalist id="list-obat">
                        @foreach($obat as $o)
                            <option data-id="{{ $o->id }}" value="{{ $o->nama_obat }}">
                                Stok Tersedia: {{ $o->stok }} | Harga: Rp {{ number_format($o->harga) }}
                            </option>
                        @endforeach
                    </datalist>
                </div>

                {{-- 4. SUBMIT SECTION --}}
                <div class="pt-10 border-t-2 border-slate-100 flex flex-col items-end gap-3">
                    <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center gap-3 bg-emerald-600 hover:bg-emerald-700 text-white px-10 py-5 rounded-2xl text-base font-black uppercase tracking-widest transition-all active:scale-95 shadow-xl shadow-emerald-600/30">
                        Selesaikan Pemeriksaan
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                    <p class="text-slate-500 text-sm font-bold uppercase tracking-wider text-center md:text-right w-full md:w-auto">
                        Data akan otomatis diteruskan ke admin untuk proses pembayaran.
                    </p>
                </div>
                
            </form>
        </div>
    </div>
</div>

{{-- ================= JAVASCRIPT UNTUK OBAT ================= --}}
<script>
    // Sinkronisasi Input Datalist dengan Input Hidden (Obat ID)
    function syncObatId(input) {
        const val = input.value;
        const options = document.getElementById('list-obat').options;
        const hiddenInput = input.parentElement.querySelector('.real-id');
        
        hiddenInput.value = ""; // Reset value setiap mengetik
        for (let i = 0; i < options.length; i++) {
            if (options[i].value === val) {
                hiddenInput.value = options[i].getAttribute('data-id');
                break;
            }
        }
    }

    // Fungsi Tambah Baris Obat
    function tambahObat() {
        const wrapper = document.getElementById('obat-wrapper');
        const rows = document.querySelectorAll('.obat-row');
        
        // Clone baris pertama
        const newRow = rows[0].cloneNode(true);
        
        // Kosongkan semua isi input pada baris hasil clone
        newRow.querySelectorAll('input').forEach(input => {
            input.value = '';
        });
        
        // Tambahkan elemen ke dalam form wrapper
        wrapper.appendChild(newRow);
    }

    // Fungsi Hapus Baris Obat
    function hapusObat(btn) {
        const rows = document.querySelectorAll('.obat-row');
        // Berikan validasi agar minimal tersisa 1 input baris
        if (rows.length > 1) {
            btn.closest('.obat-row').remove();
        } else {
            alert('Minimal harus ada satu baris input obat. Kosongkan isinya saja jika tidak memberikan resep obat.');
        }
    }
</script>

@endsection
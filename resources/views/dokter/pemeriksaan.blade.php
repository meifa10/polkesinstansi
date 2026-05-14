@extends('layouts.dokter')

@section('content')
<!-- Google Fonts & Font Awesome -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; color: #0f172a; }
    
    /* Card & Container */
    .card-container { background: white; border-radius: 2.5rem; padding: 3rem; border: 1px solid #e2e8f0; box-shadow: 0 10px 30px rgba(0,0,0,0.03); }
    
    /* Typography */
    .label-custom { font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: #64748b; margin-bottom: 8px; display: block; }
    .text-value { color: #0f172a; font-weight: 800; }
    
    /* Form Elements */
    .input-custom { 
        width: 100%; border: 2px solid #e2e8f0; border-radius: 1.25rem; padding: 1rem 1.25rem; 
        font-size: 15px; font-weight: 700; color: #0f172a; background: #ffffff; transition: all 0.2s ease; 
    }
    .input-custom:focus { outline: none; border-color: #10b981; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1); }
    
    /* Obat Section */
    .obat-row { background: #f8fafc; border: 2px solid #f1f5f9; border-radius: 1.5rem; padding: 2rem; margin-bottom: 1.5rem; position: relative; }
    .btn-delete { color: #ef4444; transition: all 0.2s; padding: 0.5rem; border-radius: 0.75rem; }
    .btn-delete:hover { background: #fee2e2; }
</style>

<div class="p-6 lg:p-10 max-w-6xl mx-auto">
    
    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-10 gap-4">
        <div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">Formulir Pemeriksaan</h1>
            <p class="text-slate-500 font-bold text-lg">Pasien: <span class="text-emerald-600">{{ $pasien->nama_pasien }}</span></p>
        </div>
        <div class="bg-slate-900 text-white px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl">
            Unit: {{ $pasien->poli }}
        </div>
    </div>

    {{-- VALIDATION ALERT --}}
    @if(session('error'))
        <div class="mb-8 p-5 bg-red-50 border-2 border-red-200 text-red-700 rounded-[1.5rem] flex items-center gap-4">
            <i class="fa-solid fa-circle-exclamation text-2xl"></i>
            <p class="font-bold">{{ session('error') }}</p>
        </div>
    @endif

    <div class="card-container">
        <form method="POST" action="{{ route('dokter.pemeriksaan.store', $pasien->id) }}" class="space-y-12">
            @csrf

            {{-- 1. DATA VITAL (READ ONLY) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 bg-slate-50 p-10 rounded-[2rem] border border-slate-100">
                <div class="flex flex-col">
                    <span class="label-custom">Berat Badan</span>
                    <div class="text-3xl font-black text-slate-900">{{ $pasien->berat_badan }} <span class="text-slate-400 text-sm">KG</span></div>
                </div>
                <div class="flex flex-col">
                    <span class="label-custom">Tinggi Badan</span>
                    <div class="text-3xl font-black text-slate-900">{{ $pasien->tinggi_badan }} <span class="text-slate-400 text-sm">CM</span></div>
                </div>
                <div class="flex flex-col">
                    <span class="label-custom">Keluhan Pasien</span>
                    <div class="text-slate-700 font-bold italic text-lg leading-relaxed">"{{ $pasien->keluhan }}"</div>
                    <input type="hidden" name="keluhan" value="{{ $pasien->keluhan }}">
                </div>
            </div>

            {{-- 2. DIAGNOSIS & TINDAKAN --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-3">
                    <label class="label-custom ml-1">Hasil Diagnosis Medis</label>
                    <textarea name="diagnosis" rows="4" class="input-custom" required placeholder="Tulis diagnosa lengkap di sini...">{{ old('diagnosis') }}</textarea>
                </div>
                <div class="space-y-3">
                    <label class="label-custom ml-1">Tindakan Medis</label>
                    <select name="tindakan" class="input-custom appearance-none" required>
                        <option value="">-- Pilih Tindakan --</option>
                        @foreach($tindakan as $t)
                            <option value="{{ $t }}" {{ old('tindakan') == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- 3. RESEP OBAT --}}
            <div class="space-y-8">
                <div class="flex items-center justify-between border-b-2 border-slate-100 pb-4">
                    <h2 class="text-2xl font-black text-slate-900 flex items-center gap-3">
                        <i class="fa-solid fa-pills text-emerald-500"></i> Resep Obat
                    </h2>
                    <button type="button" onclick="tambahObat()" class="bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-wider transition-all flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Tambah Item
                    </button>
                </div>

                <div id="obat-wrapper">
                    <div class="obat-row flex flex-wrap lg:flex-nowrap items-end gap-6 shadow-sm border-2 border-transparent hover:border-emerald-100 transition-colors">
                        <div class="flex-1 min-w-[300px]">
                            <label class="label-custom">Pilih Nama Obat</label>
                            <div class="relative">
                                <input list="list-obat" class="input-custom pr-12 search-obat" placeholder="Cari obat..." oninput="syncObatId(this)" required>
                                <input type="hidden" name="obat_id[]" class="real-id">
                                <i class="fa-solid fa-magnifying-glass absolute right-5 top-1/2 -translate-y-1/2 text-slate-300"></i>
                            </div>
                        </div>

                        <div class="w-28">
                            <label class="label-custom text-center">Qty</label>
                            <input type="number" name="qty[]" placeholder="0" class="input-custom text-center" min="1" required>
                        </div>

                        <div class="flex-1 min-w-[200px]">
                            <label class="label-custom">Aturan Pakai</label>
                            <input type="text" name="aturan_minum[]" placeholder="3 x 1 (Sesudah Makan)" class="input-custom" required>
                        </div>

                        <button type="button" onclick="hapusObat(this)" class="btn-delete mb-1">
                            <i class="fa-solid fa-trash-can text-xl"></i>
                        </button>
                    </div>
                </div>

                <datalist id="list-obat">
                    @foreach($obat as $o)
                        <option data-id="{{ $o->id }}" value="{{ $o->nama_obat }}">
                            Stok: {{ $o->stok }} | Rp {{ number_format($o->harga) }}
                        </option>
                    @endforeach
                </datalist>
            </div>

            {{-- 4. SUBMIT SECTION --}}
            <div class="pt-10 flex flex-col items-end gap-4 border-t-2 border-slate-100">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-16 py-6 rounded-[1.5rem] font-black uppercase tracking-[0.2em] text-sm shadow-2xl shadow-emerald-200 transition-all transform hover:-translate-y-1 active:scale-95">
                    Simpan & Selesaikan Pemeriksaan <i class="fa-solid fa-chevron-right ml-2"></i>
                </button>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-tighter">Pasien akan otomatis diarahkan ke antrean pembayaran admin</p>
            </div>
        </form>
    </div>
</div>

<script>
    // Fungsi sinkronisasi ID Obat dengan Datalist
    function syncObatId(input) {
        const val = input.value;
        const options = document.getElementById('list-obat').options;
        const hiddenInput = input.parentElement.querySelector('.real-id');
        
        hiddenInput.value = ""; // Reset setiap kali mengetik
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
        const newRow = rows[0].cloneNode(true);
        
        // Bersihkan input pada baris baru
        newRow.querySelectorAll('input').forEach(input => {
            input.value = '';
        });
        
        // Pastikan hidden ID juga kosong
        newRow.querySelector('.real-id').value = '';
        
        wrapper.appendChild(newRow);
    }

    // Fungsi Hapus Baris Obat
    function hapusObat(btn) {
        const rows = document.querySelectorAll('.obat-row');
        if (rows.length > 1) {
            btn.closest('.obat-row').remove();
        } else {
            alert('Minimal harus ada satu item obat atau kosongkan baris jika tidak meresepkan obat.');
        }
    }
</script>

@endsection
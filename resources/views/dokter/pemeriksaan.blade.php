@extends('layouts.dokter')

@section('content')

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f1f5f9; }
    .card { background: white; border-radius: 35px; padding: 45px; box-shadow: 0 20px 50px rgba(0,0,0,.06); }
    .label { font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: #475569; margin-bottom: 10px; display: block; }
    .input { width: 100%; border: 2px solid #e2e8f0; border-radius: 18px; padding: 14px 18px; font-size: 15px; font-weight: 700; color: #0f172a; background: #ffffff; transition: all 0.3s ease; }
    .input:focus { outline: none; border-color: #10b981; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1); }
    .obat-card { background: #ffffff; border: 2px solid #e2e8f0; border-radius: 24px; padding: 24px; margin-bottom: 15px; }
</style>

<div class="p-10 max-w-6xl mx-auto">
    
    {{-- ALERT ERROR --}}
    @if ($errors->any())
        <div class="mb-8 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-xl">
            <p class="font-bold">Ada kesalahan input:</p>
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex items-center justify-between mb-10">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Pemeriksaan Medis</h1>
            <p class="text-slate-600 font-bold text-sm">Pasien: <span class="text-slate-900">{{ $pasien->nama_pasien }}</span></p>
        </div>
        <div class="bg-emerald-600 text-white px-6 py-2 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-emerald-200">
            {{ $pasien->poli }}
        </div>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('dokter.pemeriksaan.store', $pasien->id) }}" class="space-y-10">
            @csrf

            {{-- DATA VITAL --}}
            <div class="bg-slate-50 p-8 rounded-3xl space-y-6">
                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <label class="label">Berat Badan</label>
                        <div class="font-extrabold text-slate-900 text-2xl">{{ $pasien->berat_badan }} <span class="text-slate-500 text-sm">Kg</span></div>
                    </div>
                    <div>
                        <label class="label">Tinggi Badan</label>
                        <div class="font-extrabold text-slate-900 text-2xl">{{ $pasien->tinggi_badan }} <span class="text-slate-500 text-sm">Cm</span></div>
                    </div>
                </div>
                <div class="border-t border-slate-200 pt-6">
                    <label class="label">Keluhan Utama</label>
                    <p class="text-slate-800 font-bold text-lg italic">"{{ $pasien->keluhan }}"</p>
                    {{-- SOLUSI: Input hidden agar field keluhan tidak kosong saat divalidasi Controller --}}
                    <input type="hidden" name="keluhan" value="{{ $pasien->keluhan }}">
                </div>
            </div>

            {{-- DIAGNOSIS & TINDAKAN --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div>
                    <label class="label">Diagnosis Dokter</label>
                    <textarea name="diagnosis" rows="4" class="input" required placeholder="Tuliskan hasil diagnosa medis..."></textarea>
                </div>
                <div>
                    <label class="label">Tindakan Medis</label>
                    <select name="tindakan" class="input" required>
                        <option value="">Pilih Tindakan...</option>
                        @foreach($tindakan as $t)
                            <option value="{{ $t }}">{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- RESEP OBAT --}}
            <div class="space-y-6">
                <div class="flex items-center justify-between px-2">
                    <h2 class="text-2xl font-black text-slate-900 italic">Resep Obat</h2>
                    <button type="button" onclick="tambahObat()" class="bg-slate-900 hover:bg-black text-white px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-wider transition-all flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Tambah Obat
                    </button>
                </div>

                <div id="obat-wrapper">
                    <div class="obat-card flex flex-wrap md:flex-nowrap items-end gap-6 relative shadow-sm">
                        <div class="w-full md:flex-1">
                            <label class="label">Pilih Nama Obat</label>
                            <div class="relative">
                                <input list="list-obat" class="input" placeholder="Cari obat..." oninput="syncObatId(this)" required>
                                <input type="hidden" name="obat_id[]" class="real-id" required>
                                <i class="fa-solid fa-magnifying-glass absolute right-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            </div>
                        </div>

                        <div class="w-28">
                            <label class="label">Jumlah</label>
                            <input type="number" name="qty[]" placeholder="0" class="input text-center" required>
                        </div>

                        <div class="w-full md:w-80">
                            <label class="label">Aturan Pakai</label>
                            <input type="text" name="aturan_minum[]" placeholder="3 x 1" class="input" required>
                        </div>

                        <button type="button" onclick="hapusObat(this)" class="mb-1 w-12 h-12 flex items-center justify-center text-red-500 hover:bg-red-50 rounded-xl transition-all">
                            <i class="fa-solid fa-trash-can text-lg"></i>
                        </button>
                    </div>
                </div>

                <datalist id="list-obat">
                    @foreach($obat as $o)
                        <option data-id="{{ $o->id }}" value="{{ $o->nama_obat }}">
                            Stok: {{ $o->stok }} | Harga: Rp{{ number_format($o->harga) }}
                        </option>
                    @endforeach
                </datalist>
            </div>

            <div class="pt-10 flex justify-end">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-16 py-5 rounded-2xl font-black uppercase tracking-[0.2em] text-sm shadow-xl transition-all transform hover:-translate-y-1 active:scale-95">
                    Selesaikan Pemeriksaan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function syncObatId(input) {
        const val = input.value;
        const options = document.getElementById('list-obat').options;
        const hiddenInput = input.parentElement.querySelector('.real-id');
        hiddenInput.value = ""; 
        for (let i = 0; i < options.length; i++) {
            if (options[i].value === val) {
                hiddenInput.value = options[i].getAttribute('data-id');
                break;
            }
        }
    }

    function tambahObat() {
        const wrapper = document.getElementById('obat-wrapper');
        const items = document.querySelectorAll('.obat-card');
        const newItem = items[0].cloneNode(true);
        newItem.querySelectorAll('input').forEach(input => input.value = '');
        wrapper.appendChild(newItem);
    }

    function hapusObat(btn) {
        const items = document.querySelectorAll('.obat-card');
        if (items.length > 1) {
            btn.closest('.obat-card').remove();
        }
    }
</script>

@endsection
@extends('layouts.dokter')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #f8fafc; 
    }

    .bg-pattern {
        background-image: radial-gradient(#e2e8f0 1.2px, transparent 1.2px);
        background-size: 24px 24px;
    }

    .glass-card {
        background: rgba(255, 255, 255, 1); 
        border: 2px solid #f1f5f9; 
        border-radius: 32px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.04);
    }

    .input-field {
        width: 100%;
        background: #f1f5f9; 
        border: 2px solid transparent;
        border-radius: 20px;
        padding: 22px;
        font-size: 15px; 
        font-weight: 700; 
        color: #1e293b; 
        line-height: 1.6;
        transition: all .3s ease;
    }

    .input-field:focus {
        outline: none;
        border-color: #10b981;
        background: white;
        box-shadow: 0 0 0 5px rgba(16,185,129, 0.1);
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 12px; 
        font-weight: 800;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: #64748b; 
        margin-bottom: 12px;
    }

    .btn-submit {
        transition: all .3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        background-color: #059669;
        box-shadow: 0 15px 35px rgba(16, 185, 129, 0.3);
    }
</style>

<div class="min-h-screen bg-pattern p-6 md:p-12">
    <div class="max-w-4xl mx-auto space-y-10">

        {{-- TOP NAV --}}
        <div class="flex items-center justify-between px-2">
            <a href="{{ url()->previous() }}" class="flex items-center gap-3 text-slate-500 hover:text-emerald-600 transition group">
                <div class="w-10 h-10 rounded-full border-2 border-slate-200 flex items-center justify-center group-hover:border-emerald-500 transition">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                </div>
                <span class="text-[12px] font-black uppercase tracking-widest">Kembali</span>
            </a>

            <div class="text-right">
                <p class="text-[11px] font-black text-emerald-600 uppercase tracking-widest mb-1">Sesi Pemeriksaan Aktif</p>
                <p class="text-sm font-extrabold text-slate-500 tracking-tight">
                    {{ now()->format('d M Y') }} <span class="text-slate-300 mx-1">|</span> {{ now()->format('H:i') }} WIB
                </p>
            </div>
        </div>

        {{-- HEADER PASIEN --}}
        <div class="relative bg-slate-900 rounded-[35px] p-10 text-white flex flex-col md:flex-row items-center justify-between gap-8 shadow-2xl overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/10 rounded-full -mr-20 -mt-20 blur-3xl"></div>
            
            <div class="relative flex items-center gap-8">
                <div class="w-20 h-20 bg-emerald-500 rounded-3xl flex items-center justify-center text-3xl shadow-2xl shadow-emerald-500/40">
                    <i class="fa-solid fa-user-doctor"></i>
                </div>
                <div>
                    <p class="text-[11px] font-black uppercase tracking-[0.4em] text-emerald-400/80 mb-2">Nama Pasien</p>
                    <h1 class="text-3xl md:text-4xl font-black tracking-tight uppercase">{{ $pasien->nama_pasien }}</h1>
                </div>
            </div>

            <div class="relative flex flex-col items-center md:items-end gap-2">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Unit Pelayanan</span>
                <div class="px-8 py-3 bg-emerald-500/10 border-2 border-emerald-500/20 rounded-2xl">
                    <p class="text-lg font-black text-emerald-400 tracking-tight">{{ $pasien->poli }}</p>
                </div>
            </div>
        </div>

        {{-- FORM CARD --}}
        <div class="glass-card p-8 md:p-16">
            <form method="POST" action="{{ route('dokter.pemeriksaan.store', $pasien->id) }}" class="space-y-12">
                @csrf

                {{-- KELUHAN --}}
                <div class="group">
                    <label class="form-label group-focus-within:text-emerald-600 transition-colors">
                        <i class="fa-solid fa-comment-medical text-emerald-500 text-lg"></i>
                        01. Keluhan Pasien
                    </label>
                    <textarea name="keluhan" rows="4" class="input-field shadow-sm"
                        placeholder="Apa yang dikeluhkan pasien saat ini?"
                        required oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                </div>

                {{-- DIAGNOSIS --}}
                <div class="group">
                    <label class="form-label group-focus-within:text-rose-600 transition-colors">
                        <i class="fa-solid fa-stethoscope text-rose-500 text-lg"></i>
                        02. Diagnosis Medis
                    </label>
                    <textarea name="diagnosis" rows="4" class="input-field shadow-sm"
                        placeholder="Tuliskan hasil diagnosa penyakit..."
                        required oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                </div>

                {{-- TINDAKAN --}}
                <div class="group">
                    <label class="form-label group-focus-within:text-blue-600 transition-colors">
                        <i class="fa-solid fa-syringe text-blue-500 text-lg"></i>
                        03. Tindakan / Terapi
                    </label>
                    <textarea name="tindakan" rows="3" class="input-field shadow-sm"
                        placeholder="Tindakan medis yang dilakukan dokter..."
                        required oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                </div>

                {{-- RESEP --}}
                <div class="group">
                    <label class="form-label group-focus-within:text-amber-600 transition-colors">
                        <i class="fa-solid fa-pills text-amber-500 text-lg"></i>
                        04. Resep Obat & Dosis
                    </label>
                    <textarea name="resep" rows="3" class="input-field shadow-sm"
                        placeholder="Contoh: Paracetamol 500mg (3x1 sehari sesudah makan)"
                        oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                </div>

                {{-- FOOTER --}}
                <div class="flex flex-col md:flex-row items-center justify-between pt-12 border-t-2 border-slate-50 gap-8">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-inner">
                            <i class="fa-solid fa-fingerprint text-xl"></i>
                        </div>
                        <div>
                            <p class="text-[12px] font-black text-slate-700 uppercase tracking-wide leading-none mb-1">Autentikasi Digital</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">Dokter: {{ Auth::user()->name ?? 'Dr. Pelaksana' }}</p>
                        </div>
                    </div>

                    <button type="submit"
                        class="btn-submit w-full md:w-auto flex items-center justify-center gap-4 bg-slate-900 hover:bg-emerald-600 text-white px-14 py-5 rounded-[22px] font-black text-xs uppercase tracking-[0.25em] shadow-2xl transition-all active:scale-95">
                        <i class="fa-solid fa-check-double text-emerald-400"></i>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('textarea').forEach(el => {
        el.style.height = el.scrollHeight + 'px';
    });
</script>
@endsection
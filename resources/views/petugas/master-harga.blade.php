@extends('layouts.petugas')

@section('content')
<div class="p-6 lg:p-8 bg-slate-50 min-h-screen font-sans">

    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl flex items-center gap-3 shadow-sm transform transition-all animate-fade-in-down">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-8 gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100/80 text-emerald-800 text-xs font-bold tracking-wider uppercase mb-3 border border-emerald-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M8.433 7.418c.554-.389 1.148-.767 1.567-1.126 1.172-1.006 1.316-2.18 1.065-2.868-.242-.654-.755-1.012-1.314-1.012-.314 0-.616.111-.849.317-.417.367-.611.97-.611 1.777v.105c0 .324-.263.586-.586.586H6.586A.586.586 0 016 4.512v-.105c0-1.593.593-2.82 1.543-3.654C8.113.242 8.797 0 9.751 0c1.41 0 2.502.835 3.018 2.231.517 1.397.234 3.197-1.314 4.526-.412.353-.943.71-1.464 1.053a17.206 17.206 0 00-1.782 1.3c-.09.078-.178.16-.264.24H12a1 1 0 011 1v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-1a4 4 0 012.433-3.682z" />
                </svg>
                Konfigurasi Keuangan
            </div>
            <h1 class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">
                Master Tarif <span class="text-emerald-600">Jasa & Layanan</span>
            </h1>
            <p class="text-slate-500 font-medium mt-2 text-sm lg:text-base max-w-xl">
                Sesuaikan nominal standar biaya konsultasi dokter dan administrasi rekam medis instansi secara dinamis.
            </p>
        </div>
        
        <div id="statusBadge" class="hidden md:inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-amber-50 text-amber-700 border border-amber-200 text-sm font-semibold transition-opacity opacity-0">
            <span class="relative flex h-2.5 w-2.5">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
            </span>
            Mode Edit Aktif
        </div>
    </div>

    <div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-slate-200 p-6 lg:p-8">
        <form action="{{ route('petugas.master_harga.update') }}" method="POST" class="space-y-6" id="formTarif">
            @csrf
            @method('PUT')

            <div class="group">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">
                    Jasa Dokter & Konsultasi (IDR)
                </label>
                <p class="text-xs text-slate-400 font-medium mb-3">Pemeriksaan medis dasar operasional poliklinik</p>
                <div class="relative rounded-xl shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="text-slate-500 font-bold text-base">Rp</span>
                    </div>
                    <input type="number" name="biaya_dokter" value="{{ $biayaDokter->value }}" required min="0" disabled
                        class="tarif-input w-full pl-12 pr-5 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-lg font-bold text-slate-500 cursor-not-allowed transition-all outline-none">
                </div>
            </div>

            <div class="pt-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">
                    Administrasi Instansi (IDR)
                </label>
                <p class="text-xs text-slate-400 font-medium mb-3">Pencatatan rekam medis digital terintegrasi cloud</p>
                <div class="relative rounded-xl shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="text-slate-500 font-bold text-base">Rp</span>
                    </div>
                    <input type="number" name="biaya_admin" value="{{ $biayaAdmin->value }}" required min="0" disabled
                        class="tarif-input w-full pl-12 pr-5 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-lg font-bold text-slate-500 cursor-not-allowed transition-all outline-none">
                </div>
            </div>

            <div class="pt-8 border-t border-slate-100 flex flex-col-reverse md:flex-row justify-end gap-3">
                
                <button type="button" id="btnBatal" class="hidden w-full md:w-auto bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-6 py-3 rounded-xl font-bold text-sm transition-colors focus:ring-2 focus:ring-slate-200 outline-none">
                    Batal
                </button>

                <button type="submit" id="btnSimpan" class="hidden w-full md:w-auto bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3 rounded-xl font-bold text-sm transition-colors shadow-lg shadow-emerald-500/20 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 outline-none">
                    Simpan Perubahan
                </button>

                <button type="button" id="btnEdit" class="w-full md:w-auto bg-slate-800 hover:bg-slate-900 text-white px-8 py-3 rounded-xl font-bold text-sm transition-colors shadow-md flex items-center justify-center gap-2 focus:ring-2 focus:ring-slate-800 focus:ring-offset-2 outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Edit Tarif
                </button>

            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnEdit = document.getElementById('btnEdit');
        const btnBatal = document.getElementById('btnBatal');
        const btnSimpan = document.getElementById('btnSimpan');
        const inputs = document.querySelectorAll('.tarif-input');
        const statusBadge = document.getElementById('statusBadge');
        
        let originalValues = {};

        btnEdit.addEventListener('click', function() {
            btnEdit.classList.add('hidden');
            btnBatal.classList.remove('hidden');
            btnSimpan.classList.remove('hidden');
            
            statusBadge.classList.remove('opacity-0');

            inputs.forEach((input, index) => {
                originalValues[input.name] = input.value;
                
                input.disabled = false;
                
                input.classList.remove('bg-slate-50', 'text-slate-500', 'cursor-not-allowed', 'border-slate-200');
                input.classList.add('bg-white', 'text-slate-900', 'border-emerald-400', 'focus:ring-4', 'focus:ring-emerald-500/10', 'focus:border-emerald-500');
                
                if(index === 0) input.focus();
            });
        });

        btnBatal.addEventListener('click', function() {
            btnEdit.classList.remove('hidden');
            btnBatal.classList.add('hidden');
            btnSimpan.classList.add('hidden');

            statusBadge.classList.add('opacity-0');

            inputs.forEach(input => {
                input.value = originalValues[input.name];
                
                input.disabled = true;
                
                input.classList.remove('bg-white', 'text-slate-900', 'border-emerald-400', 'focus:ring-4', 'focus:ring-emerald-500/10', 'focus:border-emerald-500');
                input.classList.add('bg-slate-50', 'text-slate-500', 'cursor-not-allowed', 'border-slate-200');
            });
        });
    });
</script>
@endsection
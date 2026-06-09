@extends('layouts.petugas')

@section('content')
<div class="min-h-screen bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-emerald-50 via-slate-50 to-slate-100 p-6 lg:p-12 font-sans selection:bg-emerald-200 selection:text-emerald-900">
    <div class="max-w-4xl mx-auto">
        
        @if(session('success'))
            <div class="mb-8 bg-white/80 backdrop-blur-md border border-emerald-200/60 text-emerald-800 p-4 rounded-2xl flex items-center gap-4 shadow-sm transform transition-all animate-fade-in-down">
                <div class="bg-emerald-100/50 p-2 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-emerald-900">Berhasil Disimpan</h3>
                    <p class="text-sm font-medium text-emerald-700 opacity-90">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-6 mb-10">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-white/60 backdrop-blur-sm text-slate-600 text-xs font-bold tracking-widest uppercase mb-4 border border-slate-200/60 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Konfigurasi Keuangan
                </div>
                <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
                    Master Tarif <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">Jasa & Layanan</span>
                </h1>
                <p class="text-slate-500 font-medium mt-4 text-base leading-relaxed">
                    Kelola dan sesuaikan nominal standar biaya konsultasi medis serta administrasi sistem secara dinamis dan aman.
                </p>
            </div>
            
            <div id="statusBadge" class="hidden md:flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-amber-500/10 text-amber-700 border border-amber-500/20 text-sm font-bold transition-all duration-300 opacity-0 transform translate-y-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                Mode Edit Aktif
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white p-8 lg:p-12 relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-emerald-50 rounded-full blur-3xl opacity-60 pointer-events-none"></div>
            
            <form action="{{ route('petugas.master_harga.update') }}" method="POST" class="relative z-10 space-y-8" id="formTarif">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="group relative bg-slate-50/50 rounded-3xl p-6 border border-slate-100 hover:border-slate-200 transition-colors">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="bg-indigo-100/50 p-2.5 rounded-xl text-indigo-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Jasa Dokter
                                </label>
                                <p class="text-xs text-slate-400 font-medium mt-0.5">Pemeriksaan medis dasar</p>
                            </div>
                        </div>
                        
                        <div class="relative rounded-2xl shadow-sm group-focus-within:shadow-md transition-shadow">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <span class="text-slate-400 font-bold text-lg">Rp</span>
                            </div>
                            <input type="number" name="biaya_dokter" value="{{ $biayaDokter->value }}" required min="0" disabled
                                class="tarif-input w-full pl-14 pr-6 py-4 bg-white/50 border-2 border-slate-200/60 rounded-2xl text-2xl font-extrabold text-slate-400 cursor-not-allowed transition-all duration-300 outline-none">
                        </div>
                    </div>

                    <div class="group relative bg-slate-50/50 rounded-3xl p-6 border border-slate-100 hover:border-slate-200 transition-colors">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="bg-emerald-100/50 p-2.5 rounded-xl text-emerald-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Administrasi
                                </label>
                                <p class="text-xs text-slate-400 font-medium mt-0.5">Pencatatan RM Digital</p>
                            </div>
                        </div>

                        <div class="relative rounded-2xl shadow-sm group-focus-within:shadow-md transition-shadow">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <span class="text-slate-400 font-bold text-lg">Rp</span>
                            </div>
                            <input type="number" name="biaya_admin" value="{{ $biayaAdmin->value }}" required min="0" disabled
                                class="tarif-input w-full pl-14 pr-6 py-4 bg-white/50 border-2 border-slate-200/60 rounded-2xl text-2xl font-extrabold text-slate-400 cursor-not-allowed transition-all duration-300 outline-none">
                        </div>
                    </div>
                </div>

                <div class="pt-8 mt-4 border-t border-slate-100/60 flex flex-col-reverse md:flex-row justify-end gap-4 items-center">
                    <button type="button" id="btnBatal" class="hidden w-full md:w-auto px-8 py-4 rounded-2xl font-bold text-sm text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition-all duration-200 outline-none">
                        Batalkan
                    </button>

                    <button type="submit" id="btnSimpan" class="hidden w-full md:w-auto bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white px-10 py-4 rounded-2xl font-bold text-sm transition-all duration-300 shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/50 hover:-translate-y-0.5 outline-none">
                        Simpan Perubahan Tarif
                    </button>

                    <button type="button" id="btnEdit" class="w-full md:w-auto bg-slate-900 hover:bg-slate-800 text-white px-10 py-4 rounded-2xl font-bold text-sm transition-all duration-300 shadow-xl shadow-slate-900/20 hover:shadow-slate-900/40 hover:-translate-y-0.5 flex items-center justify-center gap-2 outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Tarif
                    </button>
                </div>
            </form>
        </div>
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
            
            statusBadge.classList.remove('opacity-0', 'translate-y-2');
            statusBadge.classList.add('opacity-100', 'translate-y-0');

            inputs.forEach((input, index) => {
                originalValues[input.name] = input.value;
                input.disabled = false;
                
                input.classList.remove('bg-white/50', 'text-slate-400', 'cursor-not-allowed', 'border-slate-200/60');
                input.classList.add('bg-white', 'text-slate-800', 'border-emerald-400', 'focus:ring-4', 'focus:ring-emerald-500/10', 'focus:border-emerald-500', 'shadow-sm');
                
                input.previousElementSibling.querySelector('span').classList.remove('text-slate-400');
                input.previousElementSibling.querySelector('span').classList.add('text-slate-800');

                if(index === 0) input.focus();
            });
        });

        btnBatal.addEventListener('click', function() {
            btnEdit.classList.remove('hidden');
            btnBatal.classList.add('hidden');
            btnSimpan.classList.add('hidden');

            statusBadge.classList.add('opacity-0', 'translate-y-2');
            statusBadge.classList.remove('opacity-100', 'translate-y-0');

            inputs.forEach(input => {
                input.value = originalValues[input.name];
                input.disabled = true;
                
                input.classList.remove('bg-white', 'text-slate-800', 'border-emerald-400', 'focus:ring-4', 'focus:ring-emerald-500/10', 'focus:border-emerald-500', 'shadow-sm');
                input.classList.add('bg-white/50', 'text-slate-400', 'cursor-not-allowed', 'border-slate-200/60');

                input.previousElementSibling.querySelector('span').classList.add('text-slate-400');
                input.previousElementSibling.querySelector('span').classList.remove('text-slate-800');
            });
        });
    });
</script>
@endsection
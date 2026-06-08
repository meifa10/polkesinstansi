@extends('layouts.petugas')

@section('content')
<div class="p-6 lg:p-8 bg-slate-50 min-h-screen font-sans">

    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl flex items-center gap-3 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-8 gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-sm font-bold tracking-wide uppercase mb-3 border border-emerald-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M8.433 7.418c.554-.389 1.148-.767 1.567-1.126 1.172-1.006 1.316-2.18 1.065-2.868-.242-.654-.755-1.012-1.314-1.012-.314 0-.616.111-.849.317-.417.367-.611.97-.611 1.777v.105c0 .324-.263.586-.586.586H6.586A.586.586 0 016 4.512v-.105c0-1.593.593-2.82 1.543-3.654C8.113.242 8.797 0 9.751 0c1.41 0 2.502.835 3.018 2.231.517 1.397.234 3.197-1.314 4.526-.412.353-.943.71-1.464 1.053a17.206 17.206 0 00-1.782 1.3c-.09.078-.178.16-.264.24H12a1 1 0 011 1v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-1a4 4 0 012.433-3.682z" />
                </svg>
                Konfigurasi Keuangan
            </div>
            <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight">
                Master Tarif <span class="text-emerald-600">Jasa & Layanan</span>
            </h1>
            <p class="text-slate-600 font-medium mt-3 text-base lg:text-lg">
                Sesuaikan nominal standar biaya konsultasi dokter dan administrasi rekam medis instansi secara dinamis.
            </p>
        </div>
    </div>

    <div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
        <form action="{{ route('petugas.master_harga.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide mb-2">
                    JASA DOKTER & KONSULTASI (IDR)
                </label>
                <p class="text-xs text-slate-400 font-semibold mb-3">Pemeriksaan medis dasar operasional poliklinik</p>
                <div class="relative rounded-xl shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="text-slate-500 font-extrabold text-base">Rp</span>
                    </div>
                    <input type="number" name="biaya_dokter" value="{{ $biayaDokter->value }}" required min="0"
                        class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-300 rounded-xl text-lg font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100">
                <label class="block text-sm font-bold text-slate-700 uppercase tracking-wide mb-2">
                    ADMINISTRASI INSTANSI (IDR)
                </label>
                <p class="text-xs text-slate-400 font-semibold mb-3">Pencatatan rekam medis digital terintegrasi cloud</p>
                <div class="relative rounded-xl shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="text-slate-500 font-extrabold text-base">Rp</span>
                    </div>
                    <input type="number" name="biaya_admin" value="{{ $biayaAdmin->value }}" required min="0"
                        class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-300 rounded-xl text-lg font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none">
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" class="w-full md:w-auto bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-4 rounded-xl font-bold text-base transition-colors shadow-lg shadow-emerald-500/20">
                    Simpan Perubahan Tarif
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

<div class="p-6 bg-slate-100 min-h-screen font-['Plus_Jakarta_Sans']">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
        <div>
            <nav class="flex text-[11px] text-slate-500 mb-1 gap-1 font-bold uppercase tracking-wider">
                <span>Admin</span>
                <span class="text-slate-400">/</span>
                <span class="text-emerald-700">Manajemen SDM</span>
            </nav>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                Data <span class="text-emerald-600">Dokter Praktik</span>
            </h1>
        </div>

        <button onclick="document.getElementById('modal').classList.remove('hidden')"
                class="flex items-center gap-2 px-6 py-3 bg-slate-900 hover:bg-emerald-600 text-white rounded-xl text-sm font-bold shadow-lg transition-all active:scale-95 border border-slate-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Data Praktik Dokter
        </button>
    </div>

    {{-- ================= GRID KARTU DOKTER ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($jadwal as $j)
        <div class="bg-white rounded-[2rem] shadow-md border border-slate-200 overflow-hidden group hover:border-emerald-500 transition-all duration-300">
            
            <div class="p-6 pb-0">
                <div class="flex justify-between items-start mb-4">
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-800 text-[10px] font-black rounded-lg border border-emerald-200 uppercase tracking-widest">
                        {{ $j->poli }}
                    </span>
                    
                    @if($j->buka_hari_ini)
                        <span class="flex items-center gap-1.5 px-3 py-1 bg-emerald-600 text-white text-[9px] font-black rounded-full shadow-md uppercase animate-pulse">
                            <span class="w-1.5 h-1.5 bg-white rounded-full"></span>
                            Buka Hari Ini
                        </span>
                    @endif
                </div>

                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-black text-slate-900 text-lg leading-tight uppercase tracking-tight">
                            {{ $j->dokter->name }}
                        </h2>
                        <p class="text-[11px] font-bold text-slate-400 tracking-widest uppercase italic">Tenaga Ahli Polkes</p>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-y border-slate-200 space-y-3">
                <div class="flex items-center gap-3 text-slate-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-xs font-black uppercase tracking-tight">{{ $j->hari }}</span>
                </div>
                <div class="flex items-center gap-3 text-slate-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-xs font-black tracking-tighter">{{ substr($j->jam_mulai,0,5) }} - {{ substr($j->jam_selesai,0,5) }} WIB</span>
                </div>
            </div>

            <div class="p-4 bg-white">
                <form method="POST" action="{{ route('admin.jadwal_dokter.toggle',$j->id) }}">
                    @csrf
                    @if($j->status == 'aktif')
                        <button class="w-full py-3 rounded-xl bg-red-100 hover:bg-red-600 text-red-800 hover:text-white text-[10px] font-black uppercase tracking-widest transition-all active:scale-95 border border-red-200">
                            Nonaktifkan Layanan
                        </button>
                    @else
                        <button class="w-full py-3 rounded-xl bg-emerald-100 hover:bg-emerald-600 text-emerald-800 hover:text-white text-[10px] font-black uppercase tracking-widest transition-all active:scale-95 border border-emerald-200">
                            Aktifkan Layanan
                        </button>
                    @endif
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- ================= MODAL TAMBAH JADWAL ================= --}}
<div id="modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-sm" onclick="document.getElementById('modal').classList.add('hidden')"></div>
    
    <div class="flex min-h-full items-center justify-center p-4">
        <form method="POST" action="{{ route('admin.jadwal_dokter.store') }}"
              class="relative bg-white w-full max-w-lg p-8 rounded-[2.5rem] shadow-2xl border border-slate-300 space-y-6">
            @csrf

            <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                <h2 class="text-xl font-black text-slate-900 uppercase tracking-tighter">Tambah Jadwal Baru</h2>
                <button type="button" onclick="document.getElementById('modal').classList.add('hidden')" class="text-slate-400 hover:text-red-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-5">
                {{-- Pilih Dokter --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase ml-1 tracking-widest">Nama Dokter Pelaksana</label>
                    <div class="relative">
                        <select name="dokter_id" class="w-full border-2 border-slate-200 p-3 rounded-xl text-sm font-bold text-slate-900 focus:border-slate-900 outline-none appearance-none bg-slate-50 cursor-pointer">
                            @foreach($dokter as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor font-bold"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                    </div>
                </div>

                {{-- Unit Poliklinik --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase ml-1 tracking-widest">Unit Poliklinik</label>
                    <div class="relative">
                        <select name="poli" class="w-full border-2 border-slate-200 p-3 rounded-xl text-sm font-bold text-slate-900 focus:border-slate-900 outline-none appearance-none bg-slate-50 cursor-pointer" required>
                            <option value="">-- Pilih Poliklinik --</option>
                            <option value="Poli Umum">Poli Umum</option>
                            <option value="Poli Gigi">Poli Gigi</option>
                            <option value="Poli KIA & KB">Poli KIA & KB</option>
                        </select>
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor font-bold"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                    </div>
                </div>

                {{-- Pilih Hari (Checkbox) --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase ml-1 tracking-widest">Pilih Hari Kerja</label>
                    <div class="grid grid-cols-4 sm:grid-cols-7 gap-2 bg-slate-50 p-4 rounded-xl border-2 border-slate-200">
                        @php $hari_list = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']; @endphp
                        @foreach($hari_list as $h)
                        <label class="flex flex-col items-center gap-1 cursor-pointer group">
                            <input type="checkbox" name="hari[]" value="{{ $h }}" 
                                   class="w-5 h-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer transition-all">
                            <span class="text-[9px] font-black text-slate-400 group-hover:text-slate-900 transition-colors uppercase">{{ substr($h, 0, 3) }}</span>
                        </label>
                        @endforeach
                    </div>
                    <p class="text-[9px] text-slate-400 font-bold italic">* Anda dapat memilih lebih dari satu hari sekaligus.</p>
                </div>

                {{-- Jam Praktik --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase ml-1 tracking-widest">Waktu Mulai</label>
                        <input type="time" name="jam_mulai" class="w-full border-2 border-slate-200 p-3 rounded-xl text-sm font-bold text-slate-900 focus:border-slate-900 outline-none bg-slate-50" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase ml-1 tracking-widest">Waktu Selesai</label>
                        <input type="time" name="jam_selesai" class="w-full border-2 border-slate-200 p-3 rounded-xl text-sm font-bold text-slate-900 focus:border-slate-900 outline-none bg-slate-50" required>
                    </div>
                </div>
            </div>

            <button class="w-full bg-slate-900 hover:bg-emerald-600 text-white py-4 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] shadow-xl transition-all active:scale-95 border border-slate-900">
                Verifikasi & Simpan Data
            </button>
        </form>
    </div>
</div>

<style>
    body { background-color: #f1f5f9; color: #0f172a; }
    * {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-rendering: optimizeLegibility;
    }
</style>
@endsection
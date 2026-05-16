@extends('layouts.admin')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    body { 
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
</style>

<div class="p-4 md:p-6 lg:p-8 bg-slate-50 min-h-screen font-sans">

    {{-- ================= HEADER SECTION ================= --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-8 gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold tracking-wide uppercase mb-2 border border-emerald-200 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Admin / Manajemen SDM
            </div>
            <h1 class="text-2xl md:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">
                Jadwal <span class="text-emerald-600">Dokter Praktik</span>
            </h1>
            <p class="text-slate-600 font-medium mt-1.5 text-sm md:text-base">
                Kelola jadwal operasional, hari kerja, dan status layanan dokter dengan mudah.
            </p>
        </div>

        <button onclick="document.getElementById('modalTambah').classList.remove('hidden')"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-slate-900 hover:bg-emerald-600 text-white rounded-xl text-sm font-bold shadow-md transition-all active:scale-95 border border-slate-900 hover:border-emerald-600 group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-400 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Jadwal Praktik
        </button>
    </div>

    {{-- ================= GRID KARTU DOKTER ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($jadwal as $j)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden group hover:border-emerald-400 transition-all duration-300 flex flex-col">
            
            {{-- Header Card --}}
            <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <span class="px-2.5 py-1 bg-white text-slate-700 text-[10px] font-black rounded-md border border-slate-200 uppercase tracking-widest shadow-sm">
                    {{ $j->poli }}
                </span>
                
                @if($j->buka_hari_ini)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-black rounded-md border border-emerald-200 uppercase tracking-widest animate-pulse">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                        Buka Hari Ini
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 text-slate-500 text-[10px] font-bold rounded-md border border-slate-200 uppercase tracking-widest">
                        <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>
                        Tutup
                    </span>
                @endif
            </div>

            {{-- Profil Dokter --}}
            <div class="p-5 flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 group-hover:bg-emerald-50 group-hover:text-emerald-600 group-hover:border-emerald-200 transition-all duration-300 flex-shrink-0 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="overflow-hidden">
                    <h2 class="font-extrabold text-slate-800 text-lg leading-tight uppercase truncate">
                        {{ $j->dokter->name ?? 'Dokter Terhapus' }}
                    </h2>
                    <p class="text-[11px] font-bold text-slate-400 tracking-widest uppercase mt-0.5">Tenaga Ahli Polkes</p>
                </div>
            </div>

            {{-- Info Waktu --}}
            <div class="px-5 py-3.5 bg-slate-50 border-y border-slate-100 space-y-2 flex-grow">
                <div class="flex items-start gap-2.5 text-slate-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <div class="flex flex-wrap gap-1">
                        @foreach(explode(', ', $j->hari) as $h)
                            <span class="text-[10px] font-bold bg-white border border-slate-200 px-1.5 py-0.5 rounded shadow-sm">{{ $h }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="flex items-center gap-2.5 text-slate-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-xs font-bold tracking-wide">{{ substr($j->jam_mulai,0,5) }} - {{ substr($j->jam_selesai,0,5) }} WIB</span>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="p-4 bg-white space-y-2">
                {{-- Edit & Hapus Row --}}
                <div class="flex gap-2">
                    {{-- Tombol Edit --}}
                    <button onclick="openEditModal({{ json_encode($j) }})" class="flex-1 flex items-center justify-center gap-1.5 py-2.5 bg-amber-50 text-amber-700 hover:bg-amber-500 hover:text-white rounded-lg text-[11px] font-bold uppercase tracking-wider transition-colors border border-amber-200 hover:border-amber-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Edit Data
                    </button>

                    {{-- Tombol Hapus --}}
                    <form method="POST" action="{{ route('admin.jadwal_dokter.destroy', $j->id) }}" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal dokter ini?')" class="w-full flex items-center justify-center gap-1.5 py-2.5 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white rounded-lg text-[11px] font-bold uppercase tracking-wider transition-colors border border-rose-200 hover:border-rose-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus
                        </button>
                    </form>
                </div>

                {{-- Status Toggle --}}
                <form method="POST" action="{{ route('admin.jadwal_dokter.toggle',$j->id) }}">
                    @csrf
                    @if($j->status == 'aktif')
                        <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-900 text-[10px] font-black uppercase tracking-widest transition-all active:scale-95 border border-slate-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Nonaktifkan Layanan
                        </button>
                    @else
                        <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-black uppercase tracking-widest transition-all active:scale-95 shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Aktifkan Layanan
                        </button>
                    @endif
                </form>
            </div>
        </div>
        @empty
        <div class="md:col-span-2 xl:col-span-3 py-20 text-center">
            <div class="flex flex-col items-center justify-center">
                <div class="bg-emerald-50 p-5 rounded-full mb-4 border border-emerald-100 text-emerald-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1 uppercase tracking-wide">Belum Ada Jadwal</h3>
                <p class="text-slate-500 text-sm">
                    Silakan tambahkan jadwal praktik dokter baru.
                </p>
            </div>
        </div>
        @endforelse
    </div>
</div>

{{-- ================= MODAL TAMBAH ================= --}}
<div id="modalTambah" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="document.getElementById('modalTambah').classList.add('hidden')"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden transform transition-all">
            <div class="bg-slate-900 px-6 py-4 flex justify-between items-center">
                <h2 class="text-lg font-black text-white uppercase tracking-wide">Tambah Jadwal Praktik</h2>
                <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="text-slate-400 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form method="POST" action="{{ route('admin.jadwal_dokter.store') }}" class="p-6 space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Pilih Dokter</label>
                    <select name="dokter_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all cursor-pointer">
                        <option value="" disabled selected>-- Pilih Dokter --</option>
                        @foreach($dokter as $d) 
                            <option value="{{ $d->id }}">{{ $d->name }}</option> 
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Poliklinik</label>
                    <select name="poli" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all cursor-pointer">
                        <option value="Poli Umum">Poli Umum</option>
                        <option value="Poli Gigi">Poli Gigi</option>
                        <option value="Poli KIA & KB">Poli KIA & KB</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Hari Kerja</label>
                    <div class="grid grid-cols-4 gap-3 bg-slate-50 p-4 rounded-xl border border-slate-200">
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $h)
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="hari[]" value="{{ $h }}" class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300 cursor-pointer"> 
                            <span class="text-xs font-bold text-slate-700 group-hover:text-emerald-600 transition-colors">{{ substr($h,0,3) }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all cursor-pointer" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all cursor-pointer" required>
                    </div>
                </div>
                
                <div class="pt-2">
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3.5 rounded-xl text-sm font-bold uppercase tracking-widest transition-all active:scale-95 shadow-md">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ================= MODAL EDIT ================= --}}
<div id="modalEdit" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeEditModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden transform transition-all">
            <div class="bg-amber-500 px-6 py-4 flex justify-between items-center">
                <h2 class="text-lg font-black text-white uppercase tracking-wide">Edit Jadwal Praktik</h2>
                <button type="button" onclick="closeEditModal()" class="text-amber-100 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form id="editForm" method="POST" action="" class="p-6 space-y-5">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Pilih Dokter</label>
                    <select name="dokter_id" id="edit_dokter_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all cursor-pointer">
                        @foreach($dokter as $d) 
                            <option value="{{ $d->id }}">{{ $d->name }}</option> 
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Poliklinik</label>
                    <select name="poli" id="edit_poli" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all cursor-pointer">
                        <option value="Poli Umum">Poli Umum</option>
                        <option value="Poli Gigi">Poli Gigi</option>
                        <option value="Poli KIA & KB">Poli KIA & KB</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Hari Kerja</label>
                    <div class="grid grid-cols-4 gap-3 bg-slate-50 p-4 rounded-xl border border-slate-200">
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $h)
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="hari[]" value="{{ $h }}" class="edit-hari-checkbox w-4 h-4 rounded text-amber-500 focus:ring-amber-500 border-slate-300 cursor-pointer"> 
                            <span class="text-xs font-bold text-slate-700 group-hover:text-amber-600 transition-colors">{{ substr($h,0,3) }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Jam Mulai</label>
                        <input type="time" name="jam_mulai" id="edit_jam_mulai" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all cursor-pointer" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Jam Selesai</label>
                        <input type="time" name="jam_selesai" id="edit_jam_selesai" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all cursor-pointer" required>
                    </div>
                </div>
                
                <div class="pt-2">
                    <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white py-3.5 rounded-xl text-sm font-bold uppercase tracking-widest transition-all active:scale-95 shadow-md">
                        Update Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditModal(jadwal) {
        const modal = document.getElementById('modalEdit');
        const form = document.getElementById('editForm');
        
        // Update Action URL
        form.action = `/admin/jadwal-dokter/${jadwal.id}`;
        
        // Fill Data
        document.getElementById('edit_dokter_id').value = jadwal.dokter_id;
        document.getElementById('edit_poli').value = jadwal.poli;
        document.getElementById('edit_jam_mulai').value = jadwal.jam_mulai.substring(0,5);
        document.getElementById('edit_jam_selesai').value = jadwal.jam_selesai.substring(0,5);
        
        // Checkboxes
        const hariArray = jadwal.hari.split(', ');
        const checkboxes = document.querySelectorAll('.edit-hari-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = hariArray.includes(cb.value);
        });
        
        modal.classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('modalEdit').classList.add('hidden');
    }
</script>

@endsection
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

    @if(session('error') || $errors->any())
        <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-xl space-y-1 shadow-sm">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <span class="font-bold text-sm">Terjadi Kesalahan:</span>
            </div>
            @foreach($errors->all() as $error)
                <p class="text-xs font-semibold pl-8">• {{ $error }}</p>
            @endforeach
            @if(session('error'))
                <p class="text-xs font-semibold pl-8">• {{ session('error') }}</p>
            @endif
        </div>
    @endif

    <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-8 gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-sm font-bold tracking-wide uppercase mb-3 border border-emerald-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                Katalog ICD-10
            </div>
            <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight">
                Master Data <span class="text-emerald-600">Penyakit</span>
            </h1>
            <p class="text-slate-600 font-medium mt-3 text-base lg:text-lg">
                Kelola kamus referensi diagnosa penyakit ICD-10 medis untuk mempermudah operasional pemeriksaan dokter.
            </p>
        </div>

        <div class="bg-white px-8 py-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-6 min-w-[220px]">
            <div class="p-4 bg-emerald-50 rounded-xl text-emerald-600 border border-emerald-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <p class="text-sm uppercase font-bold text-slate-400 tracking-wider">Total Penyakit</p>
                <h2 class="text-4xl font-black text-slate-800 leading-none mt-1">{{ $penyakit->total() }}</h2>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
        <div class="lg:col-span-12 bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            <div class="flex items-center gap-3 mb-6 pb-5 border-b border-slate-100">
                <div class="bg-emerald-100 p-2 rounded-lg text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-slate-800">Tambah Penyakit Referensi Baru</h2>
            </div>
            <form action="{{ route('petugas.master_penyakit.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-5 items-end">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Kode ICD-10</label>
                    <input type="text" name="kode_icd10" placeholder="Contoh: K04.0" required value="{{ old('kode_icd10') }}"
                        class="w-full px-5 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Nama Deskripsi Penyakit</label>
                    <input type="text" name="nama_penyakit" placeholder="Contoh: Pulpitis Radang Pulpa" required value="{{ old('nama_penyakit') }}"
                        class="w-full px-5 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Poli Layanan</label>
                    <div class="flex gap-2">
                        <div class="relative flex-1">
                            <select name="poli_tujuan" required class="w-full px-4 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none appearance-none cursor-pointer">
                                <option value="Poli Umum">Poli Umum</option>
                                <option value="Poli Gigi">Poli Gigi</option>
                                <option value="Poli KIA & KB">Poli KIA & KB</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-500">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <button type="submit" class="bg-emerald-600 text-white px-5 py-3.5 rounded-xl font-bold text-base hover:bg-emerald-700 shadow-lg shadow-emerald-500/30 transition-all flex items-center justify-center min-w-[3rem]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
        <div class="lg:col-span-6 bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col justify-center">
            <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Pencarian Cepat</label>
            <form action="{{ route('petugas.master_penyakit.index') }}" method="GET" class="flex flex-col gap-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-6 w-6 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode atau nama penyakit..."
                        class="w-full pl-12 pr-5 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none">
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-slate-800 text-white py-3.5 rounded-xl text-base font-bold hover:bg-slate-900 transition-colors shadow-md">
                        Cari Diagnosa
                    </button>
                    @if(request('q'))
                        <a href="{{ route('petugas.master_penyakit.index') }}" class="px-6 py-3.5 bg-slate-100 text-slate-600 rounded-xl text-base font-bold hover:bg-slate-200 transition-colors border border-slate-300 flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="lg:col-span-6 bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col justify-center">
            <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Import Data Massal (.CSV)</label>
            <form action="{{ route('petugas.master_penyakit.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
                @csrf
                <div class="relative">
                    <input type="file" name="file_excel" accept=".csv" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-600 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-emerald-600 text-white py-3.5 rounded-xl text-base font-bold hover:bg-emerald-700 transition-colors shadow-md">
                        Unggah & Proses File
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-emerald-900 text-white text-sm uppercase tracking-widest font-bold">
                        <th class="py-5 px-6 w-16 text-center rounded-tl-xl">No</th>
                        <th class="py-5 px-6 min-w-[150px]">Kode ICD-10</th>
                        <th class="py-5 px-6 min-w-[300px]">Nama Deskripsi Penyakit</th>
                        <th class="py-5 px-6 min-w-[180px]">Poliklinik</th>
                        <th class="py-5 px-6 min-w-[180px] rounded-tr-xl text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-base divide-y divide-slate-200">
                    @forelse($penyakit as $index => $item)
                        <tr class="hover:bg-emerald-50/60 transition-colors">
                            <td class="py-5 px-6 text-center text-slate-500 font-bold align-middle text-lg">
                                {{ ($penyakit->currentPage() - 1) * $penyakit->perPage() + $loop->iteration }}
                            </td>
                            <td class="py-5 px-6 align-middle font-mono font-black text-rose-700 text-lg">
                                {{ $item->kode_icd10 }}
                            </td>
                            <td class="py-5 px-6 align-middle">
                                <span class="font-extrabold text-slate-800 text-lg">{{ $item->nama_penyakit }}</span>
                            </td>
                            <td class="py-5 px-6 align-middle">
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-slate-100 border border-slate-300 text-slate-800 text-sm font-extrabold">
                                    {{ $item->poli_tujuan }}
                                </span>
                            </td>
                            <td class="py-5 px-6 align-middle">
                                <div class="flex items-center justify-center gap-3">
                                    <button type="button" onclick="openEditModal({{ $item->id }}, '{{ $item->kode_icd10 }}', '{{ addslashes($item->nama_penyakit) }}', '{{ $item->poli_tujuan }}')" 
                                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-100 text-amber-800 hover:bg-amber-500 hover:text-white font-bold rounded-xl transition-colors border border-amber-300 hover:border-amber-500 text-base shadow-sm">
                                        Edit
                                    </button>
                                    <form action="{{ route('petugas.master_penyakit.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data penyakit ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-rose-100 text-rose-800 hover:bg-rose-600 hover:text-white font-bold rounded-xl transition-colors border border-rose-300 hover:border-rose-600 text-base shadow-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-20 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="bg-emerald-50 p-6 rounded-full mb-5 border border-emerald-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-800 mb-2">Data Penyakit Tidak Ditemukan</h3>
                                    <p class="text-slate-500 text-base max-w-md">
                                        Belum ada data penyakit ICD-10 yang terdaftar atau pencarian Anda tidak membuahkan hasil.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {!! $penyakit->links() !!}
    </div>
</div>

<div id="editModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4 transition-all">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl border border-slate-200 overflow-hidden transform scale-95 opacity-0 transition-all duration-300" id="modalContainer">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
            <h3 class="font-bold text-slate-800 text-lg">Perbarui Informasi Penyakit</h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="editForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Kode ICD-10</label>
                <input type="text" name="kode_icd10" id="edit_kode_icd10" required
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Nama Deskripsi Penyakit</label>
                <input type="text" name="nama_penyakit" id="edit_nama_penyakit" required
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Poli Layanan</label>
                <div class="relative">
                    <select name="poli_tujuan" id="edit_poli_tujuan" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none appearance-none cursor-pointer">
                        <option value="Poli Umum">Poli Umum</option>
                        <option value="Poli Gigi">Poli Gigi</option>
                        <option value="Poli KIA & KB">Poli KIA & KB</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-500">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <button type="button" onclick="closeEditModal()" class="px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl text-sm transition-colors">
                    Batalkan
                </button>
                <button type="submit" class="px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm shadow-md transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(id, kode, nama, poli) {
        const modal = document.getElementById('editModal');
        const container = document.getElementById('modalContainer');
        const form = document.getElementById('editForm');
        
        form.action = `/petugas/master-penyakit/${id}`;
        document.getElementById('edit_kode_icd10').value = kode;
        document.getElementById('edit_nama_penyakit').value = nama;
        document.getElementById('edit_poli_tujuan').value = poli;
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            container.classList.remove('scale-95', 'opacity-0');
            container.classList.add('scale-100', 'opacity-100');
        }, 20);
    }

    function closeEditModal() {
        const modal = document.getElementById('editModal');
        const container = document.getElementById('modalContainer');
        
        container.classList.remove('scale-100', 'opacity-100');
        container.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endsection
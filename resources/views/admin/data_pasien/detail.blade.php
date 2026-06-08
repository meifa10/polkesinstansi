@extends('layouts.admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    summary { list-style: none; }
    summary::-webkit-details-marker { display: none; }
    details[open] summary .accordion-icon { transform: rotate(180deg); }
</style>

<div class="p-6 lg:p-8 bg-slate-50 min-h-screen">
    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-8 gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-sm font-bold uppercase mb-3 border border-emerald-300">
                <a href="{{ route('admin.data_pasien.index') }}" class="hover:text-emerald-600">Data Pasien</a>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                Profil Lengkap
            </div>
            <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight">Detail <span class="text-emerald-600">Pasien</span></h1>
            <p class="text-slate-600 font-medium mt-3 text-base lg:text-lg">Informasi profil lengkap, riwayat klinis, dan status transaksi administrasi.</p>
        </div>

        <a href="{{ route('admin.data_pasien.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-slate-700 border-2 border-slate-300 rounded-xl text-sm font-bold hover:bg-slate-100 transition-all shadow-sm active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" /></svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
        {{-- KIRI: INFORMASI UTAMA --}}
        <div class="xl:col-span-4 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden relative">
                <div class="h-32 bg-slate-900 w-full relative">
                    <div class="absolute -bottom-12 left-8">
                        <div class="w-24 h-24 bg-emerald-100 text-emerald-700 rounded-2xl border-4 border-white shadow-sm flex items-center justify-center text-4xl font-black">{{ strtoupper(substr($pasien->nama_pasien, 0, 1)) }}</div>
                    </div>
                </div>
                <div class="px-8 pt-16 pb-8">
                    <h2 class="text-2xl font-extrabold text-slate-900 uppercase tracking-tight">{{ $pasien->nama_pasien }}</h2>
                    <div class="mt-2">
                        <span class="inline-flex px-3 py-1 rounded-md {{ strtolower($pasien->jenis_pasien) == 'jkn' || strtolower($pasien->jenis_pasien) == 'bpjs' ? 'bg-emerald-100 text-emerald-700 border-emerald-300' : 'bg-blue-100 text-blue-700 border-blue-300' }} text-xs font-black uppercase border shadow-sm">Pasien {{ $pasien->jenis_pasien }}</span>
                    </div>
                    <div class="mt-8 space-y-6 border-t border-slate-100 pt-6">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 border flex items-center justify-center text-slate-500"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" /></svg></div>
                            <div><p class="text-xs font-bold text-slate-500 uppercase">Nomor Identitas</p><p class="text-base font-extrabold text-slate-800">{{ $pasien->no_identitas }}</p></div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 border flex items-center justify-center text-slate-500"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z" /></svg></div>
                            <div><p class="text-xs font-bold text-slate-500 uppercase">Tanggal Lahir</p><p class="text-base font-extrabold text-slate-800">{{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->translatedFormat('d F Y') }}</p></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-slate-900 rounded-2xl p-8 text-white shadow-md border-b-4 border-emerald-500">
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Total Kunjungan</p>
                <h3 class="text-5xl font-black">{{ $kunjungan->count() }}<span class="text-lg text-emerald-400 font-extrabold ml-2">KALI</span></h3>
            </div>
        </div>

        {{-- KANAN: RIWAYAT AKTIVITAS --}}
        <div class="xl:col-span-8 space-y-8">
            <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6 pb-5 border-b"><div class="bg-emerald-50 p-2 rounded-lg text-emerald-600 border"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012-2m-6 9l2 2 4-4" /></svg></div><h2 class="text-xl font-bold text-slate-800">Status Transaksi & Kunjungan</h2></div>
                <div class="space-y-4">
                    @forelse($kunjungan as $k)
                    <details class="group bg-slate-50 border rounded-xl overflow-hidden shadow-sm" {{ $loop->first ? 'open' : '' }}>
                        <summary class="flex justify-between items-center cursor-pointer p-5 hover:bg-slate-100 transition-colors">
                            <div class="flex items-center gap-4"><div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-emerald-600 border shadow-sm"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg></div><div><p class="font-extrabold text-slate-900">{{ $k->created_at->translatedFormat('d M Y') }}</p><span class="px-2 py-0.5 rounded bg-slate-200 text-[10px] font-black uppercase">{{ $k->poli }}</span></div></div>
                            <div class="flex items-center gap-4"><div class="hidden md:block">@if($k->pembayaran)<span class="text-xs font-bold {{ $k->pembayaran->status === 'lunas' ? 'text-emerald-600' : 'text-amber-600' }} uppercase">{{ $k->pembayaran->status }}</span>@endif</div><div class="w-8 h-8 flex items-center justify-center rounded-full bg-white border accordion-icon transition-transform"><svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div></div>
                        </summary>
                        <div class="p-5 border-t bg-white flex flex-col md:flex-row justify-between items-center gap-4">
                            <div class="text-sm text-slate-600">Waktu: <span class="font-bold text-slate-800">{{ $k->created_at->format('H:i') }} WIB</span></div>
                            @if($k->pembayaran)
                                <a href="{{ $k->pembayaran->status === 'lunas' ? route('admin.pembayaran.print', $k->pembayaran->id) : route('admin.pembayaran.show', $k->pembayaran->id) }}" class="px-4 py-2 text-xs font-bold rounded-lg border transition-all {{ $k->pembayaran->status === 'lunas' ? 'bg-emerald-600 text-white' : 'bg-white text-slate-700' }} shadow-sm">
                                    {{ $k->pembayaran->status === 'lunas' ? 'Cetak Struk' : 'Lihat Tagihan' }}
                                </a>
                            @endif
                        </div>
                    </details>
                    @empty
                    <div class="py-10 text-center border-2 border-dashed rounded-xl text-slate-500">Tidak ada data kunjungan.</div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
<script src="https://unpkg.com/@phosphor-icons/web"></script>

<div class="p-6 bg-slate-100 min-h-screen font-['Plus_Jakarta_Sans']">

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-6">

        <div>
            <nav class="flex items-center gap-1 text-[11px] uppercase tracking-wider font-bold text-slate-500 mb-2">
                <span>Admin</span>
                <span>/</span>
                <span class="text-emerald-600">Rekam Medis</span>
            </nav>

            <h1 class="text-3xl font-extrabold text-slate-900">
                Rekam <span class="text-emerald-600">Medis Pasien</span>
            </h1>

            <p class="text-sm text-slate-500 mt-1 font-medium">
                Riwayat pemeriksaan pasien Polkes Jombang.
            </p>
        </div>

        <div class="bg-slate-900 text-white px-6 py-4 rounded-2xl shadow-lg border-b-4 border-emerald-500">
            <p class="text-[10px] uppercase tracking-[0.2em] text-slate-400 font-bold">
                Total Data
            </p>

            <h2 class="text-2xl font-extrabold mt-1">
                {{ $pemeriksaan->count() }}
            </h2>
        </div>

    </div>

    {{-- FILTER --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-6">

        <form method="GET" action="{{ route('admin.pemeriksaan') }}">

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">

                {{-- SEARCH --}}
                <div class="md:col-span-6 relative">

                    <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari pasien, diagnosis, tindakan..."
                        class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-300 bg-slate-50 text-sm font-semibold text-slate-800 outline-none focus:border-emerald-500 focus:bg-white transition"
                    >

                </div>

                {{-- FILTER POLI --}}
                <div class="md:col-span-3 relative">

                    <select
                        name="poli"
                        onchange="this.form.submit()"
                        class="w-full pl-4 pr-10 py-3 rounded-xl border border-slate-300 bg-white text-sm font-bold text-slate-800 appearance-none outline-none focus:border-emerald-500"
                    >

                        <option value="">Semua Poli</option>

                        <option value="Poli Umum"
                            {{ request('poli') == 'Poli Umum' ? 'selected' : '' }}>
                            Poli Umum
                        </option>

                        <option value="Poli Gigi"
                            {{ request('poli') == 'Poli Gigi' ? 'selected' : '' }}>
                            Poli Gigi
                        </option>

                        <option value="Poli KIA & KB"
                            {{ request('poli') == 'Poli KIA & KB' ? 'selected' : '' }}>
                            Poli KIA & KB
                        </option>

                    </select>

                    <i class="ph ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>

                </div>

                {{-- FILTER TANGGAL --}}
                <div class="md:col-span-3">

                    <input
                        type="date"
                        name="tanggal_dari"
                        value="{{ request('tanggal_dari') }}"
                        onchange="this.form.submit()"
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-white text-sm font-semibold text-slate-800 outline-none focus:border-emerald-500"
                    >

                </div>

            </div>

        </form>

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full min-w-[1500px]">

                <thead class="bg-slate-900">

                    <tr>

                        <th class="px-5 py-4 text-center text-xs uppercase tracking-wider text-emerald-400 font-black">
                            No
                        </th>

                        <th class="px-5 py-4 text-left text-xs uppercase tracking-wider text-white font-black">
                            Pasien
                        </th>

                        <th class="px-5 py-4 text-left text-xs uppercase tracking-wider text-white font-black">
                            Poli
                        </th>

                        <th class="px-5 py-4 text-center text-xs uppercase tracking-wider text-white font-black">
                            BB
                        </th>

                        <th class="px-5 py-4 text-center text-xs uppercase tracking-wider text-white font-black">
                            TB
                        </th>

                        <th class="px-5 py-4 text-left text-xs uppercase tracking-wider text-white font-black">
                            Keluhan
                        </th>

                        <th class="px-5 py-4 text-left text-xs uppercase tracking-wider text-white font-black">
                            Diagnosis
                        </th>

                        <th class="px-5 py-4 text-left text-xs uppercase tracking-wider text-white font-black">
                            Tindakan
                        </th>

                        <th class="px-5 py-4 text-left text-xs uppercase tracking-wider text-white font-black">
                            Resep
                        </th>

                        <th class="px-5 py-4 text-right text-xs uppercase tracking-wider text-white font-black">
                            Waktu
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($pemeriksaan as $item)

                    <tr class="hover:bg-emerald-50/40 transition">

                        {{-- NO --}}
                        <td class="px-5 py-5 text-center font-bold text-slate-700">
                            {{ $loop->iteration }}
                        </td>

                        {{-- PASIEN --}}
                        <td class="px-5 py-5">

                            <div class="flex items-center gap-3">

                                <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-black shadow">
                                    {{ strtoupper(substr($item->pendaftaran->nama_pasien ?? 'P', 0, 1)) }}
                                </div>

                                <div>

                                    <h3 class="text-sm font-bold text-slate-900 uppercase">
                                        {{ $item->pendaftaran->nama_pasien ?? '-' }}
                                    </h3>

                                    <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold mt-1">
                                        {{ $item->pendaftaran->no_identitas ?? '-' }}
                                    </p>

                                </div>

                            </div>

                        </td>

                        {{-- POLI --}}
                        <td class="px-5 py-5">

                            <span class="px-3 py-1 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-700 text-[10px] font-black uppercase">
                                {{ $item->pendaftaran->poli ?? '-' }}
                            </span>

                        </td>

                        {{-- BERAT BADAN --}}
                        <td class="px-5 py-5 text-center">

                            <span class="font-bold text-slate-700">
                                {{ $item->pendaftaran->berat_badan ?? '-' }} Kg
                            </span>

                        </td>

                        {{-- TINGGI BADAN --}}
                        <td class="px-5 py-5 text-center">

                            <span class="font-bold text-slate-700">
                                {{ $item->pendaftaran->tinggi_badan ?? '-' }} Cm
                            </span>

                        </td>

                        {{-- KELUHAN --}}
                        <td class="px-5 py-5">

                            <div class="text-sm text-slate-700 leading-relaxed max-w-[220px]">
                                {{ $item->keluhan }}
                            </div>

                        </td>

                        {{-- DIAGNOSIS --}}
                        <td class="px-5 py-5">

                            <div class="text-sm font-semibold text-slate-700 leading-relaxed max-w-[250px]">
                                {{ $item->diagnosis }}
                            </div>

                        </td>

                        {{-- TINDAKAN --}}
                        <td class="px-5 py-5">

                            <span class="px-3 py-1 rounded-lg bg-slate-100 border border-slate-200 text-slate-700 text-[10px] font-black uppercase">
                                {{ $item->tindakan }}
                            </span>

                        </td>

                        {{-- RESEP --}}
                        <td class="px-5 py-5">

                            @if($item->resep)

                                <div class="flex flex-wrap gap-2 max-w-[250px]">

                                    @foreach(preg_split('/[\n,]+/', $item->resep) as $resep)

                                        <span class="px-2 py-1 rounded-md bg-white border border-slate-300 text-[10px] font-bold uppercase text-slate-700 shadow-sm">
                                            {{ trim($resep) }}
                                        </span>

                                    @endforeach

                                </div>

                            @else

                                <span class="text-slate-400 text-xs italic">
                                    Tidak ada resep
                                </span>

                            @endif

                        </td>

                        {{-- WAKTU --}}
                        <td class="px-5 py-5 text-right">

                            <div class="flex flex-col items-end">

                                <span class="text-sm font-bold text-slate-900">
                                    {{ $item->created_at->translatedFormat('d M Y') }}
                                </span>

                                <span class="text-[10px] uppercase tracking-wider text-slate-400 font-bold mt-1">
                                    {{ $item->created_at->format('H:i') }} WIB
                                </span>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="10" class="py-20 text-center">

                            <div class="flex flex-col items-center">

                                <i class="ph ph-folder-open text-6xl text-slate-300 mb-4"></i>

                                <p class="text-slate-500 text-lg font-extrabold uppercase tracking-widest opacity-60">
                                    Data Rekam Medis Kosong
                                </p>

                            </div>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- FOOTER --}}
        <div class="bg-slate-50 border-t border-slate-200 px-6 py-4">

            <p class="text-[10px] uppercase tracking-wider font-bold text-slate-500 italic">
                * Data rekam medis berasal dari pemeriksaan petugas dan dokter.
            </p>

        </div>

    </div>

</div>

@endsection
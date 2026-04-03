@extends('layouts.admin')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

<div class="p-6 bg-slate-100 min-h-screen font-['Plus_Jakarta_Sans']">

```
<!-- HEADER -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">

    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 uppercase tracking-tight">
            Data <span class="text-emerald-600">Pasien</span>
        </h1>

        <p class="text-slate-600 text-sm font-semibold flex items-center gap-2 mt-1">
            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
            Manajemen Data Pasien
        </p>
    </div>

    <div class="bg-slate-900 px-4 py-2 rounded-lg shadow border-b-2 border-emerald-500">
        <span class="text-white font-bold text-sm tracking-wider">
            {{ $pasien->count() }} PASIEN
        </span>
    </div>

</div>



<!-- SEARCH -->
<div class="bg-white p-4 rounded-xl shadow border border-slate-200 mb-6">

    <form method="GET" action="{{ route('admin.data_pasien.index') }}" class="flex gap-3 flex-wrap md:flex-nowrap">

        <div class="relative flex-grow">

            <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                placeholder="Cari nama pasien / no identitas / jenis pasien..."
                class="w-full pl-10 pr-4 py-2 rounded-lg bg-slate-50 border border-slate-200 focus:border-slate-900 outline-none text-sm font-semibold"
            >

            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">
                🔎
            </div>

        </div>


        <button
            type="submit"
            class="bg-slate-900 text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-emerald-600 transition"
        >
            Cari
        </button>


        @if(request('q'))
        <a
            href="{{ route('admin.data_pasien.index') }}"
            class="bg-slate-200 text-slate-900 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-slate-300"
        >
            Reset
        </a>
        @endif

    </form>

</div>



<!-- TABLE -->
<div class="bg-white rounded-xl shadow border border-slate-200 overflow-hidden">

    <table class="w-full text-left">

        <thead>

            <tr class="bg-slate-900">

                <th class="px-4 py-2 text-xs font-bold text-emerald-400 uppercase">
                    No
                </th>

                <th class="px-4 py-2 text-xs font-bold text-white uppercase">
                    Nama Pasien
                </th>

                <th class="px-4 py-2 text-xs font-bold text-white uppercase">
                    No Identitas
                </th>

                <th class="px-4 py-2 text-xs font-bold text-white uppercase">
                    Jenis
                </th>

                <th class="px-4 py-2 text-xs font-bold text-white uppercase text-center">
                    Total Kunjungan
                </th>

                <th class="px-4 py-2 text-xs font-bold text-white uppercase">
                    Terakhir Berobat
                </th>

                <th class="px-4 py-2 text-xs font-bold text-white uppercase text-center">
                    Status Admin
                </th>

                <th class="px-4 py-2 text-xs font-bold text-white uppercase text-center">
                    Aksi
                </th>

            </tr>

        </thead>



        <tbody class="divide-y divide-slate-100">

            @forelse($pasien as $p)

            <tr class="hover:bg-emerald-50 transition">

                <!-- NOMOR -->
                <td class="px-4 py-2 text-sm font-semibold text-slate-500">
                    #{{ $loop->iteration }}
                </td>


                <!-- NAMA -->
                <td class="px-4 py-2">

                    <span class="text-sm font-bold text-slate-900">
                        {{ $p->nama_pasien }}
                    </span>

                </td>


                <!-- IDENTITAS -->
                <td class="px-4 py-2 text-sm text-slate-700 font-semibold">

                    {{ $p->no_identitas }}

                </td>


                <!-- JENIS -->
                <td class="px-4 py-2">

                    <span class="px-2 py-1 bg-slate-100 text-slate-900 text-xs font-semibold rounded-md uppercase">
                        {{ $p->jenis_pasien }}
                    </span>

                </td>


                <!-- TOTAL KUNJUNGAN -->
                <td class="px-4 py-2 text-center text-sm font-semibold text-slate-700">

                    {{ $p->total_kunjungan }}x

                </td>


                <!-- TERAKHIR -->
                <td class="px-4 py-2 text-sm text-slate-600">

                    {{ \Carbon\Carbon::parse($p->terakhir_kunjungan)->format('d M Y') }}

                </td>


                <!-- STATUS ADMIN -->
                <td class="px-4 py-2 text-center">

                    @if($p->status_admin === 'lunas')

                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded-md text-xs font-semibold">
                        Lunas
                    </span>

                    @elseif($p->status_admin === 'belum_lunas')

                    <span class="bg-red-100 text-red-700 px-2 py-1 rounded-md text-xs font-semibold">
                        Belum Lunas
                    </span>

                    @else

                    <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-md text-xs font-semibold">
                        Belum Ada Tagihan
                    </span>

                    @endif

                </td>



                <!-- AKSI -->
                <td class="px-4 py-2 text-center">

                    <a
                        href="{{ route('admin.data_pasien.detail', $p->no_identitas) }}"
                        class="inline-block px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md text-xs font-semibold transition"
                    >
                        Detail
                    </a>

                </td>

            </tr>

            @empty

            <tr>

                <td colspan="8" class="py-10 text-center text-slate-400 text-sm">

                    Data pasien tidak ditemukan

                </td>

            </tr>

            @endforelse

        </tbody>

    </table>

</div>
```

</div>

@endsection

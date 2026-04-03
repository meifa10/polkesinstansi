@extends('layouts.admin')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

<div class="p-6 bg-slate-100 min-h-screen font-['Plus_Jakarta_Sans']">

```
<!-- HEADER -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">

    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 uppercase tracking-tight">
            Pendaftaran <span class="text-emerald-600">Pasien</span>
        </h1>

        <p class="text-slate-600 text-sm font-semibold flex items-center gap-2 mt-1">
            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
            Manajemen Antrian Poliklinik
        </p>
    </div>

    <div class="bg-slate-900 px-4 py-2 rounded-lg shadow border-b-2 border-emerald-500">
        <span class="text-white font-bold text-sm tracking-wider">
            {{ $pendaftaran->where('status','menunggu')->count() }} ANTRIAN
        </span>
    </div>

</div>



<!-- SEARCH -->
<div class="bg-white p-4 rounded-xl shadow border border-slate-200 mb-6">

    <form method="GET" action="{{ route('admin.pendaftaran.index') }}" class="flex gap-3 flex-wrap md:flex-nowrap">

        <div class="relative flex-grow">

            <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                placeholder="Cari nama pasien / NIK / poli..."
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
            href="{{ route('admin.pendaftaran.index') }}"
            class="bg-slate-200 text-slate-900 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-slate-300"
        >
            Reset
        </a>
        @endif

    </form>

</div>



<!-- SUCCESS MESSAGE -->
@if(session('success'))

<div class="mb-4 p-3 bg-emerald-600 text-white rounded-lg shadow flex items-center gap-2 text-sm">
    ✅ {{ session('success') }}
</div>

@endif




<!-- TABLE -->
<div class="bg-white rounded-xl shadow border border-slate-200 overflow-hidden">

    <table class="w-full text-left">

        <thead>

            <tr class="bg-slate-900">

                <th class="px-4 py-2 text-xs font-bold text-emerald-400 uppercase">
                    No
                </th>

                <th class="px-4 py-2 text-xs font-bold text-white uppercase">
                    Pasien
                </th>

                <th class="px-4 py-2 text-xs font-bold text-white uppercase">
                    Jenis
                </th>

                <th class="px-4 py-2 text-xs font-bold text-white uppercase">
                    Poli
                </th>

                <th class="px-4 py-2 text-xs font-bold text-white uppercase text-center">
                    Status
                </th>

                <th class="px-4 py-2 text-xs font-bold text-white uppercase text-right">
                    Aksi
                </th>

            </tr>

        </thead>



        <tbody class="divide-y divide-slate-100">

            @foreach($pendaftaran as $item)

            <tr class="hover:bg-emerald-50 transition">

                <!-- NO -->
                <td class="px-4 py-2 text-sm font-semibold text-slate-500">
                    #{{ $loop->iteration }}
                </td>


                <!-- PASIEN -->
                <td class="px-4 py-2">

                    <div class="flex flex-col leading-tight">

                        <span class="text-sm font-bold text-slate-900">
                            {{ $item->nama_pasien }}
                        </span>

                        <span class="text-xs text-emerald-600 font-semibold">
                            {{ $item->no_identitas }}
                        </span>

                    </div>

                </td>


                <!-- JENIS -->
                <td class="px-4 py-2">

                    <span class="px-2 py-1 bg-slate-100 text-slate-900 text-xs font-semibold rounded-md">
                        {{ $item->jenis_pasien }}
                    </span>

                </td>


                <!-- POLI -->
                <td class="px-4 py-2 text-sm font-semibold text-slate-700">
                    {{ $item->poli }}
                </td>


                <!-- STATUS -->
                <td class="px-4 py-2 text-center">

                    @if($item->status == 'menunggu')

                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-md text-xs font-semibold">
                            Menunggu
                        </span>

                    @elseif($item->status == 'diproses')

                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-md text-xs font-semibold">
                            Diproses
                        </span>

                    @elseif($item->status == 'selesai')

                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-md text-xs font-semibold">
                            Selesai
                        </span>

                    @elseif($item->status == 'ditolak')

                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded-md text-xs font-semibold">
                            Ditolak
                        </span>

                    @endif

                </td>



                <!-- UPDATE STATUS -->
                <td class="px-4 py-2 text-right">

                    <form id="form-status-{{ $item->id }}" method="POST" action="{{ route('admin.pendaftaran.status', $item->id) }}">

                        @csrf

                        <select
                            name="status"
                            onchange="document.getElementById('form-status-{{ $item->id }}').submit()"
                            class="border border-slate-300 rounded-md px-2 py-1 text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-emerald-400 outline-none"
                        >

                            <option value="menunggu" {{ $item->status=='menunggu'?'selected':'' }}>
                                Menunggu
                            </option>

                            <option value="diproses" {{ $item->status=='diproses'?'selected':'' }}>
                                Diproses
                            </option>

                            <option value="selesai" {{ $item->status=='selesai'?'selected':'' }}>
                                Selesai
                            </option>

                            <option value="ditolak" {{ $item->status=='ditolak'?'selected':'' }}>
                                Ditolak
                            </option>

                        </select>

                    </form>

                </td>

            </tr>

            @endforeach



            <!-- DATA KOSONG -->
            @if($pendaftaran->isEmpty())

            <tr>

                <td colspan="6" class="py-10 text-center text-slate-400 text-sm">

                    Tidak ada data pasien

                </td>

            </tr>

            @endif

        </tbody>

    </table>

</div>
```

</div>

@endsection

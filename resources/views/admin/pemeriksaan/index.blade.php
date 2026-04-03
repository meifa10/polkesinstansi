@extends('layouts.admin')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

<div class="p-6 bg-slate-100 min-h-screen font-['Plus_Jakarta_Sans']">

<!-- HEADER -->
<div class="flex justify-between items-center mb-6">

<div>

<h1 class="text-2xl font-extrabold text-slate-900 uppercase tracking-tight">
Medical <span class="text-emerald-600">Records</span>
</h1>

<p class="text-slate-600 text-sm font-semibold flex items-center gap-2 mt-1">
<span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
Riwayat Pemeriksaan Pasien
</p>

</div>


<div class="bg-slate-900 px-4 py-2 rounded-lg border-b-2 border-emerald-500">

<span class="text-white text-sm font-bold tracking-wide">
{{ $pemeriksaan->count() }} DATA
</span>

</div>

</div>



<!-- SEARCH -->
<div class="bg-white p-4 rounded-xl shadow border border-slate-200 mb-6">

<form method="GET" action="{{ route('admin.pemeriksaan') }}" class="flex gap-3">

<div class="relative flex-grow">

<input
type="text"
name="q"
value="{{ request('q') }}"
placeholder="Cari pasien, dokter atau diagnosis..."
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
href="{{ route('admin.pemeriksaan') }}"
class="bg-slate-200 text-slate-900 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-slate-300"
>
Reset
</a>

@endif

</form>

</div>



<!-- TABLE -->
<div class="bg-white rounded-xl shadow border border-slate-200 overflow-hidden">

<div class="overflow-x-auto">

<table class="w-full text-left text-sm">

<thead>

<tr class="bg-slate-900">

<th class="px-4 py-2 text-xs font-bold text-emerald-400 uppercase">
No
</th>

<th class="px-4 py-2 text-xs font-bold text-white uppercase">
Pasien
</th>

<th class="px-4 py-2 text-xs font-bold text-white uppercase">
Dokter
</th>

<th class="px-4 py-2 text-xs font-bold text-white uppercase">
Diagnosis
</th>

<th class="px-4 py-2 text-xs font-bold text-white uppercase">
Tindakan
</th>

<th class="px-4 py-2 text-xs font-bold text-white uppercase">
Resep
</th>

<th class="px-4 py-2 text-xs font-bold text-white uppercase text-right">
Tanggal
</th>

</tr>

</thead>



<tbody class="divide-y divide-slate-100">

@forelse ($pemeriksaan as $item)

<tr class="hover:bg-emerald-50 transition">

<!-- NOMOR -->
<td class="px-4 py-2 font-semibold text-slate-500">
#{{ $loop->iteration }}
</td>


<!-- PASIEN -->
<td class="px-4 py-2">

<div class="flex flex-col">

<span class="font-semibold text-slate-900">
{{ $item->pendaftaran->nama_pasien ?? '-' }}
</span>

<span class="text-xs text-emerald-600 font-semibold">
{{ $item->pendaftaran->poli ?? '-' }}
</span>

</div>

</td>


<!-- DOKTER -->
<td class="px-4 py-2 font-semibold text-slate-700">

{{ $item->dokter->name ?? '-' }}

</td>


<!-- DIAGNOSIS -->
<td class="px-4 py-2 text-slate-700">

{{ $item->diagnosis }}

</td>


<!-- TINDAKAN -->
<td class="px-4 py-2 text-slate-700">

{{ $item->tindakan }}

</td>



<!-- RESEP OBAT -->
<td class="px-4 py-2">

@if($item->resep)

@php
$resepList = preg_split('/[\n,]+/', $item->resep);
@endphp

<div class="flex flex-wrap gap-1">

@foreach($resepList as $obat)

<span class="px-2 py-1 bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700 rounded-md">
{{ trim($obat) }}
</span>

@endforeach

</div>

@else

<span class="text-slate-400 italic text-xs">
Tidak ada resep
</span>

@endif

</td>



<!-- TANGGAL -->
<td class="px-4 py-2 text-right">

<div class="flex flex-col items-end">

<span class="font-semibold text-slate-900">
{{ $item->created_at->format('d M Y') }}
</span>

<span class="text-xs text-slate-400">
{{ $item->created_at->format('H:i') }}
</span>

</div>

</td>

</tr>

@empty

<tr>

<td colspan="7" class="py-10 text-center text-slate-400">

Data pemeriksaan tidak ditemukan

</td>

</tr>

@endforelse

</tbody>

</table>

</div>

</div>

</div>

@endsection
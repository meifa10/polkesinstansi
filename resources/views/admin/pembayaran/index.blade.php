@extends('layouts.admin')

@section('content')
<div class="p-6 space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Pembayaran Pasien
            </h1>
            <p class="text-gray-500">
                Kelola dan validasi pembayaran pasien Polkes Jombang
            </p>
        </div>

        {{-- CTA --}}
        <a href="{{ route('admin.data_pasien.index') }}"
           class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700
                  text-white rounded-lg text-sm font-semibold">
            + Buat Pembayaran
        </a>
    </div>

    {{-- ================= SEARCH ================= --}}
    <form method="GET"
          action="{{ route('admin.pembayaran') }}"
          class="flex gap-3">

        <input
            type="text"
            name="q"
            value="{{ request('q') }}"
            placeholder="Cari nama pasien / poli / status / metode"
            class="border rounded-lg px-4 py-2 w-96
                   focus:outline-none focus:ring focus:ring-emerald-200"
        >

        <button class="bg-emerald-600 text-white px-4 py-2 rounded-lg">
            Cari
        </button>

        @if(request('q'))
            <a href="{{ route('admin.pembayaran') }}"
               class="bg-gray-200 px-4 py-2 rounded-lg">
                Reset
            </a>
        @endif
    </form>

    {{-- ================= SUMMARY ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        {{-- TOTAL TAGIHAN --}}
        <div class="bg-white rounded-xl p-5 shadow">
            <p class="text-sm text-gray-500">Total Tagihan</p>
            <h2 class="text-2xl font-bold text-emerald-600">
                Rp {{ number_format($data->sum('total_biaya')) }}
            </h2>
        </div>

        {{-- LUNAS --}}
        <div class="bg-white rounded-xl p-5 shadow">
            <p class="text-sm text-gray-500">Pembayaran Lunas</p>
            <h2 class="text-2xl font-bold text-green-600">
                {{ $data->where('status','lunas')->count() }}
            </h2>
        </div>

        {{-- BELUM LUNAS --}}
        <div class="bg-white rounded-xl p-5 shadow">
            <p class="text-sm text-gray-500">Belum Lunas</p>
            <h2 class="text-2xl font-bold text-red-600">
                {{ $data->where('status','belum_lunas')->count() }}
            </h2>
        </div>

    </div>

    {{-- ================= TABLE ================= --}}
    <div class="bg-white rounded-xl shadow overflow-x-auto">

        <table class="w-full text-sm">
            <thead class="bg-emerald-600 text-white">
                <tr>
                    <th class="p-3 text-left">Pasien</th>
                    <th class="text-left">Poli</th>
                    <th class="text-left">Tanggal</th>
                    <th class="text-right">Total</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
            @forelse($data as $p)
                <tr class="border-b hover:bg-gray-50 transition">

                    {{-- PASIEN --}}
                    <td class="p-3 font-medium text-gray-800">
                        {{ $p->pendaftaran->nama_pasien }}
                    </td>

                    {{-- POLI --}}
                    <td class="text-gray-600">
                        {{ $p->pendaftaran->poli }}
                    </td>

                    {{-- TANGGAL --}}
                    <td class="text-gray-600">
                        {{ $p->created_at->format('d M Y') }}
                    </td>

                    {{-- TOTAL --}}
                    <td class="text-right font-semibold text-emerald-700">
                        Rp {{ number_format($p->total_biaya) }}
                    </td>

                    {{-- STATUS --}}
                    <td class="text-center">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $p->status === 'lunas'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-red-100 text-red-700' }}">
                            {{ strtoupper(str_replace('_',' ',$p->status)) }}
                        </span>
                    </td>

                    {{-- AKSI --}}
                    <td class="text-center">
                        @if($p->status === 'belum_lunas')
                            <form method="POST"
                                  action="{{ route('admin.pembayaran.lunasi', $p->id) }}"
                                  onsubmit="return confirm('Tandai pembayaran ini sebagai LUNAS?')">
                                @csrf
                                <button
                                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700
                                           text-white rounded-lg text-xs">
                                    Tandai Lunas
                                </button>
                            </form>
                        @else
                            <span class="text-green-600 text-xs font-semibold">
                                ✔ Sudah Lunas
                            </span>
                        @endif
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-gray-500">
                        Data pembayaran tidak ditemukan.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

    </div>

</div>
@endsection

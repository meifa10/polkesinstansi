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

        {{-- CTA: Arahkan ke data pasien untuk buat tagihan baru --}}
        <a href="{{ route('admin.data_pasien.index') }}"
           class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 
                  text-white rounded-lg text-sm font-semibold transition-all shadow-md">
            + Buat Pembayaran
        </a>
    </div>

    {{-- ================= SEARCH & FILTER ================= --}}
    <form method="GET" 
          action="{{ route('admin.pembayaran') }}" 
          class="flex flex-wrap gap-3">

        <div class="relative flex-grow max-w-md">
            <input 
                type="text" 
                name="q" 
                value="{{ request('q') }}" 
                placeholder="Cari nama pasien / poli / status / metode"
                class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2 
                       focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
            >
            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>

        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
            Cari
        </button>

        @if(request('q'))
            <a href="{{ route('admin.pembayaran') }}" 
               class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                Reset
            </a>
        @endif
    </form>

    {{-- ================= RINGKASAN DATA (SUMMARY) ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        {{-- TOTAL TAGIHAN (Dihitung aman dari error titik) --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Total Akumulasi Tagihan</p>
            <h2 class="text-2xl font-bold text-emerald-600">
                Rp {{ number_format($data->sum(function($item) {
                    return (int) str_replace(['.', ','], '', $item->total_biaya);
                }), 0, ',', '.') }}
            </h2>
        </div>

        {{-- STATUS LUNAS --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 border-l-4 border-l-green-500">
            <p class="text-sm text-gray-500 font-medium">Pembayaran Lunas</p>
            <h2 class="text-2xl font-bold text-green-600">
                {{ $data->where('status','lunas')->count() }} <span class="text-sm font-normal text-gray-400">Transaksi</span>
            </h2>
        </div>

        {{-- BELUM LUNAS --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 border-l-4 border-l-red-500">
            <p class="text-sm text-gray-500 font-medium">Menunggu Pembayaran</p>
            <h2 class="text-2xl font-bold text-red-600">
                {{ $data->where('status','belum_lunas')->count() }} <span class="text-sm font-normal text-gray-400">Transaksi</span>
            </h2>
        </div>

    </div>

    {{-- ================= TABLE DATA ================= --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 border-b">
                    <tr>
                        <th class="p-4 text-left font-semibold">Nama Pasien</th>
                        <th class="text-left font-semibold">Poli</th>
                        <th class="text-left font-semibold">Metode</th>
                        <th class="text-left font-semibold">Tanggal</th>
                        <th class="text-right font-semibold">Total Tagihan</th>
                        <th class="text-center font-semibold">Status</th>
                        <th class="text-center font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                @forelse($data as $p)
                    <tr class="hover:bg-gray-50/80 transition-colors">

                        {{-- PASIEN --}}
                        <td class="p-4">
                            <div class="font-bold text-gray-800 text-base">
                                {{ $p->pendaftaran->nama_pasien }}
                            </div>
                            <div class="text-[10px] text-gray-400 font-mono">
                                Ref: {{ $p->payment_ref ?? '-' }}
                            </div>
                        </td>

                        {{-- POLI --}}
                        <td class="text-gray-600">
                            {{ $p->pendaftaran->poli }}
                        </td>

                        {{-- METODE --}}
                        <td class="text-gray-600">
                            <span class="capitalize px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs font-bold">
                                {{ $p->metode }}
                            </span>
                            @if($p->paid_by)
                                <small class="block text-[10px] text-gray-400 mt-1 italic">Via: {{ $p->paid_by }}</small>
                            @endif
                        </td>

                        {{-- TANGGAL --}}
                        <td class="text-gray-600">
                            {{ $p->created_at->format('d/m/Y') }}
                            <div class="text-[10px]">{{ $p->created_at->format('H:i') }} WIB</div>
                        </td>

                        {{-- TOTAL (Dipaksa bersih dari titik agar number_format jalan) --}}
                        <td class="text-right font-bold text-gray-900 text-base">
                            Rp {{ number_format((int) str_replace(['.', ','], '', $p->total_biaya), 0, ',', '.') }}
                        </td>

                        {{-- STATUS --}}
                        <td class="text-center">
                            <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest
                                {{ $p->status === 'lunas' 
                                    ? 'bg-green-100 text-green-700 border border-green-200' 
                                    : 'bg-red-50 text-red-600 border border-red-100' }}">
                                {{ str_replace('_',' ',$p->status) }}
                            </span>
                        </td>

                        {{-- AKSI --}}
                        <td class="p-4 text-center">
                            @if($p->status === 'belum_lunas')
                                <form method="POST" 
                                      action="{{ route('admin.pembayaran.lunasi', $p->id) }}" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menandai pembayaran ini sebagai LUNAS secara manual?')">
                                    @csrf
                                    <button 
                                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 
                                               text-white rounded-lg text-xs font-bold shadow-sm transition-all active:scale-95">
                                        Tandai Lunas
                                    </button>
                                </form>
                            @else
                                <div class="flex flex-col items-center">
                                    <span class="text-green-600 text-xs font-bold flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        Lunas
                                    </span>
                                    <small class="text-[9px] text-gray-400">
                                        {{ $p->tanggal_bayar ? date('d/m/y H:i', strtotime($p->tanggal_bayar)) : '' }}
                                    </small>
                                </div>
                            @endif
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p class="text-gray-400 font-medium">Belum ada data pembayaran untuk ditampilkan.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
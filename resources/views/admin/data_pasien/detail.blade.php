@extends('layouts.admin')

@section('content')
<div class="p-6 space-y-6">

    {{-- ================= HEADER ================= --}}
    <h1 class="text-2xl font-bold text-gray-800">
        Detail Pasien
    </h1>

    {{-- ================= DATA PASIEN ================= --}}
    <div class="bg-white rounded-xl shadow p-6">
        <p><strong>Nama:</strong> {{ $pasien->nama_pasien }}</p>
        <p><strong>No Identitas:</strong> {{ $pasien->no_identitas }}</p>
        <p><strong>Jenis Pasien:</strong> {{ strtoupper($pasien->jenis_pasien) }}</p>
        <p><strong>Tanggal Lahir:</strong> {{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->format('d M Y') }}</p>
    </div>

    {{-- ================= RIWAYAT KUNJUNGAN & PEMBAYARAN ================= --}}
    <h2 class="text-xl font-semibold text-gray-800">
        Riwayat Kunjungan & Pembayaran
    </h2>

    @forelse($kunjungan as $k)

    <div class="bg-white rounded-xl shadow p-6 flex justify-between items-center">

        {{-- DATA KUNJUNGAN --}}
        <div>
            <p><strong>Tanggal:</strong> {{ $k->created_at->format('d M Y') }}</p>
            <p><strong>Poli:</strong> {{ $k->poli }}</p>

            <p><strong>Status Kunjungan:</strong>
                <span class="uppercase font-semibold">
                    {{ $k->status }}
                </span>
            </p>
        </div>


        {{-- PEMBAYARAN --}}
        <div class="text-right space-y-2">

            {{-- ================= JIKA SUDAH ADA PEMBAYARAN ================= --}}
            @if($k->pembayaran)

                {{-- SUDAH LUNAS --}}
                @if($k->pembayaran->status === 'lunas')

                    <span class="inline-block px-4 py-2 bg-green-100 text-green-700 rounded-lg text-sm font-semibold">
                        ✔ LUNAS
                    </span>

                    <p class="text-xs text-gray-500">
                        Metode: {{ strtoupper($k->pembayaran->metode) }} <br>
                        Dibayar:
                        {{ \Carbon\Carbon::parse($k->pembayaran->tanggal_bayar)->format('d M Y H:i') }}
                    </p>


                {{-- BELUM LUNAS --}}
                @else

                    {{-- BPJS --}}
                    @if($k->pembayaran->metode === 'bpjs')

                        <form method="POST"
                              action="{{ route('admin.pembayaran.lunasi', $k->pembayaran->id) }}">
                            @csrf
                            <button
                                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm">
                                Tandai LUNAS (BPJS)
                            </button>
                        </form>


                    {{-- TUNAI --}}
                    @elseif($k->pembayaran->metode === 'tunai')

                        <form method="POST"
                              action="{{ route('admin.pembayaran.lunasi', $k->pembayaran->id) }}">
                            @csrf
                            <button
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm">
                                Lunasi Tunai
                            </button>
                        </form>


                    {{-- MIDTRANS / ONLINE --}}
                    @else

                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-sm font-semibold">
                            Menunggu Pembayaran Pasien
                        </span>

                    @endif

                @endif


            {{-- ================= BELUM ADA PEMBAYARAN ================= --}}
            @else

                <a href="{{ route('admin.pembayaran.create', $k->id) }}"
                   class="inline-block px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm">
                    + Buat Pembayaran
                </a>

            @endif

        </div>

    </div>

    @empty

        <div class="bg-yellow-100 text-yellow-800 p-4 rounded">
            Belum ada kunjungan pasien.
        </div>

    @endforelse



    {{-- ================= RIWAYAT REKAM MEDIS ================= --}}
    <h2 class="text-xl font-semibold text-gray-800 mt-6">
        Riwayat Rekam Medis
    </h2>

    @forelse($rekamMedis as $rm)

        <div class="bg-white rounded-xl shadow p-5">

            <p><strong>Tanggal:</strong> {{ $rm->created_at->format('d M Y') }}</p>

            <p><strong>Poli:</strong>
                {{ $rm->pendaftaran->poli ?? '-' }}
            </p>

            <p><strong>Keluhan:</strong>
                {{ $rm->keluhan }}
            </p>

            <p><strong>Diagnosis:</strong>
                {{ $rm->diagnosis }}
            </p>

            <p><strong>Tindakan:</strong>
                {{ $rm->tindakan }}
            </p>

            <p><strong>Resep:</strong>
                {{ $rm->resep }}
            </p>

        </div>

    @empty

        <div class="bg-yellow-100 text-yellow-800 p-4 rounded">
            Belum ada rekam medis.
        </div>

    @endforelse

</div>
@endsection
@extends('layouts.petugas')

@section('content')

<div class="p-6 lg:p-8 bg-slate-50 min-h-screen font-sans">

    <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-8 gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-sm font-bold tracking-wide uppercase mb-3 border border-emerald-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                </svg>
                Rekam Medis Instansi
            </div>
            <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight">
                Laporan <span class="text-emerald-600">Kunjungan Pasien</span>
            </h1>
            <p class="text-slate-600 font-medium mt-3 text-base lg:text-lg">
                Rekapitulasi dan pemantauan data kunjungan pasien berdasarkan hasil diagnosa medis.
            </p>
        </div>

        <div class="bg-white px-8 py-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-6 min-w-[220px]">
            <div class="p-4 bg-emerald-50 rounded-xl text-emerald-600 border border-emerald-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm uppercase font-bold text-slate-400 tracking-wider">Total Kunjungan</p>
                <h2 class="text-4xl font-black text-slate-800 leading-none mt-1">{{ $laporan->total() }}</h2>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
        <div class="lg:col-span-12 bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            <div class="flex items-center justify-between border-b border-slate-100 pb-5 mb-6 gap-4">
                <div class="flex items-center gap-3">
                    <div class="bg-emerald-100 p-2 rounded-lg text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">Penyaringan Data Laporan</h2>
                </div>

                <button onclick="unduhPDF()" class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-3 rounded-xl text-base font-bold transition-all shadow-lg shadow-emerald-500/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Cetak Dokumen (PDF)
                </button>
            </div>

            <form method="GET" action="{{ route('petugas.laporan.diagnosa') }}" class="grid grid-cols-1 md:grid-cols-3 gap-5 items-end">
                <div>
                    <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Pilih Tanggal</label>
                    <input type="date" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}" onchange="this.form.submit()"
                        class="w-full px-5 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-600 uppercase tracking-wide mb-2">Poliklinik</label>
                    <div class="relative">
                        <select name="poli" onchange="this.form.submit()" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all outline-none appearance-none cursor-pointer">
                            <option value="">Semua Poliklinik</option>
                            <option value="Poli Umum" {{ request('poli') == 'Poli Umum' ? 'selected' : '' }}>Poli Umum</option>
                            <option value="Poli Gigi" {{ request('poli') == 'Poli Gigi' ? 'selected' : '' }}>Poli Gigi</option>
                            <option value="Poli KIA & KB" {{ request('poli') == 'Poli KIA & KB' ? 'selected' : '' }}>Poli KIA & KB</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-500">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div>
                    @if(request('tanggal') || request('poli'))
                        <a href="{{ route('petugas.laporan.diagnosa') }}" class="w-full px-5 py-3.5 bg-slate-100 text-slate-600 rounded-xl text-base font-bold hover:bg-slate-200 transition-colors border border-slate-300 flex items-center justify-center">
                            Reset Filter Pencarian
                        </a>
                    @else
                        <div class="w-full text-slate-400 font-semibold text-center text-sm italic py-3.5 border-2 border-dashed border-slate-200 rounded-xl select-none">
                            Filter otomatis aktif saat diubah
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" id="area-laporan">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-emerald-900 text-white text-sm uppercase tracking-widest font-bold">
                        <th class="py-5 px-6 w-16 text-center rounded-tl-xl">No</th>
                        <th class="py-5 px-6 min-w-[180px]">Tanggal Kunjungan</th>
                        <th class="py-5 px-6 min-w-[220px]">Nama Pasien</th>
                        <th class="py-5 px-6 min-w-[150px]">Poliklinik</th>
                        <th class="py-5 px-6 min-w-[200px]">Dokter Pemeriksa</th>
                        <th class="py-5 px-6 min-w-[250px] rounded-tr-xl">Diagnosis Medis (ICD-10)</th>
                    </tr>
                </thead>
                <tbody class="text-base divide-y divide-slate-200">
                    @forelse($laporan as $item)
                        <tr class="hover:bg-emerald-50/60 transition-colors">
                            <td class="py-5 px-6 text-center text-slate-500 font-bold align-middle text-lg">
                                {{ ($laporan->currentPage() - 1) * $laporan->perPage() + $loop->iteration }}
                            </td>
                            <td class="py-5 px-6 align-middle text-slate-700 font-medium">
                                <span class="font-bold block text-slate-800">{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}</span>
                                <span class="block text-xs text-slate-400 font-bold mt-0.5">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB</span>
                            </td>
                            <td class="py-5 px-6 align-middle">
                                <span class="font-extrabold text-slate-800 text-lg">{{ $item->nama_pasien }}</span>
                            </td>
                            <td class="py-5 px-6 align-middle">
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-slate-100 border border-slate-300 text-slate-800 text-sm font-extrabold">
                                    {{ $item->poli }}
                                </span>
                            </td>
                            <td class="py-5 px-6 align-middle text-slate-700 font-bold">
                                {{ $item->nama_dokter ?? '-' }}
                            </td>
                            <td class="py-5 px-6 align-middle text-rose-700 font-extrabold text-lg">
                                {{ $item->rekamMedis->diagnosis ?? 'Belum Diisi Dokter' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-20 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="bg-emerald-50 p-6 rounded-full mb-5 border border-emerald-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-800 mb-2">Data Laporan Kunjungan Kosong</h3>
                                    <p class="text-slate-500 text-base max-w-md">
                                        Tidak ada rekaman data kunjungan pasien yang sesuai dengan parameter penyaringan yang Anda tentukan.
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
        {!! $laporan->links() !!}
    </div>

</div>

<div id="elemen-cetak-rahasia" style="display: none;">
    <div style="font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; color: #334155; line-height: 1.5; padding: 0; margin: 0;">
        
        <table style="width: 100%; border-bottom: 2px solid #0f172a; padding-bottom: 12px; margin-bottom: 18px;">
            <tr>
                <td>
                    <div style="font-size: 16px; font-weight: bold; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px; line-height: 1.3;">Medical Center Digital<br>Polkes Jombang</div>
                    <div style="font-size: 9px; color: #64748b; margin-top: 4px;">Jl. KH. Wahid Hasyim No.28 B Jombang, Jawa Timur<br>Sistem Rekam Medis & Pelaporan Terintegrasi Cloud</div>
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    <div style="font-size: 13px; font-weight: bold; color: #059669; letter-spacing: 0.5px; text-transform: uppercase;">DOKUMEN REKAPITULASI KUNJUNGAN</div>
                    <div style="font-size: 9px; color: #64748b; margin-top: 3px;">TANGGAL REKAP: {{ \Carbon\Carbon::parse(request('tanggal', date('Y-m-d')))->translatedFormat('d F Y') }}</div>
                </td>
            </tr>
        </table>

        <div style="text-align: center; margin-bottom: 20px; margin-top: 10px;">
            <div style="font-size: 14px; font-weight: bold; color: #0f172a; text-transform: uppercase; letter-spacing: 1px;">LAPORAN KUNJUNGAN PASIEN BERDASARKAN DIAGNOSA MEDIS</div>
            <div style="font-size: 9px; color: #475569; font-weight: bold; margin-top: 4px; text-transform: uppercase;">FILTER UNIT LAYANAN: {{ request('poli') ?? 'SEMUA POLIKLINIK' }}</div>
        </div>

        <table style="width: 100%; background-color: #f8fafc; padding: 10px 14px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #e2e8f0;">
            <tr>
                <td width="33%"><div style="color: #64748b; font-size: 8px; text-transform: uppercase; font-weight: bold; margin-bottom: 2px;">Total Rekor Data</div><div style="font-size: 11px; font-weight: bold; color: #1e293b;">{{ $laporan->total() }} Pasien Terdaftar</div></td>
                <td width="33%"><div style="color: #64748b; font-size: 8px; text-transform: uppercase; font-weight: bold; margin-bottom: 2px;">Operator Pengekstrak</div><div style="font-size: 11px; font-weight: bold; color: #1e293b;">{{ auth()->user()->name }} (Panel Petugas)</div></td>
                <td width="33%"><div style="color: #64748b; font-size: 8px; text-transform: uppercase; font-weight: bold; margin-bottom: 2px;">Waktu Pembuatan Instan</div><div style="font-size: 11px; font-weight: bold; color: #1e293b;">{{ now()->translatedFormat('d M Y, H:i') }} WIB</div></td>
            </tr>
        </table>

        <table style="width: 100%; border-collapse: collapse; margin-top: 5px; border: 1px solid #cbd5e1; border-radius: 6px; overflow: hidden;">
            <thead>
                <tr style="background-color: #0f172a; color: #ffffff; font-weight: bold; font-size: 9px; text-transform: uppercase; text-align: left;">
                    <th style="padding: 10px 8px; text-align: center; border: 1px solid #cbd5e1; width: 5%;">No</th>
                    <th style="padding: 10px 8px; border: 1px solid #cbd5e1; width: 15%;">Jam Daftar</th>
                    <th style="padding: 10px 12px; border: 1px solid #cbd5e1; width: 25%;">Nama Lengkap Pasien</th>
                    <th style="padding: 10px 12px; border: 1px solid #cbd5e1; width: 15%;">Poliklinik</th>
                    <th style="padding: 10px 12px; border: 1px solid #cbd5e1; width: 20%;">Dokter DPJP</th>
                    <th style="padding: 10px 12px; border: 1px solid #cbd5e1; width: 20%;">Diagnosa Klinis (ICD-10)</th>
                </tr>
            </thead>
            <tbody style="font-size: 9px; color: #1e293b;">
                @php $noPdf = 1; @endphp
                @forelse($laporanSemua as $item)
                    <tr style="background-color: {{ $noPdf % 2 == 0 ? '#f8fafc' : '#ffffff' }}; page-break-inside: avoid; break-inside: avoid;">
                        <td style="padding: 8px; text-align: center; border: 1px solid #cbd5e1; font-weight: bold;">{{ $noPdf++ }}</td>
                        <td style="padding: 8px; border: 1px solid #cbd5e1;">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB</td>
                        <td style="padding: 8px 12px; border: 1px solid #cbd5e1; font-weight: bold; text-transform: uppercase;">{{ $item->nama_pasien }}</td>
                        <td style="padding: 8px 12px; border: 1px solid #cbd5e1;">{{ $item->poli }}</td>
                        <td style="padding: 8px 12px; border: 1px solid #cbd5e1;">{{ $item->nama_dokter ?? '-' }}</td>
                        <td style="padding: 8px 12px; border: 1px solid #cbd5e1; font-weight: bold; color: #991b1b;">{{ $item->rekamMedis->diagnosis ?? 'Belum Diisi Dokter' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 30px; text-align: center; color: #64748b; font-style: italic; font-weight: bold; border: 1px solid #cbd5e1;">Tidak terdapat rekap data rekam medis pada parameter filter ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <table width="100%" style="margin-top: 40px; page-break-inside: avoid; break-inside: avoid;" cellpadding="0" cellspacing="0">
            <tr>
                <td width="65%"></td>
                <td width="35%" style="text-align: center;">
                    <p style="margin-bottom: 45px; font-size: 9px; color: #334155;">Kepala Bidang Pelaporan Rekam Medis,</p>
                    <p style="font-weight: bold; margin-bottom: 0; color: #0f172a; text-transform: uppercase;">{{ auth()->user()->name }}</p>
                    <div style="border-bottom: 1px solid #334155; width: 140px; margin: 4px auto;"></div>
                    <p style="font-size: 7px; color: #64748b; margin-top: 3px; font-weight: bold; line-height: 1.2;">
                        OFFICIAL REKAPITULASI VERIFIED<br>POLKES JOMBANG DIGITAL LOCK
                    </p>
                </td>
            </tr>
        </table>

        <div style="position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 7px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 6px;">
            Arsip Keluaran Resmi Server Pusat Polkes Jombang | Kode Sertifikat Log Ekspor: {{ bin2hex(random_bytes(4)) }} / SEC / WEB
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    function unduhPDF() {
        const element = document.getElementById('elemen-cetak-rahasia');
        element.style.display = 'block';

        const opt = {
            margin:       [0.4, 0.4, 0.4, 0.4],
            filename:     'Laporan_Kunjungan_Diagnosa_{{ request('tanggal', date('Y-m-d')) }}.pdf',
            image:        { type: 'jpeg', quality: 0.99 },
            html2canvas:  { scale: 2.5, useCORS: true, logging: false },
            jsPDF:        { unit: 'in', format: 'letter', orientation: 'landscape' },
            pagebreak:    { mode: 'css' }
        };

        html2pdf().set(opt).from(element).save().then(() => {
            element.style.display = 'none';
        }).catch(err => {
            element.style.display = 'none';
        });
    }
</script>

@endsection
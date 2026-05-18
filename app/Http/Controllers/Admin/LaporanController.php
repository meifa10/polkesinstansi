<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranPoli;
use App\Models\Pembayaran;
use App\Models\RekamMedis;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->bulan ?? now()->month; 
        $tahun = (int) ($request->tahun ?? now()->year);
        $poli  = $request->poli ?? 'semua';

        if ($bulan === 'semua') {
            $start = Carbon::create($tahun)->startOfYear();
            $end   = Carbon::create($tahun)->endOfYear();
        } else {
            $bulanInt = (int) $bulan;
            $start = Carbon::create($tahun, $bulanInt, 1)->startOfMonth();
            $end   = Carbon::create($tahun, $bulanInt, 1)->endOfMonth();
        }

        // 1. BASE QUERY PENDAFTARAN
        $qPendaftaran = PendaftaranPoli::whereBetween('created_at', [$start, $end]);
        if ($poli !== 'semua') {
            $qPendaftaran->where('poli', $poli);
        }

        $totalKunjungan = (clone $qPendaftaran)->count();
        $bpjs = (clone $qPendaftaran)->whereRaw('LOWER(jenis_pasien) = ?', ['jkn'])->count();
        $umum = (clone $qPendaftaran)->whereRaw('LOWER(jenis_pasien) = ?', ['umum'])->count();
        
        $kunjunganPerPoli = (clone $qPendaftaran)
            ->select('poli', DB::raw('COUNT(*) as total'))
            ->groupBy('poli')->orderByDesc('total')->get();

        // 2. BASE QUERY PEMBAYARAN & FILTER POLI VIA RELASI
        $qPembayaranLunas = Pembayaran::where('status', 'lunas')->whereBetween('tanggal_bayar', [$start, $end]);
        $qPembayaranBelum = Pembayaran::where('status', 'belum_lunas')->whereBetween('created_at', [$start, $end]);

        if ($poli !== 'semua') {
            $qPembayaranLunas->whereHas('pendaftaran', function($q) use ($poli) {
                $q->where('poli', $poli);
            });
            $qPembayaranBelum->whereHas('pendaftaran', function($q) use ($poli) {
                $q->where('poli', $poli);
            });
        }

        // Kalkulasi Total Pemasukan agar aman dari format string (ex: 100.000)
        $totalPemasukanList = (clone $qPembayaranLunas)->pluck('total_biaya');
        $totalPemasukan = $totalPemasukanList->sum(function($biaya) {
            return (int) str_replace(['.', ','], '', $biaya);
        });

        $lunas = (clone $qPembayaranLunas)->count();
        $belumLunas = (clone $qPembayaranBelum)->count();

        $metodePembayaran = (clone $qPembayaranLunas)
            ->select('paid_by', DB::raw('COUNT(*) as total'))
            ->groupBy('paid_by')->get();

        // 3. BASE QUERY REKAM MEDIS
        $qRekamMedis = RekamMedis::whereBetween('created_at', [$start, $end]);
        if ($poli !== 'semua') {
            $qRekamMedis->whereHas('pendaftaran', function($q) use ($poli) {
                $q->where('poli', $poli);
            });
        }
        $totalPemeriksaan = $qRekamMedis->count();

        return view('admin.laporan.index', compact(
            'bulan', 'tahun', 'poli', 'totalKunjungan', 'bpjs', 'umum', 
            'kunjunganPerPoli', 'totalPemasukan', 'lunas', 
            'belumLunas', 'totalPemeriksaan', 'metodePembayaran'
        ));
    }

    public function exportPdf(Request $request, $bulan, $tahun) 
    {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '512M');

        $poli = $request->query('poli', 'semua');

        if ($bulan === 'semua') {
            $start = Carbon::create($tahun)->startOfYear();
            $end   = Carbon::create($tahun)->endOfYear();
            $namaBulan = 'Semua Bulan';
        } else {
            $bulanInt = (int) $bulan;
            $start = Carbon::create($tahun, $bulanInt, 1)->startOfMonth();
            $end   = Carbon::create($tahun, $bulanInt, 1)->endOfMonth();
            $namaBulan = Carbon::create(null, $bulanInt, 1)->translatedFormat('F');
        }

        $namaFile = "Laporan_Polkes_Jombang_{$namaBulan}_{$tahun}.pdf";
        if ($poli !== 'semua') {
            $namaFile = "Laporan_{$poli}_{$namaBulan}_{$tahun}.pdf";
        }

        $qLaporan = PendaftaranPoli::with(['pembayaran'])
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'asc');

        if ($poli !== 'semua') {
            $qLaporan->where('poli', $poli);
        }

        $dataLaporan = $qLaporan->get();

        $totalBpjs = $dataLaporan->filter(function($item) {
            return strtolower($item->jenis_pasien) == 'jkn';
        })->count();

        $totalUmum = $dataLaporan->filter(function($item) {
            return strtolower($item->jenis_pasien) == 'umum';
        })->count();

        $data = [
            'namaBulan'        => $namaBulan,
            'tahun'            => $tahun,
            'poli'             => $poli,
            'dataLaporan'      => $dataLaporan,
            'totalKunjungan'   => $dataLaporan->count(),
            'totalBpjs'        => $totalBpjs,
            'totalUmum'        => $totalUmum,
            'totalPendapatan'  => $dataLaporan->sum(function($item) {
                                      if ($item->pembayaran && $item->pembayaran->status == 'lunas') {
                                          return (int) str_replace(['.', ','], '', $item->pembayaran->total_biaya);
                                      }
                                      return 0;
                                  }),
        ];

        $pdf = Pdf::loadView('admin.laporan.pdf', $data)->setPaper('A4', 'portrait');

        if (ob_get_length()) {
            ob_end_clean();
        }

        return $pdf->download($namaFile);
    }
}
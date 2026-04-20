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

        if ($bulan === 'semua') {
            $start = Carbon::create($tahun)->startOfYear();
            $end   = Carbon::create($tahun)->endOfYear();
        } else {
            $bulanInt = (int) $bulan;
            $start = Carbon::create($tahun, $bulanInt, 1)->startOfMonth();
            $end   = Carbon::create($tahun, $bulanInt, 1)->endOfMonth();
        }

     
        $totalKunjungan = PendaftaranPoli::whereBetween('created_at', [$start, $end])->count();

        $bpjs = PendaftaranPoli::where('jenis_pasien', 'jkn')
            ->whereBetween('created_at', [$start, $end])
            ->count();

        $umum = PendaftaranPoli::where('jenis_pasien', 'umum')
            ->whereBetween('created_at', [$start, $end])
            ->count();

        $kunjunganPerPoli = PendaftaranPoli::select('poli', DB::raw('COUNT(*) as total'))
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('poli')
            ->orderByDesc('total')
            ->get();

        $totalPemasukan = Pembayaran::where('status', 'lunas')
            ->whereBetween('tanggal_bayar', [$start, $end])
            ->sum('total_biaya');

        $lunas = Pembayaran::where('status', 'lunas')
            ->whereBetween('tanggal_bayar', [$start, $end])
            ->count();

        $belumLunas = Pembayaran::where('status', 'belum_lunas')
            ->whereBetween('created_at', [$start, $end])
            ->count();

        $metodePembayaran = Pembayaran::select('paid_by', DB::raw('COUNT(*) as total'))
            ->where('status', 'lunas')
            ->whereBetween('tanggal_bayar', [$start, $end])
            ->groupBy('paid_by')
            ->get();

        $totalPemeriksaan = RekamMedis::whereBetween('created_at', [$start, $end])->count();

        return view('admin.laporan.index', compact(
            'bulan', 
            'tahun',
            'totalKunjungan',
            'bpjs',
            'umum',
            'kunjunganPerPoli',
            'totalPemasukan',
            'lunas',
            'belumLunas',
            'metodePembayaran',
            'totalPemeriksaan'
        ));
    }


    public function exportPdf(Request $request)
    {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '512M');

        $bulan = $request->bulan ?? now()->month;
        $tahun = (int) ($request->tahun ?? now()->year);

        if ($bulan === 'semua') {
            $start = Carbon::create($tahun)->startOfYear();
            $end   = Carbon::create($tahun)->endOfYear();
            $namaBulan = 'Semua Bulan';
            $namaFile = "laporan-polkes-tahunan-{$tahun}.pdf";
        } else {
            $bulanInt = (int) $bulan;
            $start = Carbon::create($tahun, $bulanInt, 1)->startOfMonth();
            $end   = Carbon::create($tahun, $bulanInt, 1)->endOfMonth();
            $namaBulan = Carbon::create(null, $bulanInt, 1)->translatedFormat('F');
            $namaFile = "laporan-polkes-{$bulanInt}-{$tahun}.pdf";
        }

        $data = [
            'bulan'            => $bulan,
            'tahun'            => $tahun,
            'namaBulan'        => $namaBulan,
            'totalKunjungan'   => PendaftaranPoli::whereBetween('created_at', [$start, $end])->count(),
            'bpjs'             => PendaftaranPoli::where('jenis_pasien', 'jkn')->whereBetween('created_at', [$start, $end])->count(),
            'umum'             => PendaftaranPoli::where('jenis_pasien', 'umum')->whereBetween('created_at', [$start, $end])->count(),
            'totalPemasukan'   => Pembayaran::where('status', 'lunas')->whereBetween('tanggal_bayar', [$start, $end])->sum('total_biaya'),
            'totalPemeriksaan' => RekamMedis::whereBetween('created_at', [$start, $end])->count(),
        ];

        $pdf = Pdf::loadView('admin.laporan.pdf', $data)
            ->setPaper('A4', 'portrait');

        if (ob_get_length()) {
            ob_end_clean();
        }
        return $pdf->stream($namaFile);
    }
}
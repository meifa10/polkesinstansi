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
    /**
     * =========================
     * HALAMAN LAPORAN (ADMIN)
     * =========================
     */
    public function index(Request $request)
    {
        // 1. AMBIL REQUEST BULAN DAN TAHUN
        $bulan = $request->bulan ?? now()->month; 
        $tahun = (int) ($request->tahun ?? now()->year);

        // 2. TENTUKAN RANGE WAKTU VALID (BULANAN ATAU TAHUNAN)
        if ($bulan === 'semua') {
            // Jika "Semua Bulan", ambil dari 1 Januari - 31 Desember di tahun terkait
            $start = Carbon::create($tahun)->startOfYear();
            $end   = Carbon::create($tahun)->endOfYear();
        } else {
            // Jika spesifik 1 bulan, cast ke integer dan ambil range bulan tersebut
            $bulanInt = (int) $bulan;
            $start = Carbon::create($tahun, $bulanInt, 1)->startOfMonth();
            $end   = Carbon::create($tahun, $bulanInt, 1)->endOfMonth();
        }

        /* ======================
         * LAPORAN KUNJUNGAN
         * ====================== */
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

        /* ======================
         * LAPORAN PEMBAYARAN
         * ====================== */
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

        /* ======================
         * LAPORAN PEMERIKSAAN
         * ====================== */
        $totalPemeriksaan = RekamMedis::whereBetween('created_at', [$start, $end])->count();

        return view('admin.laporan.index', compact(
            'bulan', // Tetap dikirim apa adanya ke view agar value "semua" terdeteksi
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

    /**
     * =========================
     * EXPORT PDF LAPORAN RESMI
     * =========================
     */
    public function exportPdf(Request $request)
    {
        // 1. TAMBAHKAN INI UNTUK MENCEGAH LIMIT MEMORY/WAKTU DOMPDF
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '512M');

        $bulan = $request->bulan ?? now()->month;
        $tahun = (int) ($request->tahun ?? now()->year);

        // PENGECEKAN RANGE WAKTU UNTUK PDF
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

        // 2. MEMBERSIHKAN OUTPUT BUFFER SEBELUM RENDER PDF
        // Ini adalah kunci agar file PDF tidak rusak/corrupt karena spasi kosong
        // GANTI DENGAN KODE INI:
        if (ob_get_length()) {
            ob_end_clean();
        }
        return $pdf->stream($namaFile);
    }
}
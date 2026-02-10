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
        // 🔒 PASTIKAN BULAN & TAHUN INTEGER
        $bulan = (int) ($request->bulan ?? now()->month);
        $tahun = (int) ($request->tahun ?? now()->year);

        // 🔑 RANGE WAKTU VALID
        $start = Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $end   = Carbon::create($tahun, $bulan, 1)->endOfMonth();

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

    /**
     * =========================
     * EXPORT PDF LAPORAN RESMI
     * =========================
     */
    public function exportPdf(Request $request)
    {
        // 🔒 CAST KE INTEGER (INI KUNCI FIX ERROR KAMU)
        $bulan = (int) ($request->bulan ?? now()->month);
        $tahun = (int) ($request->tahun ?? now()->year);

        $start = Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $end   = Carbon::create($tahun, $bulan, 1)->endOfMonth();

        // 🟢 NAMA BULAN (DIHITUNG DI CONTROLLER, BUKAN DI BLADE)
        $namaBulan = Carbon::create()->month($bulan)->translatedFormat('F');

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

        return $pdf->download(
            'laporan-polkes-' . $bulan . '-' . $tahun . '.pdf'
        );
    }
}

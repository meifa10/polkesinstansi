<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranPoli;
use App\Models\RekamMedis;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class DataPasienController extends Controller
{
    /**
     * =========================
     * LIST DATA PASIEN (ADMIN)
     * + SEARCH + AGREGASI BENAR
     * =========================
     */
    public function index(Request $request)
    {
        /**
         * =========================
         * BASE QUERY (JOIN REKAM MEDIS)
         * =========================
         */
        $query = PendaftaranPoli::whereNotNull('pendaftaran_poli.no_identitas')
            ->leftJoin('rekam_medis', 'rekam_medis.pendaftaran_id', '=', 'pendaftaran_poli.id')
            ->select(
                'pendaftaran_poli.nama_pasien',
                'pendaftaran_poli.no_identitas',
                'pendaftaran_poli.jenis_pasien',
                'pendaftaran_poli.tanggal_lahir'
            )
            ->selectRaw('COUNT(rekam_medis.id) as total_kunjungan')
            ->selectRaw('MAX(rekam_medis.created_at) as terakhir_kunjungan');

        /**
         * =========================
         * 🔍 SEARCH (FIXED)
         * =========================
         */
        if ($request->filled('q')) {

            $search = trim($request->q);

            $query->where(function ($q) use ($search) {
                $q->where('pendaftaran_poli.nama_pasien', 'like', '%' . $search . '%')
                  ->orWhere('pendaftaran_poli.no_identitas', 'like', '%' . $search . '%')
                  ->orWhere('pendaftaran_poli.jenis_pasien', 'like', '%' . $search . '%');
            });
        }

        /**
         * =========================
         * GROUPING & ORDER
         * =========================
         */
        $pasien = $query
            ->groupBy(
                'pendaftaran_poli.nama_pasien',
                'pendaftaran_poli.no_identitas',
                'pendaftaran_poli.jenis_pasien',
                'pendaftaran_poli.tanggal_lahir'
            )
            ->orderByDesc('terakhir_kunjungan')
            ->get();

        /**
         * =========================
         * STATUS ADMINISTRASI
         * =========================
         */
        $pasien->transform(function ($p) {

            $pembayaran = Pembayaran::whereHas('pendaftaran', function ($q) use ($p) {
                $q->where('no_identitas', $p->no_identitas);
            })->latest()->first();

            if (!$pembayaran) {
                $p->status_admin = 'belum_tagihan';
            } elseif ($pembayaran->status === 'lunas') {
                $p->status_admin = 'lunas';
            } else {
                $p->status_admin = 'belum_lunas';
            }

            return $p;
        });

        return view('admin.data_pasien.index', compact('pasien'));
    }

    /**
     * =================================
     * DETAIL PASIEN + RIWAYAT KUNJUNGAN
     * =================================
     */
    public function detail($no_identitas)
    {
        /**
         * =========================
         * 1️⃣ AMBIL PENDAFTARAN
         * =========================
         */
        $pendaftaran = PendaftaranPoli::where('no_identitas', $no_identitas)
            ->orderByDesc('created_at')
            ->get();

        if ($pendaftaran->isEmpty()) {
            abort(404, 'Pasien tidak ditemukan');
        }

        /**
         * =========================
         * 2️⃣ AMBIL REKAM MEDIS
         * =========================
         */
        $rekamMedis = RekamMedis::whereIn(
                'pendaftaran_id',
                $pendaftaran->pluck('id')
            )
            ->latest()
            ->get();

        /**
         * =========================
         * 3️⃣ AMBIL PEMBAYARAN
         * =========================
         */
        $pembayaran = Pembayaran::whereIn(
                'pendaftaran_id',
                $pendaftaran->pluck('id')
            )
            ->latest()
            ->first();

        /**
         * =========================
         * RETURN VIEW
         * =========================
         */
        return view('admin.data_pasien.detail', [
            'pasien'     => $pendaftaran->first(),
            'kunjungan'  => $pendaftaran,
            'rekamMedis' => $rekamMedis,
            'pembayaran' => $pembayaran
        ]);
    }
}
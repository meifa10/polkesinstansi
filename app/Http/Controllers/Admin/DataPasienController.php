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
     * + SEARCH
     * =========================
     */
    public function index(Request $request)
    {
        /**
         * 1️⃣ Query dasar pasien unik
         */
        $query = PendaftaranPoli::whereNotNull('no_identitas')
            ->select(
                'nama_pasien',
                'no_identitas',
                'jenis_pasien',
                'tanggal_lahir'
            )
            ->selectRaw('COUNT(id) as total_kunjungan')
            ->selectRaw('MAX(created_at) as terakhir_kunjungan');

        /**
         * 2️⃣ SEARCH
         * (Nama / No Identitas / Jenis Pasien)
         */
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_pasien', 'like', '%' . $request->q . '%')
                  ->orWhere('no_identitas', 'like', '%' . $request->q . '%')
                  ->orWhere('jenis_pasien', 'like', '%' . $request->q . '%');
            });
        }

        /**
         * 3️⃣ GROUPING & ORDER
         */
        $pasien = $query
            ->groupBy(
                'nama_pasien',
                'no_identitas',
                'jenis_pasien',
                'tanggal_lahir'
            )
            ->orderByDesc('terakhir_kunjungan')
            ->get();

        /**
         * 4️⃣ STATUS ADMINISTRASI
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
         * 1️⃣ Ambil semua pendaftaran pasien ini
         */
        $pendaftaran = PendaftaranPoli::where('no_identitas', $no_identitas)
            ->orderByDesc('created_at')
            ->get();

        if ($pendaftaran->isEmpty()) {
            abort(404, 'Pasien tidak ditemukan');
        }

        /**
         * 2️⃣ Ambil semua rekam medis
         *    (AMAN dengan DB kamu)
         */
        $rekamMedis = RekamMedis::whereIn(
                'pendaftaran_id',
                $pendaftaran->pluck('id')
            )
            ->orderByDesc('id') // ⛔ jangan pakai created_at
            ->get();

        /**
         * 3️⃣ Ambil pembayaran terakhir (jika ada)
         */
        $pembayaran = Pembayaran::whereIn(
                'pendaftaran_id',
                $pendaftaran->pluck('id')
            )
            ->latest()
            ->first();

        return view('admin.data_pasien.detail', [
            'pasien'     => $pendaftaran->first(), // data utama pasien
            'kunjungan'  => $pendaftaran,           // histori kunjungan
            'rekamMedis' => $rekamMedis,            // histori medis
            'pembayaran' => $pembayaran             // status pembayaran
        ]);
    }
}

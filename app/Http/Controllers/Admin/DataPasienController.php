<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranPoli;
use App\Models\RekamMedis;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class DataPasienController extends Controller
{
    public function index(Request $request)
    {
        $query = PendaftaranPoli::leftJoin(
                'rekam_medis',
                'rekam_medis.pendaftaran_id',
                '=',
                'pendaftaran_poli.id'
            )
            ->selectRaw('
                COALESCE(pendaftaran_poli.no_identitas, CONCAT("TEMP-", pendaftaran_poli.id)) as no_identitas,
                MAX(pendaftaran_poli.nama_pasien) as nama_pasien,
                MAX(pendaftaran_poli.jenis_pasien) as jenis_pasien,
                MAX(pendaftaran_poli.tanggal_lahir) as tanggal_lahir,
                COUNT(rekam_medis.id) as total_kunjungan,
                MAX(rekam_medis.created_at) as terakhir_kunjungan
            ');

        if ($request->filled('q')) {
            $search = trim($request->q);
            $query->where(function ($q) use ($search) {
                $q->where('pendaftaran_poli.nama_pasien', 'like', "%{$search}%")
                  ->orWhere('pendaftaran_poli.no_identitas', 'like', "%{$search}%")
                  ->orWhere('pendaftaran_poli.jenis_pasien', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jenis')) {
            $jenis = strtolower($request->jenis);
            $query->whereRaw('LOWER(pendaftaran_poli.jenis_pasien) = ?', [$jenis]);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('pendaftaran_poli.created_at', $request->tanggal);
        }

        $allData = $query
            ->groupByRaw('COALESCE(pendaftaran_poli.no_identitas, CONCAT("TEMP-", pendaftaran_poli.id))')
            ->orderByDesc('terakhir_kunjungan')
            ->get();

        $perPage = 10;
        $page = Paginator::resolveCurrentPage() ?: 1;

        $currentPageItems = $allData->slice(($page - 1) * $perPage, $perPage)->values();

        $currentPageItems->transform(function ($p) {
            if (str_starts_with($p->no_identitas, 'TEMP-')) {
                $p->status_admin = 'belum_tagihan';
                return $p;
            }

            $pendaftaranIds = PendaftaranPoli::where('no_identitas', $p->no_identitas)
                ->pluck('id');

            $pembayaran = Pembayaran::whereIn('pendaftaran_id', $pendaftaranIds)
                ->latest()
                ->first();

            if (!$pembayaran) {
                $p->status_admin = 'belum_tagihan';
            } elseif ($pembayaran->status === 'lunas') {
                $p->status_admin = 'lunas';
            } else {
                $p->status_admin = 'belum_lunas';
            }

            return $p;
        });

        $pasien = new LengthAwarePaginator(
            $currentPageItems,
            $allData->count(),
            $perPage,
            $page,
            [
                'path' => Paginator::resolveCurrentPath(),
                'query' => $request->query() 
            ]
        );

        return view('admin.data_pasien.index', compact('pasien'));
    }

    public function detail($no_identitas)
    {
        // PERBAIKAN: Menambahkan with('dokter') untuk menghindari N+1 issue
        if (str_starts_with($no_identitas, 'TEMP-')) {
            $id = str_replace('TEMP-', '', $no_identitas);
            $pendaftaran = PendaftaranPoli::with('dokter')->where('id', $id)
                ->orWhere('no_identitas', $id)
                ->get();
        } else {
            $pendaftaran = PendaftaranPoli::with('dokter')->where('no_identitas', $no_identitas)
                ->orWhere('id', $no_identitas)
                ->orderByDesc('created_at')
                ->get();
        }

        if ($pendaftaran->isEmpty()) {
            abort(404, 'Pasien tidak ditemukan');
        }

        // PERBAIKAN: Membawa relasi pendaftaran.dokter ke dalam RekamMedis
        $rekamMedis = RekamMedis::with(['pendaftaran', 'pendaftaran.dokter'])
            ->whereIn('pendaftaran_id', $pendaftaran->pluck('id'))
            ->latest()
            ->get();

        $pembayaran = Pembayaran::whereIn('pendaftaran_id', $pendaftaran->pluck('id'))
            ->latest()
            ->first();

        return view('admin.data_pasien.detail', [
            'pasien'     => $pendaftaran->first(),
            'kunjungan'  => $pendaftaran,
            'rekamMedis' => $rekamMedis,
            'pembayaran' => $pembayaran
        ]);
    }
}
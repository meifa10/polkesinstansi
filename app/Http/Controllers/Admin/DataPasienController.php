<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranPoli;
use App\Models\RekamMedis;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

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

        $pasien = $query
            ->groupByRaw('COALESCE(pendaftaran_poli.no_identitas, CONCAT("TEMP-", pendaftaran_poli.id))')
            ->orderByDesc('terakhir_kunjungan')
            ->paginate(10)
            ->withQueryString();

        $pasien->getCollection()->transform(function ($p) {

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

        return view('admin.data_pasien.index', compact('pasien'));
    }

    public function detail($no_identitas)
    {
        if (str_starts_with($no_identitas, 'TEMP-')) {
            $id = str_replace('TEMP-', '', $no_identitas);
            $pendaftaran = PendaftaranPoli::where('id', $id)
                ->orWhere('no_identitas', $id)
                ->get();
        } else {
            $pendaftaran = PendaftaranPoli::where('no_identitas', $no_identitas)
                ->orWhere('id', $no_identitas)
                ->orderByDesc('created_at')
                ->get();
        }

        if ($pendaftaran->isEmpty()) {
            abort(404, 'Pasien tidak ditemukan');
        }

        $rekamMedis = RekamMedis::whereIn('pendaftaran_id', $pendaftaran->pluck('id'))
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
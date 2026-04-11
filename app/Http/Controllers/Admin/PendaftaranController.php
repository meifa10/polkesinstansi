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
        // 1. Ambil pendaftaran dengan relasi pembayaran & rekam medis
        $query = PendaftaranPoli::with(['pembayaran', 'rekamMedis']);

        if ($request->filled('q')) {
            $search = trim($request->q);
            $query->where(function ($q) use ($search) {
                $q->where('nama_pasien', 'like', "%{$search}%")
                  ->orWhere('no_identitas', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jenis')) {
            $query->where('jenis_pasien', $request->jenis);
        }

        // 2. Ambil data dan grouping berdasarkan identitas
        $pasien = $query->latest()->get()->groupBy(function ($item) {
            return $item->no_identitas ?: 'TEMP-' . $item->id;
        })->map(function ($group) {
            $latest = $group->first();
            
            // Cari pembayaran lunas di dalam grup pendaftaran ini
            $pembayaran = $group->map(fn($p) => $p->pembayaran)->filter()->first();

            $latest->display_id = $latest->no_identitas ?: 'TEMP-' . $latest->id;
            $latest->total_kunjungan = $group->count();

            // Logika Status
            if (!$pembayaran) {
                $latest->status_admin = 'belum_tagihan';
            } else {
                $latest->status_admin = (strtolower($pembayaran->status) == 'lunas') ? 'lunas' : 'belum_lunas';
            }

            return $latest;
        });

        return view('admin.data_pasien.index', ['pasien' => $pasien]);
    }

    public function detail($no_identitas)
    {
        if (str_starts_with($no_identitas, 'TEMP-')) {
            $id = str_replace('TEMP-', '', $no_identitas);
            $pendaftaran = PendaftaranPoli::where('id', $id)->get();
        } else {
            $pendaftaran = PendaftaranPoli::where('no_identitas', $no_identitas)->latest()->get();
        }

        if ($pendaftaran->isEmpty()) { abort(404); }

        $ids = $pendaftaran->pluck('id');
        return view('admin.data_pasien.detail', [
            'pasien'     => $pendaftaran->first(),
            'kunjungan'  => $pendaftaran,
            'rekamMedis' => RekamMedis::whereIn('pendaftaran_id', $ids)->latest()->get(),
            'pembayaran' => Pembayaran::whereIn('pendaftaran_id', $ids)->latest()->first()
        ]);
    }
}
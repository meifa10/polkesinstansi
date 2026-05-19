<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use App\Models\PendaftaranPoli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class RekamMedisController extends Controller
{
    /**
     * Halaman Utama: Daftar Unik Pasien (Satu Nama Hanya Muncul Sekali)
     */
    public function index(Request $request)
    {
        $dokterId = auth()->id();

        // Ambil rekam medis milik dokter ini, gabungkan dengan data pendaftaran
        $query = RekamMedis::join('pendaftaran_polis', 'rekam_medis.pendaftaran_id', '=', 'pendaftaran_polis.id')
            ->where('rekam_medis.dokter_id', $dokterId)
            // Mengelompokkan berdasarkan Nama Pasien agar tidak terjadi duplikasi nama
            ->select(
                'pendaftaran_polis.nama_pasien',
                'pendaftaran_polis.nik',
                DB::raw('MAX(pendaftaran_polis.poli) as poli_terakhir'),
                DB::raw('COUNT(rekam_medis.id) as total_kunjungan'),
                DB::raw('MAX(rekam_medis.created_at) as terakhir_periksa')
            )
            ->groupBy('pendaftaran_polis.nama_pasien', 'pendaftaran_polis.nik');

        // Filter Live / Server-Side jika ada pencarian Poliklinik
        if ($request->filled('poli') && $request->poli !== 'ALL') {
            $query->where('pendaftaran_polis.poli', $request->poli);
        }

        $pasienList = $query->orderBy('terakhir_periksa', 'desc')->get();

        return view('dokter.rekam_medis.index', compact('pasienList'));
    }

    /**
     * Halaman Detail: Riwayat Rekam Medis Berdasarkan Nama Pasien (Maksimal 5 Data per Halaman)
     */
    public function riwayat(Request $request, $nama_pasien_encrypted)
    {
        $dokterId = auth()->id();
        
        // Dekripsi nama pasien dari URL aman
        $namaPasien = Crypt::decryptString($nama_pasien_encrypted);

        // Cari riwayat periksa dokter ini khusus untuk pasien dengan nama tersebut
        $query = RekamMedis::with('pendaftaran')
            ->where('dokter_id', $dokterId)
            ->whereHas('pendaftaran', function($q) use ($namaPasien) {
                $q->where('nama_pasien', $namaPasien);
            });

        // Filter pencarian berdasarkan tanggal rekam medis jika diisi
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // Pagination Ketat: Maksimal 5 data per halaman
        $dataRiwayat = $query->latest()->paginate(5);

        // Ambil satu sampel data pendaftaran untuk informasi header komponen visual
        $sampelPendaftaran = PendaftaranPoli::where('nama_pasien', $namaPasien)->latest()->first();

        return view('dokter.rekam_medis.riwayat', compact('dataRiwayat', 'namaPasien', 'sampelPendaftaran'));
    }
}
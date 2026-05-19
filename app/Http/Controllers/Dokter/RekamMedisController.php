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

        // 1. Ambil nama pasien unik yang pernah diperiksa oleh dokter ini
        $pasienUnikQuery = RekamMedis::where('dokter_id', $dokterId)
            ->select('pendaftaran_id')
            ->with('pendaftaran')
            // Menggunakan chunk/get lalu diproses via Collection agar aman dari struktur tabel pendaftaran_poli
            ->get()
            ->map(function ($item) {
                return [
                    'nama_pasien' => $item->pendaftaran->nama_pasien ?? null,
                    'nik'         => $item->pendaftaran->nik ?? '-',
                    'poli'        => $item->pendaftaran->poli ?? '-',
                    'created_at'  => $item->created_at
                ];
            })
            ->filter(fn($item) => !is_null($item['nama_pasien']))
            ->groupBy('nama_pasien');

        // 2. Olah data Grouping menjadi bentuk array terstruktur untuk dibaca di blade template
        $pasienList = [];
        foreach ($pasienUnikQuery as $namaPasien => $kunjungan) {
            // Ambil data kunjungan paling terakhir (terbaru)
            $terakhir = $kunjungan->sortByDesc('created_at')->first();
            
            $pasienList[] = (object) [
                'nama_pasien'      => $namaPasien,
                'nik'              => $terakhir['nik'],
                'poli_terakhir'    => $terakhir['poli'],
                'total_kunjungan'  => $kunjungan->count(),
                'terakhir_periksa' => $terakhir['created_at'],
            ];
        }

        // 3. Ubah ke bentuk collection dan urutkan berdasarkan pemeriksaan terbaru
        $pasienList = collect($pasienList)->sortByDesc('terakhir_periksa');

        // 4. Jika ada filter poliklinik dari dropdown view
        if ($request->filled('poli') && $request->poli !== 'ALL') {
            $pasienList = $pasienList->where('poli_terakhir', $request->poli);
        }

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
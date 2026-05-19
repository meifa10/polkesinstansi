<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use App\Models\PendaftaranPoli;
use Illuminate\Http\Request;
use DB;

class RekamMedisController extends Controller
{
    /**
     * Halaman Utama: Daftar Unik Pasien yang Pernah Diperiksa Dokter Ini
     */
    public function index(Request $request)
    {
        $dokterId = auth()->id();

        // Query dasar mengambil data pendaftaran yang unik berdasarkan NIK/Nama Pasien untuk dokter ini
        $query = RekamMedis::with('pendaftaran')
            ->where('dokter_id', $dokterId)
            ->select('pendaftaran_id', DB::raw('count(*) as total_kunjungan'), DB::raw('MAX(created_at) as terakhir_periksa'))
            ->groupBy('pendaftaran_id');

        // Filter Berdasarkan Poliklinik (Jika menggunakan filter live JS, ini opsional tapi bagus untuk backup)
        if ($request->filled('poli') && $request->poli !== 'ALL') {
            $query->whereHas('pendaftaran', function($q) use ($request) {
                $q->where('poli', $request->poli);
            });
        }

        // Ambil data pasien (Bisa disesuaikan jumlah per halamannya, misal 10 untuk daftar pasien utama)
        $pasienList = $query->latest('terakhir_periksa')->get();

        return view('dokter.rekam_medis.index', compact('pasienList'));
    }

    /**
     * Halaman Detail: Riwayat Rekam Medis Spesifik Per Pasien (Maksimal 5 Data per Halaman)
     */
    public function riwayat(Request $request, $pendaftaran_id)
    {
        // Ambil info pendaftaran dasar untuk header nama pasien
        $pendaftaranUtama = PendaftaranPoli::findOrFail($pendaftaran_id);
        $dokterId = auth()->id();

        $query = RekamMedis::with('pendaftaran')
            ->where('dokter_id', $dokterId)
            // Mengunci pencarian berdasarkan kesamaan Nama Pasien / NIK agar seluruh riwayatnya terikat
            ->whereHas('pendaftaran', function($q) use ($pendaftaranUtama) {
                $q->where('nama_pasien', $pendaftaranUtama->nama_pasien);
            });

        // Filter pencarian berdasarkan tanggal rekam medis jika diisi
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // Pagination Ketat: Maksimal 5 data per halaman
        $dataRiwayat = $query->latest()->paginate(5);

        return view('dokter.rekam_medis.riwayat', compact('dataRiwayat', 'pendaftaranUtama'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required',
            'keluhan'        => 'required',
            'diagnosis'      => 'required',
            'tindakan'       => 'required',
            'resep'          => 'nullable',
        ]);

        RekamMedis::create([
            'pendaftaran_id' => $request->pendaftaran_id,
            'dokter_id'      => auth()->id(),
            'keluhan'        => $request->keluhan,
            'diagnosis'      => $request->diagnosis,
            'tindakan'       => $request->tindakan,
            'resep'          => $request->resep,
        ]);

        PendaftaranPoli::where('id', $request->pendaftaran_id)
            ->update([
                'status' => 'selesai'
            ]);

        return redirect()
            ->route('dokter.pasien')
            ->with('success', 'Rekam medis berhasil disimpan');
    }
}
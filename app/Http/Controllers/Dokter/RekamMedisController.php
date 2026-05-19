<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use App\Models\PendaftaranPoli;
use Illuminate\Http\Request;

class RekamMedisController extends Controller
{
    public function index(Request $request)
    {
        // Jalankan query dasar dengan memanggil relasi pendaftaran pasien
        $query = RekamMedis::with('pendaftaran');

        // Filter Pencarian Tanggal (Gunakan tanggal dari created_at rekam medis)
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // Ambil data dengan batasan maksimal 5 data per halaman (Pagination)
        $data = $query->latest()->paginate(5);

        return view('dokter.rekam_medis.index', compact('data'));
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

        // simpan rekam medis
        RekamMedis::create([
            'pendaftaran_id' => $request->pendaftaran_id,
            'dokter_id'      => auth()->id(),
            'keluhan'        => $request->keluhan,
            'diagnosis'      => $request->diagnosis,
            'tindakan'       => $request->tindakan,
            'resep'          => $request->resep,
        ]);

        // ubah status pasien menjadi selesai
        PendaftaranPoli::where('id', $request->pendaftaran_id)
            ->update([
                'status' => 'selesai'
            ]);

        return redirect()
            ->route('dokter.pasien')
            ->with('success', 'Rekam medis berhasil disimpan');
    }
}
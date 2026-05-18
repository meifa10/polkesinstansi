<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\PendaftaranPoli;
use App\Models\RekamMedis;
use App\Models\Obat;
use App\Models\ResepObat;
use App\Models\Pembayaran;

class PemeriksaanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DAFTAR PASIEN DOKTER
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        // PERBAIKAN DI SINI
        if (Auth::user()->kategori_poli == 'semua_poli') {

            $pasien = PendaftaranPoli::with('dokter')
                ->where('status', 'diproses_dokter')
                ->orderBy('nomor_antrian', 'asc')
                ->get();

        } else {

            $pasien = PendaftaranPoli::with('dokter')
                ->where('dokter_id', Auth::id())
                ->where('status', 'diproses_dokter')
                ->orderBy('nomor_antrian', 'asc')
                ->get();
        }

        return view('dokter.pasien', compact('pasien'));
    }

    /*
    |--------------------------------------------------------------------------
    | FORM PEMERIKSAAN PASIEN
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        // PERBAIKAN DI SINI
        if (Auth::user()->kategori_poli == 'semua_poli') {

            $pasien = PendaftaranPoli::with('dokter')
                ->where('id', $id)
                ->firstOrFail();

        } else {

            $pasien = PendaftaranPoli::with('dokter')
                ->where('id', $id)
                ->where('dokter_id', Auth::id())
                ->firstOrFail();
        }

        $obat = Obat::where('stok', '>', 0)
            ->orderBy('nama_obat', 'asc')
            ->get();

        $tindakan = [];

        if ($pasien->poli == 'Poli Umum') {
            $tindakan = [
                'Pemeriksaan Umum', 'Pemberian Obat', 'Infus', 
                'Suntik Vitamin', 'Nebulizer', 'Rujukan'
            ];
        } elseif ($pasien->poli == 'Poli Gigi') {
            $tindakan = [
                'Tambal Gigi', 'Cabut Gigi', 'Pembersihan Karang Gigi', 
                'Scalling', 'Pemberian Obat', 'Rujukan'
            ];
        } elseif ($pasien->poli == 'Poli KIA & KB') {
            $tindakan = [
                'Pemeriksaan Kehamilan', 'USG', 'Konsultasi KB', 
                'Pemberian Vitamin', 'Imunisasi', 'Rujukan'
            ];
        }

        return view('dokter.pemeriksaan', compact(
            'pasien',
            'tindakan',
            'obat'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN PEMERIKSAAN
    |--------------------------------------------------------------------------
    */
    public function store($id, Request $request)
    {
        // PERBAIKAN DI SINI
        if (Auth::user()->kategori_poli == 'semua_poli') {

            $pasien = PendaftaranPoli::where('id', $id)
                ->firstOrFail();

        } else {

            $pasien = PendaftaranPoli::where('id', $id)
                ->where('dokter_id', Auth::id())
                ->firstOrFail();
        }

        $request->validate([
            'keluhan'        => 'required',
            'diagnosis'      => 'required',
            'tindakan'       => 'required',
            'obat_id.*'      => 'nullable|exists:obat,id',
            'qty.*'          => 'nullable|numeric|min:1',
            'aturan_minum.*' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {

            $resepText = '';
            $totalObat = 0;

            $rekam = RekamMedis::create([
                'pendaftaran_id' => $pasien->id,
                'dokter_id'      => Auth::id(),
                'keluhan'        => $request->keluhan,
                'diagnosis'      => $request->diagnosis,
                'tindakan'       => $request->tindakan,
                'resep'          => '-'
            ]);

            if ($request->has('obat_id')) {

                foreach ($request->obat_id as $key => $obatId) {

                    if (empty($obatId)) {
                        continue;
                    }

                    $obat = Obat::lockForUpdate()->find($obatId);

                    if (!$obat) {
                        continue;
                    }

                    $qty = (int) ($request->qty[$key] ?? 0);

                    if ($qty > $obat->stok) {
                        throw new \Exception(
                            'Stok obat ' . $obat->nama_obat . ' tidak mencukupi.'
                        );
                    }

                    $subtotal = $obat->harga * $qty;
                    $totalObat += $subtotal;

                    ResepObat::create([
                        'rekam_medis_id' => $rekam->id,
                        'obat_id'        => $obatId,
                        'qty'            => $qty,
                        'aturan_minum'   => $request->aturan_minum[$key] ?? '-',
                        'subtotal'       => $subtotal
                    ]);

                    $resepText .= $obat->nama_obat . ' (' . $qty . ' pcs) - ' . 
                                  ($request->aturan_minum[$key] ?? '-') . "\n";

                    $obat->decrement('stok', $qty);
                }
            }

            $rekam->update([
                'resep' => $resepText ?: '-'
            ]);

            $biayaDokter = 50000;
            $biayaAdmin  = 10000;

            $totalFinal = ($totalObat + $biayaDokter + $biayaAdmin);

            Pembayaran::updateOrCreate(
                ['pendaftaran_id' => $pasien->id],
                [
                    'total_obat'   => $totalObat,
                    'biaya_dokter' => $biayaDokter,
                    'biaya_admin'  => $biayaAdmin,
                    'total_biaya'  => $totalFinal,
                    'metode'       => 'midtrans',
                    'status'       => 'pending',
                    'payment_ref'  => 'INV-' . time() . '-' . $pasien->id
                ]
            );

            $pasien->update([
                'status' => 'menunggu_pembayaran'
            ]);

            DB::commit();

            return redirect()
                ->route('dokter.pasien')
                ->with('success', 'Pemeriksaan pasien berhasil disimpan.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RIWAYAT REKAM MEDIS
    |--------------------------------------------------------------------------
    */
    public function rekamMedis()
    {
        // PERBAIKAN DI SINI
        if (Auth::user()->kategori_poli == 'semua_poli') {

            $data = RekamMedis::with(['pendaftaran', 'dokter'])
                ->latest()
                ->get();

        } else {

            $data = RekamMedis::with(['pendaftaran', 'dokter'])
                ->where('dokter_id', Auth::id())
                ->latest()
                ->get();
        }

        return view('dokter.rekammedis', compact('data'));
    }
}
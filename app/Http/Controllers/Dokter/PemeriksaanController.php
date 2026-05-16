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
        // Dokter global → melihat semua pasien
        if (Auth::user()->akses_semua_poli == 1) {

            $pasien = PendaftaranPoli::with('dokter')
                ->where('status', 'diproses_dokter')
                ->orderBy('nomor_antrian', 'asc')
                ->get();

        } else {

            // Dokter biasa → hanya pasien miliknya
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
        // Dokter global
        if (Auth::user()->akses_semua_poli == 1) {

            $pasien = PendaftaranPoli::with('dokter')
                ->where('id', $id)
                ->firstOrFail();

        } else {

            // Dokter biasa
            $pasien = PendaftaranPoli::with('dokter')
                ->where('id', $id)
                ->where('dokter_id', Auth::id())
                ->firstOrFail();
        }

        // Ambil data obat yang stok masih tersedia
        $obat = Obat::where('stok', '>', 0)
            ->orderBy('nama_obat', 'asc')
            ->get();

        // Tindakan berdasarkan poli
        $tindakan = [];

        if ($pasien->poli == 'Poli Umum') {

            $tindakan = [
                'Pemeriksaan Umum',
                'Pemberian Obat',
                'Infus',
                'Suntik Vitamin',
                'Nebulizer',
                'Rujukan'
            ];

        } elseif ($pasien->poli == 'Poli Gigi') {

            $tindakan = [
                'Tambal Gigi',
                'Cabut Gigi',
                'Pembersihan Karang Gigi',
                'Scalling',
                'Pemberian Obat',
                'Rujukan'
            ];

        } elseif ($pasien->poli == 'Poli KIA & KB') {

            $tindakan = [
                'Pemeriksaan Kehamilan',
                'USG',
                'Konsultasi KB',
                'Pemberian Vitamin',
                'Imunisasi',
                'Rujukan'
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
        // Dokter global
        if (Auth::user()->akses_semua_poli == 1) {

            $pasien = PendaftaranPoli::where('id', $id)
                ->firstOrFail();

        } else {

            // Dokter biasa
            $pasien = PendaftaranPoli::where('id', $id)
                ->where('dokter_id', Auth::id())
                ->firstOrFail();
        }

        // Validasi input
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

            /*
            |--------------------------------------------------------------------------
            | SIMPAN REKAM MEDIS
            |--------------------------------------------------------------------------
            */
            $rekam = RekamMedis::create([
                'pendaftaran_id' => $pasien->id,
                'dokter_id'      => Auth::id(),
                'keluhan'        => $request->keluhan,
                'diagnosis'      => $request->diagnosis,
                'tindakan'       => $request->tindakan,
                'resep'          => '-'
            ]);

            /*
            |--------------------------------------------------------------------------
            | SIMPAN RESEP OBAT
            |--------------------------------------------------------------------------
            */
            if ($request->has('obat_id')) {

                foreach ($request->obat_id as $key => $obatId) {

                    // Skip jika kosong
                    if (empty($obatId)) {
                        continue;
                    }

                    $obat = Obat::lockForUpdate()->find($obatId);

                    if (!$obat) {
                        continue;
                    }

                    $qty = (int) ($request->qty[$key] ?? 0);

                    // Validasi stok
                    if ($qty > $obat->stok) {

                        throw new \Exception(
                            'Stok obat ' .
                            $obat->nama_obat .
                            ' tidak mencukupi.'
                        );
                    }

                    // Hitung subtotal
                    $subtotal = $obat->harga * $qty;

                    $totalObat += $subtotal;

                    // Simpan resep obat
                    ResepObat::create([
                        'rekam_medis_id' => $rekam->id,
                        'obat_id'        => $obatId,
                        'qty'            => $qty,
                        'aturan_minum'   => $request->aturan_minum[$key] ?? '-',
                        'subtotal'       => $subtotal
                    ]);

                    // Text resep
                    $resepText .=
                        $obat->nama_obat .
                        ' (' . $qty . ' pcs) - ' .
                        ($request->aturan_minum[$key] ?? '-') .
                        "\n";

                    // Kurangi stok obat
                    $obat->decrement('stok', $qty);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE RESEP REKAM MEDIS
            |--------------------------------------------------------------------------
            */
            $rekam->update([
                'resep' => $resepText ?: '-'
            ]);

            /*
            |--------------------------------------------------------------------------
            | PEMBAYARAN
            |--------------------------------------------------------------------------
            */
            $biayaDokter = 50000;
            $biayaAdmin  = 10000;

            $totalFinal = (
                $totalObat +
                $biayaDokter +
                $biayaAdmin
            );

            Pembayaran::updateOrCreate(

                [
                    'pendaftaran_id' => $pasien->id
                ],

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

            /*
            |--------------------------------------------------------------------------
            | UPDATE STATUS PASIEN
            |--------------------------------------------------------------------------
            */
            $pasien->update([
                'status' => 'menunggu_pembayaran'
            ]);

            DB::commit();

            return redirect()
                ->route('dokter.pasien')
                ->with(
                    'success',
                    'Pemeriksaan pasien berhasil disimpan.'
                );

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RIWAYAT REKAM MEDIS
    |--------------------------------------------------------------------------
    */
    public function rekamMedis()
    {
        // Dokter global
        if (Auth::user()->akses_semua_poli == 1) {

            $data = RekamMedis::with([
                    'pendaftaran',
                    'dokter'
                ])
                ->latest()
                ->get();

        } else {

            // Dokter biasa
            $data = RekamMedis::with([
                    'pendaftaran',
                    'dokter'
                ])
                ->where('dokter_id', Auth::id())
                ->latest()
                ->get();
        }

        return view('dokter.rekammedis', compact('data'));
    }
}
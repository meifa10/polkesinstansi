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
    /**
     * DAFTAR PASIEN KHUSUS DOKTER YANG LOGIN
     */
    public function index()
    {
        // KUNCI: Hanya ambil pasien yang memilih dokter ini (dokter_id = Auth::id())
        $pasien = PendaftaranPoli::where('dokter_id', Auth::id())
            ->where('status', 'diproses_dokter')
            ->orderBy('nomor_antrian', 'asc')
            ->get();

        return view('dokter.pasien', compact('pasien'));
    }

    /**
     * FORM PEMERIKSAAN (DENGAN SECURITY CHECK)
     */
    public function show($id)
    {
        // Pastikan pasien ini memang milik dokter yang sedang login
        $pasien = PendaftaranPoli::where('id', $id)
            ->where('dokter_id', Auth::id())
            ->firstOrFail(); // Jika dokter lain coba akses ID ini via URL, akan 404

        $obat = Obat::where('stok', '>', 0)
            ->orderBy('nama_obat', 'asc')
            ->get();

        // Tentukan pilihan tindakan berdasarkan poliklinik pasien
        $tindakan = [];
        if ($pasien->poli == 'Poli Umum') {
            $tindakan = ['Pemeriksaan Umum', 'Pemberian Obat', 'Infus', 'Suntik Vitamin', 'Nebulizer', 'Rujukan'];
        } elseif ($pasien->poli == 'Poli Gigi') {
            $tindakan = ['Tambal Gigi', 'Cabut Gigi', 'Pembersihan Karang Gigi', 'Scalling', 'Pemberian Obat', 'Rujukan'];
        } elseif ($pasien->poli == 'Poli KIA & KB') {
            $tindakan = ['Pemeriksaan Kehamilan', 'USG', 'Konsultasi KB', 'Pemberian Vitamin', 'Imunisasi', 'Rujukan'];
        }

        return view('dokter.pemeriksaan', compact('pasien', 'tindakan', 'obat'));
    }

    /**
     * SIMPAN HASIL PEMERIKSAAN
     */
    public function store($id, Request $request)
    {
        // Validasi Kepemilikan Pasien sebelum proses
        $pasien = PendaftaranPoli::where('id', $id)
            ->where('dokter_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'keluhan' => 'required',
            'diagnosis' => 'required',
            'tindakan' => 'required',
            'obat_id' => 'nullable|array',
            'qty' => 'nullable|array',
            'aturan_minum' => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            $resepText = '';
            $totalObat = 0;

            // 1. Simpan Rekam Medis
            $rekam = RekamMedis::create([
                'pendaftaran_id' => $pasien->id,
                'dokter_id' => Auth::id(),
                'keluhan' => $request->keluhan,
                'diagnosis' => $request->diagnosis,
                'tindakan' => $request->tindakan,
                'resep' => '-' 
            ]);

            // 2. Simpan Resep & Hitung Biaya
            if ($request->has('obat_id')) {
                foreach ($request->obat_id as $key => $obatId) {
                    if (empty($obatId)) continue;

                    $obat = Obat::lockForUpdate()->find($obatId);
                    $qty = (int) $request->qty[$key];

                    if (!$obat || $qty > $obat->stok) {
                        throw new \Exception("Stok " . ($obat->nama_obat ?? 'Obat') . " tidak mencukupi.");
                    }

                    $subtotal = $obat->harga * $qty;
                    $totalObat += $subtotal;

                    ResepObat::create([
                        'rekam_medis_id' => $rekam->id,
                        'obat_id' => $obatId,
                        'qty' => $qty,
                        'aturan_minum' => $request->aturan_minum[$key],
                        'subtotal' => $subtotal
                    ]);

                    $resepText .= "{$obat->nama_obat} ({$qty}) - {$request->aturan_minum[$key]}\n";
                    $obat->decrement('stok', $qty);
                }
            }

            // 3. Update Ringkasan Resep
            $rekam->update(['resep' => $resepText ?: '-']);

            // 4. Buat Tagihan Pembayaran
            $biayaDokter = 50000;
            $biayaAdmin = 10000;
            $totalFinal = $totalObat + $biayaDokter + $biayaAdmin;

            Pembayaran::updateOrCreate(
                ['pendaftaran_id' => $pasien->id],
                [
                    'total_obat' => $totalObat,
                    'biaya_dokter' => $biayaDokter,
                    'biaya_admin' => $biayaAdmin,
                    'total_biaya' => $totalFinal,
                    'metode' => 'midtrans',
                    'status' => 'pending',
                    'payment_ref' => 'INV-' . time() . '-' . $pasien->id
                ]
            );

            // 5. Update Status Pasien (Pindah dari antrian dokter ke antrian kasir)
            $pasien->update(['status' => 'menunggu_pembayaran']);

            DB::commit();
            return redirect()->route('dokter.pasien')->with('success', 'Pemeriksaan selesai. Pasien diarahkan ke kasir.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * RIWAYAT REKAM MEDIS KHUSUS DOKTER INI
     */
    public function rekamMedis()
    {
        $data = RekamMedis::with(['pendaftaran'])
            ->where('dokter_id', Auth::id())
            ->latest()
            ->get();

        return view('dokter.rekammedis', compact('data'));
    }
}
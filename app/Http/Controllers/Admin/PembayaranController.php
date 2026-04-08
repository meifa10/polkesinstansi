<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\PendaftaranPoli;
use Illuminate\Support\Str;

class PembayaranController extends Controller
{
    /**
     * Tampilkan semua daftar pembayaran
     */
    public function index()
    {
        $data = Pembayaran::with('pendaftaran')
            ->latest()
            ->get();

        return view('admin.pembayaran.index', compact('data'));
    }

    /**
     * Tampilkan form input tagihan
     */
    public function create($pendaftaran_id)
    {
        $pendaftaran = PendaftaranPoli::findOrFail($pendaftaran_id);

        return view('admin.pembayaran.create', compact('pendaftaran'));
    }

    /**
     * SIMPAN TAGIHAN (DI SINI PERBAIKANNYA)
     */
    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftaran_poli,id',
            'metode'         => 'required',
            'total_biaya'    => 'required' // 'numeric' dilepas karena input mungkin membawa format Rp
        ]);

        $pendaftaran = PendaftaranPoli::findOrFail($request->pendaftaran_id);

        /**
         * 2. BERSIHKAN TOTAL BIAYA (POIN PENTING!)
         * Kita buang titik atau koma agar menjadi angka murni (Integer).
         * Contoh: "50.000" menjadi 50000.
         */
        $cleanBiaya = str_replace(['.', ','], '', $request->total_biaya);
        $totalBiaya = (int) $cleanBiaya;

        /**
         * 3. BUAT PAYMENT REF (ORDER ID)
         * Gunakan random string agar unik dan tidak ditolak Midtrans.
         */
        $paymentRef = 'PAY-' . $pendaftaran->id . '-' . strtoupper(Str::random(6));

        // 4. Create ke Database
        Pembayaran::create([
            'pendaftaran_id' => $request->pendaftaran_id,
            'metode'         => $request->metode,
            'total_biaya'    => $totalBiaya, // Simpan angka bulat
            'status'         => 'belum_lunas',
            'payment_ref'    => $paymentRef,
            'snap_token'     => null,
            'paid_by'        => null,
            'tanggal_bayar'  => null,
        ]);

        return redirect()
            ->route('admin.data_pasien.detail', $pendaftaran->no_identitas ?? 'TEMP-'.$pendaftaran->id)
            ->with('success', 'Tagihan sebesar Rp ' . number_format($totalBiaya, 0, ',', '.') . ' berhasil dibuat.');
    }

    /**
     * Tandai lunas manual (BPJS/Tunai di kasir)
     */
    public function lunasi($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        $pembayaran->update([
            'status'        => 'lunas',
            'paid_by'       => 'manual_kasir',
            'tanggal_bayar' => now()
        ]);

        return back()->with('success', 'Pembayaran berhasil dilunasi secara manual.');
    }
}
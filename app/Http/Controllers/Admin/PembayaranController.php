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
     * Tampilkan semua daftar pembayaran (Index)
     */
    public function index()
    {
        $data = Pembayaran::with('pendaftaran')
            ->latest()
            ->get();

        return view('admin.pembayaran.index', compact('data'));
    }

    /**
     * Tampilkan form input tagihan (Create)
     */
    public function create($pendaftaran_id)
    {
        $pendaftaran = PendaftaranPoli::findOrFail($pendaftaran_id);

        return view('admin.pembayaran.create', compact('pendaftaran'));
    }

    /**
     * SIMPAN TAGIHAN KE DATABASE (Store)
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftaran_poli,id',
            'metode'         => 'required|string',
            'total_biaya'    => 'required' // Jangan pakai 'numeric' karena input mungkin bawa titik
        ]);

        $pendaftaran = PendaftaranPoli::findOrFail($request->pendaftaran_id);

        /**
         * 2. PEMBERSIHAN TOTAL (POIN KRUSIAL)
         * Menggunakan regex untuk membuang SEMUA karakter kecuali angka (0-9).
         * Jadi "Rp 50.000" atau "50.000" akan BERSIH menjadi "50000".
         */
        $totalBiaya = (int) preg_replace('/[^0-9]/', '', $request->total_biaya);

        // Validasi tambahan: jangan sampai total biaya nol (kecuali BPJS)
        if ($totalBiaya <= 0 && $request->metode !== 'bpjs') {
            return back()->withErrors(['total_biaya' => 'Total biaya harus lebih dari 0.'])->withInput();
        }

        /**
         * 3. BUAT PAYMENT REF (ORDER ID)
         * Menggunakan random string agar unik di Midtrans.
         */
        $paymentRef = 'PAY-' . $pendaftaran->id . '-' . strtoupper(Str::random(6));

        // 4. Create ke Database
        Pembayaran::create([
            'pendaftaran_id' => $request->pendaftaran_id,
            'metode'         => $request->metode,
            'total_biaya'    => $totalBiaya, // Simpan angka bulat murni
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
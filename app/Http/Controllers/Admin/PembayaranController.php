<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\PendaftaranPoli;

class PembayaranController extends Controller
{

    /**
     * =========================================
     * LIST SEMUA PEMBAYARAN
     * =========================================
     */
    public function index()
    {
        $data = Pembayaran::with('pendaftaran')
            ->latest()
            ->get();

        return view('admin.pembayaran.index', compact('data'));
    }



    /**
     * =========================================
     * FORM BUAT PEMBAYARAN
     * =========================================
     */
    public function create($pendaftaran_id)
    {
        $pendaftaran = PendaftaranPoli::findOrFail($pendaftaran_id);

        return view('admin.pembayaran.create', compact('pendaftaran'));
    }



    /**
     * =========================================
     * SIMPAN TAGIHAN PEMBAYARAN
     * =========================================
     */
    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required',
            'metode' => 'required',
            'total_biaya' => 'required|numeric'
        ]);

        // Ambil data pendaftaran
        $pendaftaran = PendaftaranPoli::findOrFail($request->pendaftaran_id);

        // Simpan pembayaran
        Pembayaran::create([
            'pendaftaran_id' => $request->pendaftaran_id,
            'metode' => $request->metode,
            'total_biaya' => $request->total_biaya,
            'status' => 'belum_lunas'
        ]);

        return redirect()
            ->route('admin.data_pasien.detail', $pendaftaran->no_identitas)
            ->with('success', 'Tagihan pembayaran berhasil dibuat');
    }



    /**
     * =========================================
     * TANDAI LUNAS (BPJS / TUNAI)
     * =========================================
     */
    public function lunasi($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        $pembayaran->update([
            'status' => 'lunas',
            'tanggal_bayar' => now()
        ]);

        return back()->with('success', 'Pembayaran berhasil dilunasi');
    }
}
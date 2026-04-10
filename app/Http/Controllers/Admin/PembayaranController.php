<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\PendaftaranPoli;
use Illuminate\Support\Str;

class PembayaranController extends Controller
{

    public function index(Request $request)
    {
        $query = Pembayaran::with('pendaftaran');

        // 🔎 SEARCH (nama pasien, poli, metode, status)
        if ($request->q) {
            $query->where(function ($q) use ($request) {
                $q->where('metode', 'like', '%' . $request->q . '%')
                  ->orWhere('status', 'like', '%' . $request->q . '%')
                  ->orWhereHas('pendaftaran', function ($sub) use ($request) {
                      $sub->where('nama_pasien', 'like', '%' . $request->q . '%')
                          ->orWhere('poli', 'like', '%' . $request->q . '%');
                  });
            });
        }

        // 🏥 FILTER POLI
        if ($request->poli) {
            $query->whereHas('pendaftaran', function ($q) use ($request) {
                $q->where('poli', $request->poli);
                // kalau di DB pakai nama panjang, ganti ke:
                // $q->where('poli', 'like', '%' . $request->poli . '%');
            });
        }

        $data = $query->latest()->get();

        return view('admin.pembayaran.index', compact('data'));
    }

    public function create($pendaftaran_id)
    {
        $pendaftaran = PendaftaranPoli::findOrFail($pendaftaran_id);

        return view('admin.pembayaran.create', compact('pendaftaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftaran_poli,id',
            'metode'         => 'required|string',
            'total_biaya'    => 'required'
        ]);

        $pendaftaran = PendaftaranPoli::findOrFail($request->pendaftaran_id);

        // 💰 Bersihin format rupiah (hapus titik/koma)
        $totalBiaya = (int) preg_replace('/[^0-9]/', '', $request->total_biaya);

        if ($totalBiaya <= 0 && $request->metode !== 'bpjs') {
            return back()->withErrors([
                'total_biaya' => 'Total biaya harus lebih dari 0.'
            ])->withInput();
        }

        // 🔑 Generate kode pembayaran
        $paymentRef = 'PAY-' . $pendaftaran->id . '-' . strtoupper(Str::random(6));

        Pembayaran::create([
            'pendaftaran_id' => $request->pendaftaran_id,
            'metode'         => $request->metode,
            'total_biaya'    => $totalBiaya,
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
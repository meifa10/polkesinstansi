<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\PendaftaranPoli;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    /**
     * =========================
     * LIST PEMBAYARAN (ADMIN)
     * + SEARCH
     * =========================
     */
    public function index(Request $request)
    {
        $query = Pembayaran::with('pendaftaran')
            ->orderByDesc('created_at');

        /**
         * 🔍 SEARCH
         * - Nama Pasien
         * - Poli
         * - Status (lunas / belum)
         * - Metode (bpjs / tunai)
         */
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('status', 'like', '%' . $request->q . '%')
                  ->orWhere('metode', 'like', '%' . $request->q . '%');
            })
            ->orWhereHas('pendaftaran', function ($q) use ($request) {
                $q->where('nama_pasien', 'like', '%' . $request->q . '%')
                  ->orWhere('poli', 'like', '%' . $request->q . '%')
                  ->orWhere('no_identitas', 'like', '%' . $request->q . '%');
            });
        }

        $data = $query->get();

        return view('admin.pembayaran.index', compact('data'));
    }

    /**
     * =========================
     * FORM BUAT PEMBAYARAN
     * =========================
     */
    public function create(PendaftaranPoli $pendaftaran)
    {
        if ($pendaftaran->pembayaran) {
            return redirect()
                ->route('admin.data_pasien.detail', $pendaftaran->no_identitas)
                ->with('error', 'Pembayaran untuk kunjungan ini sudah dibuat.');
        }

        return view('admin.pembayaran.create', compact('pendaftaran'));
    }

    /**
     * =========================
     * SIMPAN PEMBAYARAN
     * =========================
     */
    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftaran_poli,id',
            'metode'         => 'required|in:bpjs,tunai',
            'total_biaya'    => 'nullable|numeric|min:0',
        ]);

        $pendaftaran = PendaftaranPoli::findOrFail($request->pendaftaran_id);

        if ($pendaftaran->pembayaran) {
            return back()->with('error', 'Pembayaran sudah pernah dibuat.');
        }

        $isBpjs = $request->metode === 'bpjs';

        Pembayaran::create([
            'pendaftaran_id' => $pendaftaran->id,
            'metode'         => $request->metode,
            'total_biaya'    => $isBpjs ? 0 : (int) $request->total_biaya,
            'status'         => $isBpjs ? 'lunas' : 'belum_lunas',
            'tanggal_bayar'  => $isBpjs ? now() : null,
            'paid_by'        => $isBpjs ? 'bpjs' : null,
        ]);

        return redirect()
            ->route('admin.data_pasien.detail', $pendaftaran->no_identitas)
            ->with('success', 'Pembayaran berhasil dibuat.');
    }

    /**
     * =========================
     * LUNASI PEMBAYARAN (TUNAI)
     * =========================
     */
    public function lunasi(Pembayaran $pembayaran)
    {
        if ($pembayaran->status === 'lunas') {
            return back()->with('info', 'Pembayaran sudah lunas.');
        }

        $pembayaran->update([
            'status'        => 'lunas',
            'tanggal_bayar' => now(),
            'paid_by'       => 'tunai',
        ]);

        return back()->with('success', 'Pembayaran tunai berhasil dilunasi.');
    }
}

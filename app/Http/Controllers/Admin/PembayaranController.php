<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Pembayaran;
use App\Models\PendaftaranPoli;

class PembayaranController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | LIST PEMBAYARAN
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = Pembayaran::with('pendaftaran');

        /* SEARCH */
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

        /* FILTER POLI */
        if ($request->poli) {
            $query->whereHas('pendaftaran', function ($q) use ($request) {
                $q->where('poli', $request->poli);
            });
        }

        $data = $query->latest()->get();

        return view('admin.pembayaran.index', compact('data'));
    }

    /*
    |--------------------------------------------------------------------------
    | FORM CREATE PEMBAYARAN
    |--------------------------------------------------------------------------
    */
    public function create($pendaftaran_id)
    {
        $pendaftaran = PendaftaranPoli::findOrFail($pendaftaran_id);

        return view('admin.pembayaran.create', compact('pendaftaran'));
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN TAGIHAN (DIPERBAIKI)
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftaran_poli,id',
            'metode'         => 'required|string',
            'total_biaya'    => 'required'
        ]);

        $pendaftaran = PendaftaranPoli::findOrFail($request->pendaftaran_id);

        // Format total biaya (menghilangkan karakter non-angka)
        $totalBiaya = (int) preg_replace('/[^0-9]/', '', $request->total_biaya);

        if ($totalBiaya <= 0) {
            return back()->withErrors(['total_biaya' => 'Total biaya harus lebih dari 0'])->withInput();
        }

        // Cek apakah sudah ada tagihan aktif (pending)
        $cekPembayaran = Pembayaran::where('pendaftaran_id', $request->pendaftaran_id)
            ->where('status', 'pending')
            ->first();

        if ($cekPembayaran) {
            return back()->with('error', 'Tagihan pending sudah ada untuk pasien ini.');
        }

        /* 
        | LOGIKA RINCIAN BIAYA (Agar tidak muncul 0 di laporan pasien)
        | Jika Admin menginput total secara manual, kita definisikan rincian standarnya.
        */
        $biayaDokter = 50000; // Tarif standar
        $biayaAdmin  = 10000; // Tarif standar
        
        // Sisa dari total biaya setelah dikurangi tarif standar dianggap sebagai biaya obat
        $totalObat = $totalBiaya - ($biayaDokter + $biayaAdmin);
        if ($totalObat < 0) {
            $totalObat = 0; 
        }

        $paymentRef = 'INV-' . time() . '-' . $pendaftaran->id;

        // Simpan Pembayaran dengan rincian lengkap
        Pembayaran::create([
            'pendaftaran_id' => $request->pendaftaran_id,
            'metode'         => $request->metode,
            'biaya_dokter'   => $biayaDokter, // Disimpan agar PDF tidak 0
            'biaya_admin'    => $biayaAdmin,  // Disimpan agar PDF tidak 0
            'total_obat'     => $totalObat,   // Disimpan agar PDF tidak 0
            'total_biaya'    => $totalBiaya,
            'status'         => 'pending',
            'payment_ref'    => $paymentRef,
            'snap_token'     => null,
            'paid_by'        => null,
            'tanggal_bayar'  => null,
        ]);

        // Update status pendaftaran menjadi menunggu pembayaran
        $pendaftaran->update(['status' => 'menunggu_pembayaran']);

        return redirect()
            ->route('admin.data_pasien.detail', $pendaftaran->no_identitas ?? ('TEMP-' . $pendaftaran->id))
            ->with('success', 'Tagihan berhasil dibuat dengan rincian lengkap.');
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDASI MANUAL KASIR
    |--------------------------------------------------------------------------
    */
    public function lunasi($id)
    {
        $pembayaran = Pembayaran::with('pendaftaran')->findOrFail($id);

        $pembayaran->update([
            'status'        => 'lunas',
            'paid_by'       => 'manual_kasir',
            'tanggal_bayar' => now()
        ]);

        if ($pembayaran->pendaftaran) {
            $pembayaran->pendaftaran->update(['status' => 'selesai']);
        }

        return back()->with('success', 'Pembayaran berhasil dilunasi secara manual.');
    }
}
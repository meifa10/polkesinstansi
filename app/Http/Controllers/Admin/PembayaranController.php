<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
// Pastikan library DomPDF sudah terinstall (composer require barryvdh/laravel-dompdf)
use Barryvdh\DomPDF\Facade\Pdf; 

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with('pendaftaran');

        if ($request->q) {
            $query->where(function ($q) use ($request) {
                $q->where('metode', 'like', '%' . $request->q . '%')
                  ->orWhere('payment_ref', 'like', '%' . $request->q . '%')
                  ->orWhereHas('pendaftaran', function ($sub) use ($request) {
                      $sub->where('nama_pasien', 'like', '%' . $request->q . '%')
                          ->orWhere('poli', 'like', '%' . $request->q . '%');
                  });
            });
        }

        if ($request->poli) {
            $query->whereHas('pendaftaran', function ($q) use ($request) {
                $q->where('poli', $request->poli);
            });
        }

        $data = $query->latest()->get();
        return view('admin.pembayaran.index', compact('data'));
    }

    public function show($id)
    {
        // Load pendaftaran dan rekam medis sekaligus
        $pembayaran = Pembayaran::with(['pendaftaran.rekamMedis'])->findOrFail($id);
        return view('admin.pembayaran.show', compact('pembayaran'));
    }

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

        return back()->with('success', 'Pembayaran pasien berhasil diverifikasi.');
    }

    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD PDF STRUK LENGKAP (AUTO-DOWNLOAD)
    |--------------------------------------------------------------------------
    */
    public function printStruk($id)
    {
        // Pastikan memuat rekam medis untuk mengambil data resep
        $pembayaran = Pembayaran::with(['pendaftaran.rekamMedis'])->findOrFail($id);
        
        $pdf = Pdf::loadView('admin.pembayaran.print', compact('pembayaran'));
        
        // Mengatur ukuran kertas: 80mm x 180mm (tinggi ditambah untuk ruang resep)
        // 1mm = 2.83pt -> 80mm = 226.7pt, 180mm = 510pt
        $pdf->setPaper([0, 0, 226.77, 510.23], 'portrait');

        return $pdf->download('Struk_Pembayaran_' . $pembayaran->payment_ref . '.pdf');
    }
}
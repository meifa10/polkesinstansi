<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
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

       
        $data = $query->latest()->paginate(10);
        
        return view('admin.pembayaran.index', compact('data'));
    }

    public function show($id)
    {
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


    public function printStruk($id)
    {
        $pembayaran = Pembayaran::with(['pendaftaran.rekamMedis'])->findOrFail($id);
        
        $pdf = Pdf::loadView('admin.pembayaran.print', compact('pembayaran'));
        
       
        $pdf->setPaper([0, 0, 226.77, 510.23], 'portrait');

        return $pdf->download('Struk_Pembayaran_' . $pembayaran->payment_ref . '.pdf');
    }
}
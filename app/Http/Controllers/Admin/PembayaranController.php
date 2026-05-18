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

        // 1. FILTER SATU TANGGAL
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // 2. FILTER PENCARIAN TEKS
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('metode', 'like', '%' . $request->q . '%')
                  ->orWhere('payment_ref', 'like', '%' . $request->q . '%')
                  ->orWhereHas('pendaftaran', function ($sub) use ($request) {
                      $sub->where('nama_pasien', 'like', '%' . $request->q . '%')
                          ->orWhere('poli', 'like', '%' . $request->q . '%');
                  });
            });
        }

        // 3. FILTER LAYANAN POLI
        if ($request->filled('poli')) {
            $query->whereHas('pendaftaran', function ($q) use ($request) {
                $q->where('poli', $request->poli);
            });
        }

        // 4. HITUNG METRIK SECARA DINAMIS
        $totalPasienLunas = (clone $query)->where('status', 'lunas')->count();

        $listBiaya = (clone $query)->pluck('total_biaya');
        $totalTagihan = $listBiaya->sum(function($biaya) {
            return (int) str_replace(['.', ','], '', $biaya);
        });

        // 5. AMBIL DATA DENGAN PAGINASI
        $data = $query->latest()->paginate(10);
        
        return view('admin.pembayaran.index', compact('data', 'totalTagihan', 'totalPasienLunas'));
    }

    public function show($id)
    {
        $pembayaran = Pembayaran::with(['pendaftaran.rekamMedis'])->findOrFail($id);
        
        // Memecah teks resep dari rekam medis menjadi struktur item obat berrincian harga
        $resepString = $pembayaran->pendaftaran->rekamMedis->resep ?? '';
        $rincianObat = $this->parseResepPecahDetail($resepString);

        return view('admin.pembayaran.show', compact('pembayaran', 'rincianObat'));
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
        
        $resepString = $pembayaran->pendaftaran->rekamMedis->resep ?? '';
        $rincianObat = $this->parseResepPecahDetail($resepString);

        $pdf = Pdf::loadView('admin.pembayaran.print', compact('pembayaran', 'rincianObat'));
        $pdf->setPaper([0, 0, 226.77, 650.00], 'portrait');

        return $pdf->download('Struk_Pembayaran_' . $pembayaran->payment_ref . '.pdf');
    }

    /**
     * Fungsi Super Parser: Memecah string resep menjadi item, qty, dan harga secara detail.
     * Format input wajib di rekam medis: Nama Obat x Jumlah @ Harga
     * Contoh: Paracetamol x 10 @ 1200
     */
    private function parseResepPecahDetail($resepString)
    {
        $listObat = [];
        if (empty(trim($resepString))) return $listObat;

        // Pecah berdasarkan baris baru atau koma jika ditulis berjejer
        $rows = preg_split('/[\n,]+/', $resepString);

        foreach ($rows as $row) {
            $row = trim($row);
            if (empty($row)) continue;

            // Inisialisasi data dasar jika tidak memakai format pemisah @
            $namaObat = $row;
            $qty = 1;
            $hargaSatuan = 0;

            // Validasi format: "Nama Obat x Jumlah @ Harga"
            if (str_contains($row, 'x') && str_contains($row, '@')) {
                // Pecah teks berdasarkan karakter '@' untuk memisahkan harga
                $partHarga = explode('@', $row);
                $hargaSatuan = isset($partHarga[1]) ? (int)preg_replace('/[^0-9]/', '', $partHarga[1]) : 0;

                // Pecah bagian depan berdasarkan karakter 'x' untuk memisahkan nama obat dan jumlah
                $partNamaQty = explode('x', $partHarga[0]);
                $namaObat = isset($partNamaQty[0]) ? trim($partNamaQty[0]) : trim($partHarga[0]);
                $qty = isset($partNamaQty[1]) ? (int)preg_replace('/[^0-9]/', '', $partNamaQty[1]) : 1;
            }
            // Validasi format standar tanpa harga: "Nama Obat x Jumlah"
            elseif (str_contains($row, 'x')) {
                $partNamaQty = explode('x', $row);
                $namaObat = trim($partNamaQty[0]);
                $qty = isset($partNamaQty[1]) ? (int)preg_replace('/[^0-9]/', '', $partNamaQty[1]) : 1;
            }

            $listObat[] = [
                'nama'  => $namaObat,
                'qty'   => $qty,
                'harga' => $hargaSatuan,
                'total' => $qty * $hargaSatuan
            ];
        }

        return $listObat;
    }
}
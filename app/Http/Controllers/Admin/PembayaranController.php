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
        
        // Memecah teks resep menjadi rincian item obat yang detail
        $resepString = $pembayaran->pendaftaran->rekamMedis->resep ?? '';
        $rincianObat = $this->parseResepDetail($resepString);

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
        $rincianObat = $this->parseResepDetail($resepString);

        $pdf = Pdf::loadView('admin.pembayaran.print', compact('pembayaran', 'rincianObat'));
        
        // Ukuran kertas thermal diperpanjang otomatis ke bawah (tinggi 650pt) agar muat rincian obat banyak
        $pdf->setPaper([0, 0, 226.77, 650.00], 'portrait');

        return $pdf->download('Struk_Pembayaran_' . $pembayaran->payment_ref . '.pdf');
    }

    /**
     * Fungsi Parser: Memecah string teks resep menjadi list item obat detail
     * Mendukung format penulisan umum: "Nama Obat (Jumlah x Harga)" atau "Nama Obat xJumlah"
     */
    private function parseResepDetail($resepString)
    {
        $result = [];
        if (empty(trim($resepString))) return $result;

        // Pisahkan teks resep per baris atau berdasarkan koma
        $lines = preg_split('/[\n,]+/', $resepString);

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $namaObat = $line;
            $qty = 1;
            $harga = 0;

            // Pola 1: Mencari format modern "Paracetamol (10 x 1200)" atau "Amoxicilin (5x2000)"
            if (preg_match('/^(.*?)\s*\((.*?)\)$/', $line, $matches)) {
                $namaObat = trim($matches[1]);
                $ekspresi = $matches[2]; // Berisi "10 x 1200"

                if (preg_match('/(\d+)\s*[xX*]\s*(\d+)/', $ekspresi, $expMatches)) {
                    $qty = (int)$expMatches[1];
                    $harga = (int)$expMatches[2];
                } else {
                    $qty = (int)preg_replace('/[^0-9]/', '', $ekspresi) ?: 1;
                }
            } 
            // Pola 2: Mencari format standar "Paracetamol x10" atau "Cataflam *5"
            elseif (preg_match('/^(.*?)\s*[xX*]\s*(\d+)$/', $line, $matches)) {
                $namaObat = trim($matches[1]);
                $qty = (int)$matches[2];
            }

            $totalItem = $qty * $harga;

            $result[] = [
                'nama' => $namaObat,
                'qty' => $qty,
                'harga' => $harga,
                'total' => $totalItem
            ];
        }

        return $result;
    }
}
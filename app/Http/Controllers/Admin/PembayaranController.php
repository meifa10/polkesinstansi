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

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

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

        $totalPasienLunas = (clone $query)->where('status', 'lunas')->count();

        $listBiaya = (clone $query)->pluck('total_biaya');
        $totalTagihan = $listBiaya->sum(function($biaya) {
            return (int) str_replace(['.', ','], '', $biaya);
        });

        $data = $query->latest()->paginate(10);
        
        return view('admin.pembayaran.index', compact('data', 'totalTagihan', 'totalPasienLunas'));
    }


    public function show($id)
    {
        $pembayaran = Pembayaran::with(['pendaftaran.rekamMedis'])->findOrFail($id);
        
        $resepString = $pembayaran->pendaftaran->rekamMedis->resep ?? '';
        $totalHargaObat = (int) str_replace(['.', ','], '', $pembayaran->total_obat ?? 0);
        $rincianObat = $this->parseResepPecahDetail($resepString, $totalHargaObat);

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
        $totalHargaObat = (int) str_replace(['.', ','], '', $pembayaran->total_obat ?? 0);
        $rincianObat = $this->parseResepPecahDetail($resepString, $totalHargaObat);

        $pdf = Pdf::loadView('admin.pembayaran.print', compact('pembayaran', 'rincianObat'));
        $pdf->setPaper([0, 0, 226.77, 650.00], 'portrait');

        return $pdf->download('Struk_Pembayaran_' . $pembayaran->payment_ref . '.pdf');
    }

   
    private function parseResepPecahDetail($resepString, $totalHargaObat = 0)
    {
        $listObat = [];
        if (empty(trim($resepString))) return $listObat;

        $rows = preg_split('/[\n,]+/', $resepString);
        $barisValid = [];

        foreach ($rows as $row) {
            $row = trim($row);
            if (!empty($row)) {
                $barisValid[] = $row;
            }
        }

        $jumlahBaris = count($barisValid);
        if ($jumlahBaris === 0) return $listObat;

        foreach ($barisValid as $row) {
            $namaObat = $row;
            $qty = 1;
            $hargaSatuan = 0;

            if (str_contains($row, 'x') && str_contains($row, '@')) {
                $partHarga = explode('@', $row);
                $hargaSatuan = isset($partHarga[1]) ? (int)preg_replace('/[^0-9]/', '', $partHarga[1]) : 0;

                $partNamaQty = explode('x', $partHarga[0]);
                $namaObat = isset($partNamaQty[0]) ? trim($partNamaQty[0]) : trim($partHarga[0]);
                $qty = isset($partNamaQty[1]) ? (int)preg_replace('/[^0-9]/', '', $partNamaQty[1]) : 1;
            }
            elseif (preg_match('/^(.*?)\s*\(((\d+)\s*[pP][cC][sS]|\d+)\)/', $row, $matches)) {
                $namaObat = trim($matches[1]);
                $qty = (int)$matches[3];
                
                if ($totalHargaObat > 0 && $qty > 0) {
                    $hargaSatuan = (int)($totalHargaObat / $jumlahBaris / $qty);
                }
            }
            elseif (str_contains($row, '-')) {
                $partStrip = explode('-', $row);
                $namaObat = trim($partStrip[0]);
                
                if (preg_match('/\((\d+)\s*\w+\)/', $partStrip[0], $qtyMatches)) {
                    $qty = (int)$qtyMatches[1];
                }

                if ($totalHargaObat > 0 && $qty > 0) {
                    $hargaSatuan = (int)($totalHargaObat / $jumlahBaris / $qty);
                }
            }
            // STRATEGI CADANGAN: Pemisahan teks dasar menggunakan huruf 'x'
            elseif (str_contains($row, 'x') || str_contains($row, 'X')) {
                $delimiter = str_contains($row, 'x') ? 'x' : 'X';
                $partNamaQty = explode($delimiter, $row);
                $namaObat = trim($partNamaQty[0]);
                $qty = isset($partNamaQty[1]) ? (int)preg_replace('/[^0-9]/', '', $partNamaQty[1]) : 1;

                if ($totalHargaObat > 0 && $qty > 0) {
                    $hargaSatuan = (int)($totalHargaObat / $jumlahBaris / $qty);
                }
            }
            else {
                if ($totalHargaObat > 0) {
                    $hargaSatuan = (int)($totalHargaObat / $jumlahBaris);
                }
            }

            if (str_contains($namaObat, '(')) {
                $partClean = explode('(', $namaObat);
                $namaObat = trim($partClean[0]);
            }

            $listObat[] = [
                'nama'  => rtrim($namaObat, ' -:'),
                'qty'   => $qty,
                'harga' => $hargaSatuan,
                'total' => $qty * $hargaSatuan
            ];
        }

        return $listObat;
    }
}
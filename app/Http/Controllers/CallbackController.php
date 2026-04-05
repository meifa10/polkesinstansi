<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CallbackController extends Controller
{
    public function handle(Request $request)
    {
        /**
         * =========================================
         * 1. AMBIL RAW BODY (PALING AMAN)
         * =========================================
         */
        $raw = $request->getContent();
        $data = json_decode($raw, true);

        Log::info('🔥 MIDTRANS CALLBACK MASUK', [
            'raw' => $raw
        ]);

        /**
         * =========================================
         * 2. FALLBACK (kalau JSON gagal)
         * =========================================
         */
        if (!$data) {
            $data = $request->all();
        }

        /**
         * =========================================
         * 3. AMBIL DATA PENTING
         * =========================================
         */
        $orderId           = $data['order_id'] ?? null;
        $transactionStatus = strtolower($data['transaction_status'] ?? '');
        $paymentType       = $data['payment_type'] ?? '-';
        $fraudStatus       = $data['fraud_status'] ?? null;

        Log::info('📦 DATA CALLBACK', [
            'order_id' => $orderId,
            'status'   => $transactionStatus,
            'payment'  => $paymentType,
        ]);

        /**
         * =========================================
         * 4. VALIDASI ORDER ID
         * =========================================
         */
        if (!$orderId) {
            Log::error('❌ ORDER ID KOSONG');
            return response()->json(['message' => 'no order id'], 200);
        }

        /**
         * =========================================
         * 5. CARI DATA PEMBAYARAN
         * =========================================
         */
        $pembayaran = Pembayaran::where('payment_ref', $orderId)->first();

        if (!$pembayaran) {
            Log::warning("❌ TRANSAKSI TIDAK DITEMUKAN: $orderId");
            return response()->json(['message' => 'not found'], 200);
        }

        /**
         * =========================================
         * 6. UPDATE STATUS (SUPER AMAN)
         * =========================================
         */
        DB::beginTransaction();

        try {

            /**
             * 🔥 LOGIKA FINAL (ANTI GAGAL)
             */
            if (in_array($transactionStatus, ['capture', 'settlement'])) {

                // khusus kartu kredit
                if ($transactionStatus === 'capture' && $fraudStatus === 'challenge') {
                    $pembayaran->status = 'pending';
                } else {
                    $pembayaran->status = 'lunas';
                    $pembayaran->tanggal_bayar = now();
                }

            } elseif ($transactionStatus === 'pending') {

                $pembayaran->status = 'belum_lunas';

            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {

                $pembayaran->status = 'gagal';

            } else {

                Log::warning("⚠ STATUS TIDAK DIKENAL: $transactionStatus");
            }

            /**
             * Simpan metode pembayaran
             */
            $pembayaran->paid_by = $paymentType;

            /**
             * Save ke DB
             */
            $pembayaran->save();

            DB::commit();

            Log::info("✅ STATUS BERHASIL DIUPDATE", [
                'order_id' => $orderId,
                'status_db' => $pembayaran->status
            ]);

            /**
             * WAJIB 200 (BIAR MIDTRANS STOP RETRY)
             */
            return response()->json(['message' => 'OK'], 200);

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error("❌ ERROR UPDATE DB", [
                'error' => $e->getMessage()
            ]);

            return response()->json(['message' => 'error'], 200);
        }
    }
}
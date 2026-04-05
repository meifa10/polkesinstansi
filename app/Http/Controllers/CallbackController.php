<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CallbackController extends Controller
{
    /**
     * =========================================
     * MIDTRANS CALLBACK / WEBHOOK (FINAL FIX)
     * =========================================
     */
    public function handle(Request $request)
    {
        /**
         * =========================================
         * 1. AMBIL RAW JSON (WAJIB)
         * =========================================
         */
        $raw = $request->getContent();
        $data = json_decode($raw, true);

        Log::info('🔥 MIDTRANS CALLBACK MASUK (RAW)', [
            'raw' => $raw,
        ]);

        /**
         * =========================================
         * 2. VALIDASI DATA
         * =========================================
         */
        if (!$data) {
            Log::error('❌ JSON TIDAK VALID');
            return response()->json(['message' => 'invalid json'], 200);
        }

        /**
         * =========================================
         * 3. AMBIL DATA PENTING
         * =========================================
         */
        $orderId           = $data['order_id'] ?? null;
        $transactionStatus = $data['transaction_status'] ?? null;
        $paymentType       = $data['payment_type'] ?? null;
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
         * 5. CARI PEMBAYARAN
         * =========================================
         */
        $pembayaran = Pembayaran::where('payment_ref', $orderId)->first();

        if (!$pembayaran) {
            Log::warning("❌ TRANSAKSI TIDAK DITEMUKAN: $orderId");
            return response()->json(['message' => 'not found'], 200);
        }

        /**
         * =========================================
         * 6. UPDATE STATUS (TRANSACTION SAFE)
         * =========================================
         */
        DB::beginTransaction();

        try {

            switch ($transactionStatus) {

                case 'capture':
                    if ($fraudStatus == 'challenge') {
                        $pembayaran->status = 'pending';
                    } elseif ($fraudStatus == 'accept') {
                        $pembayaran->status = 'lunas';
                        $pembayaran->tanggal_bayar = now();
                    }
                    break;

                case 'settlement':
                    $pembayaran->status = 'lunas';
                    $pembayaran->tanggal_bayar = now();
                    break;

                case 'pending':
                    $pembayaran->status = 'belum_lunas';
                    break;

                case 'deny':
                case 'expire':
                case 'cancel':
                    $pembayaran->status = 'gagal';
                    break;

                default:
                    Log::warning("⚠ STATUS TIDAK DIKENAL: $transactionStatus");
                    break;
            }

            /**
             * Simpan metode pembayaran
             */
            $pembayaran->paid_by = $paymentType;

            /**
             * Save
             */
            $pembayaran->save();

            DB::commit();

            Log::info("✅ UPDATE BERHASIL: $orderId -> " . $pembayaran->status);

            return response()->json(['message' => 'OK'], 200);

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error("❌ ERROR DB: " . $e->getMessage());

            return response()->json(['message' => 'error'], 200);
        }
    }
}
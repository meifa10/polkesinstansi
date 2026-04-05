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
     * MIDTRANS CALLBACK / WEBHOOK (FINAL STABLE)
     * =========================================
     */
    public function handle(Request $request)
    {
        /**
         * =========================================
         * 1. LOG RAW DATA (WAJIB UNTUK DEBUG)
         * =========================================
         */
        Log::info('🔥 MIDTRANS CALLBACK MASUK', $request->all());

        /**
         * =========================================
         * 2. AMBIL DATA DARI REQUEST
         * =========================================
         */
        $data = $request->all();

        $orderId           = $data['order_id'] ?? null;
        $transactionStatus = $data['transaction_status'] ?? null;
        $paymentType       = $data['payment_type'] ?? null;
        $fraudStatus       = $data['fraud_status'] ?? null;
        $grossAmount       = $data['gross_amount'] ?? null;
        $signatureKey      = $data['signature_key'] ?? null;
        $statusCode        = $data['status_code'] ?? null;

        /**
         * =========================================
         * 3. VALIDASI ORDER ID
         * =========================================
         */
        if (!$orderId) {
            Log::error('❌ ORDER ID KOSONG');
            return response()->json(['message' => 'no order id'], 200);
        }

        /**
         * =========================================
         * 4. (OPSIONAL) VALIDASI SIGNATURE
         * =========================================
         * NOTE:
         * Aktifkan ini nanti kalau sudah stabil
         */
        /*
        $serverKey = config('services.midtrans.server_key');

        $expectedSignature = hash(
            'sha512',
            $orderId . $statusCode . $grossAmount . $serverKey
        );

        if ($signatureKey !== $expectedSignature) {
            Log::warning("❌ SIGNATURE TIDAK VALID: $orderId");
            return response()->json(['message' => 'invalid signature'], 200);
        }
        */

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
         * 6. UPDATE STATUS TRANSAKSI
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
             * Simpan ke database
             */
            $pembayaran->save();

            DB::commit();

            Log::info("✅ PEMBAYARAN BERHASIL UPDATE: $orderId -> " . $pembayaran->status);

            /**
             * WAJIB RETURN 200 (BIAR MIDTRANS TIDAK ERROR)
             */
            return response()->json([
                'message' => 'OK'
            ], 200);

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error("❌ ERROR UPDATE DB: " . $e->getMessage());

            /**
             * TETAP RETURN 200
             */
            return response()->json([
                'message' => 'error'
            ], 200);
        }
    }
}
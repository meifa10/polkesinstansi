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
        // 1. Ambil data (Midtrans mengirimkan JSON secara default)
        $data = $request->all();

        /**
         * =========================================
         * 2. VERIFIKASI SIGNATURE (WAJIB!)
         * =========================================
         * Ini untuk memastikan bahwa yang mengirim data benar-benar Midtrans.
         */
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $orderId   = $data['order_id'] ?? null;
        $statusCode = $data['status_code'] ?? null;
        $grossAmount = $data['gross_amount'] ?? null;
        $signatureKey = $data['signature_key'] ?? null;

        $localSignature = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);

        if ($localSignature !== $signatureKey) {
            Log::error('❌ SIGNATURE TIDAK VALID - Percobaan akses ilegal!', ['order_id' => $orderId]);
            return response()->json(['message' => 'Invalid Signature'], 403);
        }

        Log::info('🔥 MIDTRANS CALLBACK VALID', ['order_id' => $orderId, 'status' => $data['transaction_status']]);

        /**
         * =========================================
         * 3. CARI DATA PEMBAYARAN
         * =========================================
         */
        $pembayaran = Pembayaran::where('payment_ref', $orderId)->first();

        if (!$pembayaran) {
            Log::warning("❌ TRANSAKSI TIDAK DITEMUKAN: $orderId");
            return response()->json(['message' => 'Transaction not found'], 200); // Tetap 200 agar Midtrans tidak retry
        }

        /**
         * =========================================
         * 4. UPDATE STATUS DENGAN TRANSACTION
         * =========================================
         */
        DB::beginTransaction();
        try {
            $transactionStatus = strtolower($data['transaction_status']);
            $fraudStatus       = strtolower($data['fraud_status'] ?? '');
            $paymentType       = $data['payment_type'] ?? '-';

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $pembayaran->status = 'pending';
                } else {
                    $pembayaran->status = 'lunas';
                    $pembayaran->tanggal_bayar = now();
                }
            } elseif ($transactionStatus == 'settlement') {
                $pembayaran->status = 'lunas';
                $pembayaran->tanggal_bayar = now();
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $pembayaran->status = 'gagal';
            } elseif ($transactionStatus == 'pending') {
                $pembayaran->status = 'belum_lunas';
            }

            // Simpan informasi tambahan
            $pembayaran->paid_by = $paymentType;
            $pembayaran->save();

            DB::commit();
            Log::info("✅ DATABASE UPDATED: $orderId menjadi $pembayaran->status");

            return response()->json(['message' => 'OK'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("❌ ERROR UPDATE CALLBACK: " . $e->getMessage());
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
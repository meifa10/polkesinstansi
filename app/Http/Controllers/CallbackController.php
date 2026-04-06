<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CallbackController extends Controller
{
    public function handle(Request $request)
    {
        // ===============================
        // 1. AMBIL DATA DARI MIDTRANS
        // ===============================
        $data = $request->all();

        Log::info('📥 CALLBACK MASUK', $data);

        // ===============================
        // 2. VALIDASI SIGNATURE
        // ===============================
        $serverKey    = env('MIDTRANS_SERVER_KEY');
        $orderId      = $data['order_id'] ?? null;
        $statusCode   = $data['status_code'] ?? null;
        $grossAmount  = $data['gross_amount'] ?? null;
        $signatureKey = $data['signature_key'] ?? null;

        $localSignature = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);

        if ($localSignature !== $signatureKey) {
            Log::error('❌ SIGNATURE TIDAK VALID', [
                'order_id' => $orderId
            ]);
            return response()->json(['message' => 'Invalid Signature'], 403);
        }

        Log::info('✅ SIGNATURE VALID', [
            'order_id' => $orderId,
            'status'   => $data['transaction_status'] ?? null
        ]);

        // ===============================
        // 3. CARI DATA PEMBAYARAN
        // ===============================
        $pembayaran = Pembayaran::where('payment_ref', $orderId)->first();

        if (!$pembayaran) {
            Log::warning("❌ DATA TIDAK DITEMUKAN: $orderId");
            return response()->json(['message' => 'Transaction not found'], 200);
        }

        // ===============================
        // 4. UPDATE STATUS
        // ===============================
        DB::beginTransaction();

        try {
            $transactionStatus = strtolower($data['transaction_status'] ?? '');
            $fraudStatus       = strtolower($data['fraud_status'] ?? '');
            $paymentType       = $data['payment_type'] ?? '-';

            // 🔥 LOG STATUS MASUK
            Log::info('📊 STATUS TRANSAKSI', [
                'transaction_status' => $transactionStatus,
                'fraud_status'       => $fraudStatus
            ]);

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $pembayaran->status = 'pending';
                } else {
                    $pembayaran->status = 'lunas';
                    $pembayaran->tanggal_bayar = now();
                }
            } 
            elseif ($transactionStatus == 'settlement') {
                $pembayaran->status = 'lunas';
                $pembayaran->tanggal_bayar = now();
            } 
            elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $pembayaran->status = 'gagal';
            } 
            elseif ($transactionStatus == 'pending') {
                $pembayaran->status = 'belum_lunas';
            }

            // Simpan metode pembayaran
            $pembayaran->paid_by = $paymentType;

            // SAVE KE DB INSTANSI
            $pembayaran->save();

            Log::info("💾 DB INSTANSI UPDATED", [
                'order_id' => $orderId,
                'status'   => $pembayaran->status
            ]);

            // ===============================
            // 5. KIRIM KE POLKES JOMBANG
            // ===============================
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'X-API-KEY' => 'POLKES_SECRET'
                ])
                ->post('https://polkesjombang.satcloud.tech/api/update-status', [
                    'order_id' => $orderId,
                    'status'   => $pembayaran->status
                ]);

            // 🔥 LOG RESPONSE API
            Log::info('📡 RESPONSE JOMBANG', [
                'status_code' => $response->status(),
                'body'        => $response->body()
            ]);

            // ===============================
            // 6. COMMIT
            // ===============================
            DB::commit();

            return response()->json(['message' => 'OK'], 200);

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error("❌ ERROR CALLBACK", [
                'message' => $e->getMessage(),
                'order_id' => $orderId
            ]);

            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
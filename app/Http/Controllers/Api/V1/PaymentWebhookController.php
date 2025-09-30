<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    /**
     * Menangani notifikasi webhook dari Xendit.
     */
    public function handle(Request $request)
    {
        // 1. Verifikasi Webhook Token untuk keamanan.
        $webhookToken = $request->header('x-callback-token');
        if ($webhookToken !== config('services.xendit.webhook_token')) {
            Log::warning('Invalid Xendit webhook token received.');
            return response()->json(['message' => 'Invalid token'], 401);
        }

        $payload = $request->all();

        Log::info('Xendit webhook received:', $payload);


        if (isset($payload['status']) && $payload['status'] === 'PAID') {
            $orderNumber = $payload['external_id'];
            $order = Order::where('order_number', $orderNumber)->first();


            if ($order && $order->status === 'pending') {


                $order->update(['status' => 'paid']);


                Payment::create([
                    'order_id' => $order->id,
                    'amount' => $payload['amount'],
                    'payment_method' => $payload['payment_channel'] ?? 'N/A',
                    'status' => 'success',
                    'response_payload' => $payload,
                ]);

                Log::info("Order {$orderNumber} successfully marked as paid.");
            }
        }


        return response()->json(['status' => 'ok']);
    }
}

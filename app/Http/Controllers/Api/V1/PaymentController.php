<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Api\InvoiceApi as InvoiceApiClient;

class PaymentController extends Controller
{
    use AuthorizesRequests;

    /**
     * Membuat link pembayaran untuk pesanan yang sudah ada.
     */
    public function create(Order $order)
    {
        $this->authorize('update', $order);

        if ($order->status !== 'pending') {
            return response()->json([
                'message' => 'This order cannot be paid.',
                'payment_url' => $order->payment_url,
            ], 422);
        }

        try {
            Configuration::getDefaultConfiguration()->setApiKey(config('services.xendit.api_key'));
            $apiInstance = new InvoiceApi();
            $apiInstance->getConfig()->setApiKey(config('services.xendit.api_key'));

            $params = [
                'external_id' => $order->order_number,
                'amount' => (int) $order->total_amount,
                'payer_email' => $order->user->email,
                'description' => 'Payment for Order #' . $order->order_number,
                'success_redirect_url' => config('app.frontend_url', 'http://localhost:3000/payment-success'),
                'failure_redirect_url' => config('app.frontend_url', 'http://localhost:3000/payment-failed'),
            ];

            $result = $apiInstance->createInvoice($params);

            $order->update([
                'payment_gateway_ref' => $result['id'],
                'payment_url' => $result['invoice_url'],
            ]);

            return response()->json([
                'message' => 'Payment link created successfully.',
                'payment_url' => $result['invoice_url'],
            ]);
        } catch (\Xendit\XenditSdkException $e) {
            return response()->json(['message' => 'Failed to create payment link: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred: ' . $e->getMessage()], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CheckoutRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Memproses checkout, membuat order, dan mengurangi stok dalam satu transaksi.
     */
    public function store(CheckoutRequest $request)
    {
        try {
            $validated = $request->validated();
            $totalAmount = 0;
            $orderItemsData = [];

            $order = DB::transaction(function () use ($validated, &$totalAmount, &$orderItemsData) {
                // mengunci produk yang akan diupdate untuk mencegah race condition
                $productIds = collect($validated['items'])->pluck('product_id')->sort();
                $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

                // loop melalui items, validasi stock barang, dan hitung total
                foreach ($validated['items'] as $item) {
                    $product = $products->get($item['product_id']);
                    if (!$product || $product->stock < $item['quantity']) {
                        throw new \Exception("Product stock is not sufficient for product ID:" . $item['product_id']);
                    }
                    $price = $product->price;
                    $totalAmount += $price * $item['quantity'];
                    $orderItemsData[] = [
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $price,
                    ];
                    // kurangi stok barang
                    $product->decrement('stock', $item['quantity']);
                }
                //buat pesanan (Order)
                $order = Order::create([
                    'user_id' => auth()->guard()->user()->id,
                    'order_number' => 'INV' . date('YmdHis') . '/' . Str::upper(Str::random(3)),
                    'shipping_address' => $validated['shipping_address'],
                    'total_amount' => $totalAmount,
                    'status' => 'pending',
                ]);
                //buat item pesanan (order items)
                $order->items()->createMany($orderItemsData);
                return $order;
            });
            $createOrder = new OrderResource($order);
            return response()->json([
                'status' => 201,
                'message' => 'Checkout successful, Please proceed payment',
                'data' => $createOrder
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 422,
                'message' => 'Checkout failed',
                'error' => $e->getMessage()
            ], 422);
        }
    }
}

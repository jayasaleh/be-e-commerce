<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use AuthorizesRequests;


    public function index(Request $request)
    {
        $orders = $request->user()->orders()->latest()->paginate(10);
        return OrderResource::collection($orders);
    }
    public function show(Order $order)
    {
        $this->authorize('view', $order);
        return new OrderResource($order->load(['items.product', 'payments']));
    }
}

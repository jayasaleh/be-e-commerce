<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'total_amount' => (float) $this->total_amount,
            'status' => $this->status,
            'shipping_address' => $this->shipping_address,
            'payment_url' => $this->payment_url,
            'created_at' => $this->created_at->toIso8601String(),
            'user' => new UserResource($this->whenLoaded('user')),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
        ];
    }
}

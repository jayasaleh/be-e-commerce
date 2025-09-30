<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'quantity' => $this->quantity,
            'price_at_purchase' => (float) $this->price,
            'sub_total' => (float) $this->sub_total,
            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}

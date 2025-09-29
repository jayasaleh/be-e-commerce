<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class Payment extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'order_id',
        'amount',
        'payment_method',
        'status',
        'response_payload'
    ];
    protected $casts = [
        'amount' => 'decimal:2',
        'response_payload' => 'array'
    ];
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}

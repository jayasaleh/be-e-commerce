<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ApiKey extends Model
{
    //
    use HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'key',
        'is_active',
        'last_used_at'
    ];
    protected $hidden = [
        'key'
    ];
    protected $casts = [
        'is_active' => 'boolean',
        'last_used_at' => 'datetime'
    ];
}

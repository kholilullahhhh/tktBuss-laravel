<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'order_id',
        'transaction_id',
        'payment_type',
        'gross_amount',
        'transaction_status',
        'payment_status',
        'paid_at',
        'raw_response',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'raw_response' => 'array',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}

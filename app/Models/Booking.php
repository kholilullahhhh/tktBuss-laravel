<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'user_id',
        'schedule_id',
        'kode_booking',
        'tanggal_booking',
        'total_harga',
        'status_booking',
        'status_pembayaran',
        'payment_method',
        'paid_at',
        'expired_at',
    ];

    protected $casts = [
        'tanggal_booking' => 'datetime',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
        'total_harga' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function seats()
    {
        return $this->hasMany(BookingSeat::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function isActive(): bool
    {
        return in_array($this->status_booking, ['pending', 'confirmed', 'completed']);
    }

    public function isPaid(): bool
    {
        return $this->status_pembayaran === 'paid';
    }
}

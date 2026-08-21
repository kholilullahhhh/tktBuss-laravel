<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingSeat extends Model
{
    protected $fillable = [
        'booking_id',
        'schedule_id',
        'seat_id',
        'harga',
        'nama_penumpang',
        'nik',
        'no_hp',
        'jenis_kelamin',
        'tanggal_lahir',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'harga' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}

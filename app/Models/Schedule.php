<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'bus_id',
        'route_id',
        'tanggal_keberangkatan',
        'jam_keberangkatan',
        'jam_tiba',
        'harga',
        'status',
    ];

    protected $casts = [
        'tanggal_keberangkatan' => 'date',
        'harga' => 'decimal:2',
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Jumlah kursi yang sudah terpesan pada jadwal ini (booking aktif).
     */
    public function bookedSeatsCount(): int
    {
        return BookingSeat::where('schedule_id', $this->id)
            ->whereHas('booking', function ($q) {
                $q->whereIn('status_booking', ['pending', 'confirmed', 'completed']);
            })
            ->count();
    }

    public function availableSeatsCount(): int
    {
        return max(0, ($this->bus?->kapasitas ?? 0) - $this->bookedSeatsCount());
    }

    public function getDurasiMenitAttribute(): int
    {
        try {
            return now()->parse($this->jam_keberangkatan)->diffInMinutes(now()->parse($this->jam_tiba));
        } catch (\Throwable) {
            return 0;
        }
    }
}

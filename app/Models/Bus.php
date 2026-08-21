<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'operator_id',
        'nomor_polisi',
        'kode_bus',
        'nama_bus',
        'kelas',
        'kapasitas',
        'fasilitas',
        'status',
    ];

    protected $casts = [
        'kapasitas' => 'integer',
        'status' => 'boolean',
    ];

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}

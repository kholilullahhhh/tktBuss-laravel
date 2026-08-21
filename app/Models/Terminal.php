<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terminal extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'nama_terminal',
        'kode_terminal',
        'alamat',
        'kota',
        'provinsi',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function routesAsal()
    {
        return $this->hasMany(Route::class, 'terminal_asal_id');
    }

    public function routesTujuan()
    {
        return $this->hasMany(Route::class, 'terminal_tujuan_id');
    }
}

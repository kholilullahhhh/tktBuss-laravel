<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'terminal_asal_id',
        'terminal_tujuan_id',
        'jarak',
        'estimasi_durasi',
        'status',
    ];

    protected $casts = [
        'jarak' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function terminalAsal()
    {
        return $this->belongsTo(Terminal::class, 'terminal_asal_id');
    }

    public function terminalTujuan()
    {
        return $this->belongsTo(Terminal::class, 'terminal_tujuan_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}

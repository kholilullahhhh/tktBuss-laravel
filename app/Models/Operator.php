<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'nama_operator',
        'kode_operator',
        'alamat',
        'telepon',
        'email',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function buses()
    {
        return $this->hasMany(Bus::class);
    }
}

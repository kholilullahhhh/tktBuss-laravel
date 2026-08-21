<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained('buses')->cascadeOnDelete();
            $table->foreignId('route_id')->constrained('routes')->cascadeOnDelete();
            $table->date('tanggal_keberangkatan');
            $table->time('jam_keberangkatan');
            $table->time('jam_tiba');
            $table->decimal('harga', 12, 2)->default(0);
            $table->enum('status', ['aktif', 'penuh', 'selesai', 'dibatalkan'])->default('aktif');
            $table->timestamps();

            $table->index(['tanggal_keberangkatan', 'route_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};

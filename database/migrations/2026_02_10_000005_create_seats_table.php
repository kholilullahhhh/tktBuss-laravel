<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained('buses')->cascadeOnDelete();
            $table->string('nomor_kursi', 10);
            $table->string('posisi', 50)->default('kiri');
            $table->enum('status', ['aktif', 'rusak'])->default('aktif');
            $table->unique(['bus_id', 'nomor_kursi']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
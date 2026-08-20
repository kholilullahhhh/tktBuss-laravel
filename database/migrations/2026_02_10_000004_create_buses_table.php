<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')->constrained('operators')->cascadeOnDelete();
            $table->string('nomor_polisi')->unique();
            $table->string('kode_bus')->unique();
            $table->string('nama_bus');
            $table->enum('kelas', ['Ekonomi', 'Bisnis', 'Executive', 'Sleeper'])->default('Ekonomi');
            $table->integer('kapasitas')->default(40);
            $table->text('fasilitas')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buses');
    }
};
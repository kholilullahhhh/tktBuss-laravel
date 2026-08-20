<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('terminals', function (Blueprint $table) {
            $table->id();
            $table->string('nama_terminal');
            $table->string('kode_terminal')->unique();
            $table->text('alamat')->nullable();
            $table->string('kota', 100)->nullable();
            $table->string('provinsi', 100)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('terminals');
    }
};
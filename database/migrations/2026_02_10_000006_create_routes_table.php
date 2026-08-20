<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('terminal_asal_id')->constrained('terminals')->cascadeOnDelete();
            $table->foreignId('terminal_tujuan_id')->constrained('terminals')->cascadeOnDelete();
            $table->decimal('jarak', 10, 2)->default(0);
            $table->integer('estimasi_durasi')->nullable()->comment('menit');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
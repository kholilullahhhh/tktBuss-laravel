<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('schedule_id')->constrained('schedules')->cascadeOnDelete();
            $table->string('kode_booking')->unique();
            $table->dateTime('tanggal_booking');
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->enum('status_booking', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->enum('status_pembayaran', ['unpaid', 'pending', 'paid', 'failed', 'expired'])->default('unpaid');
            $table->string('payment_method', 50)->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status_booking']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

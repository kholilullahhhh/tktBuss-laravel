<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->string('order_id', 100)->nullable();
            $table->string('transaction_id', 100)->nullable();
            $table->string('payment_type', 50)->nullable();
            $table->decimal('gross_amount', 12, 2)->default(0);
            $table->string('transaction_status', 50)->nullable();
            $table->string('payment_status', 50)->default('unpaid');
            $table->dateTime('paid_at')->nullable();
            $table->json('raw_response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('products');
    }

    public function down(): void
    {
        Schema::create('products', function ($table) {
            $table->id();
            $table->string('name');
            $table->integer('price')->default(0);
            $table->integer('quantity')->default(0);
            $table->text('description')->nullable();
            $table->string('cover')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
};

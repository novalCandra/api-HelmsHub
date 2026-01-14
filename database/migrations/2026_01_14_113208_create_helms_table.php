<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('helms', function (Blueprint $table) {
            $table->id();
            $table->string('helmet_name');
            $table->enum('condition', ['good', 'very_good', 'excellent'])->default('good');
            $table->enum('status', ['available', 'rented', 'maintenance'])->default('available');
            $table->decimal('daily_price', 15, 2);
            $table->decimal('late_fee_per_day', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('helms');
    }
};

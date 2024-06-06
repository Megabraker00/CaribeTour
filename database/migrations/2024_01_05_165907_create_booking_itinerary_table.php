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
        Schema::create('booking_itinerary', function (Blueprint $table) {
            $table->foreignId('booking_id')->constrained();
            $table->foreignId('itinerary_id')->constrained();
            $table->tinyInteger('itinerary_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_itinerary');
    }
};

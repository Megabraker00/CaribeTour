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
        Schema::create('itineraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->dateTime('departure_date');
            $table->foreignId('departure_terminal_id')->constrained('terminals');
            $table->dateTime('arrival_date')->nullable();
            $table->foreignId('arrival_terminal_id')->nullable()->constrained('terminals');
            $table->decimal('price');
            $table->decimal('taxes');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itineraries');
    }
};

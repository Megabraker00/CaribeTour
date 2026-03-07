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
        Schema::create('itinerary_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('itinerary_id')->constrained('itineraries');
            $table->foreignId('passenger_type_id')->constrained('types');
            $table->decimal('price', 10, 2)->unsigned()->comment('Precio del itinerario según el tipo de pasajero (por persona)');
            $table->decimal('taxes', 10, 2)->unsigned();
            $table->timestamps();
            $table->comment('Tabla que indica el precio de un itinerario según el tipo de pasajero (infante, niño, adulto, senior)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itinerary_prices');
    }
};

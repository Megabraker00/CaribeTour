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
        Schema::create('segments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('itinerary_id')->constrained('itineraries');
            $table->foreignId('type_id')->constrained('types');
            $table->integer('sort_order')->default(1);
            $table->dateTime('departure_date');
            $table->foreignId('departure_terminal_id')->constrained('terminals');
            $table->string('origin')->nullable()->comment('Ciudad o lugar de origen (codigo IATA, nombre de ciudad, etc.)');
            $table->dateTime('arrival_date')->nullable();
            $table->foreignId('arrival_terminal_id')->nullable()->constrained('terminals');
            $table->string('destination')->nullable()->comment('Ciudad o lugar de destino (codigo IATA, nombre de ciudad, etc.)');
            $table->timestamps();
            $table->comment('Cada segmento representa una parte del itinerario, como un vuelo, un traslado, una actividad, etc. El campo type_id indica el tipo de segmento (vuelo, traslado, actividad, etc.) y sort_order define el orden de los segmentos dentro del itinerario.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('segments');
    }
};

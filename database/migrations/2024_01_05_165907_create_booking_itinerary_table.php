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
            $table->primary(['booking_id','itinerary_id']);
            //$table->decimal('price_at_booking', 10, 2)->unsigned()->comment('Precio del itinerario en el momento de la reserva (por persona)'); // ya se especifica en la tabla pasajero passengers
            //$table->decimal('taxes_at_booking', 10, 2)->unsigned(); // ya se especifica en la tabla pasajero passengers
            //$table->string('currency', 3)->default('EUR');
            //$table->integer('pax')->unsigned()->default(1)->comment('Número de pasajeros para este itinerario');
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

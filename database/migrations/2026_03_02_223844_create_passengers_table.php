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
        Schema::create('passengers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('last_name', 100);
            $table->date('date_of_birth')->nullable();
            $table->string('dni_passport', 20);
            $table->string('nationality', 20)->nullable();
            $table->string('gender', 10)->nullable();
            $table->foreignId('passenger_type_id')->constrained('types')->comment('Es el tipo de pasajero (infante, niño, adulto, senior), se calcula en base a date_of_birth');
            $table->foreignId('status_id')->constrained('statuses');
            $table->foreignId('booking_id')->constrained();
            $table->decimal('price_at_booking', 10, 2)->unsigned()->comment('Precio del itinerario en el momento de la reserva (por persona)');
            $table->decimal('taxes_at_booking', 10, 2)->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passengers');
    }
};

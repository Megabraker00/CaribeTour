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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('external_ref', 20)->nullable()->comment('Referencia o localizador externo');
            $table->foreignId('client_id')->constrained();
            $table->foreignId('status_id')->constrained('statuses');
            $table->decimal('total_price', 10, 2)->unsigned()->comment('Precio total de la reserva incluyendo impuestos y tasas');
            $table->string('currency', 3)->default('EUR');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

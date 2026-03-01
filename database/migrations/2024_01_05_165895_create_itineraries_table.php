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
            $table->foreignId('product_id')->constrained('products');
            $table->integer('total_stock')->default(20)->comment('Stock total para esta fecha');
            $table->integer('available_stock')->default(20)->comment('Stock disponible para esta fecha');
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('taxes', 10, 2)->default(0);
            $table->string('currency', 3)->default('EUR');
            $table->timestamps();
            $table->comment('Tabla que agrupa las fechas de salida para cada producto/tour. Cada fila representa una fecha de salida con su precio y stock asociado.');
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

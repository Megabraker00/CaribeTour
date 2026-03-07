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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique()->comment('Numero de referencia');
            $table->foreignId('booking_id')->constrained();
            $table->foreignId('created_user_id')->nullable()->constrained('users')->comment('Usuario que crea la factura (null si es el sistema)');
            $table->decimal('total_amount', 10, 2);
            $table->string('currency', 3)->default('EUR');
            $table->string('billing_name')->nullable();
            $table->string('billing_vat')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_country', 2)->nullable();
            $table->date('issue_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};

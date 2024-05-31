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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('last_name', 100);
            $table->string('email_personal', 100);
            $table->string('email_company', 100);
            $table->string('dni_passport', 20);
            $table->string('phone', 20)->nullable();
            $table->foreignId('position_id')->constrained();
            $table->foreignId('status_id')->constrained('statuses');
            $table->foreignId('created_user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};

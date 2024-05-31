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
        Schema::create('terminals', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('address')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();
            $table->foreign('parent_id')->references('id')->on('terminals');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terminals');
    }
};

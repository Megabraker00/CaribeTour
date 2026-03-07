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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug');
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('type_id')->constrained();
            $table->foreignId('status_id')->constrained('statuses');
            $table->foreignId('supplier_id')->constrained();
            $table->foreignId('created_user_id')->constrained('users');
            $table->timestamps();

            $table->unique(['slug', 'category_id', 'type_id'], 'products_category_type_slug_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

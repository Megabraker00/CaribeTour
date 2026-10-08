<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('segments', function (Blueprint $table) {
            $table->unique(['itinerary_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::table('segments', function (Blueprint $table) {
            $table->dropUnique(['itinerary_id', 'sort_order']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->string('subject', 150)->nullable()->after('email');
            $table->foreignId('product_id')->nullable()->after('subject')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropConstrainedForeignId('product_id');
            $table->dropColumn('subject');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('author_linkedin')->nullable()->after('created_user_id');
            $table->string('author_facebook')->nullable()->after('author_linkedin');
            $table->string('author_instagram')->nullable()->after('author_facebook');
            $table->string('author_x')->nullable()->after('author_instagram');
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn([
                'author_linkedin',
                'author_facebook',
                'author_instagram',
                'author_x',
            ]);
        });
    }
};

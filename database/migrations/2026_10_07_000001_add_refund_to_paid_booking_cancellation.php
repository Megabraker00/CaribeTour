<?php

use App\Models\Payment;
use App\Models\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('refund_id')->nullable()->after('transaction_id');
        });

        if (!DB::table('statuses')
            ->where('statusable', Payment::class)
            ->where('slug', Status::PAYMENT_REFUNDED)
            ->exists()
        ) {
            DB::table('statuses')->insert([
                'id' => 53,
                'name' => 'Reembolsado',
                'slug' => Status::PAYMENT_REFUNDED,
                'statusable' => Payment::class,
                'is_system' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('refund_id');
        });

        DB::table('statuses')
            ->where('statusable', Payment::class)
            ->where('slug', Status::PAYMENT_REFUNDED)
            ->delete();
    }
};

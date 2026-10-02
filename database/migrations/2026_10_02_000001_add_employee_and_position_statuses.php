<?php

use App\Models\Employee;
use App\Models\Position;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    public function up(): void
    {
        $rows = [
            ['id' => 70, 'name' => 'Activo', 'statusable' => Employee::class],
            ['id' => 71, 'name' => 'Inactivo', 'statusable' => Employee::class],
            ['id' => 80, 'name' => 'Activo', 'statusable' => Position::class],
        ];

        foreach ($rows as $row) {
            if (!DB::table('statuses')->where('id', $row['id'])->exists()) {
                DB::table('statuses')->insert(array_merge($row, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }

    public function down(): void
    {
        DB::table('statuses')->whereIn('id', [70, 71, 80])->delete();
    }
};

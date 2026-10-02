<?php

use App\Models\Employee;
use App\Models\Position;
use App\Models\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    public function up(): void
    {
        $rows = [
            ['id' => Status::EMPLOYEE_ACTIVE, 'name' => 'Activo', 'statusable' => Employee::class],
            ['id' => Status::EMPLOYEE_INACTIVE, 'name' => 'Inactivo', 'statusable' => Employee::class],
            ['id' => Status::POSITION_ACTIVE, 'name' => 'Activo', 'statusable' => Position::class],
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
        DB::table('statuses')->whereIn('id', [
            Status::EMPLOYEE_ACTIVE,
            Status::EMPLOYEE_INACTIVE,
            Status::POSITION_ACTIVE,
        ])->delete();
    }
};

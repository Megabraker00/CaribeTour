<?php

use App\Models\Blog;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Passenger;
use App\Models\Payment;
use App\Models\Position;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('statuses', function (Blueprint $table) {
            $table->string('slug', 50)->default('')->after('name');
            $table->boolean('is_system')->default(false)->after('statusable');
        });

        Schema::table('types', function (Blueprint $table) {
            $table->string('slug', 50)->default('')->after('name');
            $table->boolean('is_system')->default(false)->after('typeable');
        });

        $statusSlugs = [
            1 => ['slug' => 'active', 'statusable' => Product::class],
            2 => ['slug' => 'inactive', 'statusable' => Product::class],
            3 => ['slug' => 'draft', 'statusable' => Product::class],
            10 => ['slug' => 'pending_payment', 'statusable' => Booking::class],
            11 => ['slug' => 'paid', 'statusable' => Booking::class],
            12 => ['slug' => 'pending', 'statusable' => Booking::class],
            13 => ['slug' => 'confirmed', 'statusable' => Booking::class],
            14 => ['slug' => 'completed', 'statusable' => Booking::class],
            15 => ['slug' => 'cancelled', 'statusable' => Booking::class],
            16 => ['slug' => 'refunded', 'statusable' => Booking::class],
            17 => ['slug' => 'no_show', 'statusable' => Booking::class],
            20 => ['slug' => 'active', 'statusable' => Client::class],
            30 => ['slug' => 'active', 'statusable' => Category::class],
            31 => ['slug' => 'inactive', 'statusable' => Category::class],
            40 => ['slug' => 'active', 'statusable' => Supplier::class],
            41 => ['slug' => 'inactive', 'statusable' => Supplier::class],
            50 => ['slug' => 'pending', 'statusable' => Payment::class],
            51 => ['slug' => 'paid', 'statusable' => Payment::class],
            52 => ['slug' => 'cancelled', 'statusable' => Payment::class],
            60 => ['slug' => 'published', 'statusable' => Blog::class],
            61 => ['slug' => 'draft', 'statusable' => Blog::class],
            70 => ['slug' => 'active', 'statusable' => Employee::class],
            71 => ['slug' => 'inactive', 'statusable' => Employee::class],
            80 => ['slug' => 'active', 'statusable' => Position::class],
        ];

        foreach (DB::table('statuses')->get() as $row) {
            $known = $statusSlugs[$row->id] ?? null;
            $slug = $known['slug'] ?? Str::slug((string) $row->name);
            if ($slug === '') {
                $slug = 'status-'.$row->id;
            }

            $base = $slug;
            $suffix = 2;
            while (DB::table('statuses')
                ->where('statusable', $row->statusable)
                ->where('slug', $slug)
                ->where('id', '!=', $row->id)
                ->exists()
            ) {
                $slug = $base.'-'.$suffix;
                $suffix++;
            }

            DB::table('statuses')->where('id', $row->id)->update([
                'slug' => $slug,
                'is_system' => $known !== null,
            ]);
        }

        $typeSlugs = [
            1 => ['slug' => 'tour', 'typeable' => Product::class],
            2 => ['slug' => 'excursion', 'typeable' => Product::class],
            3 => ['slug' => 'hotel', 'typeable' => Product::class],
            4 => ['slug' => 'insurance', 'typeable' => Product::class],
            5 => ['slug' => 'cruise', 'typeable' => Product::class],
            6 => ['slug' => 'flight', 'typeable' => Product::class],
            7 => ['slug' => 'transfer', 'typeable' => Product::class],
            8 => ['slug' => 'freetour', 'typeable' => Product::class],
            10 => ['slug' => 'card', 'typeable' => Payment::class],
            11 => ['slug' => 'bank_transfer', 'typeable' => Payment::class],
            12 => ['slug' => 'stripe', 'typeable' => Payment::class],
            13 => ['slug' => 'paypal', 'typeable' => Payment::class],
            14 => ['slug' => 'cash', 'typeable' => Payment::class],
            20 => ['slug' => 'infant', 'typeable' => Passenger::class],
            21 => ['slug' => 'child', 'typeable' => Passenger::class],
            22 => ['slug' => 'adult', 'typeable' => Passenger::class],
            23 => ['slug' => 'senior', 'typeable' => Passenger::class],
        ];

        foreach (DB::table('types')->get() as $row) {
            $known = $typeSlugs[$row->id] ?? null;
            $slug = $known['slug'] ?? Str::slug((string) $row->name);
            if ($slug === '') {
                $slug = 'type-'.$row->id;
            }

            $base = $slug;
            $suffix = 2;
            while (DB::table('types')
                ->where('typeable', $row->typeable)
                ->where('slug', $slug)
                ->where('id', '!=', $row->id)
                ->exists()
            ) {
                $slug = $base.'-'.$suffix;
                $suffix++;
            }

            DB::table('types')->where('id', $row->id)->update([
                'slug' => $slug,
                'is_system' => $known !== null,
            ]);
        }

        Schema::table('statuses', function (Blueprint $table) {
            $table->unique(['statusable', 'slug']);
        });

        Schema::table('types', function (Blueprint $table) {
            $table->unique(['typeable', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::table('statuses', function (Blueprint $table) {
            $table->dropUnique(['statusable', 'slug']);
            $table->dropColumn(['slug', 'is_system']);
        });

        Schema::table('types', function (Blueprint $table) {
            $table->dropUnique(['typeable', 'slug']);
            $table->dropColumn(['slug', 'is_system']);
        });
    }
};

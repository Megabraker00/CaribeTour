<?php

use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReservationController;
use App\Models\Client;
use App\Models\Type;
use App\Models\Status;
use Illuminate\Support\Facades\Route;

// para ver las consultas que se están ejecutando 
// DB::listen(function ($query) { dump($query->sql); });

Route::get('', [HomeController::class, 'index'])->name('admin');

Route::controller(ReservationController::class)->group(function(){
    Route::get("/reservas", 'index')->name('admin.booking.index');
});

Route::controller(ClientController::class)->group(function(){
    Route::get("/clientes", 'index')->name('admin.client.index');
    Route::get("/clientes/{id}", 'show')->name('admin.client.show');
});

Route::controller(ProductController::class)->group(function(){
    Route::get("/tours", "indexTours")->name('admin.tour.index');
    Route::get("/tours/{id}", "showTours")->name('admin.tour.show');
});

/**
 * TODO: eliminar ruta, sólo usar en dev
 */
Route::get('popularcliente', function() {
    for ($i = 0; $i <= 2000; $i++) {
        $client = new Client();
        $client->name = fake()->name();;
        $client->last_name = fake()->words(2, true);
        $client->dni_passport = fake()->randomNumber(5, true);
        $client->type_id = Type::CLIENT_HOLDER;
        $client->status_id = Status::CLIENT_ACTIVE;
        $client->booking_id = 1;
        $client->save();
    }
    

    return "los cliente han sidos creados correctamente";
});
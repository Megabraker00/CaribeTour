<?php

use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DatatableController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('', [HomeController::class, 'index'])->name('admin');

Route::controller(ReservationController::class)->group(function(){
    Route::get("/reservas", 'index')->name('admin.booking.index');
});

Route::controller(ClientController::class)->group(function(){
    Route::get("/clientes", 'index')->name('admin.client.index');
});

Route::controller(DatatableController::class)->group(function(){
    Route::get('datatable/bookings', 'bookings')->name('datatable.bookings');
    Route::get('datatable/clientes', 'clients')->name('datatable.clients');
});
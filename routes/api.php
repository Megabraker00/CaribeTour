<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DatatableController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * DataTable
 */
Route::controller(DatatableController::class)->group(function(){
    Route::get('datatable/bookings', 'bookings')->name('api.datatable.bookings');
    Route::get('datatable/clientes', 'clients')->name('api.datatable.clients');
    Route::get('datatable/tours', 'tours')->name('api.datatable.tours');
    Route::get('datatable/tours/{id}/itineraries', 'tourItinerarys')->name('api.datatable.tours.itineraries');
});
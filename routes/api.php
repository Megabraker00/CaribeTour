<?php

use App\Http\Controllers\Admin\DatatableController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * DataTables used by the authenticated admin panel (session cookie).
 */
Route::middleware(['web', 'auth', 'can:access-admin'])->controller(DatatableController::class)->group(function () {
    Route::get('datatable/bookings', 'bookings')->name('api.datatable.bookings');
    Route::get('datatable/clientes', 'clients')->name('api.datatable.clients');
    Route::get('datatable/tours', 'tours')->name('api.datatable.tours');
    Route::get('datatable/catalog/{kind}', 'catalogProducts')->where('kind', 'tours|excursiones|hoteles|seguros')->name('api.datatable.catalog');
    Route::get('datatable/tours/{id}/itineraries', 'tourItinerarys')->name('api.datatable.tours.itineraries');
    Route::get('datatable/invoices', 'invoices')->name('api.datatable.invoices');
    Route::get('datatable/blogs', 'blogs')->name('api.datatable.blogs');
});

Route::prefix('v1')->group(function () {
    Route::apiResource('products', App\Http\Controllers\Api\ProductController::class)->only(['index', 'show']);
    Route::apiResource('categories', App\Http\Controllers\Api\CategoryController::class)->only(['index', 'show']);
    Route::apiResource('statuses', App\Http\Controllers\Api\StatusController::class)->only(['index', 'show']);
    Route::apiResource('types', App\Http\Controllers\Api\TypeController::class)->only(['index', 'show']);
    Route::apiResource('terminals', App\Http\Controllers\Api\TerminalController::class)->only(['index', 'show']);
    Route::get('products/{product}/itineraries', [App\Http\Controllers\Api\ProductController::class, 'tourItineraries'])
        ->name('api.products.itineraries');

    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('products', App\Http\Controllers\Api\ProductController::class)->except(['index', 'show']);
        Route::apiResource('categories', App\Http\Controllers\Api\CategoryController::class)->except(['index', 'show']);
        Route::apiResource('statuses', App\Http\Controllers\Api\StatusController::class)->except(['index', 'show']);
        Route::apiResource('types', App\Http\Controllers\Api\TypeController::class)->except(['index', 'show']);
        Route::apiResource('terminals', App\Http\Controllers\Api\TerminalController::class)->except(['index', 'show']);
    });
});

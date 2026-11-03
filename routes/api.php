<?php

use App\Http\Controllers\Admin\DatatableController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\StripeWebhookController;
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

/**
 * DataTables used by the authenticated admin panel (session cookie).
 */
Route::middleware(['web', 'auth', 'can:access-admin', 'role.writable'])->controller(DatatableController::class)->group(function () {
    Route::get('datatable/bookings', 'bookings')->name('api.datatable.bookings');
    Route::get('datatable/clientes', 'clients')->name('api.datatable.clients');
    Route::get('datatable/tours', 'tours')->name('api.datatable.tours');
    Route::get('datatable/catalog/{kind}', 'catalogProducts')->where('kind', 'tours|excursiones|hoteles|seguros')->name('api.datatable.catalog');
    Route::get('datatable/tours/{id}/itineraries', 'tourItinerarys')->name('api.datatable.tours.itineraries');
    Route::get('datatable/invoices', 'invoices')->name('api.datatable.invoices');
    Route::get('datatable/blogs', 'blogs')->name('api.datatable.blogs');
    Route::get('datatable/mensajes', 'contactMessages')->name('api.datatable.contact-messages');
});

Route::get('v1/products/{product}/itineraries', [ProductController::class, 'tourItineraries'])
    ->name('api.products.itineraries');

<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ItineraryController;
use App\Http\Controllers\Admin\SegmentController;
use App\Http\Controllers\Admin\ItineraryPriceController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\TerminalController;
use App\Http\Controllers\Admin\TypeController;
use App\Models\Client;
use App\Models\Type;
use App\Models\Status;
use Illuminate\Support\Facades\Route;

// para ver las consultas que se están ejecutando
// DB::listen(function ($query) { dump($query->sql); });

Route::get('', [HomeController::class, 'index'])->name('admin');

Route::controller(ReservationController::class)->group(function () {
    Route::get('/reservas', 'index')->name('admin.booking.index');
    Route::get('/reservas/{booking}', 'show')->name('admin.booking.show');
    Route::put('/reservas/{booking}/meta', 'updateMeta')->name('admin.booking.meta.update');
});

Route::controller(ClientController::class)->group(function () {
    Route::get('/clientes', 'index')->name('admin.client.index');
    Route::get('/clientes/{client}', 'show')->name('admin.client.show');
    Route::get('/clientes/{client}/edit', 'edit')->name('admin.client.edit');
    Route::put('/clientes/{client}', 'update')->name('admin.client.update');
});

Route::controller(ProductController::class)->group(function () {
    Route::get('/tours', 'indexTour')->name('admin.tour.index');
    Route::get('/tours/new', 'createTour')->name('admin.tour.create');
    Route::post('/tours', 'storeTour')->name('admin.tour.store');
    Route::get('/tours/{id}', 'showTour')->name('admin.tour.show');
    Route::get('/tours/{id}/edit', 'editTour')->name('admin.tour.edit');
    Route::put('/tours/{id}', 'updateTour')->name('admin.tour.update');
    Route::post('/tours/{id}/images', 'storeTourImages')->name('admin.tour.images.store');
    Route::post('/tours/{id}/images/names', 'updateTourImagesNames')->name('admin.tour.images.names');
    Route::post('/tours/{id}/images/{image}/main', 'setMainTourImage')->name('admin.tour.images.main');
    Route::delete('/tours/{id}/images/{image}', 'destroyTourImage')->name('admin.tour.images.destroy');
});

Route::post('/tourDate', [SegmentController::class, 'storeTourDate'])->name('admin.tour.date.store');

Route::controller(ItineraryController::class)->group(function () {
    Route::delete('/tourDate/{id}/destroy', 'destroyTourDate')->name('admin.tour.date.destroy');
});

Route::get('itineraries/{itinerary}/prices', [ItineraryPriceController::class, 'edit'])
    ->name('admin.itineraries.prices.edit');
Route::put('itineraries/{itinerary}/prices', [ItineraryPriceController::class, 'update'])
    ->name('admin.itineraries.prices.update');

Route::get('itineraries/{itinerary}/segments', [SegmentController::class, 'manageItinerarySegments'])
    ->name('admin.itineraries.segments.index');
Route::post('itineraries/{itinerary}/segments', [SegmentController::class, 'storeSegment'])
    ->name('admin.itineraries.segments.store');
Route::get('itineraries/{itinerary}/segments/{segment}/edit', [SegmentController::class, 'editSegment'])
    ->name('admin.itineraries.segments.edit');
Route::put('itineraries/{itinerary}/segments/{segment}', [SegmentController::class, 'updateSegment'])
    ->name('admin.itineraries.segments.update');
Route::delete('itineraries/{itinerary}/segments/{segment}', [SegmentController::class, 'destroySegment'])
    ->name('admin.itineraries.segments.destroy');

Route::resource('types', TypeController::class)->except(['show'])->names([
    'index' => 'admin.types.index',
    'create' => 'admin.types.create',
    'store' => 'admin.types.store',
    'edit' => 'admin.types.edit',
    'update' => 'admin.types.update',
    'destroy' => 'admin.types.destroy',
]);

Route::resource('statuses', StatusController::class)->except(['show'])->names([
    'index' => 'admin.statuses.index',
    'create' => 'admin.statuses.create',
    'store' => 'admin.statuses.store',
    'edit' => 'admin.statuses.edit',
    'update' => 'admin.statuses.update',
    'destroy' => 'admin.statuses.destroy',
]);

Route::resource('categories', CategoryController::class)->only([
    'index', 'create', 'store', 'edit', 'update',
])->names([
    'index' => 'admin.categories.index',
    'create' => 'admin.categories.create',
    'store' => 'admin.categories.store',
    'edit' => 'admin.categories.edit',
    'update' => 'admin.categories.update',
]);

Route::resource('terminals', TerminalController::class)->only([
    'index', 'create', 'store', 'edit', 'update',
])->names([
    'index' => 'admin.terminals.index',
    'create' => 'admin.terminals.create',
    'store' => 'admin.terminals.store',
    'edit' => 'admin.terminals.edit',
    'update' => 'admin.terminals.update',
]);

Route::controller(InvoiceController::class)->group(function () {
    Route::get('/facturas', 'index')->name('admin.facturas.index');
    Route::get('/facturas/new', 'create')->name('admin.facturas.create');
    Route::post('/facturas', 'store')->name('admin.facturas.store');
    Route::get('/facturas/{id}/edit', 'edit')->name('admin.facturas.edit');
    Route::put('/facturas/{id}', 'update')->name('admin.facturas.update');
    Route::delete('/facturas/{id}', 'destroy')->name('admin.facturas.destroy');
});

/**
 * TODO: eliminar ruta, sólo usar en dev
 */
Route::get('popularcliente', function () {
    for ($i = 0; $i <= 2000; $i++) {
        $client = new Client();
        $client->name = fake()->name();
        $client->last_name = fake()->words(2, true);
        $client->dni_passport = fake()->randomNumber(5, true);
        $client->type_id = Type::HOLDER;
        $client->status_id = Status::CLIENT_ACTIVE;
        $client->booking_id = 1;
        $client->save();
    }

    return 'los cliente han sidos creados correctamente';
});

<?php

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ItineraryController;
use App\Http\Controllers\Admin\SegmentController;
use App\Http\Controllers\Admin\ItineraryPriceController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\TerminalController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// para ver las consultas que se están ejecutando
// DB::listen(function ($query) { dump($query->sql); });

Route::get('', [HomeController::class, 'index'])->name('admin');

Route::controller(ReservationController::class)->group(function () {
    Route::get('/reservas', 'index')->name('admin.booking.index');
    Route::get('/reservas/{booking}', 'show')->name('admin.booking.show');
    Route::put('/reservas/{booking}/meta', 'updateMeta')->name('admin.booking.meta.update');
    Route::get('/reservas/{booking}/pasajeros/{passenger}/edit', 'editPassenger')->name('admin.booking.passengers.edit');
    Route::put('/reservas/{booking}/pasajeros/{passenger}', 'updatePassenger')->name('admin.booking.passengers.update');
});

Route::controller(ClientController::class)->group(function () {
    Route::get('/clientes', 'index')->name('admin.client.index');
    Route::get('/clientes/{client}', 'show')->name('admin.client.show');
    Route::get('/clientes/{client}/edit', 'edit')->name('admin.client.edit');
    Route::put('/clientes/{client}', 'update')->name('admin.client.update');
});

Route::controller(ProductController::class)->group(function () {
    $kind = 'tours|excursiones|hoteles|seguros';

    Route::get('/{kind}', 'index')->where('kind', $kind)->name('admin.catalog.index');
    Route::get('/{kind}/new', 'create')->where('kind', $kind)->name('admin.catalog.create');
    Route::post('/{kind}', 'store')->where('kind', $kind)->name('admin.catalog.store');
    Route::get('/{kind}/{id}', 'show')->where('kind', $kind)->whereNumber('id')->name('admin.catalog.show');
    Route::get('/{kind}/{id}/edit', 'edit')->where('kind', $kind)->whereNumber('id')->name('admin.catalog.edit');
    Route::put('/{kind}/{id}', 'update')->where('kind', $kind)->whereNumber('id')->name('admin.catalog.update');
    Route::post('/{kind}/{id}/images', 'storeImages')->where('kind', $kind)->whereNumber('id')->name('admin.catalog.images.store');
    Route::post('/{kind}/{id}/images/names', 'updateImagesNames')->where('kind', $kind)->whereNumber('id')->name('admin.catalog.images.names');
    Route::post('/{kind}/{id}/images/{image}/main', 'setMainImage')->where('kind', $kind)->whereNumber('id')->name('admin.catalog.images.main');
    Route::delete('/{kind}/{id}/images/{image}', 'destroyImage')->where('kind', $kind)->whereNumber('id')->name('admin.catalog.images.destroy');
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

Route::resource('blogs', BlogController::class)->names('admin.blogs');

Route::resource('empleados', EmployeeController::class)
    ->parameters(['empleados' => 'employee'])
    ->except(['show'])
    ->names('admin.employees');

Route::resource('proveedores', SupplierController::class)
    ->parameters(['proveedores' => 'supplier'])
    ->except(['show'])
    ->names('admin.suppliers');

Route::resource('usuarios', UserController::class)
    ->parameters(['usuarios' => 'user'])
    ->except(['show'])
    ->names('admin.users');

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
    Route::get('/facturas/{invoice}', 'show')->name('admin.facturas.show');
    Route::post('/reservas/{booking}/facturas', 'store')->name('admin.booking.invoices.store');
    Route::post('/reservas/{booking}/facturas/{invoice}/abono', 'storeCredit')->name('admin.booking.invoices.credit');
});

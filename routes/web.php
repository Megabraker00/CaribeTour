<?php

use App\Http\Controllers\DestinationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ServiceController;
use App\Models\Blog;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', HomeController::class)->name('inicio');

Route::controller(ServiceController::class)->group(function() {
    Route::get('/servicios', 'index')->name('servicios');
    Route::get('/servicios/{servicio}', 'show');
});

Route::get('/blogs', function() {

    $blogs = Blog::all();

    return view('blogs', ['blogs' => $blogs]);
})->name('blogs');

Route::get('/blogs/{post}', function($postSlug) {

    $post = Blog::where('slug', $postSlug)->first();

    return view('blog_post', ['post' => $post]);
});

Route::get('/contacto', function() {
    return view('contact');
})->name('contacto');

Route::controller(DestinationController::class)->group(function() {
    Route::get('/destinos', 'index')->name('destinos');
    Route::get('/destinos/resultados', 'searchResult')->name('destinos.resultado');
    Route::get('/destinos/{cat}', 'categoryIndex');
    Route::get("/destinos/{cat}/{subcat}", 'subcategoryIndex');
    Route::get("/destinos/{cat}/{subcat}/{tour}", 'tourShow');
});

Route::get('/galeria', function() {
    return view('gallery');
})->name('galeria');

Route::controller(ReservationController::class)->group(function() {
    Route::get("/reserva/{producto}", 'create')->name('reservation.create');
    Route::post("/reserva/{producto}", 'store');
    Route::get("/reserva/{producto}/pago", 'payment');
    Route::get("/reserva/{producto}/pago/ok", 'paymentOk');
    Route::get("/reserva/{producto}/pago/no-ok", 'paymentNoOk');
});
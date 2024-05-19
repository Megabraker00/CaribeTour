<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\PostController;
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

// para ver las consultas que se están ejecutando 
//DB::listen(function ($query) { dump($query->sql); });

Route::get('/', HomeController::class)->name('inicio');

Route::controller(ServiceController::class)->group(function() {
    Route::get('/servicios', 'index')->name('servicios');
    Route::get('/servicios/{servicio}', 'show')->name('servicios.detalle');
    Route::get('/servicios/{cat}', 'categoryIndex');
});

Route::get('/blogs', [PostController::class, 'index'])->name('blogs');

Route::get('/blogs/{post}', [PostController::class, 'show'])->name('blog.show');

Route::get('/contacto', function() {
    return view('contact');
})->name('contacto');

Route::controller(DestinationController::class)->group(function() {
    Route::get('/destinos', 'countryIndex')->name('destinos');
    Route::get('/destinos/{country}', 'provinceIndex')->name('destinos.pais');
    Route::get("/destinos/{country}/{province}", 'tourIndex')->name('destinos.provincia');
    Route::get("/destinos/{country}/{province}/{tour}", 'tourShow')->name('destinos.tour');
    Route::get('/destinos/resultados', 'searchResult')->name('destinos.resultado');
});

Route::get('/galeria', function() {
    return view('gallery');
})->name('galeria');

Route::controller(ReservationController::class)->group(function() {
    Route::get("/reserva/{producto}", 'create')->name('reservation.create');
    Route::post("/reserva/{producto}", 'store')->name('reservation.store');
    Route::get("/reserva/{producto}/pago", 'payment')->name('payment');
    Route::get("/reserva/{producto}/pago/ok", 'paymentOk');
    Route::get("/reserva/{producto}/pago/no-ok", 'paymentNoOk');
});

//Auth::routes();


Route::get('/login', LoginController::class)->name('login');
Route::get('/register', RegisterController::class)->name('register');
Route::post('/register', [RegisterController::class, 'create']);
//Route::get('/password/reset', ForgotPasswordController::class);


/**
 * Authentication routes
 */
/*
Route::middleware(['guest'])->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/reset', [ResetPasswordController::class, 'reset']);
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
});
*/


//Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\BookingLookupController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\PostController;
use App\Models\Image;
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

Route::controller(ServiceController::class)->group(function () {
    Route::get('/servicios', 'index')->name('servicios');
    Route::get('/servicios/{servicio:slug}', 'show')->name('servicios.detalle');
    Route::get('/servicios/{cat:slug}', 'categoryIndex');
});

Route::get('/blogs', [PostController::class, 'index'])->name('blogs');

Route::get('/blogs/{post:slug}', [PostController::class, 'show'])->name('blogs.show');

Route::get('/contacto', function () {
    return response()->view('contacto')->header(
        'Permissions-Policy',
        'local-network=(self "https://www.google.com" "https://maps.google.com"), loopback-network=(self "https://www.google.com" "https://maps.google.com")'
    );
})->name('contacto');

Route::controller(DestinationController::class)->group(function () {
    Route::get('/destinos', 'countryIndex')->name('destinos');
    Route::get('/destinos/{country:slug}', 'countryShow')->name('destinos.pais');
    Route::get("/destinos/{country:slug}/{province:slug}", 'provinceShow')->name('destinos.provincia');
    Route::get("/destinos/{country:slug}/{province:slug}/{tour:slug}", 'tourShow')->name('destinos.tour');
    Route::get('/destinos/resultados', 'searchResult')->name('destinos.resultado');
});

Route::get('/galeria', function () {
    //$images = Image::all();
    $images = Image::where('imageable_type', App\Models\Product::class)->paginate(12);
    return view('galeria', compact('images'));
})->name('galeria');

Route::get('/reserva/consulta', [BookingLookupController::class, 'show'])->name('reservation.lookup');
Route::post('/reserva/consulta', [BookingLookupController::class, 'lookup'])
    ->middleware('throttle:reservation-lookup')
    ->name('reservation.lookup.submit');

Route::controller(ReservationController::class)->scopeBindings()->group(function () {
    Route::get("/reserva/{product:slug}/{itinerary}", 'create')->name('reservation.create');
    Route::post("/reserva/{product:slug}/{itinerary}", 'store')
        ->middleware('throttle:reservation-store')
        ->name('reservation.store');
    Route::get("/reserva/{product:slug}/{itinerary}/pago", 'payment')->name('reservation.payment');
    Route::get("/reserva/{product:slug}/{itinerary}/pago/finalizar", 'paymentCallback')->name('reservation.payment.callback');
});

Route::match(['GET', 'POST'], '/register', function () {
    abort(404);
})->middleware('throttle:register');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

<?php

use App\Http\Controllers\HomeController;
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

Route::get('/', HomeController::class);
/**
 * Definir grupo de rutas gestionadas por el mismo contorlador
 */
/*
Route::controller(HomeController::class)->group(function() {
    Route::get('/servicios', 'nombreMetodo');
    Route::get('/servicios/{servicio}', 'nombreMetodo');
});
*/

Route::get('/servicios', function() {
    return 'Estamos en Servicios';
});

Route::get('/servicios/{servicio}', function($servicio) {
    return "datos del servicio $servicio";
});

Route::get('/blog', function() {
    return 'Lista de blogs';
});

Route::get('/blog/{post}', function($post) {
    return "datos de blogs/$post";
});

Route::get('/contacto', function() {
    return 'Lista de blogs';
});

Route::get("/destinos", function() {
    return "mostrar todos los";
});

/**
 * buscador de tours
 */
Route::get("/destinos/resultados", function($cat) {
    return "estas en la categoria: $cat";
});

Route::get("/destinos/{cat}", function($cat) {
    return "estas en la categoria: $cat";
});

Route::get("/destinos/{cat}/{subcat}", function($cat, $subcat) {
    return "estas en la categoria: $cat y subcategoria $subcat";
});

Route::get("/destinos/{cat}/{subcat}/{tour}", function($cat, $subcat, $tour) {
    return "estas en la categoria: $cat y subcategoria $subcat y el tour $tour";
});

Route::get("/reserva/{producto}", function($producto) {
    return "estamos introduciendo los datos del producto $producto";
});

Route::get("/reserva/{producto}/pago", function($producto) {
    return "estamos pagando el producto $producto";
});

Route::get("/reserva/{producto}/pago/ok", function($producto) {
    return "estamos pagando el producto $producto";
});

Route::get("/reserva/{producto}/pago/no-ok", function($producto) {
    return "estamos pagando el producto $producto";
});
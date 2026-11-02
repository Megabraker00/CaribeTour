<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Compartir categorías con todas las vistas
        //$categories_search = \App\Models\Category::whereNotNull('parent_id')->get();
        //view()->share('categories', $categories_search);

        Paginator::useBootstrapFive();

        // Los listados ya cargaban Bootstrap 4 encima de AdminLTE. Sin esa hoja,
        // el inicio se queda con la tipografía y los controles más finos de AdminLTE.
        View::composer('adminlte::page', function () {
            view()->startPush('css', '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">');
        });

        // El mismo dibujo del sitio, sobre un azul más oscuro que el del agua,
        // para distinguirlo en la pestaña. El SVG va el último: el navegador lo prefiere.
        View::composer('adminlte::master', function ($view) {
            $icons = '<link rel="icon" href="'.asset('favicons/favicon.ico').'" sizes="any">'
                .'<link rel="icon" href="'.asset('favicons/favicon.svg').'" type="image/svg+xml">';
            $view->getFactory()->startSection('adminlte_css');
            echo $icons;
            $view->getFactory()->appendSection();
        });
    }
}

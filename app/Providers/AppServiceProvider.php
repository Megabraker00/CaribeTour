<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
    }
}

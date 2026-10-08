<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/admin';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('register', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->ip())
                ->response(fn (Request $request, array $headers) => response(
                    __('auth.too_many'),
                    Response::HTTP_TOO_MANY_REQUESTS,
                    $headers
                ));
        });

        RateLimiter::for('reservation-lookup', function (Request $request) {
            return $this->formAttemptLimit($request, 10, 'external_ref');
        });

        RateLimiter::for('reservation-store', function (Request $request) {
            return $this->formAttemptLimit($request, 5, 'email');
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware(['web', 'auth', 'can:access-admin', 'role.writable'])
                ->prefix('admin')
                ->group(base_path('routes/admin.php'));
        });
    }

    private function formAttemptLimit(Request $request, int $perMinute, string $errorField): Limit
    {
        return Limit::perMinute($perMinute)
            ->by($request->ip())
            ->response(function (Request $request, array $headers) use ($errorField) {
                return back()
                    ->withInput($request->except('password', 'password_confirmation'))
                    ->withErrors([$errorField => __('auth.too_many')])
                    ->withHeaders($headers);
            });
    }
}

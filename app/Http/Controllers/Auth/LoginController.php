<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Intentos fallidos por correo e IP antes de bloquear esa cuenta.
     */
    protected $maxAttempts = 5;

    /**
     * Minutos que dura el bloqueo.
     */
    protected $decayMinutes = 1;

    /**
     * Tope por IP para que cambiar de correo no reinicie el contador.
     */
    private const MAX_ATTEMPTS_PER_IP = 20;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function hasTooManyLoginAttempts(Request $request)
    {
        return $this->limiter()->tooManyAttempts($this->throttleKey($request), $this->maxAttempts())
            || $this->limiter()->tooManyAttempts($this->ipThrottleKey($request), self::MAX_ATTEMPTS_PER_IP);
    }

    protected function incrementLoginAttempts(Request $request)
    {
        $this->limiter()->hit($this->throttleKey($request), $this->decayMinutes() * 60);
        $this->limiter()->hit($this->ipThrottleKey($request), $this->decayMinutes() * 60);
    }

    protected function clearLoginAttempts(Request $request)
    {
        $this->limiter()->clear($this->throttleKey($request));
        $this->limiter()->clear($this->ipThrottleKey($request));
    }

    protected function sendLockoutResponse(Request $request)
    {
        $seconds = max(
            $this->limiter()->availableIn($this->throttleKey($request)),
            $this->limiter()->availableIn($this->ipThrottleKey($request))
        );

        throw ValidationException::withMessages([
            $this->username() => [trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => (int) ceil($seconds / 60),
            ])],
        ])->status(Response::HTTP_TOO_MANY_REQUESTS);
    }

    protected function ipThrottleKey(Request $request): string
    {
        return 'login|ip|'.$request->ip();
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureWritableRole
{
    /**
     * Block creating or changing data when the signed-in user is read-only.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user !== null && !$user->canWrite() && $this->attemptsWrite($request)) {
            abort(403, 'Tu rol solo permite consultar.');
        }

        return $next($request);
    }

    private function attemptsWrite(Request $request): bool
    {
        if (!in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true)) {
            return true;
        }

        return $request->routeIs('*.create');
    }
}

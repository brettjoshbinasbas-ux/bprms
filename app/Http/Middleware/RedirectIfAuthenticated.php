<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        if (auth('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        if (auth('resident')->check()) {
            return redirect()->route('resident.dashboard');
        }

        return $next($request);
    }
}

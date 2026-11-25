<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HandleGuardSwitch
{
    /**
     * Handle an incoming request - set the correct guard for this request
     */
    public function handle(Request $request, Closure $next, $expectedGuard = null): Response
    {
        if (!$expectedGuard) {
            return $next($request);
        }

        // Set the default guard untuk request ini agar Gate bisa resolve user dengan benar
        Auth::shouldUse($expectedGuard);

        // Cukup cek apakah user sudah login dengan guard yang sesuai
        if (Auth::guard($expectedGuard)->check()) {
            return $next($request);
        }

        // User tidak authenticated - redirect ke login
        return redirect()->route('login');
    }
}

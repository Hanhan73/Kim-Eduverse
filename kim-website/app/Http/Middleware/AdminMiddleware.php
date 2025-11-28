<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if digital admin logged in
        if (!session()->has('digital_admin_id')) {
            return redirect()->route('admin.digital.login')
                ->with('error', 'Silakan login terlebih dahulu');
        }

        return $next($request);
    }
}

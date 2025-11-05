<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EdutechStudent
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if logged in
        if (!session()->has('edutech_user_id')) {
            return redirect()
                ->route('edutech.login')
                ->with('error', 'Silakan login terlebih dahulu');
        }

        // Student can access (all roles can access student pages for viewing)
        return $next($request);
    }
}

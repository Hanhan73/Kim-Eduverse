<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EdutechAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if logged in
        if (!session()->has('edutech_user_id')) {
            return redirect()
                ->route('edutech.login')
                ->with('error', 'Silakan login terlebih dahulu');
        }

        // Check if user is admin
        if (session('edutech_user_role') !== 'admin') {
            abort(403, 'Unauthorized. Admin access only.');
        }

        return $next($request);
    }
}

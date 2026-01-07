<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Super admin bisa akses semua
        if ($request->user()->isSuperAdmin()) {
            return $next($request);
        }

        // Check jika user punya salah satu permission yang diizinkan
        if ($request->user()->hasAnyPermission($permissions)) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
    }
}

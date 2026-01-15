<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EdutechAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('edutech_user_id')) {
            return redirect()
                ->route('edutech.login')
                ->with('error', 'Silakan login terlebih dahulu');
        }

        return $next($request);
    }
}
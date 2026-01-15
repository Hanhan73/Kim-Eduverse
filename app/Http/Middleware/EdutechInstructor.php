<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EdutechInstructor
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if logged in
        if (!session()->has('edutech_user_id')) {
            return redirect()
                ->route('edutech.login')
                ->with('error', 'Silakan login terlebih dahulu');
        }

        // Check if user is instructor or admin
        $role = session('edutech_user_role');
        
        if (!in_array($role, ['instructor', 'admin'])) {
            abort(403, 'Unauthorized. Instructor access only.');
        }

        return $next($request);
    }
}

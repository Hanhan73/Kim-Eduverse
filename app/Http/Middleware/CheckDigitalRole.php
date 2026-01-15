<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDigitalRole
{
    /**
     * Handle an incoming request.
     *
     * @param  string  $role  Expected role (collaborator, bendahara_digital, admin)
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if user logged in
        if (!session()->has('digital_admin_id')) {
            return redirect()->route('admin.digital.login')
                ->with('error', 'Silakan login terlebih dahulu');
        }

        // Get user role from session
        $userRole = session('digital_admin_role', 'admin');

        // Check if role matches
        if ($userRole !== $role) {
            abort(403, 'Unauthorized - You do not have permission to access this page');
        }

        return $next($request);
    }
}
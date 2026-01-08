<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // 2. Get a fresh user from the DB
        $user = User::with('role')->find(auth()->id());

        // 3. Get the role object from the relations array to avoid conflict with the 'role' column
        $roleObject = $user->getRelation('role'); // This is the key!

        // 4. If the role object is not found, deny access
        if (!$roleObject) {
            abort(401, 'Your account does not have a role assigned. Please contact an administrator.');
        }

        // 5. Get the role name from the object
        $userRoleName = $roleObject->name;

        // 6. Super Admin can access everything
        if ($userRoleName === 'super_admin') {
            return $next($request);
        }

        // 7. Check if the user's role is in the list of allowed roles for the route
        if (in_array($userRoleName, $roles)) {
            return $next($request);
        }

        // 8. If none of the above, deny access
        abort(403, 'You do not have permission to access this page.');
    }
}
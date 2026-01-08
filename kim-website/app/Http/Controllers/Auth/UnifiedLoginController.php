<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UnifiedLoginController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.unified-login');
    }

    /**
     * Handle login dan redirect berdasarkan role
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Log untuk debug
            Log::info('User logged in', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role_id' => $user->role_id,
                'role' => $user->role ? $user->role : 'no_role'
            ]);

            // Check if user has role
            if (!$user->role) {
                Log::warning('User has no role', ['user_id' => $user->id]);
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda belum memiliki role. Hubungi administrator.',
                ]);
            }

            // Redirect berdasarkan role - dengan try catch untuk safety
            try {
                // Check method exists
                if (!method_exists($user, 'isSuperAdmin')) {
                    Log::error('User model missing role methods');
                    return redirect()->route('home');
                }

                if ($user->isSuperAdmin()) {
                    Log::info('Redirecting to super admin dashboard');
                    return redirect()->intended(route('admin.super-admin.dashboard'));
                }

                if ($user->isBendahara()) {
                    Log::info('Redirecting to bendahara dashboard');
                    return redirect()->intended(route('admin.bendahara.dashboard'));
                }

                if ($user->isInstruktor()) {
                    Log::info('Redirecting to instructor dashboard');
                    return redirect()->intended(route('instructor.dashboard'));
                }

                if ($user->isAdminBlog()) {
                    Log::info('Redirecting to blog admin dashboard');
                    // Adjust route sesuai blog admin dashboard kamu
                    return redirect()->intended('/admin/blog');
                }

                // Default untuk student atau role lain
                Log::info('Redirecting to home (default)');
                return redirect()->intended(route('home'));
            } catch (\Exception $e) {
                Log::error('Login redirect error', [
                    'error' => $e->getMessage(),
                    'user_id' => $user->id
                ]);

                // Fallback ke home jika ada error
                return redirect()->route('home');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

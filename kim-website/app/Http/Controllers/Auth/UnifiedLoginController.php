<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

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
        $user = Auth::user(); // The standard Auth::user() is fine now

        // Redirect based on role - the model methods are now safe
        if ($user->isSuperAdmin()) {
            return redirect()->intended(route('admin.super-admin.dashboard'));
        }

        if ($user->isBendahara()) {
            return redirect()->intended(route('admin.bendahara.dashboard'));
        }

        if ($user->isAdminDigital()) {
            return redirect()->intended(route('admin.digital.dashboard'));
        }

        if ($user->isAdminEdutech()) {
            return redirect()->intended(route('edutech.admin.dashboard'));
        }   

        if ($user->isInstruktor()) {
            return redirect()->intended(route('instructor.dashboard'));
        }

        if ($user->isAdminBlog()) {
            return redirect()->intended('/admin/blog');
        }

        // Default for student or other roles
        return redirect()->intended(route('home'));
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
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
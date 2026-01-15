<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show login form
    public function showLogin()
    {
        // Redirect if already logged in
        if (session()->has('admin_id')) {
            return redirect()->route('admin.articles.index');
        }

        return view('admin.auth.login');
    }

    // Process login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Find admin by email
        $admin = Admin::where('email', $request->email)->first();

        // Check if admin exists and password is correct
        if ($admin && Hash::check($request->password, $admin->password)) {
            // Store admin info in session
            session([
                'admin_id' => $admin->id,
                'admin_name' => $admin->name,
                'admin_email' => $admin->email,
            ]);

            // Remember me functionality
            if ($request->has('remember')) {
                cookie()->queue('admin_email', $request->email, 43200); // 30 days
            }

            return redirect()
                ->route('admin.articles.index')
                ->with('success', 'Selamat datang, ' . $admin->name . '!');
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah'])
            ->withInput($request->only('email'));
    }

    // Logout
    public function logout()
    {
        session()->forget(['admin_id', 'admin_name', 'admin_email']);
        session()->flush();

        return redirect()
            ->route('admin.login')
            ->with('success', 'Anda telah berhasil logout');
    }

    // Show register form (optional - for creating new admin)
    public function showRegister()
    {
        // Only allow if logged in as admin
        if (!session()->has('admin_id')) {
            return redirect()->route('admin.login');
        }

        return view('admin.auth.register');
    }

    // Process registration (optional)
    public function register(Request $request)
    {
        // Only allow if logged in as admin
        if (!session()->has('admin_id')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6|confirmed',
        ]);

        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Admin baru berhasil ditambahkan!');
    }
}
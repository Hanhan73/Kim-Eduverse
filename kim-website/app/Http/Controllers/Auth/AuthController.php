<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\DigitalAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        if (session()->has('digital_admin_id')) {
            return redirect()->route('admin.digital.dashboard');
        }

        return view('admin.digital.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $admin = DigitalAdmin::where('email', $request->email)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            session([
                'digital_admin_id' => $admin->id,
                'digital_admin_name' => $admin->name,
                'digital_admin_email' => $admin->email,
            ]);

            if ($request->has('remember')) {
                cookie()->queue('digital_admin_email', $request->email, 43200);
            }

            return redirect()->route('admin.digital.dashboard')
                ->with('success', 'Selamat datang, ' . $admin->name . '!');
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah'])
            ->withInput($request->only('email'));
    }

    /**
     * Logout
     */
    public function logout()
    {
        session()->forget(['digital_admin_id', 'digital_admin_name', 'digital_admin_email']);
        session()->flush();

        return redirect()->route('admin.digital.login')
            ->with('success', 'Anda telah berhasil logout');
    }
}

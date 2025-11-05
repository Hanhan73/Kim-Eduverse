<?php

namespace App\Http\Controllers\Edutech;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Show login form
    public function showLogin()
    {
        // Redirect if already logged in
        if (session()->has('edutech_user_id')) {
            return $this->redirectBasedOnRole();
        }

        return view('edutech.auth.login');
    }

    // Process login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Find user by email
        $user = User::where('email', $request->email)
            ->where('is_active', true)
            ->first();

        // Check if user exists and password is correct
        if ($user && Hash::check($request->password, $user->password)) {
            // Store user info in session
            session([
                'edutech_user_id' => $user->id,
                'edutech_user_name' => $user->name,
                'edutech_user_email' => $user->email,
                'edutech_user_role' => $user->role,
            ]);

            // Remember me functionality
            if ($request->has('remember')) {
                cookie()->queue('edutech_email', $request->email, 43200); // 30 days
            }

            // Redirect based on role
            return $this->redirectBasedOnRole();
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah'])
            ->withInput($request->only('email'));
    }

    // Show register form
    public function showRegister()
    {
        // Redirect if already logged in
        if (session()->has('edutech_user_id')) {
            return $this->redirectBasedOnRole();
        }

        return view('edutech.auth.register');
    }

    // Process registration (for students)
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        // Create new student user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'student', // Default role
            'is_active' => true,
        ]);

        // Auto login after register
        session([
            'edutech_user_id' => $user->id,
            'edutech_user_name' => $user->name,
            'edutech_user_email' => $user->email,
            'edutech_user_role' => $user->role,
        ]);

        return redirect()
            ->route('edutech.student.dashboard')
            ->with('success', 'Selamat datang di KIM Edutech, ' . $user->name . '!');
    }

    // Logout
    public function logout()
    {
        session()->forget(['edutech_user_id', 'edutech_user_name', 'edutech_user_email', 'edutech_user_role']);
        session()->flush();

        return redirect()
            ->route('edutech.login')
            ->with('success', 'Anda telah berhasil logout');
    }

    // Helper: Redirect based on role
    private function redirectBasedOnRole()
    {
        $role = session('edutech_user_role');

        switch ($role) {
            case 'admin':
                return redirect()
                    ->route('edutech.admin.dashboard')
                    ->with('success', 'Selamat datang, Admin!');
            
            case 'instructor':
                return redirect()
                    ->route('edutech.instructor.dashboard')
                    ->with('success', 'Selamat datang, Pengajar!');
            
            case 'student':
            default:
                return redirect()
                    ->route('edutech.student.dashboard')
                    ->with('success', 'Selamat datang kembali!');
        }
    }

    // Get current logged in user
    public static function user()
    {
        $userId = session('edutech_user_id');
        
        if ($userId) {
            return User::find($userId);
        }

        return null;
    }

    // Check if user is logged in
    public static function check()
    {
        return session()->has('edutech_user_id');
    }

    // Check role
    public static function isAdmin()
    {
        return session('edutech_user_role') === 'admin';
    }

    public static function isInstructor()
    {
        return session('edutech_user_role') === 'instructor';
    }

    public static function isStudent()
    {
        return session('edutech_user_role') === 'student';
    }
}
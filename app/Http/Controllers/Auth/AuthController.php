<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\DigitalAdmin;
use App\Models\User;
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
            return $this->redirectToDashboard();
        }

        return view('admin.digital.login');
    }

    /**
     * Handle login - Support multiple roles
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Try login as DigitalAdmin first (existing admin)
        $digitalAdmin = DigitalAdmin::where('email', $request->email)->first();

        if ($digitalAdmin && Hash::check($request->password, $digitalAdmin->password)) {
            session([
                'digital_admin_id' => $digitalAdmin->id,
                'digital_admin_name' => $digitalAdmin->name,
                'digital_admin_email' => $digitalAdmin->email,
                'digital_admin_role' => 'admin', // Admin digital
            ]);

            if ($request->has('remember')) {
                cookie()->queue('digital_admin_email', $request->email, 43200);
            }

            return redirect()->route('admin.digital.dashboard')
                ->with('success', 'Selamat datang, ' . $digitalAdmin->name . '!');
        }

        // Try login as User (collaborator or bendahara_digital)
        $user = User::where('email', $request->email)
            ->whereIn('role', ['collaborator', 'bendahara_digital'])
            ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            session([
                'digital_admin_id' => $user->id,
                'digital_admin_name' => $user->name,
                'digital_admin_email' => $user->email,
                'digital_admin_role' => $user->role, // collaborator or bendahara_digital
            ]);

            if ($request->has('remember')) {
                cookie()->queue('digital_admin_email', $request->email, 43200);
            }

            // Redirect based on role
            $dashboardRoute = $this->getDashboardRoute($user->role);
            
            return redirect()->route($dashboardRoute)
                ->with('success', 'Selamat datang, ' . $user->name . '!');
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
        session()->forget(['digital_admin_id', 'digital_admin_name', 'digital_admin_email', 'digital_admin_role']);
        session()->flush();

        return redirect()->route('admin.digital.login')
            ->with('success', 'Anda telah berhasil logout');
    }

    /**
     * Redirect to appropriate dashboard based on role
     */
    private function redirectToDashboard()
    {
        $role = session('digital_admin_role', 'admin');
        $route = $this->getDashboardRoute($role);
        
        return redirect()->route($route);
    }

    /**
     * Get dashboard route based on role
     */
    private function getDashboardRoute($role)
    {
        return match($role) {
            'collaborator' => 'digital.collaborator.dashboard',
            'bendahara_digital' => 'digital.bendahara.dashboard',
            default => 'admin.digital.dashboard',
        };
    }
}
<?php

namespace App\Http\Controllers\Edutech;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session()->has('edutech_user_id')) {
            return $this->redirectBasedOnRole();
        }

        return view('edutech.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $request->email)
            ->where('is_active', true)
            ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Cek apakah email sudah diverifikasi
            if (is_null($user->email_verified_at)) {
                return back()
                    ->withErrors(['email' => 'Email belum diverifikasi. Silakan cek email Anda.'])
                    ->withInput($request->only('email'));
            }

            session([
                'edutech_user_id' => $user->id,
                'edutech_user_name' => $user->name,
                'edutech_user_email' => $user->email,
                'edutech_user_role' => $user->role,
            ]);

            if ($request->has('remember')) {
                cookie()->queue('edutech_email', $request->email, 43200);
            }

            return $this->redirectBasedOnRole();
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah'])
            ->withInput($request->only('email'));
    }

    public function showRegister()
    {
        if (session()->has('edutech_user_id')) {
            return $this->redirectBasedOnRole();
        }

        return view('edutech.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        $verificationToken = Str::random(64);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'student',
            'is_active' => true,
            'verification_token' => $verificationToken,
            'email_verified_at' => null,
        ]);

        // Kirim email verifikasi
        $this->sendVerificationEmail($user);

        return redirect()
            ->route('edutech.verification.notice')
            ->with('success', 'Akun berhasil dibuat! Silakan cek email Anda untuk verifikasi.');
    }

    public function verificationNotice()
    {
        return view('edutech.auth.verify-notice');
    }

    public function verify($token)
    {
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return redirect()
                ->route('edutech.login')
                ->with('error', 'Token verifikasi tidak valid.');
        }

        if ($user->email_verified_at) {
            return redirect()
                ->route('edutech.login')
                ->with('info', 'Email sudah diverifikasi sebelumnya.');
        }

        $user->email_verified_at = now();
        $user->verification_token = null;
        $user->save();

        return redirect()
            ->route('edutech.login')
            ->with('success', 'Email berhasil diverifikasi! Silakan login.');
    }

    public function resendVerification(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->email_verified_at) {
            return back()->with('info', 'Email sudah diverifikasi.');
        }

        $user->verification_token = Str::random(64);
        $user->save();

        $this->sendVerificationEmail($user);

        return back()->with('success', 'Email verifikasi telah dikirim ulang!');
    }

    public function logout()
    {
        session()->forget(['edutech_user_id', 'edutech_user_name', 'edutech_user_email', 'edutech_user_role']);
        session()->flush();

        return redirect()
            ->route('edutech.login')
            ->with('success', 'Anda telah berhasil logout');
    }

    private function redirectBasedOnRole()
    {
        $role = session('edutech_user_role');

        switch ($role) {
            case 'admin':
                return redirect()->route('edutech.admin.dashboard');
            case 'instructor':
                return redirect()->route('edutech.instructor.dashboard');
            case 'student':
                return redirect()->route('edutech.student.dashboard');
            case 'bendahara_edutech':
                return redirect()->route('edutech.bendahara.withdrawals.index');
            default:
                return redirect()->route('edutech.student.dashboard');
        }
    }

    private function sendVerificationEmail($user)
    {
        $verificationUrl = route('edutech.verify', ['token' => $user->verification_token]);

        Mail::send('edutech.emails.verify', [
            'user' => $user,
            'verificationUrl' => $verificationUrl
        ], function ($message) use ($user) {
            $message->to($user->email, $user->name)
                ->subject('Verifikasi Email - KIM Edutech');
        });
    }
}
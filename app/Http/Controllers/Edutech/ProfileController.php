<?php

namespace App\Http\Controllers\Edutech;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profile
     */
    public function index()
    {
        $userId = session('edutech_user_id');
        $user = User::findOrFail($userId);

        return view('edutech.profile.index', compact('user'));
    }

    /**
     * Update profile user
     */
    public function update(Request $request)
    {
        $userId = session('edutech_user_id');
        $user = User::findOrFail($userId);

        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Upload avatar baru
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }

        // Update user
        $user->update($validated);

        // Update session
        session([
            'edutech_user_name' => $validated['name'],
            'edutech_user_email' => $validated['email'],
        ]);

        if (isset($validated['avatar'])) {
            session(['edutech_user_avatar' => $validated['avatar']]);
        }

        return redirect()
            ->route('edutech.profile.index')
            ->with('success', 'Profile berhasil diperbarui');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $userId = session('edutech_user_id');
        $user = User::findOrFail($userId);

        // Validasi input
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai');
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password berhasil diperbarui');
    }

    /**
     * Hapus avatar
     */
    public function deleteAvatar()
    {
        $userId = session('edutech_user_id');
        $user = User::findOrFail($userId);

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
            session()->forget('edutech_user_avatar');
        }

        return response()->json(['success' => true]);
    }
}

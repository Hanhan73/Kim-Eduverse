<?php

namespace App\Http\Controllers\Digital\Collaborator;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CollaboratorProfileController extends Controller
{
    public function index()
    {
        $userId = session('digital_admin_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'collaborator') {
            abort(403, 'Unauthorized');
        }

        return view('digital.collaborator.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $userId = session('digital_admin_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'collaborator') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Update session
        session(['digital_admin_name' => $user->name, 'digital_admin_email' => $user->email]);

        return back()->with('success', 'Profile berhasil diupdate!');
    }

    public function updatePassword(Request $request)
    {
        $userId = session('digital_admin_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'collaborator') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }
}
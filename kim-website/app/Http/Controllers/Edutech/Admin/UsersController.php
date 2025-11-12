<?php

namespace App\Http\Controllers\Edutech\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        $users = $query->latest()->paginate(15);

        return view('edutech.admin.users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::with(['coursesAsInstructor', 'enrollments.course'])->findOrFail($id);

        $stats = [
            'total_courses' => $user->role === 'instructor' ? $user->coursesAsInstructor->count() : 0,
            'total_enrollments' => $user->enrollments->count(),
            'completed_courses' => $user->enrollments->whereNotNull('completed_at')->count(),
            'certificates' => $user->enrollments->whereNotNull('certificate_issued_at')->count(),
        ];

        return view('edutech.admin.users.show', compact('user', 'stats'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('edutech.admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,instructor,student',
            'is_active' => 'boolean',
        ]);

        $user->update($validated);

        return redirect()->route('edutech.admin.users.show', $user->id)
            ->with('success', 'User updated successfully!');
    }

    public function promote($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'student') {
            $user->role = 'instructor';
        } elseif ($user->role === 'instructor') {
            $user->role = 'admin';
        }

        $user->save();

        return redirect()->back()
            ->with('success', "User promoted to {$user->role} successfully!");
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'You cannot deactivate your own account!');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activated' : 'deactivated';
        return redirect()->back()
            ->with('success', "User {$status} successfully!");
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'You cannot delete your own account!');
        }

        $user->delete();

        return redirect()->route('edutech.admin.users')
            ->with('success', 'User deleted successfully!');
    }
}
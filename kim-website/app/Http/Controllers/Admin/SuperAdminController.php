<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\RevenueShare;
use App\Models\WithdrawalRequest;
use App\Models\InstructorEarning;
use App\Services\RevenueService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    // protected $revenueService;

    // public function __construct(RevenueService $revenueService)
    // {
    //     // $this->middleware(['auth', 'role:super_admin']);
    //     $this->revenueService = $revenueService;
    // }

    /**
     * Dashboard super admin
     */
    public function dashboard(RevenueService $revenueService)
    {
        $revenueStats = $revenueService->getRevenueStatistics('month');
        $stats = [
            'total_users' => User::count(),
            'total_instructors' => User::instructors()->count(),
            'total_students' => User::students()->count(),
            'total_bendaharas' => User::bendaharas()->count(),
            'total_revenue' => RevenueShare::completed()->sum('total_amount'),
            'company_revenue' => RevenueShare::completed()->sum('company_share'),
            'instructor_revenue' => RevenueShare::completed()->sum('instructor_share'),
            'pending_withdrawals' => WithdrawalRequest::pending()->count(),
            'pending_withdrawal_amount' => WithdrawalRequest::pending()->sum('amount'),
        ];

        $revenueStats = $revenueService->getRevenueStatistics('month');
        $topInstructors = $revenueService->getTopInstructors(5);
        $recentRevenues = RevenueShare::with(['instructor', 'user'])
            ->completed()
            ->latest('paid_at')
            ->limit(10)
            ->get();

        return view('admin.super-admin.dashboard', compact('stats', 'revenueStats', 'topInstructors', 'recentRevenues'));
    }

    /**
     * User Management
     */
    public function users()
    {
        $users = User::with('role')->latest()->paginate(20);
        $roles = Role::all();

        return view('admin.super-admin.users.index', compact('users', 'roles'));
    }

    public function createUser()
    {
        $roles = Role::all();
        return view('admin.super-admin.users.create', compact('roles'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        // Jika instruktor, create earning record
        if ($user->isInstruktor()) {
            $user->getOrCreateEarning();
        }

        return redirect()->route('admin.super-admin.users')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function editUser(User $user)
    {
        $roles = Role::all();
        return view('admin.super-admin.users.edit', compact('user', 'roles'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|min:8|confirmed',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $wasInstructor = $user->isInstruktor();
        $user->update($validated);

        // Jika berubah menjadi instruktor, create earning record
        if (!$wasInstructor && $user->isInstruktor()) {
            $user->getOrCreateEarning();
        }

        return redirect()->route('admin.super-admin.users')
            ->with('success', 'User berhasil diupdate');
    }

    public function deleteUser(User $user)
    {
        // Tidak bisa delete diri sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri');
        }

        $user->delete();

        return redirect()->route('admin.super-admin.users')
            ->with('success', 'User berhasil dihapus');
    }

    /**
     * Revenue Overview
     */
    public function revenue(Request $request, RevenueService $revenueService)
    {
        $period = $request->get('period', 'month');

        $stats = $revenueService->getRevenueStatistics($period);
        $breakdown = $revenueService->getRevenueBreakdown($period);

        $revenues = RevenueShare::with(['instructor', 'user'])
            ->completed()
            ->when($period !== 'all', function ($query) use ($period) {
                return $this->applyPeriodFilter($query, $period);
            })
            ->latest('paid_at')
            ->paginate(20);

        return view('admin.super-admin.revenue', compact('stats', 'breakdown', 'revenues', 'period'));
    }

    /**
     * Instructor Management
     */
    public function instructors()
    {
        $instructors = User::instructors()
            ->with(['instructorEarning', 'revenueShares'])
            ->withCount(['edutechCourses', 'kimDigitalCourses'])
            ->latest()
            ->paginate(20);

        return view('admin.super-admin.instructors.index', compact('instructors'));
    }

    public function instructorDetail(User $instructor)
    {
        if (!$instructor->isInstruktor()) {
            abort(404);
        }

        $earning = $instructor->getOrCreateEarning();
        $revenues = $instructor->revenueShares()
            ->with('course')
            ->completed()
            ->latest('paid_at')
            ->paginate(20);

        $withdrawals = $instructor->withdrawalRequests()
            ->latest()
            ->paginate(10);

        return view('admin.super-admin.instructors.detail', compact('instructor', 'earning', 'revenues', 'withdrawals'));
    }

    /**
     * Withdrawal Management
     */
    public function withdrawals()
    {
        $withdrawals = WithdrawalRequest::with(['instructor', 'approvedBy'])
            ->latest()
            ->paginate(20);

        $stats = [
            'pending' => WithdrawalRequest::pending()->count(),
            'approved' => WithdrawalRequest::approved()->count(),
            'completed' => WithdrawalRequest::completed()->count(),
            'rejected' => WithdrawalRequest::rejected()->count(),
        ];

        return view('admin.super-admin.withdrawals', compact('withdrawals', 'stats'));
    }

    /**
     * Settings
     */
    public function settings()
    {
        return view('admin.super-admin.settings');
    }

    private function applyPeriodFilter($query, string $period)
    {
        $now = now();

        return match ($period) {
            'today' => $query->whereDate('paid_at', $now->toDateString()),
            'week' => $query->whereBetween('paid_at', [$now->startOfWeek(), $now->endOfWeek()]),
            'month' => $query->whereMonth('paid_at', $now->month)->whereYear('paid_at', $now->year),
            'year' => $query->whereYear('paid_at', $now->year),
            default => $query,
        };
    }
}
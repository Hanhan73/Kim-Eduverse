<?php

namespace App\Http\Controllers\Edutech\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\InstructorRevenue;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class BendaharaRevenueController extends Controller
{
    public function index(Request $request)
    {
        $userId = session('edutech_user_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'bendahara_edutech') {
            abort(403, 'Unauthorized');
        }

        // Base query
        $revenuesQuery = InstructorRevenue::with(['instructor', 'course', 'payment', 'order']);
        
        // Apply period filter
        if ($request->filled('period')) {
            switch ($request->period) {
                case 'today':
                    $revenuesQuery->whereDate('created_at', today());
                    break;
                case 'this_week':
                    $revenuesQuery->whereBetween('created_at', [
                        now()->startOfWeek(),
                        now()->endOfWeek()
                    ]);
                    break;
                case 'this_month':
                    $revenuesQuery->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
                    break;
                case 'last_month':
                    $revenuesQuery->whereMonth('created_at', now()->subMonth()->month)
                        ->whereYear('created_at', now()->subMonth()->year);
                    break;
                case 'this_year':
                    $revenuesQuery->whereYear('created_at', now()->year);
                    break;
                case 'custom':
                    if ($request->filled('start_date') && $request->filled('end_date')) {
                        $revenuesQuery->whereBetween('created_at', [
                            $request->start_date . ' 00:00:00',
                            $request->end_date . ' 23:59:59'
                        ]);
                    }
                    break;
            }
        }
        
        // Apply instructor filter
        if ($request->filled('instructor_id')) {
            $revenuesQuery->where('instructor_id', $request->instructor_id);
        }
        
        // Handle export
        if ($request->has('export') && $request->export == 'excel') {
            return $this->exportRevenue($revenuesQuery->get());
        }
        
        // Get paginated results
        $revenues = $revenuesQuery->latest()->paginate(20);
        
        // Calculate stats based on current query
        $statsQuery = clone $revenuesQuery;
        $allRevenues = $statsQuery->get();
        
        $stats = [
            'total_revenue' => $allRevenues->sum('course_price'),
            'instructor_total' => $allRevenues->sum('instructor_share'),
            'platform_share' => $allRevenues->sum('platform_share'),
            'platform_fee_total' => $allRevenues->count() * 5000,
            'total_transactions' => $allRevenues->count(),
            'avg_transaction' => $allRevenues->count() > 0 
                ? $allRevenues->sum('course_price') / $allRevenues->count() 
                : 0,
        ];
        
        // Get all instructors for filter
        $instructors = User::where('role', 'instructor')->orderBy('name')->get();
        
        return view('edutech.bendahara.revenues', compact(
            'revenues',
            'stats',
            'instructors'
        ));
    }

    private function exportRevenue($revenues)
    {
        $filename = 'revenue_report_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($revenues) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, [
                'Tanggal',
                'Instructor',
                'Email',
                'Course',
                'Order Number',
                'Harga Course',
                'Platform Fee',
                'Sisa',
                'Instructor Share (70%)',
                'Platform Additional (30%)',
                'Total Platform',
                'Status'
            ]);
            
            // Data
            foreach ($revenues as $revenue) {
                $remaining = max(0, $revenue->course_price - 5000);
                $platformAdditional = $revenue->platform_share - 5000;
                
                fputcsv($file, [
                    $revenue->created_at->format('d/m/Y H:i'),
                    $revenue->instructor->name,
                    $revenue->instructor->email,
                    $revenue->course->title,
                    $revenue->order->order_number ?? 'N/A',
                    $revenue->course_price,
                    ,
                    $remaining,
                    $revenue->instructor_share,
                    $platformAdditional,
                    $revenue->platform_share,
                    $revenue->status
                ]);
            }
            
            // Total
            fputcsv($file, []);
            fputcsv($file, [
                'TOTAL',
                '',
                '',
                '',
                '',
                $revenues->sum('course_price'),
                $revenues->count() * 5000,
                $revenues->sum('course_price') - ($revenues->count() * 5000),
                $revenues->sum('instructor_share'),
                $revenues->sum('platform_share') - ($revenues->count() * 5000),
                $revenues->sum('platform_share'),
                ''
            ]);
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}

class BendaharaInstructorController extends Controller
{
    public function index()
    {
        $userId = session('edutech_user_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'bendahara_edutech') {
            abort(403, 'Unauthorized');
        }

        // Get all instructors with their stats
        $instructors = User::where('role', 'instructor')
            ->get()
            ->map(function ($instructor) {
                $totalRevenue = InstructorRevenue::where('instructor_id', $instructor->id)
                    ->sum('instructor_share');
                $available = InstructorRevenue::where('instructor_id', $instructor->id)
                    ->where('status', 'available')
                    ->sum('instructor_share');
                $withdrawn = InstructorRevenue::where('instructor_id', $instructor->id)
                    ->where('status', 'withdrawn')
                    ->sum('instructor_share');
                $totalCourses = Course::where('instructor_id', $instructor->id)->count();
                $totalSales = InstructorRevenue::where('instructor_id', $instructor->id)->count();

                return [
                    'instructor' => $instructor,
                    'total_revenue' => $totalRevenue,
                    'available' => $available,
                    'withdrawn' => $withdrawn,
                    'total_courses' => $totalCourses,
                    'total_sales' => $totalSales,
                ];
            })
            ->sortByDesc('total_revenue');

        return view('edutech.bendahara.instructors', compact('instructors'));
    }

    public function show($instructorId)
    {
        $userId = session('edutech_user_id');
        $user = User::find($userId);
        
        if (!$user || $user->role !== 'bendahara_edutech') {
            abort(403, 'Unauthorized');
        }

        $instructor = User::findOrFail($instructorId);

        // Instructor stats
        $stats = [
            'total_revenue' => InstructorRevenue::where('instructor_id', $instructorId)
                ->sum('instructor_share'),
            'available' => InstructorRevenue::where('instructor_id', $instructorId)
                ->where('status', 'available')
                ->sum('instructor_share'),
            'withdrawn' => InstructorRevenue::where('instructor_id', $instructorId)
                ->where('status', 'withdrawn')
                ->sum('instructor_share'),
            'pending' => InstructorRevenue::where('instructor_id', $instructorId)
                ->where('status', 'pending')
                ->sum('instructor_share'),
        ];

        // Revenues
        $revenues = InstructorRevenue::where('instructor_id', $instructorId)
            ->with(['course', 'payment'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Courses
        $courses = Course::where('instructor_id', $instructorId)
            ->withCount(['enrollments'])
            ->get()
            ->map(function ($course) {
                $sales = InstructorRevenue::where('course_id', $course->id)->count();
                $revenue = InstructorRevenue::where('course_id', $course->id)
                    ->sum('instructor_share');
                
                return [
                    'course' => $course,
                    'sales' => $sales,
                    'revenue' => $revenue,
                ];
            });

        return view('edutech.bendahara.instructor_detail', compact('instructor', 'stats', 'revenues', 'courses'));
    }
}
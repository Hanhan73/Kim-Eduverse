<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'status',
        'progress_percentage',
        'enrolled_at',
        'completed_at',
        'certificate_issued_at',
        'payment_status',
        'payment_amount',
        'certified_number',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'completed_at' => 'datetime',
        'certificate_issued_at' => 'datetime',
        'progress_percentage' => 'integer',
        'payment_amount' => 'decimal:2',

    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class, 'user_id', 'student_id')
            ->where('course_id', $this->course_id);
    }

    /**
     * ✅ TAMBAHAN BARU: Relasi ke Payment
     * Enrollment bisa punya banyak payment attempts (jika gagal bisa retry)
     * Tapi biasanya kita ambil yang latest
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'enrollment_id');
    }

    /**
     * Get latest payment untuk enrollment ini
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'enrollment_id')->latestOfMany();
    }

    /**
     * Get successful payment (yang sudah berhasil)
     */
    public function successfulPayment()
    {
        return $this->hasOne(Payment::class, 'enrollment_id')
            ->where('status', 'success')
            ->latest();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    /**
     * ============================================================
     * ACCESS CONTROL METHODS
     * ============================================================
     */

    /**
     * Check if user can access course materials
     * RULE: Must pass pre-test first (if exists)
     */
    public function canAccessMaterials()
    {
        $course = $this->course()->with('quizzes')->first();
        
        // Check if pre-test exists
        $preTest = $course->quizzes()
            ->where('type', 'pre_test')
            ->where('is_active', true)
            ->first();
        
        // If no pre-test, allow access immediately
        if (!$preTest) {
            return true;
        }
        
        // Check if user has passed pre-test
        $passedAttempt = $preTest->getPassedAttempt($this->student_id);
        
        return $passedAttempt !== null;
    }

    /**
     * Check if user can access specific module
     * RULE: Must complete previous modules first (sequential learning)
     */
    public function canAccessModule($moduleId)
    {
        $module = Module::findOrFail($moduleId);
        
        // Check if module belongs to this course
        if ($module->course_id !== $this->course_id) {
            return false;
        }
        
        // Get previous modules
        $previousModules = Module::where('course_id', $this->course_id)
            ->where('order', '<', $module->order)
            ->where('is_published', true)
            ->get();
        
        // If no previous modules, allow access
        if ($previousModules->isEmpty()) {
            return true;
        }
        
        // Check if all previous modules are completed
        foreach ($previousModules as $prevModule) {
            $moduleProgress = ModuleProgress::where('enrollment_id', $this->id)
                ->where('module_id', $prevModule->id)
                ->first();
            
            if (!$moduleProgress || $moduleProgress->status !== 'completed') {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Check if user can take post-test
     * RULE: Must complete all modules first
     */
    public function canTakePostTest()
    {
        $course = $this->course()->with('modules')->first();
        
        // Check if all modules are completed
        foreach ($course->modules as $module) {
            $moduleProgress = ModuleProgress::where('enrollment_id', $this->id)
                ->where('module_id', $module->id)
                ->first();
            
            if (!$moduleProgress || $moduleProgress->status !== 'completed') {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get enrollment progress data
     */
    public function getProgressData()
    {
        $course = $this->course()->with(['modules.lessons'])->first();
        
        $totalLessons = 0;
        $completedLessons = 0;
        
        foreach ($course->modules as $module) {
            $totalLessons += $module->lessons->count();
            
            $completedCount = LessonProgress::where('enrollment_id', $this->id)
                ->whereIn('lesson_id', $module->lessons->pluck('id'))
                ->where('is_completed', true)
                ->count();
            
            $completedLessons += $completedCount;
        }
        
        return [
            'total_lessons' => $totalLessons,
            'completed_lessons' => $completedLessons,
            'progress_percentage' => $totalLessons > 0 
                ? round(($completedLessons / $totalLessons) * 100) 
                : 0,
        ];
    }

    /**
     * Update progress percentage
     */
    public function updateProgress()
    {
        $data = $this->getProgressData();
        
        $this->update([
            'progress_percentage' => $data['progress_percentage'],
        ]);
        
        // Check if completed
        if ($data['progress_percentage'] >= 100) {
            $this->markAsCompleted();
        }
    }

    /**
     * Mark enrollment as completed
     */
    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'progress_percentage' => 100,
            'certificate_issued_at' => now(),
            'certified_number' => $this->generateCertifiedNumber(),
        ]);
    }

    /**
     * Generate certified number
     */
    protected function generateCertifiedNumber()
    {
        return 'CERT-' . strtoupper(uniqid());
    }
}
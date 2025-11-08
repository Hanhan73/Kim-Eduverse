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

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Calculate progress percentage based on NEW SYSTEM:
     * - Pre-test + All Modules + Post-test
     * - Each component has equal weight
     * 
     * Example: 
     * - Pre-test (1) + 3 Modules + Post-test (1) = 5 total components
     * - Each component = 100% / 5 = 20%
     */
    public function calculateProgress()
    {
        $course = $this->course()->with(['modules.lessons', 'quizzes'])->first();
        
        // Count total components
        $totalComponents = 0;
        $completedComponents = 0;
        
        // 1. Pre-test (if exists)
        $preTest = $course->quizzes()->where('type', 'pre_test')->where('is_active', true)->first();
        if ($preTest) {
            $totalComponents++;
            
            // Check if pre-test is passed
            $preTestPassed = \App\Models\QuizAttempt::where('user_id', $this->student_id)
                ->where('quiz_id', $preTest->id)
                ->where('is_passed', true)
                ->exists();
                
            if ($preTestPassed) {
                $completedComponents++;
            }
        }
        
        // 2. All Modules (count each module's lessons)
        foreach ($course->modules as $module) {
            $totalLessons = $module->lessons->count();
            
            if ($totalLessons > 0) {
                $totalComponents++;
                
                // Check if all lessons in this module are completed
                $completedLessons = \App\Models\LessonCompletion::where('user_id', $this->student_id)
                    ->whereIn('lesson_id', $module->lessons->pluck('id'))
                    ->where('is_completed', true)
                    ->count();
                
                if ($completedLessons >= $totalLessons) {
                    $completedComponents++;
                }
            }
        }
        
        // 3. Post-test (if exists)
        $postTest = $course->quizzes()->where('type', 'post_test')->where('is_active', true)->first();
        if ($postTest) {
            $totalComponents++;
            
            // Check if post-test is passed
            $postTestPassed = \App\Models\QuizAttempt::where('user_id', $this->student_id)
                ->where('quiz_id', $postTest->id)
                ->where('is_passed', true)
                ->exists();
                
            if ($postTestPassed) {
                $completedComponents++;
            }
        }
        
        // Calculate percentage
        if ($totalComponents > 0) {
            $progressPercentage = ($completedComponents / $totalComponents) * 100;
        } else {
            $progressPercentage = 0;
        }
        
        return round($progressPercentage, 2);
    }

    /**
     * Update progress and save to database
     */
    public function updateProgress()
    {
        $newProgress = $this->calculateProgress();
        
        $this->update([
            'progress_percentage' => $newProgress,
        ]);
        
        // Auto complete if 100%
        if ($newProgress >= 100 && $this->status !== 'completed') {
            $this->markAsCompleted();
        }
        
        return $newProgress;
    }

    // Helpers
    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'progress_percentage' => 100,
        ]);

        // Auto issue certificate if passing score met
        $this->issueCertificate();
    }

    public function issueCertificate()
    {
        // Check if certificate already exists
        if ($this->certificate()->exists()) {
            return;
        }

        // Create certificate
        $certificate = Certificate::create([
            'user_id' => $this->student_id,
            'course_id' => $this->course_id,
            'certificate_number' => Certificate::generateCertificateNumber(),
            'file_path' => 'certificates/temp.pdf',
            'issued_at' => now(),
        ]);

        // Update enrollment
        $this->update([
            'certificate_issued_at' => now(),
        ]);

        return $certificate;
    }

    /**
     * Check if user can access course materials
     * User MUST pass pre-test first (if exists)
     */
    public function canAccessMaterials()
    {
        $course = $this->course()->with('quizzes')->first();
        
        // Check if pre-test exists
        $preTest = $course->quizzes()->where('type', 'pre_test')->where('is_active', true)->first();
        
        // If no pre-test, allow access
        if (!$preTest) {
            return true;
        }
        
        // Check if user has passed pre-test
        $preTestPassed = \App\Models\QuizAttempt::where('user_id', $this->student_id)
            ->where('quiz_id', $preTest->id)
            ->where('is_passed', true)
            ->exists();
        
        return $preTestPassed;
    }

    /**
     * Check if user can access post-test
     * User must complete ALL modules first
     */
    public function canAccessPostTest()
    {
        $course = $this->course()->with(['modules.lessons'])->first();
        
        // Count total lessons
        $totalLessons = 0;
        foreach ($course->modules as $module) {
            $totalLessons += $module->lessons->count();
        }
        
        if ($totalLessons == 0) {
            return false;
        }
        
        // Check completed lessons
        $completedLessons = \App\Models\LessonCompletion::where('user_id', $this->student_id)
            ->whereHas('lesson.module', function($query) use ($course) {
                $query->where('course_id', $course->id);
            })
            ->where('is_completed', true)
            ->count();
        
        // Must complete ALL lessons
        return $completedLessons >= $totalLessons;
    }
}
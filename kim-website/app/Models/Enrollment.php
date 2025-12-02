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
     * ============================================================
     * NEW SYSTEM: ACCESS CONTROL METHODS
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
        $preTestPassed = \App\Models\QuizAttempt::where('user_id', $this->student_id)
            ->where('quiz_id', $preTest->id)
            ->where('is_passed', true)
            ->exists();
        
        return $preTestPassed;
    }

    /**
     * Check if user can access specific module
     * RULE: Previous module must be completed (all lessons + quiz if exists)
     */
    public function canAccessModule($moduleId)
    {
        $module = \App\Models\Module::findOrFail($moduleId);
        
        // Module pertama selalu bisa diakses (jika pre-test sudah lulus)
        if ($module->order === 1) {
            return $this->canAccessMaterials();
        }
        
        // Cek module sebelumnya
        $previousModule = \App\Models\Module::where('course_id', $module->course_id)
            ->where('order', '<', $module->order)
            ->orderBy('order', 'desc')
            ->first();
            
        if (!$previousModule) {
            return true; // Tidak ada module sebelumnya
        }
        
        // 1. Cek apakah semua lessons di module sebelumnya sudah completed
        $totalLessons = $previousModule->lessons()->count();
        $completedLessons = \App\Models\LessonCompletion::where('user_id', $this->student_id)
            ->whereIn('lesson_id', $previousModule->lessons()->pluck('id'))
            ->where('is_completed', true)
            ->count();
            
        if ($totalLessons !== $completedLessons) {
            return false; // Lessons belum selesai
        }
        
        // 2. Cek apakah ada quiz di module sebelumnya
        $previousModuleQuiz = \App\Models\Quiz::where('module_id', $previousModule->id)
            ->where('type', 'module_quiz')
            ->where('is_active', true)
            ->first();
            
        if ($previousModuleQuiz) {
            // Harus lulus quiz module sebelumnya
            $passedQuiz = \App\Models\QuizAttempt::where('user_id', $this->student_id)
                ->where('quiz_id', $previousModuleQuiz->id)
                ->where('is_passed', true)
                ->exists();
                
            return $passedQuiz;
        }
        
        return true; // Tidak ada quiz, bisa lanjut
    }

    /**
     * Check if user can access post-test
     * RULE: ALL modules must be completed (all lessons + all module quizzes)
     */
    public function canAccessPostTest()
    {
        $course = $this->course()->with(['modules.lessons'])->first();
        
        // Check if post-test exists
        $postTest = $course->quizzes()
            ->where('type', 'post_test')
            ->where('is_active', true)
            ->first();
        
        if (!$postTest) {
            return false; // Tidak ada post-test
        }
        
        // 1. Hitung total lessons di course
        $totalLessons = 0;
        foreach ($course->modules as $module) {
            $totalLessons += $module->lessons()->count();
        }
        
        // 2. Hitung completed lessons
        $completedLessons = \App\Models\LessonCompletion::where('user_id', $this->student_id)
            ->whereHas('lesson.module', function($query) use ($course) {
                $query->where('course_id', $course->id);
            })
            ->where('is_completed', true)
            ->count();
            
        // Jika lessons belum 100%, tidak bisa post-test
        if ($totalLessons !== $completedLessons) {
            return false;
        }
        
        // 3. Cek semua quiz module harus lulus
        $moduleQuizzes = \App\Models\Quiz::where('course_id', $course->id)
            ->where('type', 'module_quiz')
            ->where('is_active', true)
            ->get();
            
        foreach ($moduleQuizzes as $quiz) {
            $passed = \App\Models\QuizAttempt::where('user_id', $this->student_id)
                ->where('quiz_id', $quiz->id)
                ->where('is_passed', true)
                ->exists();
                
            if (!$passed) {
                return false; // Ada quiz module yang belum lulus
            }
        }
        
        return true; // Semua syarat terpenuhi
    }

    /**
     * ============================================================
     * PROGRESS CALCULATION - NEW SYSTEM
     * ============================================================
     * Calculate based on: Lessons + Quizzes (Pre-test, Module Quizzes, Post-test)
     */
    public function calculateProgress()
    {
        $course = $this->course()->with(['modules.lessons', 'quizzes'])->first();
        
        // Count total items (lessons + quizzes)
        $totalItems = 0;
        $completedItems = 0;
        
        // 1. PRE-TEST (jika ada dan active)
        $preTest = $course->quizzes()
            ->where('type', 'pre_test')
            ->where('is_active', true)
            ->first();
            
        if ($preTest) {
            $totalItems++;
            
            $preTestPassed = \App\Models\QuizAttempt::where('user_id', $this->student_id)
                ->where('quiz_id', $preTest->id)
                ->where('is_passed', true)
                ->exists();
                
            if ($preTestPassed) {
                $completedItems++;
            }
        }
        
        // 2. MODULES (lessons + module quizzes)
        foreach ($course->modules as $module) {
            // Count lessons
            $totalLessons = $module->lessons()->count();
            
            if ($totalLessons > 0) {
                $totalItems += $totalLessons;
                
                $completedLessons = \App\Models\LessonCompletion::where('user_id', $this->student_id)
                    ->whereIn('lesson_id', $module->lessons->pluck('id'))
                    ->where('is_completed', true)
                    ->count();
                
                $completedItems += $completedLessons;
            }
            
            // Count module quiz (jika ada)
            $moduleQuiz = \App\Models\Quiz::where('module_id', $module->id)
                ->where('type', 'module_quiz')
                ->where('is_active', true)
                ->first();
                
            if ($moduleQuiz) {
                $totalItems++;
                
                $moduleQuizPassed = \App\Models\QuizAttempt::where('user_id', $this->student_id)
                    ->where('quiz_id', $moduleQuiz->id)
                    ->where('is_passed', true)
                    ->exists();
                    
                if ($moduleQuizPassed) {
                    $completedItems++;
                }
            }
        }
        
        // 3. POST-TEST (jika ada dan active)
        $postTest = $course->quizzes()
            ->where('type', 'post_test')
            ->where('is_active', true)
            ->first();
            
        if ($postTest) {
            $totalItems++;
            
            $postTestPassed = \App\Models\QuizAttempt::where('user_id', $this->student_id)
                ->where('quiz_id', $postTest->id)
                ->where('is_passed', true)
                ->exists();
                
            if ($postTestPassed) {
                $completedItems++;
            }
        }
        
        // Calculate percentage
        if ($totalItems > 0) {
            $progressPercentage = ($completedItems / $totalItems) * 100;
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

    /**
     * ============================================================
     * HELPERS
     * ============================================================
     */

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
}
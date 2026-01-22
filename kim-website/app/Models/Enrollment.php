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
        'degree_certificate_issued_at',
        'payment_status',
        'payment_amount',
        'certified_number',
        'degree_certificate_number',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'completed_at' => 'datetime',
        'certificate_issued_at' => 'datetime',
        'degree_certificate_issued_at' => 'datetime',
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

    public function payments()
    {
        return $this->hasMany(Payment::class, 'enrollment_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'enrollment_id')->latestOfMany();
    }

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

    // Access Control Methods
    public function canAccessMaterials()
    {
        $course = $this->course()->with('quizzes')->first();

        $preTest = $course->quizzes()
            ->where('type', 'pre_test')
            ->where('is_active', true)
            ->first();

        if (!$preTest) {
            return true;
        }

        $passedAttempt = $preTest->getPassedAttempt($this->student_id);

        return $passedAttempt !== null;
    }

    public function canAccessModule($moduleId)
    {
        $module = Module::findOrFail($moduleId);

        if ($module->course_id !== $this->course_id) {
            return false;
        }

        $previousModules = Module::where('course_id', $this->course_id)
            ->where('order', '<', $module->order)
            ->where('is_published', true)
            ->get();

        if ($previousModules->isEmpty()) {
            return true;
        }

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

    public function canTakePostTest()
    {
        $course = $this->course()->with('modules')->first();

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

    public function updateProgress()
    {
        $data = $this->getProgressData();

        $this->update([
            'progress_percentage' => $data['progress_percentage'],
        ]);

        if ($data['progress_percentage'] >= 100) {
            $this->markAsCompleted();
        }
    }

    public function markAsCompleted()
    {
        $course = $this->course;

        $updateData = [
            'status' => 'completed',
            'completed_at' => now(),
            'progress_percentage' => 100,
            'certificate_issued_at' => now(),
            'certified_number' => $this->generateCertifiedNumber(),
        ];

        // Issue degree certificate if course has degree
        if ($course->has_degree) {
            $updateData['degree_certificate_issued_at'] = now();
            $updateData['degree_certificate_number'] = $this->generateDegreeCertificateNumber();
        }

        $this->update($updateData);
    }

    protected function generateCertifiedNumber()
    {
        return 'CERT-' . strtoupper(uniqid());
    }

    protected function generateDegreeCertificateNumber()
    {
        return 'DEGREE-' . strtoupper(uniqid());
    }
}

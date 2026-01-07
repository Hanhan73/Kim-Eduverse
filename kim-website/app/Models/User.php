<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Eager load role by default
    protected $with = ['role'];

    // Relationships
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function instructorEarning(): HasOne
    {
        return $this->hasOne(InstructorEarning::class, 'instructor_id');
    }

    public function bankAccounts(): HasMany
    {
        return $this->hasMany(InstructorBankAccount::class, 'instructor_id');
    }

    public function withdrawalRequests(): HasMany
    {
        return $this->hasMany(WithdrawalRequest::class, 'instructor_id');
    }

    public function revenueShares(): HasMany
    {
        return $this->hasMany(RevenueShare::class, 'instructor_id');
    }

    public function edutechCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    public function kimDigitalCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    // Role checking methods - FIXED with proper null checking
    public function hasRole(string $roleName): bool
    {
        // Load role if not loaded
        if (!$this->relationLoaded('role')) {
            $this->load('role');
        }

        return $this->role && is_object($this->role) && $this->role->name === $roleName;
    }

    public function hasAnyRole(array $roleNames): bool
    {
        // Load role if not loaded
        if (!$this->relationLoaded('role')) {
            $this->load('role');
        }

        return $this->role && is_object($this->role) && in_array($this->role->name, $roleNames);
    }

    public function hasPermission(string $permission): bool
    {
        // Load role if not loaded
        if (!$this->relationLoaded('role')) {
            $this->load('role');
        }

        return $this->role && is_object($this->role) && $this->role->hasPermission($permission);
    }

    public function hasAnyPermission(array $permissions): bool
    {
        // Load role if not loaded
        if (!$this->relationLoaded('role')) {
            $this->load('role');
        }

        return $this->role && is_object($this->role) && $this->role->hasAnyPermission($permissions);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function isBendahara(): bool
    {
        return $this->hasRole('bendahara');
    }

    public function isInstruktor(): bool
    {
        return $this->hasRole('instruktor');
    }

    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }

    public function isAdminBlog(): bool
    {
        return $this->hasRole('admin_blog');
    }

    // Helper untuk mendapatkan atau create instructor earning
    public function getOrCreateEarning(): InstructorEarning
    {
        return $this->instructorEarning()->firstOrCreate(
            ['instructor_id' => $this->id],
            [
                'total_earned' => 0,
                'available_balance' => 0,
                'withdrawn' => 0,
                'pending_withdrawal' => 0,
                'total_sales' => 0,
                'edutech_sales' => 0,
                'kim_digital_sales' => 0,
            ]
        );
    }

    // Helper untuk mendapatkan primary bank account
    public function primaryBankAccount()
    {
        return $this->bankAccounts()->where('is_primary', true)->first();
    }

    // Scope untuk query berdasarkan role
    public function scopeByRole($query, string $roleName)
    {
        return $query->whereHas('role', function ($q) use ($roleName) {
            $q->where('name', $roleName);
        });
    }

    public function scopeInstructors($query)
    {
        return $query->byRole('instruktor');
    }

    public function scopeBendaharas($query)
    {
        return $query->byRole('bendahara');
    }

    public function scopeStudents($query)
    {
        return $query->byRole('student');
    }
}

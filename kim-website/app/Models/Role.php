<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'permissions',
    ];

    protected $casts = [
        'permissions' => 'array',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function hasPermission(string $permission): bool
    {
        if (!is_array($this->permissions)) {
            return false;
        }

        return in_array($permission, $this->permissions);
    }

    public function hasAnyPermission(array $permissions): bool
    {
        if (!is_array($this->permissions)) {
            return false;
        }

        return count(array_intersect($permissions, $this->permissions)) > 0;
    }

    public function hasAllPermissions(array $permissions): bool
    {
        if (!is_array($this->permissions)) {
            return false;
        }

        return count(array_intersect($permissions, $this->permissions)) === count($permissions);
    }

    // Helper methods untuk check role spesifik
    public static function superAdmin()
    {
        return self::where('name', 'super_admin')->first();
    }

    public static function bendahara()
    {
        return self::where('name', 'bendahara')->first();
    }

    public static function instruktor()
    {
        return self::where('name', 'instruktor')->first();
    }

    public static function student()
    {
        return self::where('name', 'student')->first();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $fillable = [
        'role',
        'permission',
        'is_enabled'
    ];

    protected $casts = [
        'is_enabled' => 'boolean'
    ];

    /**
     * Check if a specific role has permission enabled.
     */
    public static function hasPermission($role, $permission)
    {
        if (empty($role)) {
            return false;
        }
        
        return self::where('role', $role)
            ->where('permission', $permission)
            ->where('is_enabled', true)
            ->exists();
    }
}

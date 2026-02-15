<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'permission_key',
        'is_enabled',
        'custom_settings',
    ];

    protected $casts = [
        'custom_settings' => 'array',
        'is_enabled' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get user permission for a specific key
     */
    public static function getUserPermission($userId, $permissionKey)
    {
        return self::where('user_id', $userId)
                  ->where('permission_key', $permissionKey)
                  ->first();
    }

    /**
     * Check if user has permission for a specific key
     */
    public static function hasPermission($userId, $permissionKey)
    {
        $permission = self::getUserPermission($userId, $permissionKey);
        return $permission ? $permission->is_enabled : false;
    }

    /**
     * Set user permission for a specific key
     */
    public static function setPermission($userId, $permissionKey, $isEnabled = true, $customSettings = null)
    {
        return self::updateOrCreate(
            ['user_id' => $userId, 'permission_key' => $permissionKey],
            ['is_enabled' => $isEnabled, 'custom_settings' => $customSettings]
        );
    }
}
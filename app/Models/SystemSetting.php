<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
        'is_public'
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::saved(function ($setting) {
            // مسح الكاش عند حفظ الإعداد
            Cache::forget("system_setting_{$setting->key}");
            Cache::forget('system_settings');
        });

        static::deleted(function ($setting) {
            // مسح الكاش عند حذف الإعداد
            Cache::forget("system_setting_{$setting->key}");
            Cache::forget('system_settings');
        });
    }

    /**
     * الحصول على قيمة إعداد
     */
    public static function get($key, $default = null)
    {
        return Cache::remember("system_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * تعيين قيمة إعداد
     */
    public static function set($key, $value, $type = 'string', $group = 'general', $description = null, $isPublic = false)
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'description' => $description,
                'is_public' => $isPublic
            ]
        );

        // إزالة من الكاش
        Cache::forget("system_setting_{$key}");
        
        return $setting;
    }

    /**
     * الحصول على جميع الإعدادات لمجموعة معينة
     */
    public static function getByGroup($group)
    {
        return static::where('group', $group)->get();
    }

    /**
     * الحصول على إعدادات النظام الأساسية
     */
    public static function getSystemSettings()
    {
        return Cache::remember('system_settings', 3600, function () {
            return static::where('is_public', true)->pluck('value', 'key')->toArray();
        });
    }

    /**
     * مسح جميع الكاش
     */
    public static function clearCache()
    {
        Cache::forget('system_settings');
        $settings = static::all();
        foreach ($settings as $setting) {
            Cache::forget("system_setting_{$setting->key}");
        }
        
        // مسح كاش Laravel أيضاً
        Cache::flush();
    }
}

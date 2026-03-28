<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
    ];

    /**
     * Model events: Kayıt/güncelleme/silme sonrası cache temizle.
     */
    protected static function booted(): void
    {
        static::saved(fn (Setting $setting) => static::clearGroupCache($setting->group));
        static::deleted(fn (Setting $setting) => static::clearGroupCache($setting->group));
    }

    /**
     * Get a setting value by group and key.
     */
    public static function get(string $key, string $group = 'general', $default = null): ?string
    {
        $settings = static::getGroup($group);

        return $settings[$key] ?? $default;
    }

    /**
     * Set a setting value by group and key.
     */
    public static function set(string $key, ?string $value, string $group = 'general'): void
    {
        static::updateOrCreate(
            ['group' => $group, 'key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Get all settings for a group as key-value array (cached).
     */
    public static function getGroup(string $group): array
    {
        $tenantId = tenant() ? tenant('id') : 'central';
        $cacheKey = "settings.{$tenantId}.{$group}";

        return Cache::remember($cacheKey, 3600, function () use ($group) {
            return static::where('group', $group)
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    /**
     * Belirli bir grubun cache'ini temizle.
     */
    public static function clearGroupCache(string $group): void
    {
        $tenantId = tenant() ? tenant('id') : 'central';
        Cache::forget("settings.{$tenantId}.{$group}");
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description'
    ];

    protected $casts = [
        'value' => 'string'
    ];

    // Cache les settings pour de meilleures performances
    public static function get($key, $default = null)
    {
        return Cache::remember("site_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->getCastedValue() : $default;
        });
    }

    public static function set($key, $value, $type = 'string', $group = 'general', $description = null)
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'description' => $description
            ]
        );

        Cache::forget("site_setting_{$key}");
        return $setting;
    }

    public function getCastedValue()
    {
        return match($this->type) {
            'boolean' => (bool) $this->value,
            'integer' => (int) $this->value,
            'float' => (float) $this->value,
            'json' => json_decode($this->value, true),
            'array' => json_decode($this->value, true),
            default => $this->value
        };
    }

    // Scope pour récupérer les settings par groupe
    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    // Méthodes statiques pour les couleurs du site
    public static function getColors()
    {
        return Cache::remember('site_colors', 3600, function () {
            $colorSettings = self::byGroup('colors')->get()->pluck('value', 'key');
            
            return [
                'primary' => $colorSettings['primary_color'] ?? '#d97706', // amber-600
                'secondary' => $colorSettings['secondary_color'] ?? '#78716c', // stone-500
                'accent' => $colorSettings['accent_color'] ?? '#f59e0b', // amber-500
                'background' => $colorSettings['background_color'] ?? '#fafaf9', // stone-50
                'text_primary' => $colorSettings['text_primary_color'] ?? '#1c1917', // stone-900
                'text_secondary' => $colorSettings['text_secondary_color'] ?? '#57534e', // stone-600
            ];
        });
    }

    public static function clearColorCache()
    {
        Cache::forget('site_colors');
        Cache::flush(); // Ou plus spécifiquement les clés site_setting_*
    }

    protected static function boot()
    {
        parent::boot();

        // Clear cache when settings are updated
        static::saved(function ($setting) {
            Cache::forget("site_setting_{$setting->key}");
            if ($setting->group === 'colors') {
                self::clearColorCache();
            }
        });

        static::deleted(function ($setting) {
            Cache::forget("site_setting_{$setting->key}");
            if ($setting->group === 'colors') {
                self::clearColorCache();
            }
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Collection extends Model implements HasMedia
{
    use HasUlids, HasTranslations, InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'history',
        'banner_image',
        'cover_media_id',
        'featured',
        'is_featured',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public array $translatable = ['name', 'description'];

    public function artworks(): HasMany
    {
        return $this->hasMany(Artwork::class);
    }
}

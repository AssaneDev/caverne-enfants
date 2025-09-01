<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Artist extends Model implements HasMedia
{
    use HasUlids, InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'bio',
        'links',
    ];

    protected $casts = [
        'links' => 'array',
    ];

    public function artworks(): HasMany
    {
        return $this->hasMany(Artwork::class);
    }
}

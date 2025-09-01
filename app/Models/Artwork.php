<?php

namespace App\Models;

use App\ArtworkStatus;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Artwork extends Model implements HasMedia
{
    use HasUlids, HasTranslations, InteractsWithMedia;

    protected $fillable = [
        'sku',
        'title',
        'slug',
        'artist_id',
        'collection_id',
        'year',
        'medium',
        'dimensions',
        'price_cents',
        'currency',
        'status',
        'reserved_until',
        'featured',
        'on_home',
        'weight_grams',
        'shipping_class',
        'meta_title',
        'meta_description',
        'image_path',
        'is_featured',
    ];

    protected $casts = [
        'status' => ArtworkStatus::class,
        'reserved_until' => 'datetime',
        'featured' => 'boolean',
        'on_home' => 'boolean',
        'price_cents' => 'integer',
        'year' => 'integer',
        'weight_grams' => 'integer',
        'is_featured' => 'boolean',
    ];

    public array $translatable = ['title', 'medium'];

    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isAvailable(): bool
    {
        return $this->status === ArtworkStatus::PUBLISHED;
    }

    public function isReserved(): bool
    {
        return $this->status === ArtworkStatus::RESERVED
            && $this->reserved_until
            && $this->reserved_until->isFuture();
    }

    public function getPriceAttribute(): float
    {
        return $this->price_cents / 100;
    }
}

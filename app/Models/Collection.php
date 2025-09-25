<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Collection extends Model implements HasMedia
{
    use HasUlids, InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'history',
        'banner_image',
        'cover_media_id',
        'featured',
        'is_featured',
        'atmosphere_description',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'is_featured' => 'boolean',
    ];
    
    // Méthode pour obtenir le chemin de l'image de bannière pour l'affichage
    public function getBannerImageUrlAttribute()
    {
        $bannerImage = $this->getRawOriginal('banner_image');
        if (empty($bannerImage)) {
            return null;
        }
        
        return $bannerImage;
    }
    
    // Accesseur pour Filament FileUpload - convertit le chemin en tableau
    public function getBannerImageAttribute($value)
    {
        // Si on accède depuis un contexte Filament (via reflection ou stack trace)
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10);
        $isFilamentContext = collect($backtrace)->some(function ($trace) {
            return isset($trace['class']) && str_contains($trace['class'], 'Filament');
        });
        
        if (empty($value)) {
            return null;
        }
        
        // Si c'est déjà un tableau (nouveau format Filament), on le retourne
        if (is_array($value)) {
            return $value;
        }
        
        // Si on est dans un contexte Filament, retourner le format attendu par FileUpload
        if ($isFilamentContext) {
            $filename = basename($value);
            return [$filename];
        }
        
        // Sinon, retourner la valeur brute pour l'affichage normal
        return $value;
    }
    
    // Mutateur pour Filament FileUpload - convertit le tableau en chaîne
    public function setBannerImageAttribute($value)
    {
        if (is_array($value) && !empty($value)) {
            // Si c'est juste un nom de fichier, on ajoute le chemin du répertoire
            $filename = $value[0];
            if (!str_contains($filename, '/')) {
                $this->attributes['banner_image'] = 'collections/banners/' . $filename;
            } else {
                $this->attributes['banner_image'] = $filename;
            }
        } else {
            $this->attributes['banner_image'] = $value;
        }
    }


    public function artworks(): HasMany
    {
        return $this->hasMany(Artwork::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('atmosphere_images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(200)
            ->sharpen(10)
            ->performOnCollections('atmosphere_images');

        $this->addMediaConversion('large')
            ->width(1200)
            ->height(800)
            ->sharpen(10)
            ->performOnCollections('atmosphere_images');
    }

    public function getAtmosphereImagesAttribute()
    {
        return $this->getMedia('atmosphere_images');
    }
}

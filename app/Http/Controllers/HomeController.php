<?php

namespace App\Http\Controllers;

use App\ArtworkStatus;
use App\Models\Artwork;
use App\Models\Collection;
use App\Models\HomepageBlock;

class HomeController extends Controller
{
    public function index()
    {
        // Récupérer les blocs de page d'accueil administrables
        $homepageBlocks = HomepageBlock::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->keyBy('key');
        
        $featuredArtworks = Artwork::whereIn('status', [ArtworkStatus::PUBLISHED, ArtworkStatus::SOLD])
            ->where('is_featured', true)
            ->with(['artist', 'collection'])
            ->take(6)
            ->get();

        $onHomeArtworks = Artwork::whereIn('status', [ArtworkStatus::PUBLISHED, ArtworkStatus::SOLD])
            ->where('is_featured', true)
            ->with(['artist', 'collection'])
            ->take(3)
            ->get();

        $featuredCollections = Collection::where('is_featured', true)
            ->with(['artworks' => function ($query) {
                $query->whereIn('status', [ArtworkStatus::PUBLISHED, ArtworkStatus::SOLD])->take(4);
            }])
            ->take(3)
            ->get();

        return view('home', compact(
            'homepageBlocks',
            'featuredArtworks',
            'onHomeArtworks', 
            'featuredCollections'
        ));
    }
}

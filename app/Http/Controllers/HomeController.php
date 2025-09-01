<?php

namespace App\Http\Controllers;

use App\ArtworkStatus;
use App\Models\Artwork;
use App\Models\Collection;

class HomeController extends Controller
{
    public function index()
    {
        $featuredArtworks = Artwork::where('status', ArtworkStatus::PUBLISHED)
            ->where('is_featured', true)
            ->with(['artist', 'collection'])
            ->take(6)
            ->get();

        $onHomeArtworks = Artwork::where('status', ArtworkStatus::PUBLISHED)
            ->where('is_featured', true)
            ->with(['artist', 'collection'])
            ->take(3)
            ->get();

        $featuredCollections = Collection::where('is_featured', true)
            ->with(['artworks' => function ($query) {
                $query->where('status', ArtworkStatus::PUBLISHED)->take(4);
            }])
            ->take(3)
            ->get();

        return view('home', compact(
            'featuredArtworks',
            'onHomeArtworks', 
            'featuredCollections'
        ));
    }
}

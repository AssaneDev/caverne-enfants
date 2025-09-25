<?php

namespace App\Http\Controllers;

use App\ArtworkStatus;
use App\Models\Collection;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::withCount(['artworks' => function ($query) {
            $query->whereIn('status', [ArtworkStatus::PUBLISHED, ArtworkStatus::SOLD]);
        }])
            ->with(['artworks' => function ($query) {
                $query->whereIn('status', [ArtworkStatus::PUBLISHED, ArtworkStatus::SOLD])->take(4);
            }])
            ->get();

        return view('collections.index', compact('collections'));
    }

    public function show(Collection $collection)
    {
        $collection->load(['artworks' => function ($query) {
            $query->whereIn('status', [ArtworkStatus::PUBLISHED, ArtworkStatus::SOLD])
                ->with('artist')
                ->orderBy('created_at', 'desc');
        }]);

        // Charger les images d'ambiance
        $collection->load('media');

        return view('collections.show', compact('collection'));
    }
}

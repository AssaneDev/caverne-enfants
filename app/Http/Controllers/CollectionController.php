<?php

namespace App\Http\Controllers;

use App\ArtworkStatus;
use App\Models\Collection;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::withCount(['artworks' => function ($query) {
            $query->where('status', ArtworkStatus::PUBLISHED);
        }])
            ->with(['artworks' => function ($query) {
                $query->where('status', ArtworkStatus::PUBLISHED)->take(4);
            }])
            ->get();

        return view('collections.index', compact('collections'));
    }

    public function show(Collection $collection)
    {
        $collection->load(['artworks' => function ($query) {
            $query->where('status', ArtworkStatus::PUBLISHED)
                ->with('artist')
                ->orderBy('created_at', 'desc');
        }]);

        return view('collections.show', compact('collection'));
    }
}

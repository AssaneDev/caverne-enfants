<?php

namespace App\Http\Controllers;

use App\ArtworkStatus;
use App\Models\Artwork;

class ArtworkController extends Controller
{
    public function show(Artwork $artwork)
    {
        if ($artwork->status !== ArtworkStatus::PUBLISHED) {
            abort(404);
        }

        $artwork->load(['artist', 'collection']);

        $relatedArtworks = Artwork::where('status', ArtworkStatus::PUBLISHED)
            ->where('id', '!=', $artwork->id)
            ->where(function ($query) use ($artwork) {
                $query->where('collection_id', $artwork->collection_id)
                    ->orWhere('artist_id', $artwork->artist_id);
            })
            ->with(['artist', 'collection'])
            ->take(4)
            ->get();

        return view('artworks.show', compact('artwork', 'relatedArtworks'));
    }
}

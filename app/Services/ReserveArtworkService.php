<?php

namespace App\Services;

use App\ArtworkStatus;
use App\Jobs\ReleaseReservationJob;
use App\Models\Artwork;
use Illuminate\Support\Facades\DB;

class ReserveArtworkService
{
    public function reserve(Artwork $artwork, int $minutesToReserve = 15): bool
    {
        return DB::transaction(function () use ($artwork, $minutesToReserve) {
            $artwork = Artwork::where('id', $artwork->id)
                ->lockForUpdate()
                ->first();

            if (!$artwork || $artwork->status !== ArtworkStatus::PUBLISHED) {
                return false;
            }

            $reservedUntil = now()->addMinutes($minutesToReserve);

            $artwork->update([
                'status' => ArtworkStatus::RESERVED,
                'reserved_until' => $reservedUntil,
            ]);

            ReleaseReservationJob::dispatch($artwork)
                ->delay($reservedUntil->addMinute());

            return true;
        });
    }

    public function release(Artwork $artwork): bool
    {
        return DB::transaction(function () use ($artwork) {
            $artwork = Artwork::where('id', $artwork->id)
                ->lockForUpdate()
                ->first();

            if (!$artwork || $artwork->status !== ArtworkStatus::RESERVED) {
                return false;
            }

            if ($artwork->reserved_until && $artwork->reserved_until->isPast()) {
                $artwork->update([
                    'status' => ArtworkStatus::PUBLISHED,
                    'reserved_until' => null,
                ]);

                return true;
            }

            return false;
        });
    }

    public function markAsSold(Artwork $artwork): bool
    {
        return DB::transaction(function () use ($artwork) {
            $artwork = Artwork::where('id', $artwork->id)
                ->lockForUpdate()
                ->first();

            if (!$artwork) {
                return false;
            }

            $artwork->update([
                'status' => ArtworkStatus::SOLD,
                'reserved_until' => null,
            ]);

            return true;
        });
    }
}

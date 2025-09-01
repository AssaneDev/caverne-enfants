<?php

namespace App\Jobs;

use App\Models\Artwork;
use App\Services\ReserveArtworkService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ReleaseReservationJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Artwork $artwork
    ) {}

    public function handle(): void
    {
        app(ReserveArtworkService::class)->release($this->artwork);
    }
}

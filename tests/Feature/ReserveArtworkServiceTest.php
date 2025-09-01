<?php

namespace Tests\Feature;

use App\ArtworkStatus;
use App\Models\Artwork;
use App\Services\ReserveArtworkService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReserveArtworkServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_reserve_published_artwork(): void
    {
        $artwork = Artwork::factory()->create([
            'status' => ArtworkStatus::PUBLISHED,
        ]);

        $service = new ReserveArtworkService();
        $result = $service->reserve($artwork);

        $this->assertTrue($result);
        $artwork->refresh();
        $this->assertEquals(ArtworkStatus::RESERVED, $artwork->status);
        $this->assertNotNull($artwork->reserved_until);
    }

    public function test_cannot_reserve_already_reserved_artwork(): void
    {
        $artwork = Artwork::factory()->create([
            'status' => ArtworkStatus::RESERVED,
            'reserved_until' => now()->addMinutes(10),
        ]);

        $service = new ReserveArtworkService();
        $result = $service->reserve($artwork);

        $this->assertFalse($result);
    }

    public function test_can_release_expired_reservation(): void
    {
        $artwork = Artwork::factory()->create([
            'status' => ArtworkStatus::RESERVED,
            'reserved_until' => now()->subMinutes(5),
        ]);

        $service = new ReserveArtworkService();
        $result = $service->release($artwork);

        $this->assertTrue($result);
        $artwork->refresh();
        $this->assertEquals(ArtworkStatus::PUBLISHED, $artwork->status);
        $this->assertNull($artwork->reserved_until);
    }

    public function test_cannot_reserve_sold_artwork(): void
    {
        $artwork = Artwork::factory()->create([
            'status' => ArtworkStatus::SOLD,
        ]);

        $service = new ReserveArtworkService();
        $result = $service->reserve($artwork);

        $this->assertFalse($result);
    }
}

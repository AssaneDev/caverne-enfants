<?php

namespace Database\Factories;

use App\ArtworkStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArtworkFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sku' => strtoupper($this->faker->unique()->lexify('???-???')),
            'title' => [
                'fr' => $this->faker->sentence(3),
                'en' => $this->faker->sentence(3),
            ],
            'slug' => $this->faker->unique()->slug(),
            'year' => $this->faker->numberBetween(2020, 2025),
            'medium' => [
                'fr' => $this->faker->randomElement(['Huile sur toile', 'Acrylique', 'Technique mixte']),
                'en' => $this->faker->randomElement(['Oil on canvas', 'Acrylic', 'Mixed media']),
            ],
            'dimensions' => $this->faker->randomElement(['30x40 cm', '50x70 cm', '40x60 cm']),
            'price_cents' => $this->faker->numberBetween(10000, 50000),
            'currency' => 'EUR',
            'status' => ArtworkStatus::PUBLISHED,
            'featured' => $this->faker->boolean(20),
            'on_home' => $this->faker->boolean(10),
            'weight_grams' => $this->faker->numberBetween(200, 1000),
        ];
    }
}

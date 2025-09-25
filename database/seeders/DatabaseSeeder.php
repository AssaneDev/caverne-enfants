<?php

namespace Database\Seeders;

use App\ArtworkStatus;
use App\Models\Artist;
use App\Models\Artwork;
use App\Models\Collection;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@caverne-enfants.com',
        ]);
        $admin->assignRole('Admin');

        $client = User::factory()->create([
            'name' => 'Client Test',
            'email' => 'client@example.com',
        ]);
        $client->assignRole('Client');

        $donald = Artist::create([
            'name' => 'Donald wallo',
            'slug' => 'donald-wallo',
            'bio' => 'Artiste contemporain, créateur de la série emblématique "Les Carrés du Fleuve".',
        ]);

        $collections = [
            [
                'name' => 'Les Carrés du Fleuve',
                'slug' => 'carres-du-fleuve',
                'description' => 'Une série unique explorant les formes géométriques dans la nature fluviale.',
            ],
            [
                'name' => 'La collection de l\'amitié',
                'slug' => 'collection-amitie',
                'description' => 'Poésie visuelle de l\'école de la Petite Côte.',
            ],
            [
                'name' => 'La correction des baobab',
                'slug' => 'correction-baobab',
                'description' => 'Œuvres inspirées des grands baobabs de l\'école de la Petite Côte.',
            ],
        ];

        foreach ($collections as $index => $collectionData) {
            $collection = Collection::create([
                'name' => $collectionData['name'],
                'slug' => $collectionData['slug'],
                'description' => $collectionData['description'],
                'featured' => $index === 0,
            ]);

            for ($i = 1; $i <= 3; $i++) {
                Artwork::create([
                    'sku' => strtoupper($collectionData['slug']) . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'title' => $collectionData['name'] . ' #' . $i,
                    'slug' => $collectionData['slug'] . '-' . $i,
                    'artist_id' => $index === 0 ? $donald->id : null,
                    'collection_id' => $collection->id,
                    'year' => 2023 + $index,
                    'medium' => ['Huile sur toile', 'Acrylique sur papier', 'Technique mixte'][rand(0, 2)],
                    'dimensions' => ['30x40 cm', '50x70 cm', '40x60 cm'][rand(0, 2)],
                    'price_cents' => [15000, 25000, 35000, 45000][rand(0, 3)],
                    'currency' => 'EUR',
                    'status' => ArtworkStatus::PUBLISHED,
                    'featured' => $i === 1,
                    'on_home' => $index === 0 && $i === 1,
                    'weight_grams' => rand(200, 800),
                    'meta_title' => $collectionData['name'] . ' #' . $i . ' - Caverne des Enfants',
                ]);
            }
        }
    }
}

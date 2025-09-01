<?php

namespace Database\Seeders;

use App\Models\Artwork;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ArtworkImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $artworks = Artwork::all();
        
        foreach ($artworks as $artwork) {
            try {
                $imageUrl = 'https://picsum.photos/800/800?random=' . $artwork->id;
                $response = Http::get($imageUrl);
                
                if ($response->successful()) {
                    $filename = 'artwork-' . $artwork->id . '.jpg';
                    Storage::disk('public')->put('temp/' . $filename, $response->body());
                    
                    $artwork->addMediaFromDisk('temp/' . $filename, 'public')
                        ->toMediaCollection('images');
                    
                    Storage::disk('public')->delete('temp/' . $filename);
                    
                    $this->command->info("Image ajoutée pour l'œuvre: {$artwork->title}");
                }
            } catch (\Exception $e) {
                $this->command->warn("Erreur lors de l'ajout d'image pour {$artwork->title}: {$e->getMessage()}");
            }
        }
    }
}

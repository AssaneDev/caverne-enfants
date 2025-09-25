<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // D'abord nettoyer les données JSON puis changer les types de colonnes
        $this->sanitizeCollectionsData();
        $this->sanitizeArtworksData();
        
        // Changer les types de colonnes
        Schema::table('collections', function (Blueprint $table) {
            $table->string('name', 500)->change();
            $table->text('description')->nullable()->change();
        });
        
        Schema::table('artworks', function (Blueprint $table) {
            $table->string('title', 500)->change();
            $table->string('medium', 500)->nullable()->change();
        });
    }
    
    private function sanitizeCollectionsData()
    {
        $collections = \DB::table('collections')->get();
        
        foreach ($collections as $collection) {
            $updates = [];
            
            // Nettoyer le champ name
            if ($collection->name && is_string($collection->name)) {
                $nameData = json_decode($collection->name, true);
                if (is_array($nameData) && json_last_error() === JSON_ERROR_NONE) {
                    $frenchValue = $nameData['fr'] ?? $nameData['en'] ?? array_values($nameData)[0] ?? '';
                    if ($frenchValue) {
                        $updates['name'] = json_encode($frenchValue);
                    }
                }
            }
            
            // Nettoyer le champ description
            if ($collection->description && is_string($collection->description)) {
                $descriptionData = json_decode($collection->description, true);
                if (is_array($descriptionData) && json_last_error() === JSON_ERROR_NONE) {
                    $frenchValue = $descriptionData['fr'] ?? $descriptionData['en'] ?? array_values($descriptionData)[0] ?? '';
                    if ($frenchValue) {
                        $updates['description'] = json_encode($frenchValue);
                    }
                }
            }
            
            // Mettre à jour si nécessaire
            if (!empty($updates)) {
                \DB::table('collections')->where('id', $collection->id)->update($updates);
            }
        }
    }
    
    private function sanitizeArtworksData()
    {
        $artworks = \DB::table('artworks')->get();
        
        foreach ($artworks as $artwork) {
            $updates = [];
            
            // Nettoyer le champ title
            if ($artwork->title && is_string($artwork->title)) {
                $titleData = json_decode($artwork->title, true);
                if (is_array($titleData) && json_last_error() === JSON_ERROR_NONE) {
                    $frenchValue = $titleData['fr'] ?? $titleData['en'] ?? array_values($titleData)[0] ?? '';
                    if ($frenchValue) {
                        $updates['title'] = json_encode($frenchValue);
                    }
                }
            }
            
            // Nettoyer le champ medium
            if ($artwork->medium && is_string($artwork->medium)) {
                $mediumData = json_decode($artwork->medium, true);
                if (is_array($mediumData) && json_last_error() === JSON_ERROR_NONE) {
                    $frenchValue = $mediumData['fr'] ?? $mediumData['en'] ?? array_values($mediumData)[0] ?? '';
                    if ($frenchValue) {
                        $updates['medium'] = json_encode($frenchValue);
                    }
                }
            }
            
            // Mettre à jour si nécessaire
            if (!empty($updates)) {
                \DB::table('artworks')->where('id', $artwork->id)->update($updates);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->json('name')->change();
            $table->json('description')->nullable()->change();
        });
        
        Schema::table('artworks', function (Blueprint $table) {
            $table->json('title')->change();
            $table->json('medium')->nullable()->change();
        });
    }
};

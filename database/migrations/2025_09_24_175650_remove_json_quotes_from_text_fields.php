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
        // Retirer les guillemets JSON des champs texte
        $collections = \DB::table('collections')->get();
        foreach ($collections as $collection) {
            $updates = [];
            
            if ($collection->name && is_string($collection->name)) {
                $cleanName = json_decode($collection->name);
                if ($cleanName && is_string($cleanName)) {
                    $updates['name'] = $cleanName;
                }
            }
            
            if ($collection->description && is_string($collection->description)) {
                $cleanDescription = json_decode($collection->description);
                if ($cleanDescription && is_string($cleanDescription)) {
                    $updates['description'] = $cleanDescription;
                }
            }
            
            if (!empty($updates)) {
                \DB::table('collections')->where('id', $collection->id)->update($updates);
            }
        }
        
        $artworks = \DB::table('artworks')->get();
        foreach ($artworks as $artwork) {
            $updates = [];
            
            if ($artwork->title && is_string($artwork->title)) {
                $cleanTitle = json_decode($artwork->title);
                if ($cleanTitle && is_string($cleanTitle)) {
                    $updates['title'] = $cleanTitle;
                }
            }
            
            if ($artwork->medium && is_string($artwork->medium)) {
                $cleanMedium = json_decode($artwork->medium);
                if ($cleanMedium && is_string($cleanMedium)) {
                    $updates['medium'] = $cleanMedium;
                }
            }
            
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
        // Pas de retour en arrière
    }
};

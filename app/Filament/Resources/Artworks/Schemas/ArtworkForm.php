<?php

namespace App\Filament\Resources\Artworks\Schemas;

use App\ArtworkStatus;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ArtworkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Titre')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, $set) {
                        if ($operation !== 'create') {
                            return;
                        }
                        $set('slug', Str::slug($state));
                    }),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->disabled()
                    ->dehydrated(),
                Textarea::make('description')
                    ->label('Description')
                    ->rows(4),
                Select::make('artist_id')
                    ->label('Artiste')
                    ->relationship('artist', 'name')
                    ->required(),
                Select::make('collection_id')
                    ->label('Collection')
                    ->relationship('collection', 'name')
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state) {
                            $collection = \App\Models\Collection::find($state);
                            if ($collection) {
                                $prefix = strtoupper(substr($collection->name, 0, 3));
                                $count = \App\Models\Artwork::where('collection_id', $state)->count() + 1;
                                $reference = $prefix . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
                                
                                // Vérifier l'unicité
                                while (\App\Models\Artwork::where('sku', $reference)->exists()) {
                                    $count++;
                                    $reference = $prefix . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
                                }
                                
                                $set('sku', $reference);
                            }
                        }
                    }),
                TextInput::make('sku')
                    ->label('Référence')
                    ->required()
                    ->maxLength(255)
                    ->disabled()
                    ->dehydrated(),
                TextInput::make('price_cents')
                    ->label('Prix (€)')
                    ->required()
                    ->numeric()
                    ->step(0.01)
                    ->prefix('€')
                    ->formatStateUsing(fn ($state) => $state ? $state / 100 : null)
                    ->dehydrateStateUsing(fn ($state) => $state ? (int) ($state * 100) : null),
                TextInput::make('year')
                    ->label('Année')
                    ->numeric()
                    ->maxLength(4),
                TextInput::make('medium')
                    ->label('Technique')
                    ->maxLength(255),
                TextInput::make('dimensions')
                    ->label('Dimensions')
                    ->maxLength(255),
                Select::make('status')
                    ->label('Statut')
                    ->options([
                        ArtworkStatus::DRAFT->value => 'Brouillon',
                        ArtworkStatus::PUBLISHED->value => 'Publié',
                        ArtworkStatus::RESERVED->value => 'Réservé',
                        ArtworkStatus::SOLD->value => 'Vendu',
                    ])
                    ->required(),
                Toggle::make('is_featured')
                    ->label('En vedette')
                    ->default(false),
                FileUpload::make('image_path')
                    ->label('Image principale')
                    ->image()
                    ->disk('public')
                    ->directory('artworks')
                    ->maxSize(5120)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),
                TextInput::make('meta_title')
                    ->maxLength(255),
                Textarea::make('meta_description')
                    ->rows(3),
            ]);
    }
}

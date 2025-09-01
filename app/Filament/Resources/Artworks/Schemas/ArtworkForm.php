<?php

namespace App\Filament\Resources\Artworks\Schemas;

use App\ArtworkStatus;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ArtworkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Titre')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Description')
                    ->rows(4),
                Select::make('artist_id')
                    ->label('Artiste')
                    ->relationship('artist', 'name')
                    ->required(),
                Select::make('collection_id')
                    ->label('Collection')
                    ->relationship('collection', 'name'),
                TextInput::make('sku')
                    ->label('Référence')
                    ->required()
                    ->maxLength(255),
                TextInput::make('price_cents')
                    ->label('Prix (centimes)')
                    ->required()
                    ->numeric(),
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

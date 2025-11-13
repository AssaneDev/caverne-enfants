<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations de la bannière')
                    ->schema([
                        TextInput::make('title')
                            ->label('Titre')
                            ->required()
                            ->maxLength(255),

                        FileUpload::make('image_path')
                            ->label('Image de la bannière')
                            ->image()
                            ->required()
                            ->directory('banners')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                null,
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->maxSize(5120)
                            ->helperText('Format recommandé : 1920x600px. Taille max : 5MB'),

                        TextInput::make('link_url')
                            ->label('URL du lien (optionnel)')
                            ->url()
                            ->placeholder('https://example.com')
                            ->helperText('Si renseigné, la bannière sera cliquable'),

                        Toggle::make('is_active')
                            ->label('Activer cette bannière')
                            ->default(true)
                            ->helperText('Seules les bannières actives seront affichées sur le site'),

                        TextInput::make('sort_order')
                            ->label('Ordre d\'affichage')
                            ->numeric()
                            ->default(0)
                            ->helperText('Les bannières sont affichées par ordre croissant (0 en premier)'),
                    ])
                    ->columns(2),
            ]);
    }
}

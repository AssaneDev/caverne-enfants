<?php

namespace App\Filament\Resources\HomepageBlocks\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class HomepageBlockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('key')
                    ->label('Type de bloc')
                    ->options([
                        'hero' => 'Section Une (Hero)',
                        'featured' => 'Section En vedette',
                        'about' => 'Section À propos',
                    ])
                    ->required()
                    ->unique(ignoreRecord: true),
                Toggle::make('is_active')
                    ->label('Actif')
                    ->default(true),
                TextInput::make('sort_order')
                    ->label('Ordre d\'affichage')
                    ->numeric()
                    ->default(0)
                    ->required(),
                TextInput::make('content.title')
                    ->label('Titre')
                    ->required()
                    ->maxLength(255),
                Textarea::make('content.subtitle')
                    ->label('Sous-titre')
                    ->rows(3),
                FileUpload::make('content.image_path')
                    ->label('Image')
                    ->image()
                    ->disk('public')
                    ->directory('homepage-blocks')
                    ->maxSize(10240)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->visibility('public')
                    ->imagePreviewHeight('300')
                    ->imageResizeMode('contain')
                    ->imageCropAspectRatio('16:9')
                    ->imageResizeTargetWidth('1920')
                    ->imageResizeTargetHeight('1080'),
                TextInput::make('content.button_text')
                    ->label('Texte du bouton')
                    ->maxLength(50),
                TextInput::make('content.button_url')
                    ->label('URL du bouton')
                    ->url(),
            ]);
    }
}

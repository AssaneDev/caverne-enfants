<?php

namespace App\Filament\Resources\Collections\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CollectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nom')
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
                FileUpload::make('banner_image')
                    ->label('Image de bannière')
                    ->image()
                    ->disk('public')
                    ->directory('collections/banners')
                    ->maxSize(5120)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->visibility('public')
                    ->imagePreviewHeight('200'),
                Toggle::make('is_featured')
                    ->label('En vedette')
                    ->default(false),
                RichEditor::make('history')
                    ->label('Historique de la collection')
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'underline',
                        'bulletList',
                        'orderedList',
                        'h2',
                        'h3',
                        'link',
                    ])
                    ->columnSpanFull(),

                Textarea::make('atmosphere_description')
                    ->label('Description de l\'ambiance de création')
                    ->placeholder('Décrivez l\'ambiance et le contexte de création des œuvres de cette collection...')
                    ->rows(3)
                    ->columnSpanFull(),

                SpatieMediaLibraryFileUpload::make('atmosphere_images')
                    ->label('Images d\'ambiance de création')
                    ->collection('atmosphere_images')
                    ->multiple()
                    ->reorderable()
                    ->image()
                    ->imagePreviewHeight('150')
                    ->panelLayout('grid')
                    ->maxFiles(10)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->disk('public')
                    ->directory('collections/atmosphere')
                    ->visibility('public')
                    ->hint('Maximum 10 images. Glissez-déposez plusieurs images à la fois. Formats acceptés : JPEG, PNG, WebP')
                    ->helperText('Vous pouvez sélectionner et uploader plusieurs images simultanément.')
                    ->columnSpanFull(),
            ]);
    }
}

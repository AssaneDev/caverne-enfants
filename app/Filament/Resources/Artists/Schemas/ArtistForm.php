<?php

namespace App\Filament\Resources\Artists\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ArtistForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),
                Textarea::make('biography')
                    ->label('Biographie')
                    ->rows(4),
                TextInput::make('website')
                    ->label('Site web')
                    ->url()
                    ->maxLength(255),
            ]);
    }
}

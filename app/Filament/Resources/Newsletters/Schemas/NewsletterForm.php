<?php

namespace App\Filament\Resources\Newsletters\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NewsletterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations de l\'abonné')
                    ->description('Gérer les informations de l\'abonné à la newsletter')
                    ->schema([
                        TextInput::make('email')
                            ->label('Adresse email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->prefixIcon('heroicon-o-envelope'),
                        Toggle::make('is_active')
                            ->label('Actif')
                            ->helperText('Activer ou désactiver l\'abonnement')
                            ->default(true)
                            ->required(),
                        DateTimePicker::make('subscribed_at')
                            ->label('Date d\'inscription')
                            ->default(now())
                            ->required()
                            ->displayFormat('d/m/Y H:i')
                            ->prefixIcon('heroicon-o-calendar'),
                    ]),
            ]);
    }
}

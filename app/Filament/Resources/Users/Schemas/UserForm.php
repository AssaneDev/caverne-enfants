<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nom')
                    ->required(),
                TextInput::make('email')
                    ->label('Adresse email')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at')
                    ->label('Email vérifié le'),
                TextInput::make('password')
                    ->label('Nouveau mot de passe')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => $state ? Hash::make($state) : null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->helperText('Laissez vide pour conserver le mot de passe actuel lors de la modification'),
                Placeholder::make('password_security_note')
                    ->label('Note de sécurité')
                    ->content('Les mots de passe sont automatiquement hachés et ne peuvent pas être visualisés. Utilisez l\'action "Réinitialiser le mot de passe" dans la liste pour générer un nouveau mot de passe temporaire.'),
            ]);
    }
}
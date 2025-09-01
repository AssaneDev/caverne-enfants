<?php

namespace App\Filament\Resources\Artworks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use App\ArtworkStatus;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ArtworksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('image_path')
                    ->label('Image')
                    ->formatStateUsing(fn ($state) => $state ? '📷' : '—'),
                TextColumn::make('title')
                    ->label('Titre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('sku')
                    ->label('Référence')
                    ->searchable(),
                TextColumn::make('artist.name')
                    ->label('Artiste')
                    ->sortable(),
                TextColumn::make('collection.name')
                    ->label('Collection')
                    ->sortable(),
                TextColumn::make('price')
                    ->label('Prix')
                    ->money('EUR')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => match ($state) {
                        ArtworkStatus::DRAFT => 'Brouillon',
                        ArtworkStatus::PUBLISHED => 'Publié',
                        ArtworkStatus::RESERVED => 'Réservé',
                        ArtworkStatus::SOLD => 'Vendu',
                        default => $state->value ?? 'Inconnu',
                    })
                    ->color(fn ($state): string => match ($state) {
                        ArtworkStatus::DRAFT => 'gray',
                        ArtworkStatus::PUBLISHED => 'success',
                        ArtworkStatus::RESERVED => 'warning',
                        ArtworkStatus::SOLD => 'danger',
                        default => 'gray',
                    }),
                IconColumn::make('is_featured')
                    ->label('En vedette')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        ArtworkStatus::DRAFT->value => 'Brouillon',
                        ArtworkStatus::PUBLISHED->value => 'Publié',
                        ArtworkStatus::RESERVED->value => 'Réservé',
                        ArtworkStatus::SOLD->value => 'Vendu',
                    ]),
                SelectFilter::make('artist')
                    ->label('Artiste')
                    ->relationship('artist', 'name'),
                SelectFilter::make('collection')
                    ->label('Collection')
                    ->relationship('collection', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

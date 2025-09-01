<?php

namespace App\Filament\Resources\Orders\Tables;

use App\OrderStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('Numéro')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Client')
                    ->searchable(),
                TextColumn::make('billing_email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => match ($state) {
                        OrderStatus::PENDING => 'En attente',
                        OrderStatus::AWAITING_PAYMENT => 'Attente paiement',
                        OrderStatus::PAID => 'Payé',
                        OrderStatus::SHIPPED => 'Expédié',
                        OrderStatus::DELIVERED => 'Livré',
                        OrderStatus::CANCELED => 'Annulé',
                        OrderStatus::FAILED => 'Échec',
                        default => $state->value ?? 'Inconnu',
                    })
                    ->color(fn ($state): string => match ($state) {
                        OrderStatus::PENDING => 'warning',
                        OrderStatus::AWAITING_PAYMENT => 'warning',
                        OrderStatus::PAID => 'success',
                        OrderStatus::SHIPPED => 'info',
                        OrderStatus::DELIVERED => 'success',
                        OrderStatus::CANCELED => 'danger',
                        OrderStatus::FAILED => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('total_cents')
                    ->label('Total')
                    ->formatStateUsing(fn ($state): string => number_format($state / 100, 2, ',', ' ') . ' €')
                    ->sortable(),
                TextColumn::make('payment_method')
                    ->label('Paiement')
                    ->formatStateUsing(fn ($state): string => $state ? ucfirst($state->value) : '—'),
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        OrderStatus::PENDING->value => 'En attente',
                        OrderStatus::AWAITING_PAYMENT->value => 'Attente paiement',
                        OrderStatus::PAID->value => 'Payé',
                        OrderStatus::SHIPPED->value => 'Expédié',
                        OrderStatus::DELIVERED->value => 'Livré',
                        OrderStatus::CANCELED->value => 'Annulé',
                        OrderStatus::FAILED->value => 'Échec',
                    ]),
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

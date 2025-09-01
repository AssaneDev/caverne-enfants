<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\OrderStatus;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('confirm_payment')
                ->label('Confirmer le paiement')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => $this->record->status === OrderStatus::AWAITING_PAYMENT)
                ->action(function () {
                    $this->record->update([
                        'status' => OrderStatus::PAID,
                        'paid_at' => now(),
                    ]);
                    
                    Notification::make()
                        ->title('Paiement confirmé')
                        ->body('La commande a été marquée comme payée.')
                        ->success()
                        ->send();
                        
                    $this->refreshFormData([
                        'status',
                    ]);
                }),
                
            Action::make('mark_preparing')
                ->label('Marquer en préparation')
                ->icon('heroicon-o-cog-6-tooth')
                ->color('warning')
                ->visible(fn () => $this->record->status === OrderStatus::PAID)
                ->action(function () {
                    $this->record->update([
                        'status' => OrderStatus::PREPARING,
                    ]);
                    
                    Notification::make()
                        ->title('Commande en préparation')
                        ->body('La commande a été marquée en préparation.')
                        ->success()
                        ->send();
                        
                    $this->refreshFormData([
                        'status',
                    ]);
                }),
                
            Action::make('mark_shipped')
                ->label('Marquer expédié')
                ->icon('heroicon-o-truck')
                ->color('info')
                ->visible(fn () => $this->record->status === OrderStatus::PREPARING)
                ->requiresConfirmation()
                ->form([
                    \Filament\Forms\Components\TextInput::make('tracking_carrier')
                        ->label('Transporteur')
                        ->placeholder('La Poste, Chronopost, UPS...')
                        ->required(),
                    \Filament\Forms\Components\TextInput::make('tracking_number')
                        ->label('Numéro de suivi')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $this->record->update([
                        'status' => OrderStatus::SHIPPED,
                        'tracking_carrier' => $data['tracking_carrier'],
                        'tracking_number' => $data['tracking_number'],
                        'shipped_at' => now(),
                    ]);
                    
                    Notification::make()
                        ->title('Commande expédiée')
                        ->body('La commande a été marquée comme expédiée.')
                        ->success()
                        ->send();
                        
                    $this->refreshFormData([
                        'status',
                        'tracking_carrier',
                        'tracking_number',
                        'shipped_at',
                    ]);
                }),
                
            Action::make('mark_delivered')
                ->label('Marquer livré')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->visible(fn () => $this->record->status === OrderStatus::SHIPPED)
                ->action(function () {
                    $this->record->update([
                        'status' => OrderStatus::DELIVERED,
                        'delivered_at' => now(),
                    ]);
                    
                    Notification::make()
                        ->title('Commande livrée')
                        ->body('La commande a été marquée comme livrée.')
                        ->success()
                        ->send();
                        
                    $this->refreshFormData([
                        'status',
                        'delivered_at',
                    ]);
                }),
                
            DeleteAction::make(),
        ];
    }
}

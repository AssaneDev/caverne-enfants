<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\OrderStatus;
use App\Services\OrderService;
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
                    $orderService = app(OrderService::class);
                    $emailSent = $orderService->markAsPaid($this->record);

                    if ($emailSent) {
                        Notification::make()
                            ->title('Paiement confirmé')
                            ->body('✅ La commande a été marquée comme payée et l\'email de confirmation a été envoyé avec succès.')
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Paiement confirmé')
                            ->body('⚠️ La commande a été marquée comme payée mais l\'email n\'a pas pu être envoyé (vérifiez l\'adresse email).')
                            ->warning()
                            ->send();
                    }

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
                    $orderService = app(OrderService::class);
                    $emailSent = $orderService->markAsPreparing($this->record);

                    if ($emailSent) {
                        Notification::make()
                            ->title('Commande en préparation')
                            ->body('✅ La commande a été marquée en préparation et l\'email a été envoyé avec succès.')
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Commande en préparation')
                            ->body('⚠️ La commande a été marquée en préparation mais l\'email n\'a pas pu être envoyé.')
                            ->warning()
                            ->send();
                    }

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
                    $orderService = app(OrderService::class);
                    $emailSent = $orderService->markAsShipped(
                        $this->record,
                        $data['tracking_number'],
                        $data['tracking_carrier']
                    );

                    if ($emailSent) {
                        Notification::make()
                            ->title('Commande expédiée')
                            ->body('✅ La commande a été marquée comme expédiée et l\'email avec le numéro de suivi a été envoyé avec succès.')
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Commande expédiée')
                            ->body('⚠️ La commande a été marquée comme expédiée mais l\'email n\'a pas pu être envoyé.')
                            ->warning()
                            ->send();
                    }

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

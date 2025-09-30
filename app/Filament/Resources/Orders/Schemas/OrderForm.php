<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\OrderStatus;
use App\PaymentMethod;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_number')
                    ->label('Numéro de commande')
                    ->disabled(),
                
                Select::make('status')
                    ->label('Statut')
                    ->options([
                        OrderStatus::PENDING->value => 'En attente',
                        OrderStatus::AWAITING_PAYMENT->value => 'Attente paiement',
                        OrderStatus::PAID->value => 'Payé',
                        OrderStatus::PREPARING->value => 'En préparation',
                        OrderStatus::SHIPPED->value => 'Expédié',
                        OrderStatus::DELIVERED->value => 'Livré',
                        OrderStatus::CANCELED->value => 'Annulé',
                        OrderStatus::FAILED->value => 'Échec',
                        OrderStatus::REFUNDED->value => 'Remboursé',
                    ])
                    ->required()
                    ->helperText('📧 Un email sera automatiquement envoyé au client lors du changement vers "Payé", "En préparation" ou "Expédié"'),

                Placeholder::make('email_notice')
                    ->label('📧 Notifications email automatiques')
                    ->content('
                        • **Payé** → Email de confirmation de paiement
                        • **En préparation** → Email de préparation
                        • **Expédié** → Email avec numéro de suivi
                    '),
                    
                Select::make('payment_method')
                    ->label('Méthode de paiement')
                    ->options([
                        PaymentMethod::STRIPE->value => 'Stripe',
                        PaymentMethod::PAYPAL->value => 'PayPal',
                    ]),
                    
                TextInput::make('payment_reference')
                    ->label('Référence de paiement')
                    ->disabled(),
                    
                TextInput::make('billing_name')
                    ->label('Nom de facturation')
                    ->disabled(),
                    
                TextInput::make('billing_email')
                    ->label('Email de facturation')
                    ->disabled(),
                    
                TextInput::make('billing_address')
                    ->label('Adresse de facturation')
                    ->disabled(),
                    
                TextInput::make('tracking_carrier')
                    ->label('Transporteur'),
                    
                TextInput::make('tracking_number')
                    ->label('Numéro de suivi'),
                    
                DateTimePicker::make('shipped_at')
                    ->label('Date d\'expédition'),
                    
                DateTimePicker::make('delivered_at')
                    ->label('Date de livraison'),
                    
                DateTimePicker::make('paid_at')
                    ->label('Date de paiement'),
            ]);
    }
}

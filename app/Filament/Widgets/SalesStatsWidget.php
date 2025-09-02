<?php

namespace App\Filament\Widgets;

use App\Models\Artwork;
use App\Models\Order;
use App\OrderStatus;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SalesStatsWidget extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '30s';
    
    protected function getStats(): array
    {
        $totalRevenue = Order::whereIn('status', [
                OrderStatus::PAID, 
                OrderStatus::PREPARING, 
                OrderStatus::SHIPPED, 
                OrderStatus::DELIVERED
            ])->sum('total_cents') / 100;
            
        $totalOrders = Order::whereIn('status', [
                OrderStatus::PAID, 
                OrderStatus::PREPARING, 
                OrderStatus::SHIPPED, 
                OrderStatus::DELIVERED
            ])->count();
        
        $pendingOrders = Order::where('status', OrderStatus::PENDING)->count();
        
        $totalArtworks = Artwork::where('status', 'published')->count();
        
        $soldArtworks = Artwork::where('status', 'sold')->count();
        
        $reservedArtworks = Artwork::where('status', 'reserved')->count();
        
        return [
            Stat::make('Chiffre d\'affaires', number_format($totalRevenue, 2) . ' €')
                ->description('Total des ventes validées')
                ->descriptionIcon('heroicon-m-currency-euro')
                ->color('success'),
                
            Stat::make('Commandes payées', $totalOrders)
                ->description('Commandes finalisées')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
                
            Stat::make('Commandes en attente', $pendingOrders)
                ->description('En cours de traitement')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
                
            Stat::make('Œuvres disponibles', $totalArtworks)
                ->description('Publiées sur le site')
                ->descriptionIcon('heroicon-m-photo')
                ->color('info'),
                
            Stat::make('Œuvres vendues', $soldArtworks)
                ->description('Définitivement vendues')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
                
            Stat::make('Œuvres réservées', $reservedArtworks)
                ->description('En cours de réservation')
                ->descriptionIcon('heroicon-m-lock-closed')
                ->color('warning'),
        ];
    }
}

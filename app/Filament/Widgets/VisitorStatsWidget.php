<?php

namespace App\Filament\Widgets;

use App\Models\Artwork;
use App\Models\Collection;
use App\Models\Order;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class VisitorStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalUsers = User::count();
        $totalCollections = Collection::count();
        $totalArtworks = Artwork::count();
        $avgOrderValue = Order::where('status', 'paid')->avg('total_cents') / 100;
        
        return [
            Stat::make('Utilisateurs inscrits', $totalUsers)
                ->description('Total des comptes créés')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
                
            Stat::make('Collections', $totalCollections)
                ->description('Collections d\'œuvres')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('info'),
                
            Stat::make('Total œuvres', $totalArtworks)
                ->description('Dans la base de données')
                ->descriptionIcon('heroicon-m-photo')
                ->color('info'),
                
            Stat::make('Panier moyen', number_format($avgOrderValue ?: 0, 2) . ' €')
                ->description('Valeur moyenne des commandes')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('success'),
        ];
    }
}
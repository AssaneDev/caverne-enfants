<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\OrderStatus;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class SalesChartWidget extends ChartWidget
{
    protected ?string $heading = 'Évolution des ventes (30 derniers jours)';
    
    protected int | string | array $columnSpan = 2;

    protected function getData(): array
    {
        $salesData = [];
        $labels = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dailySales = Order::where('status', OrderStatus::PAID)
                ->whereDate('created_at', $date)
                ->sum('total_cents') / 100;
                
            $salesData[] = $dailySales;
            $labels[] = $date->format('d/m');
        }
        
        return [
            'datasets' => [
                [
                    'label' => 'Ventes (€)',
                    'data' => $salesData,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

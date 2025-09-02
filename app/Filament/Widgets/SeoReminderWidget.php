<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class SeoReminderWidget extends Widget
{
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 10;
    
    protected function getViewData(): array
    {
        return [];
    }
    
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('filament.widgets.seo-reminder-widget', $this->getViewData());
    }
}
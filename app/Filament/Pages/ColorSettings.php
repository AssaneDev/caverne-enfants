<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use App\Services\ThemeService;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;

class ColorSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $title = 'Couleurs du Site';
    
    protected static ?string $slug = 'color-settings';
    
    protected static ?int $navigationSort = 100;

    public ?array $data = [];

    public static function getNavigationLabel(): string
    {
        return 'Couleurs';
    }

    protected function getViewData(): array
    {
        return [];
    }

    public function mount(): void
    {
        $colors = ThemeService::getColors();
        $this->form->fill([
            'primary_color' => $colors['primary'] ?? '#d97706',
            'secondary_color' => $colors['secondary'] ?? '#78716c',
            'accent_color' => $colors['accent'] ?? '#f59e0b',
            'background_color' => $colors['background'] ?? '#fafaf9',
            'text_primary_color' => $colors['text_primary'] ?? '#1c1917',
            'text_secondary_color' => $colors['text_secondary'] ?? '#57534e',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Couleurs Principales')
                    ->schema([
                        ColorPicker::make('primary_color')
                            ->label('Couleur Principale')
                            ->required(),
                        
                        ColorPicker::make('secondary_color')
                            ->label('Couleur Secondaire')
                            ->required(),
                        
                        ColorPicker::make('accent_color')
                            ->label('Couleur d\'Accent')
                            ->required(),
                    ])
                    ->columns(3),

                Section::make('Couleurs de Fond et Texte')
                    ->schema([
                        ColorPicker::make('background_color')
                            ->label('Fond Principal')
                            ->required(),
                        
                        ColorPicker::make('text_primary_color')
                            ->label('Texte Principal')
                            ->required(),
                        
                        ColorPicker::make('text_secondary_color')
                            ->label('Texte Secondaire')
                            ->required(),
                    ])
                    ->columns(3),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        SiteSetting::set('primary_color', $data['primary_color'], 'color', 'colors');
        SiteSetting::set('secondary_color', $data['secondary_color'], 'color', 'colors');
        SiteSetting::set('accent_color', $data['accent_color'], 'color', 'colors');
        SiteSetting::set('background_color', $data['background_color'], 'color', 'colors');
        SiteSetting::set('text_primary_color', $data['text_primary_color'], 'color', 'colors');
        SiteSetting::set('text_secondary_color', $data['text_secondary_color'], 'color', 'colors');

        SiteSetting::clearColorCache();

        Notification::make()
            ->title('Couleurs sauvegardées')
            ->success()
            ->send();
    }

    public function applyPreset($preset): void
    {
        ThemeService::applyColorPreset($preset);
        
        $colors = ThemeService::getColors();
        $this->form->fill([
            'primary_color' => $colors['primary'],
            'secondary_color' => $colors['secondary'],
            'accent_color' => $colors['accent'],
            'background_color' => $colors['background'],
            'text_primary_color' => $colors['text_primary'],
            'text_secondary_color' => $colors['text_secondary'],
        ]);

        Notification::make()
            ->title("Preset '{$preset}' appliqué")
            ->success()
            ->send();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('filament.pages.color-settings');
    }
}
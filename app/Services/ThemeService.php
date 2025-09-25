<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

class ThemeService
{
    /**
     * Récupère les couleurs du thème actuel
     */
    public static function getColors(): array
    {
        return SiteSetting::getColors();
    }

    /**
     * Génère le CSS dynamique pour les couleurs
     */
    public static function generateColorCSS(): string
    {
        $colors = self::getColors();
        
        return "
        :root {
            --color-primary: {$colors['primary']};
            --color-secondary: {$colors['secondary']};
            --color-accent: {$colors['accent']};
            --color-background: {$colors['background']};
            --color-text-primary: {$colors['text_primary']};
            --color-text-secondary: {$colors['text_secondary']};
            
            /* Variations automatiques */
            --color-primary-light: " . self::lightenColor($colors['primary'], 0.2) . ";
            --color-primary-dark: " . self::darkenColor($colors['primary'], 0.2) . ";
            --color-secondary-light: " . self::lightenColor($colors['secondary'], 0.2) . ";
            --color-secondary-dark: " . self::darkenColor($colors['secondary'], 0.2) . ";
        }
        
        /* Classes utilitaires pour les couleurs personnalisées */
        .text-theme-primary { color: var(--color-primary) !important; }
        .text-theme-secondary { color: var(--color-secondary) !important; }
        .text-theme-accent { color: var(--color-accent) !important; }
        .text-theme-text-primary { color: var(--color-text-primary) !important; }
        .text-theme-text-secondary { color: var(--color-text-secondary) !important; }
        
        .bg-theme-primary { background-color: var(--color-primary) !important; }
        .bg-theme-secondary { background-color: var(--color-secondary) !important; }
        .bg-theme-accent { background-color: var(--color-accent) !important; }
        .bg-theme-background { background-color: var(--color-background) !important; }
        
        .border-theme-primary { border-color: var(--color-primary) !important; }
        .border-theme-secondary { border-color: var(--color-secondary) !important; }
        
        /* Boutons avec couleurs dynamiques */
        .btn-theme-primary {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
            color: white;
        }
        .btn-theme-primary:hover {
            background-color: var(--color-primary-dark);
            border-color: var(--color-primary-dark);
        }
        
        .btn-theme-secondary {
            background-color: var(--color-secondary);
            border-color: var(--color-secondary);
            color: white;
        }
        .btn-theme-secondary:hover {
            background-color: var(--color-secondary-dark);
            border-color: var(--color-secondary-dark);
        }
        
        /* Remplacements Tailwind pour couleurs dynamiques */
        .dynamic-primary { color: var(--color-primary); }
        .dynamic-secondary { color: var(--color-secondary); }
        .dynamic-accent { color: var(--color-accent); }
        .dynamic-text-primary { color: var(--color-text-primary); }
        .dynamic-text-secondary { color: var(--color-text-secondary); }
        
        .dynamic-bg-primary { background-color: var(--color-primary); }
        .dynamic-bg-secondary { background-color: var(--color-secondary); }
        .dynamic-bg-accent { background-color: var(--color-accent); }
        .dynamic-bg-background { background-color: var(--color-background); }
        ";
    }

    /**
     * Génère les variables CSS Tailwind personnalisées
     */
    public static function getTailwindColorVariables(): array
    {
        $colors = self::getColors();
        
        return [
            'theme-primary' => $colors['primary'],
            'theme-secondary' => $colors['secondary'],
            'theme-accent' => $colors['accent'],
            'theme-background' => $colors['background'],
            'theme-text-primary' => $colors['text_primary'],
            'theme-text-secondary' => $colors['text_secondary'],
        ];
    }

    /**
     * Vérifie si les couleurs sont configurées
     */
    public static function hasCustomColors(): bool
    {
        return SiteSetting::where('group', 'colors')->exists();
    }

    /**
     * Récupère une couleur spécifique
     */
    public static function getColor(string $key): string
    {
        $colors = self::getColors();
        return $colors[$key] ?? '#000000';
    }

    /**
     * Sauvegarde un preset de couleurs
     */
    public static function applyColorPreset(string $presetName): void
    {
        $presets = self::getColorPresets();
        
        if (!isset($presets[$presetName])) {
            throw new \InvalidArgumentException("Preset '{$presetName}' non trouvé");
        }

        $colors = $presets[$presetName];
        
        foreach ($colors as $key => $value) {
            SiteSetting::set(
                $key . '_color',
                $value,
                'color',
                'colors',
                "Couleur {$key} du preset {$presetName}"
            );
        }

        SiteSetting::clearColorCache();
    }

    /**
     * Retourne les presets de couleurs disponibles
     */
    public static function getColorPresets(): array
    {
        return [
            'default' => [
                'primary' => '#d97706', // amber-600
                'secondary' => '#78716c', // stone-500
                'accent' => '#f59e0b', // amber-500
                'background' => '#fafaf9', // stone-50
                'text_primary' => '#1c1917', // stone-900
                'text_secondary' => '#57534e', // stone-600
            ],
            'blue_elegance' => [
                'primary' => '#2563eb', // blue-600
                'secondary' => '#64748b', // slate-500
                'accent' => '#3b82f6', // blue-500
                'background' => '#f8fafc', // slate-50
                'text_primary' => '#0f172a', // slate-900
                'text_secondary' => '#475569', // slate-600
            ],
            'green_nature' => [
                'primary' => '#059669', // emerald-600
                'secondary' => '#6b7280', // gray-500
                'accent' => '#10b981', // emerald-500
                'background' => '#f9fafb', // gray-50
                'text_primary' => '#111827', // gray-900
                'text_secondary' => '#4b5563', // gray-600
            ],
            'purple_luxury' => [
                'primary' => '#9333ea', // purple-600
                'secondary' => '#71717a', // zinc-500
                'accent' => '#a855f7', // purple-500
                'background' => '#fafafa', // zinc-50
                'text_primary' => '#18181b', // zinc-900
                'text_secondary' => '#52525b', // zinc-600
            ],
            'red_passion' => [
                'primary' => '#dc2626', // red-600
                'secondary' => '#6b7280', // gray-500
                'accent' => '#ef4444', // red-500
                'background' => '#f9fafb', // gray-50
                'text_primary' => '#111827', // gray-900
                'text_secondary' => '#4b5563', // gray-600
            ],
        ];
    }

    /**
     * Éclaircit une couleur hexadécimale
     */
    private static function lightenColor(string $hex, float $percent): string
    {
        $hex = str_replace('#', '', $hex);
        
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        $r = min(255, $r + ($r * $percent));
        $g = min(255, $g + ($g * $percent));
        $b = min(255, $b + ($b * $percent));
        
        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }

    /**
     * Assombrit une couleur hexadécimale
     */
    private static function darkenColor(string $hex, float $percent): string
    {
        $hex = str_replace('#', '', $hex);
        
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        $r = max(0, $r - ($r * $percent));
        $g = max(0, $g - ($g * $percent));
        $b = max(0, $b - ($b * $percent));
        
        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }

    /**
     * Valide qu'une couleur hexadécimale est valide
     */
    public static function isValidHexColor(string $hex): bool
    {
        return preg_match('/^#([0-9a-f]{3}|[0-9a-f]{6})$/i', $hex);
    }

    /**
     * Convertit une couleur hex en RGB
     */
    public static function hexToRgb(string $hex): array
    {
        $hex = str_replace('#', '', $hex);
        
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        
        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2)),
        ];
    }
}
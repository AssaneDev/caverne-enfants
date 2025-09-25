<?php

namespace App\Http\Controllers;

use App\Services\ThemeService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class ThemeController extends Controller
{
    /**
     * Génère et sert le CSS dynamique des couleurs
     */
    public function colors()
    {
        $css = Cache::remember('dynamic_colors_css', 3600, function () {
            return ThemeService::generateColorCSS();
        });

        return response($css, 200)
            ->header('Content-Type', 'text/css')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    /**
     * API pour récupérer les couleurs actuelles (pour JS)
     */
    public function colorsJson()
    {
        $colors = ThemeService::getColors();
        
        return response()->json($colors)
            ->header('Cache-Control', 'public, max-age=3600');
    }

    /**
     * Prévisualisation des couleurs (pour l'admin)
     */
    public function preview()
    {
        $colors = ThemeService::getColors();
        $presets = ThemeService::getColorPresets();
        
        return view('theme.preview', compact('colors', 'presets'));
    }
}
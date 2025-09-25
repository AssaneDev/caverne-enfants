<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use App\Services\ThemeService;
use Illuminate\Http\Request;

class ColorManagementController extends Controller
{
    public function index()
    {
        $colors = ThemeService::getColors();
        $presets = ThemeService::getColorPresets();
        
        return view('color-management.index', compact('colors', 'presets'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'primary_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'secondary_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'accent_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'background_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'text_primary_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'text_secondary_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        SiteSetting::set('primary_color', $request->primary_color, 'color', 'colors');
        SiteSetting::set('secondary_color', $request->secondary_color, 'color', 'colors');
        SiteSetting::set('accent_color', $request->accent_color, 'color', 'colors');
        SiteSetting::set('background_color', $request->background_color, 'color', 'colors');
        SiteSetting::set('text_primary_color', $request->text_primary_color, 'color', 'colors');
        SiteSetting::set('text_secondary_color', $request->text_secondary_color, 'color', 'colors');

        SiteSetting::clearColorCache();

        return redirect()->route('color-management')
            ->with('success', 'Couleurs mises à jour avec succès !');
    }

    public function applyPreset(Request $request)
    {
        $request->validate([
            'preset' => 'required|in:default,blue_elegance,green_nature,purple_luxury,red_passion'
        ]);

        ThemeService::applyColorPreset($request->preset);

        return redirect()->route('color-management')
            ->with('success', "Preset '{$request->preset}' appliqué avec succès !");
    }
}
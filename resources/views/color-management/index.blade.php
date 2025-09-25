<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>🎨 Gestion des Couleurs - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Couleurs dynamiques du thème -->
    <link rel="stylesheet" href="{{ route('theme.colors') }}" id="dynamic-theme-colors">
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            🎨 Gestion des Couleurs
                        </h1>
                        <p class="text-sm text-gray-600">
                            Personnalisez les couleurs de votre site web
                        </p>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('home') }}" 
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            🌐 Voir le site
                        </a>
                        @if(Route::has('admin'))
                        <a href="{{ route('admin') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            👤 Admin
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if(session('success'))
                <div class="p-4 mb-6 text-green-800 bg-green-100 border border-green-200 rounded-lg">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Formulaire des couleurs -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Configuration des Couleurs</h2>
                        
                        <form action="{{ route('color-management.update') }}" method="POST" class="space-y-8">
                            @csrf
                            @method('PATCH')

                            <!-- Couleurs Principales -->
                            <div>
                                <h3 class="text-md font-medium text-gray-900 mb-4">Couleurs Principales</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-2">
                                            Couleur Principale
                                        </label>
                                        <div class="flex items-center space-x-3">
                                            <input type="color" 
                                                   id="primary_color" 
                                                   name="primary_color" 
                                                   value="{{ $colors['primary'] }}"
                                                   class="h-12 w-16 border border-gray-300 rounded-lg cursor-pointer">
                                            <input type="text" 
                                                   value="{{ $colors['primary'] }}" 
                                                   class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono"
                                                   readonly
                                                   id="primary_color_text">
                                        </div>
                                    </div>

                                    <div>
                                        <label for="secondary_color" class="block text-sm font-medium text-gray-700 mb-2">
                                            Couleur Secondaire
                                        </label>
                                        <div class="flex items-center space-x-3">
                                            <input type="color" 
                                                   id="secondary_color" 
                                                   name="secondary_color" 
                                                   value="{{ $colors['secondary'] }}"
                                                   class="h-12 w-16 border border-gray-300 rounded-lg cursor-pointer">
                                            <input type="text" 
                                                   value="{{ $colors['secondary'] }}" 
                                                   class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono"
                                                   readonly
                                                   id="secondary_color_text">
                                        </div>
                                    </div>

                                    <div>
                                        <label for="accent_color" class="block text-sm font-medium text-gray-700 mb-2">
                                            Couleur d'Accent
                                        </label>
                                        <div class="flex items-center space-x-3">
                                            <input type="color" 
                                                   id="accent_color" 
                                                   name="accent_color" 
                                                   value="{{ $colors['accent'] }}"
                                                   class="h-12 w-16 border border-gray-300 rounded-lg cursor-pointer">
                                            <input type="text" 
                                                   value="{{ $colors['accent'] }}" 
                                                   class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono"
                                                   readonly
                                                   id="accent_color_text">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Couleurs de Fond et Texte -->
                            <div>
                                <h3 class="text-md font-medium text-gray-900 mb-4">Couleurs de Fond et Texte</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label for="background_color" class="block text-sm font-medium text-gray-700 mb-2">
                                            Fond Principal
                                        </label>
                                        <div class="flex items-center space-x-3">
                                            <input type="color" 
                                                   id="background_color" 
                                                   name="background_color" 
                                                   value="{{ $colors['background'] }}"
                                                   class="h-12 w-16 border border-gray-300 rounded-lg cursor-pointer">
                                            <input type="text" 
                                                   value="{{ $colors['background'] }}" 
                                                   class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono"
                                                   readonly
                                                   id="background_color_text">
                                        </div>
                                    </div>

                                    <div>
                                        <label for="text_primary_color" class="block text-sm font-medium text-gray-700 mb-2">
                                            Texte Principal
                                        </label>
                                        <div class="flex items-center space-x-3">
                                            <input type="color" 
                                                   id="text_primary_color" 
                                                   name="text_primary_color" 
                                                   value="{{ $colors['text_primary'] }}"
                                                   class="h-12 w-16 border border-gray-300 rounded-lg cursor-pointer">
                                            <input type="text" 
                                                   value="{{ $colors['text_primary'] }}" 
                                                   class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono"
                                                   readonly
                                                   id="text_primary_color_text">
                                        </div>
                                    </div>

                                    <div>
                                        <label for="text_secondary_color" class="block text-sm font-medium text-gray-700 mb-2">
                                            Texte Secondaire
                                        </label>
                                        <div class="flex items-center space-x-3">
                                            <input type="color" 
                                                   id="text_secondary_color" 
                                                   name="text_secondary_color" 
                                                   value="{{ $colors['text_secondary'] }}"
                                                   class="h-12 w-16 border border-gray-300 rounded-lg cursor-pointer">
                                            <input type="text" 
                                                   value="{{ $colors['text_secondary'] }}" 
                                                   class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono"
                                                   readonly
                                                   id="text_secondary_color_text">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bouton de sauvegarde -->
                            <div class="flex justify-end pt-4">
                                <button type="submit" 
                                        class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    💾 Sauvegarder les couleurs
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Presets et Prévisualisation -->
                <div class="space-y-6">
                    <!-- Presets -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Presets Rapides</h2>
                        <div class="space-y-4">
                            @foreach($presets as $presetName => $preset)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex space-x-2">
                                            <div class="w-5 h-5 rounded" style="background-color: {{ $preset['primary'] }};"></div>
                                            <div class="w-5 h-5 rounded" style="background-color: {{ $preset['secondary'] }};"></div>
                                            <div class="w-5 h-5 rounded" style="background-color: {{ $preset['accent'] }};"></div>
                                        </div>
                                        <span class="font-medium text-sm">{{ $preset['name'] }}</span>
                                    </div>
                                    <p class="text-xs text-gray-600 mb-3">{{ $preset['description'] }}</p>
                                    <form action="{{ route('color-management.preset') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="preset" value="{{ $presetName }}">
                                        <button type="submit" 
                                                class="w-full px-3 py-2 text-sm font-medium text-white rounded-lg transition-colors"
                                                style="background-color: {{ $preset['primary'] }};">
                                            Appliquer
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Prévisualisation -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Prévisualisation</h2>
                        <div id="preview" class="space-y-4">
                            <div class="p-4 rounded-lg" style="background-color: var(--color-background);">
                                <h3 style="color: var(--color-text-primary);" class="font-semibold mb-2">Titre Principal</h3>
                                <p style="color: var(--color-text-secondary);" class="text-sm mb-3">Texte secondaire pour la description</p>
                                <div class="flex space-x-2">
                                    <button style="background-color: var(--color-primary);" class="px-4 py-2 text-white rounded-lg text-sm">
                                        Bouton Principal
                                    </button>
                                    <button style="background-color: var(--color-accent);" class="px-4 py-2 text-white rounded-lg text-sm">
                                        Bouton Accent
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Synchronisation des valeurs des color pickers avec les champs texte
    document.addEventListener('DOMContentLoaded', function() {
        const colorInputs = document.querySelectorAll('input[type="color"]');
        
        colorInputs.forEach(colorInput => {
            const textInput = colorInput.nextElementSibling;
            
            colorInput.addEventListener('input', function() {
                textInput.value = this.value;
            });
        });
    });
    </script>
</body>
</html>
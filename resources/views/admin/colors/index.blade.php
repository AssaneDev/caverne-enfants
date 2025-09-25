@extends('layouts.admin')

@section('title', 'Couleurs du Site')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">
                🎨 Couleurs du Site
            </h1>
            <p class="mt-2 text-sm text-gray-600">
                Personnalisez les couleurs principales de votre site web.
            </p>
        </div>

        @if(session('success'))
            <div class="p-4 mb-6 text-green-800 bg-green-100 border border-green-200 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="p-6">
            <!-- Formulaire des couleurs -->
            <form action="{{ route('admin.colors.update') }}" method="POST" class="space-y-8">
                @csrf
                @method('PATCH')

                <!-- Couleurs Principales -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Couleurs Principales</h2>
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
                                       class="h-12 w-20 border border-gray-300 rounded-lg cursor-pointer">
                                <input type="text" 
                                       value="{{ $colors['primary'] }}" 
                                       class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono"
                                       readonly>
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
                                       class="h-12 w-20 border border-gray-300 rounded-lg cursor-pointer">
                                <input type="text" 
                                       value="{{ $colors['secondary'] }}" 
                                       class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono"
                                       readonly>
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
                                       class="h-12 w-20 border border-gray-300 rounded-lg cursor-pointer">
                                <input type="text" 
                                       value="{{ $colors['accent'] }}" 
                                       class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono"
                                       readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Couleurs de Fond et Texte -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Couleurs de Fond et Texte</h2>
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
                                       class="h-12 w-20 border border-gray-300 rounded-lg cursor-pointer">
                                <input type="text" 
                                       value="{{ $colors['background'] }}" 
                                       class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono"
                                       readonly>
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
                                       class="h-12 w-20 border border-gray-300 rounded-lg cursor-pointer">
                                <input type="text" 
                                       value="{{ $colors['text_primary'] }}" 
                                       class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono"
                                       readonly>
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
                                       class="h-12 w-20 border border-gray-300 rounded-lg cursor-pointer">
                                <input type="text" 
                                       value="{{ $colors['text_secondary'] }}" 
                                       class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono"
                                       readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bouton de sauvegarde -->
                <div class="flex justify-end">
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        💾 Sauvegarder les couleurs
                    </button>
                </div>
            </form>

            <!-- Presets -->
            <div class="mt-12 pt-8 border-t border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Presets de Couleurs</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($presets as $presetName => $preset)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex space-x-2">
                                    <div class="w-6 h-6 rounded" style="background-color: {{ $preset['primary'] }};"></div>
                                    <div class="w-6 h-6 rounded" style="background-color: {{ $preset['secondary'] }};"></div>
                                    <div class="w-6 h-6 rounded" style="background-color: {{ $preset['accent'] }};"></div>
                                </div>
                                <h3 class="font-semibold text-sm">{{ $preset['name'] }}</h3>
                            </div>
                            <p class="text-sm text-gray-600 mb-3">{{ $preset['description'] }}</p>
                            <form action="{{ route('admin.colors.preset') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="preset" value="{{ $presetName }}">
                                <button type="submit" 
                                        class="w-full px-4 py-2 text-sm font-medium text-white rounded-lg transition-colors"
                                        style="background-color: {{ $preset['primary'] }};">
                                    Appliquer
                                </button>
                            </form>
                        </div>
                    @endforeach
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
@endsection
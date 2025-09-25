<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Prévisualisation en temps réel -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Prévisualisation</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Exemple de carte d'œuvre -->
                <div class="border rounded-lg overflow-hidden shadow-sm">
                    <div class="aspect-square bg-gray-100 relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-gray-300 to-gray-400"></div>
                        <span class="absolute top-4 right-4 px-3 py-1 rounded-full text-sm font-semibold text-white"
                              style="background-color: {{ $this->primary }};">
                            Unique
                        </span>
                    </div>
                    <div class="p-4">
                        <h4 class="font-semibold mb-2" style="color: {{ $this->text_primary }};">
                            Exemple d'Œuvre
                        </h4>
                        <p class="text-sm mb-3" style="color: {{ $this->text_secondary }};">
                            par Artiste Exemple
                        </p>
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold" style="color: {{ $this->primary }};">
                                1,200 €
                            </span>
                            <button class="px-4 py-2 rounded-lg text-white font-medium transition-colors"
                                    style="background-color: {{ $this->secondary }};">
                                Voir
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Exemple de section -->
                <div class="p-6 rounded-lg" style="background-color: {{ $this->background }};">
                    <h4 class="text-xl font-bold mb-3" style="color: {{ $this->text_primary }};">
                        Section Exemple
                    </h4>
                    <p class="mb-4" style="color: {{ $this->text_secondary }};">
                        Ceci est un exemple de texte secondaire qui montre comment les couleurs s'harmonisent sur votre site.
                    </p>
                    <button class="px-6 py-3 rounded-lg text-white font-semibold transition-colors"
                            style="background-color: {{ $this->accent }};">
                        Bouton d'Accent
                    </button>
                </div>
            </div>
        </div>

        <!-- Palette de couleurs -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Palette Actuelle</h3>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="text-center">
                    <div class="w-16 h-16 rounded-lg mx-auto mb-2 border-2 border-gray-200"
                         style="background-color: {{ $this->primary }};"></div>
                    <p class="text-sm font-medium text-gray-700">Principale</p>
                    <p class="text-xs text-gray-500">{{ $this->primary }}</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 rounded-lg mx-auto mb-2 border-2 border-gray-200"
                         style="background-color: {{ $this->secondary }};"></div>
                    <p class="text-sm font-medium text-gray-700">Secondaire</p>
                    <p class="text-xs text-gray-500">{{ $this->secondary }}</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 rounded-lg mx-auto mb-2 border-2 border-gray-200"
                         style="background-color: {{ $this->accent }};"></div>
                    <p class="text-sm font-medium text-gray-700">Accent</p>
                    <p class="text-xs text-gray-500">{{ $this->accent }}</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 rounded-lg mx-auto mb-2 border-2 border-gray-200"
                         style="background-color: {{ $this->background }};"></div>
                    <p class="text-sm font-medium text-gray-700">Fond</p>
                    <p class="text-xs text-gray-500">{{ $this->background }}</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 rounded-lg mx-auto mb-2 border-2 border-gray-200"
                         style="background-color: {{ $this->text_primary }};"></div>
                    <p class="text-sm font-medium text-gray-700">Texte 1</p>
                    <p class="text-xs text-gray-500">{{ $this->text_primary }}</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 rounded-lg mx-auto mb-2 border-2 border-gray-200"
                         style="background-color: {{ $this->text_secondary }};"></div>
                    <p class="text-sm font-medium text-gray-700">Texte 2</p>
                    <p class="text-xs text-gray-500">{{ $this->text_secondary }}</p>
                </div>
            </div>
        </div>

        <!-- Formulaire de configuration -->
        {{ $this->form }}
    </div>
</x-filament-panels::page>
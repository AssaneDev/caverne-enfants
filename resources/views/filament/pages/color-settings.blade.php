<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Formulaire des couleurs -->
        <form wire:submit="save" class="space-y-6">
            {{ $this->form }}
            
            <div class="flex gap-4">
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Sauvegarder
                </button>
            </div>
        </form>

        <!-- Section Presets -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Presets Disponibles</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Default Preset -->
                <div class="p-4 border rounded-lg">
                    <div class="flex items-center space-x-2 mb-2">
                        <div class="flex space-x-1">
                            <div class="w-4 h-4 rounded" style="background-color: #d97706;"></div>
                            <div class="w-4 h-4 rounded" style="background-color: #78716c;"></div>
                            <div class="w-4 h-4 rounded" style="background-color: #f59e0b;"></div>
                        </div>
                        <span class="font-semibold text-sm">Default (Ambré)</span>
                    </div>
                    <button type="button" wire:click="applyPreset('default')"
                            class="w-full px-3 py-2 text-sm bg-amber-100 text-amber-800 rounded hover:bg-amber-200 transition-colors">
                        Appliquer
                    </button>
                </div>

                <!-- Blue Elegance -->
                <div class="p-4 border rounded-lg">
                    <div class="flex items-center space-x-2 mb-2">
                        <div class="flex space-x-1">
                            <div class="w-4 h-4 rounded" style="background-color: #2563eb;"></div>
                            <div class="w-4 h-4 rounded" style="background-color: #64748b;"></div>
                            <div class="w-4 h-4 rounded" style="background-color: #3b82f6;"></div>
                        </div>
                        <span class="font-semibold text-sm">Élégance Bleue</span>
                    </div>
                    <button type="button" wire:click="applyPreset('blue_elegance')"
                            class="w-full px-3 py-2 text-sm bg-blue-100 text-blue-800 rounded hover:bg-blue-200 transition-colors">
                        Appliquer
                    </button>
                </div>

                <!-- Green Nature -->
                <div class="p-4 border rounded-lg">
                    <div class="flex items-center space-x-2 mb-2">
                        <div class="flex space-x-1">
                            <div class="w-4 h-4 rounded" style="background-color: #059669;"></div>
                            <div class="w-4 h-4 rounded" style="background-color: #6b7280;"></div>
                            <div class="w-4 h-4 rounded" style="background-color: #10b981;"></div>
                        </div>
                        <span class="font-semibold text-sm">Nature Verte</span>
                    </div>
                    <button type="button" wire:click="applyPreset('green_nature')"
                            class="w-full px-3 py-2 text-sm bg-green-100 text-green-800 rounded hover:bg-green-200 transition-colors">
                        Appliquer
                    </button>
                </div>

                <!-- Purple Luxury -->
                <div class="p-4 border rounded-lg">
                    <div class="flex items-center space-x-2 mb-2">
                        <div class="flex space-x-1">
                            <div class="w-4 h-4 rounded" style="background-color: #9333ea;"></div>
                            <div class="w-4 h-4 rounded" style="background-color: #71717a;"></div>
                            <div class="w-4 h-4 rounded" style="background-color: #a855f7;"></div>
                        </div>
                        <span class="font-semibold text-sm">Luxe Violet</span>
                    </div>
                    <button type="button" wire:click="applyPreset('purple_luxury')"
                            class="w-full px-3 py-2 text-sm bg-purple-100 text-purple-800 rounded hover:bg-purple-200 transition-colors">
                        Appliquer
                    </button>
                </div>

                <!-- Red Passion -->
                <div class="p-4 border rounded-lg">
                    <div class="flex items-center space-x-2 mb-2">
                        <div class="flex space-x-1">
                            <div class="w-4 h-4 rounded" style="background-color: #dc2626;"></div>
                            <div class="w-4 h-4 rounded" style="background-color: #6b7280;"></div>
                            <div class="w-4 h-4 rounded" style="background-color: #ef4444;"></div>
                        </div>
                        <span class="font-semibold text-sm">Passion Rouge</span>
                    </div>
                    <button type="button" wire:click="applyPreset('red_passion')"
                            class="w-full px-3 py-2 text-sm bg-red-100 text-red-800 rounded hover:bg-red-200 transition-colors">
                        Appliquer
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>

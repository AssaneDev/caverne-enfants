<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <!-- Default Preset -->
    <div class="p-4 border rounded-lg bg-white">
        <div class="flex items-center space-x-2 mb-2">
            <div class="flex space-x-1">
                <div class="w-4 h-4 rounded" style="background-color: #d97706;"></div>
                <div class="w-4 h-4 rounded" style="background-color: #78716c;"></div>
                <div class="w-4 h-4 rounded" style="background-color: #f59e0b;"></div>
            </div>
            <h3 class="font-semibold">Default (Ambré)</h3>
        </div>
        <p class="text-sm text-gray-600 mb-3">Couleurs chaudes et accueillantes</p>
        <button 
            type="button"
            wire:click="applyPreset('default')"
            class="w-full px-3 py-2 text-sm bg-amber-100 text-amber-800 rounded hover:bg-amber-200 transition-colors"
        >
            Appliquer
        </button>
    </div>

    <!-- Blue Elegance -->
    <div class="p-4 border rounded-lg bg-white">
        <div class="flex items-center space-x-2 mb-2">
            <div class="flex space-x-1">
                <div class="w-4 h-4 rounded" style="background-color: #2563eb;"></div>
                <div class="w-4 h-4 rounded" style="background-color: #64748b;"></div>
                <div class="w-4 h-4 rounded" style="background-color: #3b82f6;"></div>
            </div>
            <h3 class="font-semibold">Élégance Bleue</h3>
        </div>
        <p class="text-sm text-gray-600 mb-3">Professionnel et moderne</p>
        <button 
            type="button"
            wire:click="applyPreset('blue_elegance')"
            class="w-full px-3 py-2 text-sm bg-blue-100 text-blue-800 rounded hover:bg-blue-200 transition-colors"
        >
            Appliquer
        </button>
    </div>

    <!-- Green Nature -->
    <div class="p-4 border rounded-lg bg-white">
        <div class="flex items-center space-x-2 mb-2">
            <div class="flex space-x-1">
                <div class="w-4 h-4 rounded" style="background-color: #059669;"></div>
                <div class="w-4 h-4 rounded" style="background-color: #6b7280;"></div>
                <div class="w-4 h-4 rounded" style="background-color: #10b981;"></div>
            </div>
            <h3 class="font-semibold">Nature Verte</h3>
        </div>
        <p class="text-sm text-gray-600 mb-3">Naturel et apaisant</p>
        <button 
            type="button"
            wire:click="applyPreset('green_nature')"
            class="w-full px-3 py-2 text-sm bg-green-100 text-green-800 rounded hover:bg-green-200 transition-colors"
        >
            Appliquer
        </button>
    </div>

    <!-- Purple Luxury -->
    <div class="p-4 border rounded-lg bg-white">
        <div class="flex items-center space-x-2 mb-2">
            <div class="flex space-x-1">
                <div class="w-4 h-4 rounded" style="background-color: #9333ea;"></div>
                <div class="w-4 h-4 rounded" style="background-color: #71717a;"></div>
                <div class="w-4 h-4 rounded" style="background-color: #a855f7;"></div>
            </div>
            <h3 class="font-semibold">Luxe Violet</h3>
        </div>
        <p class="text-sm text-gray-600 mb-3">Sophistiqué et créatif</p>
        <button 
            type="button"
            wire:click="applyPreset('purple_luxury')"
            class="w-full px-3 py-2 text-sm bg-purple-100 text-purple-800 rounded hover:bg-purple-200 transition-colors"
        >
            Appliquer
        </button>
    </div>

    <!-- Red Passion -->
    <div class="p-4 border rounded-lg bg-white">
        <div class="flex items-center space-x-2 mb-2">
            <div class="flex space-x-1">
                <div class="w-4 h-4 rounded" style="background-color: #dc2626;"></div>
                <div class="w-4 h-4 rounded" style="background-color: #6b7280;"></div>
                <div class="w-4 h-4 rounded" style="background-color: #ef4444;"></div>
            </div>
            <h3 class="font-semibold">Passion Rouge</h3>
        </div>
        <p class="text-sm text-gray-600 mb-3">Dynamique et énergique</p>
        <button 
            type="button"
            wire:click="applyPreset('red_passion')"
            class="w-full px-3 py-2 text-sm bg-red-100 text-red-800 rounded hover:bg-red-200 transition-colors"
        >
            Appliquer
        </button>
    </div>
</div>
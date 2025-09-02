<x-layouts.app 
    :metaTitle="$collection->getTranslation('name', app()->getLocale()) . ' - Caverne des Enfants'"
    :metaDescription="$collection->getTranslation('description', app()->getLocale())">
    
    @if($collection->banner_image)
        <div class="w-full relative overflow-hidden mb-12" style="aspect-ratio: 21/9;">
            <img src="{{ asset('storage/' . $collection->banner_image) }}" 
                 alt="{{ $collection->getTranslation('name', app()->getLocale()) }}"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                <div class="text-center">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4">
                        {{ $collection->getTranslation('name', app()->getLocale()) }}
                    </h1>
                    @if($collection->history)
                        <button onclick="openHistoryModal()" 
                                class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                            Voir l'histoire de la collection
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if(!$collection->banner_image)
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-stone-900 mb-4">
                    {{ $collection->getTranslation('name', app()->getLocale()) }}
                </h1>
                
                @if($collection->getTranslation('description', app()->getLocale()))
                    <p class="text-xl text-stone-600 max-w-3xl mx-auto mb-6">
                        {{ $collection->getTranslation('description', app()->getLocale()) }}
                    </p>
                @endif
                
                @if($collection->history)
                    <button onclick="openHistoryModal()" 
                            class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                        Voir l'histoire de la collection
                    </button>
                @endif
            </div>
        @else
            @if($collection->getTranslation('description', app()->getLocale()))
                <div class="text-center mb-12">
                    <p class="text-xl text-stone-600 max-w-3xl mx-auto">
                        {{ $collection->getTranslation('description', app()->getLocale()) }}
                    </p>
                </div>
            @endif
        @endif
        
        @if($collection->artworks->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($collection->artworks as $artwork)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="aspect-square bg-stone-100 relative">
                            @if($artwork->image_path)
                                <img src="{{ asset('storage/' . $artwork->image_path) }}" 
                                     alt="{{ $artwork->getTranslation('title', app()->getLocale()) }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-stone-400">
                                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @endif
                            <span class="absolute top-4 right-4 bg-amber-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                Unique
                            </span>
                        </div>
                        
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-stone-900 mb-2">
                                {{ $artwork->getTranslation('title', app()->getLocale()) }}
                            </h3>
                            
                            @if($artwork->artist)
                                <p class="text-stone-600 mb-2">par {{ $artwork->artist->name }}</p>
                            @endif
                            
                            @if($artwork->year)
                                <p class="text-sm text-stone-500 mb-3">{{ $artwork->year }}</p>
                            @endif
                            
                            <div class="flex justify-between items-center">
                                <span class="text-2xl font-bold text-amber-600">
                                    {{ number_format($artwork->price, 0, ',', ' ') }} €
                                </span>
                                
                                <a href="{{ route('artworks.show', $artwork) }}" 
                                   class="bg-stone-900 text-white px-6 py-2 rounded-lg hover:bg-stone-800 transition-colors">
                                    Voir l'œuvre
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-xl text-stone-600">Aucune œuvre disponible dans cette collection pour le moment.</p>
            </div>
        @endif
    </div>

    @if($collection->history)
        <!-- Modal pour l'historique -->
        <div id="historyModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
            <div class="bg-white rounded-lg w-full max-w-4xl shadow-2xl" style="height: 600px;">
                <!-- Header -->
                <div class="bg-amber-600 text-white p-4 rounded-t-lg flex justify-between items-center">
                    <h2 class="text-xl font-bold">Histoire de {{ $collection->getTranslation('name', app()->getLocale()) }}</h2>
                    <button onclick="closeHistoryModal()" class="text-white hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Contenu avec scroll -->
                <div class="p-8" style="height: 500px; overflow-y: scroll; overflow-x: hidden;">
                    <div class="text-gray-700 leading-relaxed space-y-4">
                        {!! $collection->history !!}
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="border-t p-4 text-center bg-gray-50 rounded-b-lg">
                    <button onclick="closeHistoryModal()" 
                            class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-2 rounded transition-colors">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
        
        <style>
            @keyframes fade-in {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            
            @keyframes scale-in {
                from { 
                    opacity: 0;
                    transform: scale(0.9) translateY(20px);
                }
                to { 
                    opacity: 1;
                    transform: scale(1) translateY(0);
                }
            }
            
            @keyframes slide-up {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            .animate-fade-in {
                animation: fade-in 0.3s ease-out;
            }
            
            .animate-scale-in {
                animation: scale-in 0.3s ease-out;
            }
            
            .animate-slide-up {
                animation: slide-up 0.5s ease-out 0.2s both;
            }
            
        </style>

        <script>
            function openHistoryModal() {
                const modal = document.getElementById('historyModal');
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeHistoryModal() {
                const modal = document.getElementById('historyModal');
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            // Fermer le modal en cliquant en dehors
            document.getElementById('historyModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeHistoryModal();
                }
            });

            // Fermer le modal avec la touche Échap
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeHistoryModal();
                }
            });
        </script>
    @endif
</x-layouts.app>
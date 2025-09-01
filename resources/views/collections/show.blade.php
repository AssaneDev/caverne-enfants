<x-layouts.app 
    :metaTitle="$collection->getTranslation('name', app()->getLocale()) . ' - Caverne des Enfants'"
    :metaDescription="$collection->getTranslation('description', app()->getLocale())">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-stone-900 mb-4">
                {{ $collection->getTranslation('name', app()->getLocale()) }}
            </h1>
            
            @if($collection->getTranslation('description', app()->getLocale()))
                <p class="text-xl text-stone-600 max-w-3xl mx-auto">
                    {{ $collection->getTranslation('description', app()->getLocale()) }}
                </p>
            @endif
        </div>
        
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
</x-layouts.app>
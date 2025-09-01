<x-layouts.app>
    <section class="bg-gradient-to-r from-amber-50 to-stone-100 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-stone-900 mb-6">
                Caverne des Enfants
            </h1>
            <p class="text-xl text-stone-600 max-w-3xl mx-auto mb-8">
                Découvrez des œuvres d'art uniques créées par des artistes passionnés
            </p>
            <a href="{{ route('collections.index') }}" 
               class="inline-block bg-amber-600 text-white px-8 py-4 rounded-lg hover:bg-amber-700 transition-colors text-lg font-semibold">
                Découvrir les collections
            </a>
        </div>
    </section>

    @if($onHomeArtworks->count() > 0)
        <section class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-stone-900 mb-8 text-center">Œuvres à la une</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($onHomeArtworks as $artwork)
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
                                    <p class="text-stone-600 mb-3">par {{ $artwork->artist->name }}</p>
                                @endif
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-2xl font-bold text-amber-600">
                                        {{ number_format($artwork->price, 0, ',', ' ') }} €
                                    </span>
                                    
                                    <a href="{{ route('artworks.show', $artwork) }}" 
                                       class="bg-stone-900 text-white px-6 py-2 rounded-lg hover:bg-stone-800 transition-colors">
                                        Voir
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if($featuredCollections->count() > 0)
        <section class="bg-stone-100 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-stone-900 mb-8 text-center">Collections en vedette</h2>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    @foreach($featuredCollections as $collection)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="h-48 bg-stone-200 relative overflow-hidden">
                                @if($collection->artworks->first() && $collection->artworks->first()->image_path)
                                    <img src="{{ asset('storage/' . $collection->artworks->first()->image_path) }}" 
                                         alt="{{ $collection->getTranslation('name', app()->getLocale()) }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-stone-400">
                                        <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-stone-900 mb-2">
                                    {{ $collection->getTranslation('name', app()->getLocale()) }}
                                </h3>
                                
                                <p class="text-stone-600 mb-4">
                                    {{ $collection->getTranslation('description', app()->getLocale()) }}
                                </p>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-stone-500">
                                        {{ $collection->artworks->count() }} œuvre{{ $collection->artworks->count() > 1 ? 's' : '' }}
                                    </span>
                                    
                                    <a href="{{ route('collections.show', $collection) }}" 
                                       class="text-amber-600 hover:text-amber-700 font-semibold">
                                        Découvrir →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if($featuredArtworks->count() > 0)
        <section class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-stone-900 mb-8 text-center">Œuvres populaires</h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($featuredArtworks as $artwork)
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
                            
                            <div class="p-4">
                                <h3 class="font-semibold text-stone-900 mb-1">
                                    {{ $artwork->getTranslation('title', app()->getLocale()) }}
                                </h3>
                                
                                @if($artwork->collection)
                                    <p class="text-sm text-stone-500 mb-3">
                                        {{ $artwork->collection->getTranslation('name', app()->getLocale()) }}
                                    </p>
                                @endif
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-amber-600">
                                        {{ number_format($artwork->price, 0, ',', ' ') }} €
                                    </span>
                                    
                                    <a href="{{ route('artworks.show', $artwork) }}" 
                                       class="text-stone-900 hover:text-amber-600 font-semibold text-sm">
                                        Voir →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts.app>
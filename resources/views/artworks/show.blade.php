<x-layouts.app 
    :metaTitle="($artwork->meta_title ?: $artwork->getTranslation('title', app()->getLocale()) . ' - Caverne des Enfants')"
    :metaDescription="$artwork->meta_description">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div class="space-y-4">
                @if($artwork->image_path)
                    <img src="{{ asset('storage/' . $artwork->image_path) }}" 
                         alt="{{ $artwork->getTranslation('title', app()->getLocale()) }}"
                         class="w-full aspect-square object-cover bg-stone-100 rounded-lg">
                @else
                    <div class="aspect-square bg-stone-100 rounded-lg flex items-center justify-center text-stone-400">
                        <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                @endif
                
            </div>
            
            <div class="space-y-6">
                <div>
                    <span class="inline-block bg-amber-600 text-white px-3 py-1 rounded-full text-sm font-semibold mb-4">
                        Œuvre unique
                    </span>
                    
                    <h1 class="text-3xl font-bold text-stone-900 mb-4">
                        {{ $artwork->getTranslation('title', app()->getLocale()) }}
                    </h1>
                    
                    <div class="flex items-center space-x-4 text-stone-600 mb-6">
                        @if($artwork->artist)
                            <span>par <strong>{{ $artwork->artist->name }}</strong></span>
                        @endif
                        
                        @if($artwork->year)
                            <span>•</span>
                            <span>{{ $artwork->year }}</span>
                        @endif
                        
                        @if($artwork->collection)
                            <span>•</span>
                            <span>{{ $artwork->collection->getTranslation('name', app()->getLocale()) }}</span>
                        @endif
                    </div>
                </div>
                
                <div class="border-t border-stone-200 pt-6">
                    <dl class="space-y-3">
                        @if($artwork->getTranslation('medium', app()->getLocale()))
                            <div class="flex">
                                <dt class="w-24 text-stone-500">Technique</dt>
                                <dd class="text-stone-900">{{ $artwork->getTranslation('medium', app()->getLocale()) }}</dd>
                            </div>
                        @endif
                        
                        @if($artwork->dimensions)
                            <div class="flex">
                                <dt class="w-24 text-stone-500">Format</dt>
                                <dd class="text-stone-900">{{ $artwork->dimensions }}</dd>
                            </div>
                        @endif
                        
                        <div class="flex">
                            <dt class="w-24 text-stone-500">Référence</dt>
                            <dd class="text-stone-900 font-mono text-sm">{{ $artwork->sku }}</dd>
                        </div>
                    </dl>
                </div>
                
                <div class="border-t border-stone-200 pt-6">
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-3xl font-bold text-amber-600">
                            {{ number_format($artwork->price, 0, ',', ' ') }} €
                        </span>
                        
                        <span class="text-sm text-stone-500">
                            Livraison incluse
                        </span>
                    </div>
                    
                    @if($artwork->isAvailable())
                        <form action="{{ route('cart.add', $artwork) }}" method="POST" class="space-y-4">
                            @csrf
                            <button type="submit" 
                                    class="w-full bg-amber-600 text-white py-4 rounded-lg hover:bg-amber-700 transition-colors text-lg font-semibold">
                                Ajouter au panier
                            </button>
                        </form>
                    @else
                        <div class="w-full bg-stone-200 text-stone-500 py-4 rounded-lg text-center text-lg font-semibold">
                            @if($artwork->status === \App\ArtworkStatus::SOLD)
                                Œuvre vendue
                            @elseif($artwork->status === \App\ArtworkStatus::RESERVED)
                                Œuvre réservée
                            @else
                                Non disponible
                            @endif
                        </div>
                    @endif
                    
                    <p class="text-sm text-stone-500 text-center mt-4">
                        Cette œuvre est unique et ne peut être vendue qu'une seule fois.
                    </p>
                </div>
            </div>
        </div>
        
        @if($relatedArtworks->count() > 0)
            <div class="mt-16 border-t border-stone-200 pt-16">
                <h2 class="text-2xl font-bold text-stone-900 mb-8">Œuvres similaires</h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedArtworks as $related)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                            @if($related->image_path)
                                <img src="{{ asset('storage/' . $related->image_path) }}" 
                                     alt="{{ $related->getTranslation('title', app()->getLocale()) }}"
                                     class="w-full aspect-square object-cover bg-stone-100">
                            @else
                                <div class="aspect-square bg-stone-100 flex items-center justify-center text-stone-400">
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @endif
                            
                            <div class="p-4">
                                <h3 class="font-semibold text-stone-900 mb-2">
                                    {{ $related->getTranslation('title', app()->getLocale()) }}
                                </h3>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-amber-600">
                                        {{ number_format($related->price, 0, ',', ' ') }} €
                                    </span>
                                    
                                    <a href="{{ route('artworks.show', $related) }}" 
                                       class="text-stone-900 hover:text-amber-600 font-semibold text-sm">
                                        Voir →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
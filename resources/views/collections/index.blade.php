<x-layouts.app metaTitle="Collections - Caverne des Enfants">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-4xl font-bold text-stone-900 mb-8 text-center">Collections</h1>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @foreach($collections as $collection)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="h-64 bg-stone-200 relative overflow-hidden">
                        @if($collection->artworks->first() && $collection->artworks->first()->image_path)
                            <img src="{{ asset('storage/' . $collection->artworks->first()->image_path) }}" 
                                 alt="{{ $collection->name }}"
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
                        <h2 class="text-2xl font-semibold text-stone-900 mb-3">
                            {{ $collection->name }}
                        </h2>
                        
                        @if($collection->description)
                            <p class="text-stone-600 mb-4">
                                {{ $collection->description }}
                            </p>
                        @endif
                        
                        <div class="flex justify-between items-center">
                            <span class="text-stone-500">
                                {{ $collection->artworks_count }} œuvre{{ $collection->artworks_count > 1 ? 's' : '' }} disponible{{ $collection->artworks_count > 1 ? 's' : '' }}
                            </span>
                            
                            <a href="{{ route('collections.show', $collection) }}" 
                               class="bg-amber-600 text-white px-6 py-2 rounded-lg hover:bg-amber-700 transition-colors">
                                Découvrir
                            </a>
                        </div>
                        
                        @if($collection->artworks->count() > 0)
                            <div class="mt-6 grid grid-cols-4 gap-2">
                                @foreach($collection->artworks->take(4) as $artwork)
                                    <div class="aspect-square bg-stone-100 rounded overflow-hidden">
                                        <a href="{{ route('artworks.show', $artwork) }}" class="block w-full h-full hover:opacity-75 transition-opacity">
                                            @if($artwork->image_path)
                                                <img src="{{ asset('storage/' . $artwork->image_path) }}" 
                                                     alt="{{ $artwork->title }}"
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-stone-400">
                                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-layouts.app>
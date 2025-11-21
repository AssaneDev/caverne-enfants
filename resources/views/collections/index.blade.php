<x-layouts.app metaTitle="Collections - Caverne des Enfants">
    {{-- Banner Section - AFO Image from R2 --}}
    <section class="w-full py-8 px-4 sm:px-6 lg:px-8 bg-stone-50">
        <div class="max-w-7xl mx-auto">
            <div class="rounded-xl overflow-hidden shadow-lg">
                <img src="{{ Storage::disk('r2')->url('images-banniere/afo.png') }}"
                     alt="Bannière Caverne des Enfants"
                     class="w-full h-auto object-cover">
            </div>
        </div>
    </section>

    {{-- Section Message Solidaire --}}
    <section class="py-8 bg-gradient-to-br from-amber-50 to-orange-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-lg p-6 md:p-8 border-l-4 border-amber-500">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg md:text-xl text-stone-700 leading-relaxed">
                            Acheter une œuvre de la Caverne, c'est soutenir concrètement les sorties scolaires culturelles et les ateliers d'art et de poésie que nous proposons chaque année aux enfants de nos écoles partenaires.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl sm:text-4xl font-bold text-stone-900 mb-8 text-center">Collections</h1>
        
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
                            <div class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-2">
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

    {{-- Video Section --}}
    <section class="w-full py-16 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-white via-stone-100 to-stone-800">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-8">
                <p class="text-stone-900 text-xl font-semibold leading-relaxed">
                    Découvrez les classes de l'école de la petite côte qui s'expriment à l'occasion d'un échange scolaire.
                </p>
            </div>
            <div class="rounded-xl overflow-hidden shadow-lg">
                <div class="aspect-video w-full">
                    <iframe
                        class="w-full h-full"
                        src="https://www.youtube.com/embed/Ie9QGvmXymY?start=82"
                        title="Échange scolaire - École de la petite côte"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
<x-layouts.app>
    {{-- Section Hero --}}
    @if($homepageBlocks->has('hero'))
        @php $heroBlock = $homepageBlocks->get('hero'); @endphp
        <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
            {{-- Image de fond si présente --}}
            @if(!empty($heroBlock->content['image_path']))
                <div class="absolute inset-0 z-0">
                    <img src="{{ asset('storage/' . $heroBlock->content['image_path']) }}" 
                         alt="{{ $heroBlock->content['title'] }}"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/70"></div>
                </div>
            @else
                <div class="absolute inset-0 z-0 bg-gradient-to-br from-amber-50 via-stone-100 to-amber-100"></div>
            @endif
            
            {{-- Contenu --}}
            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="max-w-5xl mx-auto">
                    {{-- Titre principal --}}
                    <div class="mb-8 backdrop-blur-sm bg-white/10 @if(empty($heroBlock->content['image_path'])) bg-white/80 @endif rounded-3xl p-8 sm:p-12 border border-white/20">
                        <h1 class="text-6xl sm:text-7xl lg:text-8xl xl:text-9xl font-black mb-6 leading-none tracking-tight"
                            style="font-family: 'Playfair Display', serif;">
                            <span class="@if(!empty($heroBlock->content['image_path'])) 
                                           text-white drop-shadow-2xl 
                                         @else 
                                           bg-gradient-to-br from-amber-600 via-orange-600 to-red-600 bg-clip-text text-transparent 
                                         @endif
                                         block transform hover:scale-105 transition-transform duration-500">
                                {{ $heroBlock->content['title'] ?? 'Caverne des Enfants' }}
                            </span>
                        </h1>
                        
                        @if(!empty($heroBlock->content['subtitle']))
                            <div class="max-w-4xl mx-auto">
                                <p class="text-xl sm:text-2xl lg:text-3xl mb-10 leading-relaxed font-light tracking-wide
                                          @if(!empty($heroBlock->content['image_path'])) 
                                             text-white/90 drop-shadow-xl 
                                           @else 
                                             text-stone-700 
                                           @endif"
                                   style="font-family: 'Inter', sans-serif;">
                                    {{ $heroBlock->content['subtitle'] }}
                                </p>
                            </div>
                        @endif
                        
                        @if(!empty($heroBlock->content['button_text']) && !empty($heroBlock->content['button_url']))
                            <div class="flex flex-col sm:flex-row gap-6 justify-center items-center mt-12">
                                <a href="{{ $heroBlock->content['button_url'] }}" 
                                   class="group relative inline-flex items-center justify-center px-12 py-5 overflow-hidden
                                          bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 
                                          text-white text-xl font-bold rounded-2xl
                                          transform transition-all duration-300 ease-out
                                          hover:scale-110 hover:rotate-1
                                          shadow-2xl hover:shadow-amber-500/50
                                          border-2 border-white/30
                                          backdrop-blur-sm"
                                   style="font-family: 'Inter', sans-serif;">
                                    {{-- Effet de brillance animé --}}
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent 
                                               transform -skew-x-12 -translate-x-full group-hover:translate-x-full 
                                               transition-transform duration-700 ease-in-out"></div>
                                    
                                    {{-- Contenu du bouton --}}
                                    <span class="relative z-10 flex items-center">
                                        {{ $heroBlock->content['button_text'] }}
                                        <svg class="ml-3 w-6 h-6 group-hover:translate-x-2 group-hover:scale-110 
                                                   transition-all duration-300" 
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" 
                                                  d="M13 7l5 5-5 5M6 12h12"/>
                                        </svg>
                                    </span>
                                    
                                    {{-- Particules flottantes --}}
                                    <div class="absolute -inset-1 bg-gradient-to-r from-amber-400 via-orange-400 to-red-400 
                                               rounded-2xl blur opacity-30 group-hover:opacity-60 transition-opacity duration-300"></div>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- Indicateur de scroll --}}
            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-10 animate-bounce">
                <svg class="w-6 h-6 @if(!empty($heroBlock->content['image_path'])) text-white @else text-stone-600 @endif" 
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                </svg>
            </div>
        </section>
    @else
        {{-- Section par défaut si aucun bloc hero n'est configuré --}}
        <section class="bg-gradient-to-r from-amber-50 to-stone-100 py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl md:text-6xl font-bold text-stone-900 mb-6">
                    Caverne des Enfants
                </h1>
                <p class="text-xl text-stone-600 max-w-3xl mx-auto mb-8">
                    Découvrez des œuvres uniques créées par des artistes en herbe passionnés
                </p>
                <a href="{{ route('collections.index') }}" 
                   class="inline-block bg-amber-600 text-white px-8 py-4 rounded-lg hover:bg-amber-700 transition-colors text-lg font-semibold">
                    Découvrir les collections
                </a>
            </div>
        </section>
    @endif

    {{-- Banners Section --}}
    @if($banners->count() > 0)
        <section class="py-8 bg-gradient-to-br from-stone-50 to-amber-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @if($banners->count() == 1)
                    {{-- Single Banner --}}
                    @php $banner = $banners->first(); @endphp
                    <div class="rounded-xl overflow-hidden shadow-xl hover:shadow-2xl transition-shadow duration-300">
                        @if($banner->link_url)
                            <a href="{{ $banner->link_url }}" target="_blank" rel="noopener noreferrer">
                                <img src="{{ asset('storage/' . $banner->image_path) }}"
                                     alt="{{ $banner->title }}"
                                     class="w-full h-auto object-cover hover:scale-105 transition-transform duration-500">
                            </a>
                        @else
                            <img src="{{ asset('storage/' . $banner->image_path) }}"
                                 alt="{{ $banner->title }}"
                                 class="w-full h-auto object-cover">
                        @endif
                    </div>
                @else
                    {{-- Multiple Banners Carousel --}}
                    <div x-data="{ currentBanner: 0 }" class="relative">
                        <div class="rounded-xl overflow-hidden shadow-xl">
                            @foreach($banners as $index => $banner)
                                <div x-show="currentBanner === {{ $index }}"
                                     x-transition:enter="transition ease-out duration-500"
                                     x-transition:enter-start="opacity-0 transform translate-x-full"
                                     x-transition:enter-end="opacity-100 transform translate-x-0"
                                     x-transition:leave="transition ease-in duration-500"
                                     x-transition:leave-start="opacity-100 transform translate-x-0"
                                     x-transition:leave-end="opacity-0 transform -translate-x-full"
                                     class="relative">
                                    @if($banner->link_url)
                                        <a href="{{ $banner->link_url }}" target="_blank" rel="noopener noreferrer">
                                            <img src="{{ asset('storage/' . $banner->image_path) }}"
                                                 alt="{{ $banner->title }}"
                                                 class="w-full h-auto object-cover">
                                        </a>
                                    @else
                                        <img src="{{ asset('storage/' . $banner->image_path) }}"
                                             alt="{{ $banner->title }}"
                                             class="w-full h-auto object-cover">
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- Navigation Arrows --}}
                        @if($banners->count() > 1)
                            <button @click="currentBanner = currentBanner === 0 ? {{ $banners->count() - 1 }} : currentBanner - 1"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white rounded-full p-3 shadow-lg transition-all">
                                <svg class="w-6 h-6 text-stone-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>

                            <button @click="currentBanner = currentBanner === {{ $banners->count() - 1 }} ? 0 : currentBanner + 1"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white rounded-full p-3 shadow-lg transition-all">
                                <svg class="w-6 h-6 text-stone-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>

                            {{-- Dots Indicators --}}
                            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                                @foreach($banners as $index => $banner)
                                    <button @click="currentBanner = {{ $index }}"
                                            :class="currentBanner === {{ $index }} ? 'bg-white w-8' : 'bg-white/60 w-3'"
                                            class="h-3 rounded-full transition-all duration-300"></button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Auto-rotation --}}
                    @if($banners->count() > 1)
                        <script>
                            document.addEventListener('alpine:init', () => {
                                setInterval(() => {
                                    const currentBannerElement = document.querySelector('[x-data]');
                                    if (currentBannerElement && currentBannerElement.__x) {
                                        const data = currentBannerElement.__x.$data;
                                        data.currentBanner = data.currentBanner === {{ $banners->count() - 1 }} ? 0 : data.currentBanner + 1;
                                    }
                                }, 5000); // Change every 5 seconds
                            });
                        </script>
                    @endif
                @endif
            </div>
        </section>
    @endif

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

    {{-- Section Projet Solidaire avec Logo AFO --}}
    <section class="py-12 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-center gap-8 md:gap-12">
                <div class="flex-shrink-0">
                    <img src="https://storage.lacavernedesenfants.com/images-banniere/logo.jpg"
                         alt="Logo AFO"
                         class="w-32 h-32 md:w-40 md:h-40 rounded-full shadow-lg object-cover">
                </div>
                <div class="text-center md:text-left">
                    <p class="text-xl md:text-2xl text-stone-700 leading-relaxed font-medium">
                        La Caverne des Enfants est un projet 100% solidaire porté par l'association AFO et son équipe de volontaires.
                    </p>
                </div>
            </div>
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
                                         alt="{{ $artwork->title }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-stone-400">
                                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                @endif
                                @if($artwork->status === App\ArtworkStatus::SOLD)
                                    <span class="absolute top-4 right-4 bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                        Vendu
                                    </span>
                                @else
                                    <span class="absolute top-4 right-4 bg-amber-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                        Unique
                                    </span>
                                @endif
                            </div>
                            
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-stone-900 mb-2">
                                    {{ $artwork->title }}
                                </h3>
                                
                                @if($artwork->artist)
                                    <p class="text-stone-600 mb-3">par {{ $artwork->artist->name }}</p>
                                @endif
                                
                                <div class="flex justify-between items-center">
                                    @if($artwork->status === App\ArtworkStatus::SOLD)
                                        <span class="text-2xl font-bold text-red-600 line-through opacity-75">
                                            {{ number_format($artwork->price, 0, ',', ' ') }} €
                                        </span>
                                        
                                        <a href="{{ route('artworks.show', $artwork) }}" 
                                           class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                                            Voir
                                        </a>
                                    @else
                                        <span class="text-2xl font-bold text-amber-600">
                                            {{ number_format($artwork->price, 0, ',', ' ') }} €
                                        </span>
                                        
                                        <a href="{{ route('artworks.show', $artwork) }}" 
                                           class="bg-stone-900 text-white px-6 py-2 rounded-lg hover:bg-stone-800 transition-colors">
                                            Voir
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Quote Section 2 --}}
    <section class="w-full py-16 px-6" style="background-color: #1bd1c2;">
        <div class="max-w-4xl mx-auto">
            <blockquote class="quote-cursive text-center text-white text-xl lg:text-2xl font-medium italic leading-relaxed">
                "{{ \App\Helpers\QuoteHelper::getRandomQuote() }}"
            </blockquote>
        </div>
    </section>

    {{-- Section Featured Block (administrable) --}}
    @if($homepageBlocks->has('featured'))
        @php $featuredBlock = $homepageBlocks->get('featured'); @endphp
        <section class="py-20 bg-gradient-to-br from-stone-50 to-amber-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    @if(!empty($featuredBlock->content['image_path']))
                        <div class="order-2 lg:order-1">
                            <img src="{{ asset('storage/' . $featuredBlock->content['image_path']) }}" 
                                 alt="{{ $featuredBlock->content['title'] }}"
                                 class="w-full rounded-2xl shadow-2xl transform hover:scale-105 transition-transform duration-300">
                        </div>
                    @endif
                    
                    <div class="order-1 lg:order-2 @if(empty($featuredBlock->content['image_path'])) col-span-2 text-center @endif">
                        <h2 class="text-4xl lg:text-5xl font-bold text-stone-900 mb-6">
                            {{ $featuredBlock->content['title'] }}
                        </h2>
                        
                        @if(!empty($featuredBlock->content['subtitle']))
                            <p class="text-xl text-stone-600 mb-8 leading-relaxed">
                                {{ $featuredBlock->content['subtitle'] }}
                            </p>
                        @endif
                        
                        @if(!empty($featuredBlock->content['button_text']) && !empty($featuredBlock->content['button_url']))
                            <a href="{{ $featuredBlock->content['button_url'] }}" 
                               class="inline-flex items-center px-8 py-4 bg-stone-900 text-white text-lg font-semibold rounded-xl 
                                      hover:bg-stone-800 transform hover:scale-105 transition-all duration-200 shadow-lg">
                                {{ $featuredBlock->content['button_text'] }}
                                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5-5 5M6 12h12"/>
                                </svg>
                            </a>
                        @endif
                    </div>
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
                                <h3 class="text-xl font-semibold text-stone-900 mb-2">
                                    {{ $collection->name }}
                                </h3>
                                
                                <p class="text-stone-600 mb-4">
                                    {{ $collection->description }}
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

    {{-- Quote Section 3 --}}
    <section class="w-full py-16 px-6" style="background-color: #1bd1c2;">
        <div class="max-w-4xl mx-auto">
            <blockquote class="quote-cursive text-center text-white text-xl lg:text-2xl font-medium italic leading-relaxed">
                "{{ \App\Helpers\QuoteHelper::getRandomQuote() }}"
            </blockquote>
        </div>
    </section>

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
                                         alt="{{ $artwork->title }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-stone-400">
                                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                @endif
                                @if($artwork->status === App\ArtworkStatus::SOLD)
                                    <span class="absolute top-4 right-4 bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                        Vendu
                                    </span>
                                @else
                                    <span class="absolute top-4 right-4 bg-amber-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                        Unique
                                    </span>
                                @endif
                            </div>
                            
                            <div class="p-4">
                                <h3 class="font-semibold text-stone-900 mb-1">
                                    {{ $artwork->title }}
                                </h3>
                                
                                @if($artwork->collection)
                                    <p class="text-sm text-stone-500 mb-3">
                                        {{ $artwork->collection->name }}
                                    </p>
                                @endif
                                
                                <div class="flex justify-between items-center">
                                    @if($artwork->status === App\ArtworkStatus::SOLD)
                                        <span class="text-lg font-bold text-red-600 line-through opacity-75">
                                            {{ number_format($artwork->price, 0, ',', ' ') }} €
                                        </span>
                                        
                                        <a href="{{ route('artworks.show', $artwork) }}" 
                                           class="text-gray-600 hover:text-gray-800 font-semibold text-sm">
                                            Voir →
                                        </a>
                                    @else
                                        <span class="text-lg font-bold text-amber-600">
                                            {{ number_format($artwork->price, 0, ',', ' ') }} €
                                        </span>
                                        
                                        <a href="{{ route('artworks.show', $artwork) }}" 
                                           class="text-stone-900 hover:text-amber-600 font-semibold text-sm">
                                            Voir →
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Quote Section 4 --}}
    <section class="w-full py-16 px-6" style="background-color: #1bd1c2;">
        <div class="max-w-4xl mx-auto">
            <blockquote class="quote-cursive text-center text-white text-xl lg:text-2xl font-medium italic leading-relaxed">
                "{{ \App\Helpers\QuoteHelper::getRandomQuote() }}"
            </blockquote>
        </div>
    </section>

    {{-- Section About Block (administrable) --}}
    @if($homepageBlocks->has('about'))
        @php $aboutBlock = $homepageBlocks->get('about'); @endphp
        <section class="py-20 bg-stone-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div class="@if(empty($aboutBlock->content['image_path'])) col-span-2 text-center @endif">
                        <h2 class="text-4xl lg:text-5xl font-bold text-white mb-6">
                            {{ $aboutBlock->content['title'] }}
                        </h2>
                        
                        @if(!empty($aboutBlock->content['subtitle']))
                            <p class="text-xl text-gray-300 mb-8 leading-relaxed">
                                {{ $aboutBlock->content['subtitle'] }}
                            </p>
                        @endif
                        
                        @if(!empty($aboutBlock->content['button_text']) && !empty($aboutBlock->content['button_url']))
                            <a href="{{ $aboutBlock->content['button_url'] }}" 
                               class="inline-flex items-center px-8 py-4 bg-amber-600 text-white text-lg font-semibold rounded-xl 
                                      hover:bg-amber-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                                {{ $aboutBlock->content['button_text'] }}
                                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5-5 5M6 12h12"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                    
                    @if(!empty($aboutBlock->content['image_path']))
                        <div>
                            <img src="{{ asset('storage/' . $aboutBlock->content['image_path']) }}" 
                                 alt="{{ $aboutBlock->content['title'] }}"
                                 class="w-full rounded-2xl shadow-2xl transform hover:scale-105 transition-transform duration-300">
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    {{-- Section Vidéo --}}
    <section class="relative bg-gradient-to-br from-stone-900 via-stone-800 to-amber-900 py-20 overflow-hidden">
        {{-- Motif de fond --}}
        <div class="absolute inset-0 opacity-5">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.2) 2px, transparent 2px), radial-gradient(circle at 75% 75%, rgba(255,255,255,0.2) 2px, transparent 2px); background-size: 60px 60px;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- En-tête de section --}}
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-amber-600 rounded-full mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4" style="font-family: 'Playfair Display', serif;">
                    L'art des enfants en mouvement
                </h2>
                <p class="text-xl text-amber-100 max-w-3xl mx-auto leading-relaxed">
                    Plongez dans l'univers créatif de La Caverne des Enfants
                </p>
            </div>

            {{-- Conteneur vidéo --}}
            <div class="relative max-w-5xl mx-auto">
                {{-- Effet de halo --}}
                <div class="absolute -inset-4 bg-gradient-to-r from-amber-600 via-orange-500 to-amber-600 rounded-3xl blur-2xl opacity-20"></div>

                {{-- Vidéo --}}
                <div class="relative rounded-2xl overflow-hidden shadow-2xl ring-4 ring-white/10">
                    <video
                        class="w-full h-auto"
                        controls
                        controlsList="nodownload"
                        preload="metadata"
                        playsinline
                        crossorigin="anonymous"
                        poster="{{ Storage::disk('r2')->url('images-banniere/poster-video.png') }}"
                        onloadstart="this.volume=0.7">
                        <source src="{{ Storage::disk('r2')->url('images-banniere/video.mp4') }}" type="video/mp4; codecs=avc1.42E01E,mp4a.40.2">
                        <source src="{{ Storage::disk('r2')->url('images-banniere/video.mp4') }}" type="video/mp4">
                        <p class="text-white p-8 text-center">
                            Votre navigateur ne supporte pas la lecture de vidéos HTML5.
                            <a href="{{ Storage::disk('r2')->url('images-banniere/video.mp4') }}"
                               class="text-amber-400 underline hover:text-amber-300"
                               download>
                                Télécharger la vidéo
                            </a>
                        </p>
                    </video>
                </div>

                {{-- Décoration --}}
                <div class="absolute -bottom-6 -right-6 w-48 h-48 bg-amber-600 rounded-full opacity-10 blur-3xl"></div>
                <div class="absolute -top-6 -left-6 w-48 h-48 bg-orange-600 rounded-full opacity-10 blur-3xl"></div>
            </div>
        </div>

        {{-- Script de gestion d'erreurs vidéo --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const video = document.querySelector('video');

                if (video) {
                    // Gestion des erreurs de chargement
                    video.addEventListener('error', function(e) {
                        console.error('Erreur de chargement vidéo:', e);

                        // Afficher un message d'erreur convivial
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'absolute inset-0 flex items-center justify-center bg-stone-900/90 text-white p-8 text-center';
                        errorDiv.innerHTML = `
                            <div>
                                <svg class="w-16 h-16 mx-auto mb-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <h3 class="text-xl font-bold mb-2">Impossible de charger la vidéo</h3>
                                <p class="mb-4">La vidéo ne peut pas être lue dans votre navigateur.</p>
                                <a href="{{ Storage::disk('r2')->url('images-banniere/video.mp4') }}"
                                   class="inline-block bg-amber-600 text-white px-6 py-3 rounded-lg hover:bg-amber-700 transition-colors"
                                   download>
                                    Télécharger la vidéo
                                </a>
                            </div>
                        `;
                        video.parentElement.style.position = 'relative';
                        video.parentElement.appendChild(errorDiv);
                    }, true);

                    // Forcer le rechargement si la vidéo ne se charge pas après 3 secondes
                    setTimeout(function() {
                        if (video.readyState === 0) {
                            console.log('Rechargement de la vidéo...');
                            video.load();
                        }
                    }, 3000);

                    // Log pour le debugging
                    video.addEventListener('loadstart', function() {
                        console.log('Chargement de la vidéo démarré');
                    });

                    video.addEventListener('loadedmetadata', function() {
                        console.log('Métadonnées vidéo chargées');
                    });

                    video.addEventListener('canplay', function() {
                        console.log('La vidéo peut être lue');
                    });
                }
            });
        </script>
    </section>
</x-layouts.app>
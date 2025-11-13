<x-layouts.app 
    :metaTitle="$collection->name . ' - Caverne des Enfants'"
    :metaDescription="$collection->description">
    
    @if($collection->banner_image_url)
        <div class="w-full relative overflow-hidden mb-12" style="aspect-ratio: 21/9;">
            <img src="{{ asset('storage/' . $collection->banner_image_url) }}" 
                 alt="{{ $collection->name }}"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                <div class="text-center">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4">
                        {{ $collection->name }}
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
        @if(!$collection->banner_image_url)
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-stone-900 mb-4">
                    {{ $collection->name }}
                </h1>
                
                @if($collection->description)
                    <p class="text-xl text-stone-600 max-w-3xl mx-auto mb-6">
                        {{ $collection->description }}
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
            @if($collection->description)
                <div class="text-center mb-12">
                    <p class="text-xl text-stone-600 max-w-3xl mx-auto">
                        {{ $collection->description }}
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
                                <p class="text-stone-600 mb-2">par {{ $artwork->artist->name }}</p>
                            @endif
                            
                            @if($artwork->year)
                                <p class="text-sm text-stone-500 mb-3">{{ $artwork->year }}</p>
                            @endif
                            
                            <div class="flex justify-between items-center">
                                @if($artwork->status === App\ArtworkStatus::SOLD)
                                    <span class="text-2xl font-bold text-red-600 line-through opacity-75">
                                        {{ number_format($artwork->price, 0, ',', ' ') }} €
                                    </span>
                                    
                                    <a href="{{ route('artworks.show', $artwork) }}" 
                                       class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                                        Voir l'œuvre
                                    </a>
                                @else
                                    <span class="text-2xl font-bold text-amber-600">
                                        {{ number_format($artwork->price, 0, ',', ' ') }} €
                                    </span>
                                    
                                    <a href="{{ route('artworks.show', $artwork) }}" 
                                       class="bg-stone-900 text-white px-6 py-2 rounded-lg hover:bg-stone-800 transition-colors">
                                        Voir l'œuvre
                                    </a>
                                @endif
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

        <!-- Section Images d'Ambiance de Création -->
        @if($collection->getMedia('atmosphere_images')->count() > 0 || $collection->atmosphere_description)
            <div class="mt-20 mb-12">
                <div class="relative overflow-hidden">
                    <!-- Arrière-plan avec motif -->
                    <div class="absolute inset-0 bg-gradient-to-br from-stone-900 via-stone-800 to-amber-900">
                        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 2px, transparent 2px), radial-gradient(circle at 75% 75%, rgba(255,255,255,0.1) 2px, transparent 2px); background-size: 50px 50px;"></div>
                    </div>
                    
                    <div class="relative px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
                        <!-- Header de la section -->
                        <div class="text-center mb-12">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-amber-600 rounded-full mb-6">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h2 class="text-3xl lg:text-5xl font-bold text-white mb-6">
                                L'Atelier en Images
                            </h2>
                            <p class="text-xl text-amber-100 max-w-3xl mx-auto leading-relaxed">
                                Découvrez l'univers créatif et l'ambiance qui ont donné naissance aux œuvres de cette collection
                            </p>
                            @if($collection->atmosphere_description)
                                <div class="mt-8 max-w-4xl mx-auto">
                                    <p class="text-lg text-stone-300 leading-relaxed italic border-l-4 border-amber-500 pl-6">
                                        "{{ $collection->atmosphere_description }}"
                                    </p>
                                </div>
                            @endif
                        </div>

                        @if($collection->getMedia('atmosphere_images')->count() > 0)
                            <!-- Galerie d'images -->
                            <div class="max-w-7xl mx-auto">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                                    @foreach($collection->getMedia('atmosphere_images') as $image)
                                        <div class="group relative overflow-hidden rounded-2xl shadow-2xl hover:shadow-3xl transition-all duration-500 transform hover:scale-105 cursor-pointer"
                                             onclick="openAtmosphereModal({{ $loop->index }})">
                                            <!-- Image -->
                                            <div class="aspect-[4/3] relative">
                                                <img src="{{ $image->getUrl() }}"
                                                     alt="Ambiance de création - {{ $collection->name }}"
                                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                                
                                                <!-- Overlay gradient -->
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                                                
                                                <!-- Icône d'agrandissement -->
                                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                                                    <div class="bg-white/20 backdrop-blur-sm rounded-full p-4 transform scale-75 group-hover:scale-100 transition-transform duration-300">
                                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                                        </svg>
                                                    </div>
                                                </div>
                                                
                                                <!-- Badge numéro -->
                                                <div class="absolute top-4 left-4 bg-amber-600 text-white text-sm font-bold px-3 py-1 rounded-full">
                                                    {{ $loop->iteration }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <!-- Call to action -->
                                <div class="text-center mt-12">
                                    <p class="text-amber-200 text-lg mb-6">
                                        {{ $collection->getMedia('atmosphere_images')->count() }} {{ $collection->getMedia('atmosphere_images')->count() > 1 ? 'images' : 'image' }} de l'atelier
                                    </p>
                                    <div class="inline-flex items-center text-amber-400 hover:text-amber-300 transition-colors cursor-pointer" onclick="openAtmosphereModal(0)">
                                        <span class="text-sm font-medium mr-2">Voir toute la galerie</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if($collection->history)
        <!-- Modal pour l'historique -->
        <div id="historyModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
            <div class="bg-white rounded-lg w-full max-w-4xl shadow-2xl" style="height: 600px;">
                <!-- Header -->
                <div class="bg-amber-600 text-white p-4 rounded-t-lg flex justify-between items-center">
                    <h2 class="text-xl font-bold">Histoire de {{ $collection->name }}</h2>
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

    <!-- Modal Galerie d'Ambiance -->
    @if($collection->getMedia('atmosphere_images')->count() > 0)
        <div id="atmosphereModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center p-4">
            <div class="relative w-full h-full max-w-6xl max-h-full flex flex-col">
                <!-- Header -->
                <div class="flex justify-between items-center mb-4 text-white">
                    <h3 class="text-xl font-bold">Ambiance de Création - {{ $collection->name }}</h3>
                    <div class="flex items-center gap-4">
                        <span id="imageCounter" class="text-sm opacity-75"></span>
                        <button onclick="closeAtmosphereModal()" class="text-white hover:text-gray-300 p-2">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Image principale -->
                <div class="flex-1 flex items-center justify-center relative px-16 sm:px-20">
                    <button onclick="previousImage()" class="absolute left-2 sm:left-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 p-2 sm:p-3 bg-black bg-opacity-70 rounded-full hover:bg-opacity-90 transition-all z-20 shadow-xl">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>

                    <div class="w-full h-full flex items-center justify-center p-4">
                        <img id="modalImage"
                             src=""
                             alt="Ambiance de création"
                             class="max-w-[85%] max-h-[80vh] sm:max-w-[80%] sm:max-h-[85vh] w-auto h-auto object-contain rounded-lg shadow-2xl">
                    </div>

                    <button onclick="nextImage()" class="absolute right-2 sm:right-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 p-2 sm:p-3 bg-black bg-opacity-70 rounded-full hover:bg-opacity-90 transition-all z-20 shadow-xl">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Miniatures -->
                <div class="mt-6">
                    <div id="thumbnails" class="flex justify-center gap-2 flex-wrap max-h-32 overflow-y-auto">
                        @foreach($collection->getMedia('atmosphere_images') as $image)
                            <div class="thumbnail-container cursor-pointer opacity-60 hover:opacity-100 transition-opacity rounded-lg overflow-hidden"
                                 onclick="showImage({{ $loop->index }})"
                                 data-index="{{ $loop->index }}">
                                <img src="{{ $image->getUrl() }}"
                                     alt="Miniature {{ $loop->iteration }}"
                                     class="w-16 h-16 object-cover">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            let currentImageIndex = 0;
            const atmosphereImages = [
                @foreach($collection->getMedia('atmosphere_images') as $image)
                {
                    thumb: "{{ $image->getUrl() }}",
                    large: "{{ $image->getUrl() }}",
                    original: "{{ $image->getUrl() }}"
                }@if(!$loop->last),@endif
                @endforeach
            ];

            function openAtmosphereModal(index) {
                currentImageIndex = index;
                const modal = document.getElementById('atmosphereModal');
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                showImage(index);
                updateThumbnails();
                updateCounter();
                
                // Animation d'entrée
                modal.style.opacity = '0';
                setTimeout(() => {
                    modal.style.opacity = '1';
                    modal.style.transition = 'opacity 0.3s ease';
                }, 10);
            }

            function closeAtmosphereModal() {
                const modal = document.getElementById('atmosphereModal');
                modal.style.opacity = '0';
                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }, 300);
            }

            function showImage(index) {
                if (index < 0 || index >= atmosphereImages.length) return;
                
                currentImageIndex = index;
                const modalImage = document.getElementById('modalImage');
                
                // Animation de transition
                modalImage.style.opacity = '0';
                modalImage.style.transform = 'scale(0.95)';
                
                setTimeout(() => {
                    modalImage.src = atmosphereImages[index].original;
                    modalImage.style.opacity = '1';
                    modalImage.style.transform = 'scale(1)';
                    modalImage.style.transition = 'all 0.3s ease';
                    updateThumbnails();
                    updateCounter();
                }, 150);
            }

            function nextImage() {
                const nextIndex = (currentImageIndex + 1) % atmosphereImages.length;
                showImage(nextIndex);
            }

            function previousImage() {
                const prevIndex = (currentImageIndex - 1 + atmosphereImages.length) % atmosphereImages.length;
                showImage(prevIndex);
            }

            function updateThumbnails() {
                const thumbnails = document.querySelectorAll('.thumbnail-container');
                thumbnails.forEach((thumb, index) => {
                    if (index === currentImageIndex) {
                        thumb.classList.remove('opacity-60');
                        thumb.classList.add('opacity-100', 'ring-2', 'ring-amber-500');
                    } else {
                        thumb.classList.add('opacity-60');
                        thumb.classList.remove('opacity-100', 'ring-2', 'ring-amber-500');
                    }
                });
            }

            function updateCounter() {
                const counter = document.getElementById('imageCounter');
                counter.textContent = `${currentImageIndex + 1} / ${atmosphereImages.length}`;
            }

            // Navigation au clavier
            document.addEventListener('keydown', function(e) {
                const modal = document.getElementById('atmosphereModal');
                if (!modal.classList.contains('hidden')) {
                    switch(e.key) {
                        case 'Escape':
                            closeAtmosphereModal();
                            break;
                        case 'ArrowLeft':
                            previousImage();
                            break;
                        case 'ArrowRight':
                            nextImage();
                            break;
                    }
                }
            });

            // Fermer en cliquant sur l'arrière-plan
            document.getElementById('atmosphereModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeAtmosphereModal();
                }
            });
        </script>
    @endif

    {{-- Section Vidéo (uniquement pour Les Carrés du Fleuve) --}}
    @if($collection->slug === 'les-carres-du-fleuve')
        <section class="relative bg-gradient-to-br from-stone-900 via-stone-800 to-amber-900 py-20 overflow-hidden mt-16">
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
                        L'Art en Mouvement
                    </h2>
                    <p class="text-xl text-amber-100 max-w-3xl mx-auto leading-relaxed">
                        Découvrez les coulisses de la création des Carrés du Fleuve
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
                            preload="metadata"
                            poster="{{ Storage::disk('r2')->url('images-banniere/video-poster.jpg') }}">
                            <source src="{{ Storage::disk('r2')->url('images-banniere/video.mp4') }}" type="video/mp4">
                            Votre navigateur ne supporte pas la lecture de vidéos.
                        </video>
                    </div>

                    {{-- Décoration --}}
                    <div class="absolute -bottom-6 -right-6 w-48 h-48 bg-amber-600 rounded-full opacity-10 blur-3xl"></div>
                    <div class="absolute -top-6 -left-6 w-48 h-48 bg-orange-600 rounded-full opacity-10 blur-3xl"></div>
                </div>
            </div>
        </section>
    @endif

    {{-- Section Vidéo YouTube (uniquement pour Les Carrés de la Petite Côte) --}}
    @if($collection->slug === 'les-carres-de-la-petite-cote')
        <section class="relative bg-gradient-to-br from-stone-900 via-stone-800 to-amber-900 py-20 overflow-hidden mt-16">
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
                        L'Art en Mouvement
                    </h2>
                    <p class="text-xl text-amber-100 max-w-3xl mx-auto leading-relaxed">
                        Découvrez les coulisses de la création des Carrés de la Petite Côte
                    </p>
                </div>

                {{-- Conteneur vidéo YouTube --}}
                <div class="relative max-w-5xl mx-auto">
                    {{-- Effet de halo --}}
                    <div class="absolute -inset-4 bg-gradient-to-r from-amber-600 via-orange-500 to-amber-600 rounded-3xl blur-2xl opacity-20"></div>

                    {{-- Vidéo YouTube --}}
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl ring-4 ring-white/10" style="aspect-ratio: 16/9;">
                        <iframe
                            class="w-full h-full"
                            src="https://www.youtube.com/embed/gaYHLt9QxOA"
                            title="Les Carrés de la Petite Côte - Vidéo"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen>
                        </iframe>
                    </div>

                    {{-- Décoration --}}
                    <div class="absolute -bottom-6 -right-6 w-48 h-48 bg-amber-600 rounded-full opacity-10 blur-3xl"></div>
                    <div class="absolute -top-6 -left-6 w-48 h-48 bg-orange-600 rounded-full opacity-10 blur-3xl"></div>
                </div>
            </div>
        </section>
    @endif
</x-layouts.app>
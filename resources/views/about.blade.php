<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>À propos - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .parallax-section {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
</head>
<body class="bg-stone-50 font-sans antialiased">
    <!-- Navigation -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="text-xl sm:text-2xl font-serif font-bold text-amber-600 hover:text-amber-700 transition">
                        La Caverne des Enfants
                    </a>
                </div>

                {{-- Desktop Menu --}}
                <div class="hidden md:flex space-x-8">
                    <a href="{{ route('home') }}" class="text-stone-700 hover:text-amber-600 transition font-medium">Accueil</a>
                    <a href="{{ route('about') }}" class="text-amber-600 font-medium">À propos</a>
                    <a href="{{ route('collections.index') }}" class="text-stone-700 hover:text-amber-600 transition font-medium">Collections</a>
                    <a href="{{ route('cart.show') }}" class="text-stone-700 hover:text-amber-600 transition font-medium">Panier</a>
                </div>

                {{-- Mobile Menu Button & Cart --}}
                <div class="md:hidden flex items-center space-x-4">
                    {{-- Cart Icon for Mobile --}}
                    <a href="{{ route('cart.show') }}" class="text-stone-600 hover:text-stone-900 relative transition-colors">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        @livewire('cart-counter')
                    </a>

                    {{-- Hamburger Menu Button --}}
                    <button id="mobile-menu-button-about" type="button" class="text-stone-600 hover:text-stone-900 focus:outline-none focus:text-stone-900">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path id="menu-icon-about" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path id="close-icon-about" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Mobile Menu --}}
            <div id="mobile-menu-about" class="hidden md:hidden pb-4">
                <div class="flex flex-col space-y-3 pt-2">
                    <a href="{{ route('home') }}" class="text-stone-600 hover:text-stone-900 hover:bg-stone-50 px-3 py-2 rounded-md transition-colors">
                        Accueil
                    </a>
                    <a href="{{ route('about') }}" class="text-amber-600 hover:bg-stone-50 px-3 py-2 rounded-md transition-colors font-medium">
                        À propos
                    </a>
                    <a href="{{ route('collections.index') }}" class="text-stone-600 hover:text-stone-900 hover:bg-stone-50 px-3 py-2 rounded-md transition-colors">
                        Collections
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuButton = document.getElementById('mobile-menu-button-about');
            const mobileMenu = document.getElementById('mobile-menu-about');
            const menuIcon = document.getElementById('menu-icon-about');
            const closeIcon = document.getElementById('close-icon-about');

            if (menuButton && mobileMenu) {
                menuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                    menuIcon.classList.toggle('hidden');
                    closeIcon.classList.toggle('hidden');
                });
            }
        });
    </script>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-amber-50 via-orange-50 to-stone-100 py-20 lg:py-32 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-72 h-72 bg-amber-400 rounded-full mix-blend-multiply filter blur-3xl animate-pulse"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-orange-400 rounded-full mix-blend-multiply filter blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center space-y-8 animate-fade-in-up">
                <h1 class="text-5xl md:text-7xl font-serif font-bold text-stone-900 leading-tight">
                    À propos
                    <span class="block text-amber-600 mt-2">La Caverne des Enfants</span>
                </h1>
                <p class="text-2xl md:text-3xl text-stone-700 font-light max-w-4xl mx-auto italic">
                    Quand l'art devient un pont entre les cœurs
                </p>
                <div class="w-32 h-1 bg-gradient-to-r from-amber-400 to-orange-500 mx-auto rounded-full"></div>
                <p class="text-xl text-stone-600 max-w-3xl mx-auto leading-relaxed">
                    Des artistes et des enfants unissent leurs gestes : la couleur devient poésie, la nature devient mémoire, et le Sénégal s'illumine dans chaque trait.
                </p>
            </div>
        </div>
    </section>

    <!-- Introduction -->
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-6">
                <p class="text-2xl text-stone-800 font-light leading-relaxed">
                    La Caverne des Enfants, c'est une <span class="font-semibold text-amber-600">aventure humaine et artistique</span> née de la rencontre entre la sensibilité des enfants et la passion des artistes.
                </p>

                {{-- Bouton de téléchargement de la plaquette --}}
                <div class="pt-8">
                    <a href="{{ Storage::disk('r2')->url('images-banniere/PLAQUETTE LA CAVERNE DES ENFANTS.pdf') }}"
                       target="_blank"
                       download
                       class="inline-flex items-center gap-3 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white px-8 py-4 rounded-xl font-semibold text-lg shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Télécharger la plaquette</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Notre Histoire -->
    <section class="py-20 bg-stone-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <h2 class="text-4xl md:text-5xl font-serif font-bold text-stone-900">
                        Notre histoire
                    </h2>
                    <div class="w-24 h-1 bg-amber-500 rounded-full"></div>
                    <div class="space-y-4 text-lg text-stone-700 leading-relaxed">
                        <p>
                            Tout a commencé dans un <span class="font-semibold text-amber-600">petit village au bord du Fleuve Sénégal</span>. Ce lieu paisible s'est transformé en atelier de magie où l'art est devenu un langage universel.
                        </p>
                        <p>
                            L'association <span class="font-semibold">AFO</span> a voulu révéler la vision artistique des enfants, en reliant des écoles rurales et urbaines à des artistes venus du Sénégal et d'ailleurs.
                        </p>
                        <p class="text-xl font-medium text-amber-700">
                            Ainsi sont nées trois collections uniques :
                        </p>
                        <ul class="space-y-2 pl-6">
                            <li class="flex items-start">
                                <span class="text-amber-500 mr-3 text-2xl">•</span>
                                <span>Les Carrés du Fleuve</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-amber-500 mr-3 text-2xl">•</span>
                                <span>Les Carrés de la Petite Côte</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-amber-500 mr-3 text-2xl">•</span>
                                <span>Les Baobabs de la Petite Côte</span>
                            </li>
                        </ul>
                        <p class="italic text-stone-600 pt-4">
                            Autant de reflets du monde vu à hauteur d'enfant.
                        </p>
                    </div>
                </div>

                <!-- Image Placeholder -->
                <div class="relative">
                    <div class="aspect-square bg-gradient-to-br from-amber-100 to-orange-200 rounded-2xl shadow-2xl overflow-hidden">
                        <div class="w-full h-full flex items-center justify-center">
                            <div class="text-center p-8">
                                <svg class="w-32 h-32 mx-auto text-amber-600 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-amber-700 mt-4 font-medium">Village au bord du Fleuve Sénégal</p>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -bottom-6 -right-6 w-48 h-48 bg-amber-400 rounded-full opacity-20 blur-2xl"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Notre Mission -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Image Placeholder -->
                <div class="relative order-2 lg:order-1">
                    <div class="aspect-[4/3] bg-gradient-to-br from-orange-100 to-amber-200 rounded-2xl shadow-2xl overflow-hidden">
                        <div class="w-full h-full flex items-center justify-center">
                            <div class="text-center p-8">
                                <svg class="w-32 h-32 mx-auto text-orange-600 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <p class="text-orange-700 mt-4 font-medium">Ateliers artistiques</p>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -top-6 -left-6 w-48 h-48 bg-orange-400 rounded-full opacity-20 blur-2xl"></div>
                </div>

                <div class="space-y-6 order-1 lg:order-2">
                    <h2 class="text-4xl md:text-5xl font-serif font-bold text-stone-900">
                        Notre mission
                    </h2>
                    <div class="w-24 h-1 bg-amber-500 rounded-full"></div>
                    <div class="space-y-4 text-lg text-stone-700 leading-relaxed">
                        <p>
                            Nous croyons que <span class="font-semibold text-amber-600">l'art peut être un moteur de transformation</span>, un outil d'éveil et de solidarité.
                        </p>
                        <p>
                            À travers nos ateliers et nos créations, nous offrons aux enfants un espace d'expression et de liberté.
                        </p>
                        <p>
                            Chaque œuvre est une main tendue, un souffle de poésie, un dialogue entre générations et cultures.
                        </p>
                        <p class="text-xl font-medium text-amber-700 italic pt-4">
                            L'art devient alors une passerelle, une façon d'apprendre à regarder le monde autrement.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Nos Collections -->
    <section class="py-20 bg-gradient-to-br from-amber-50 to-orange-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-stone-900 mb-4">
                    Nos collections
                </h2>
                <div class="w-24 h-1 bg-amber-500 rounded-full mx-auto"></div>
            </div>

            <div class="space-y-16">
                <!-- Les Carrés du Fleuve -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="grid lg:grid-cols-2">
                        <div class="aspect-video lg:aspect-auto bg-gradient-to-br from-blue-100 to-teal-200">
                            <div class="w-full h-full flex items-center justify-center p-8">
                                <div class="text-center">
                                    <svg class="w-24 h-24 mx-auto text-teal-600 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-teal-700 mt-4 font-medium">Les Carrés du Fleuve</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-8 lg:p-12 space-y-4">
                            <h3 class="text-3xl font-serif font-bold text-stone-900 flex items-center gap-3">
                                <span class="text-3xl">🎨</span>
                                Les Carrés du Fleuve
                            </h3>
                            <p class="text-lg text-stone-700 leading-relaxed">
                                Nés dans un petit village sahélien au bord du fleuve Sénégal, ces tableaux reflètent la beauté simple et naturelle du Fouta Toro.
                            </p>
                            <p class="text-lg text-stone-700 leading-relaxed">
                                Les couleurs y évoquent <span class="font-semibold text-teal-600">la terre, l'eau, les feuilles et les gouttes de pluie</span> qui nourrissent la vie.
                            </p>
                            <p class="text-lg text-stone-700 leading-relaxed italic">
                                Chaque carré, peint à plusieurs mains, raconte la rencontre entre la nature et l'imaginaire des enfants.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Les Carrés de la Petite Côte -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="grid lg:grid-cols-2">
                        <div class="aspect-video lg:aspect-auto bg-gradient-to-br from-cyan-100 to-blue-200 lg:order-2">
                            <div class="w-full h-full flex items-center justify-center p-8">
                                <div class="text-center">
                                    <svg class="w-24 h-24 mx-auto text-cyan-600 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                    </svg>
                                    <p class="text-cyan-700 mt-4 font-medium">Les Carrés de la Petite Côte</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-8 lg:p-12 space-y-4 lg:order-1">
                            <h3 class="text-3xl font-serif font-bold text-stone-900 flex items-center gap-3">
                                <span class="text-3xl">🌊</span>
                                Les Carrés de la Petite Côte
                            </h3>
                            <p class="text-lg text-stone-700 leading-relaxed">
                                Sur le thème de <span class="font-semibold text-cyan-600">l'amitié</span>, les enfants de Saly Mbour ont créé des œuvres pleines de tendresse.
                            </p>
                            <p class="text-lg text-stone-700 leading-relaxed">
                                Chaque tableau, peint par deux amis côte à côte, exprime la force du lien, le refuge et la complicité.
                            </p>
                            <p class="text-lg text-stone-700 leading-relaxed italic">
                                Les coquillages dorés et les barques symbolisent les mains tendues au milieu de l'océan de la vie.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Les Baobabs de la Petite Côte -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="grid lg:grid-cols-2">
                        <div class="aspect-video lg:aspect-auto bg-gradient-to-br from-green-100 to-emerald-200">
                            <div class="w-full h-full flex items-center justify-center p-8">
                                <div class="text-center">
                                    <svg class="w-24 h-24 mx-auto text-green-600 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                    </svg>
                                    <p class="text-green-700 mt-4 font-medium">Les Baobabs de la Petite Côte</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-8 lg:p-12 space-y-4">
                            <h3 class="text-3xl font-serif font-bold text-stone-900 flex items-center gap-3">
                                <span class="text-3xl">🌳</span>
                                Les Baobabs de la Petite Côte
                            </h3>
                            <p class="text-lg text-stone-700 leading-relaxed">
                                Ce projet est né d'un <span class="font-semibold text-green-600">défi artistique</span> entre une mère et sa fille.
                            </p>
                            <p class="text-lg text-stone-700 leading-relaxed">
                                En peignant avec des pailles, du coton et des objets du quotidien, les enfants ont réinventé le baobab : arbre sacré, géant généreux et symbole d'union entre les générations.
                            </p>
                            <p class="text-lg text-stone-700 leading-relaxed italic">
                                Comme eux, il porte la mémoire, la générosité et la beauté du Sénégal.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Les Artistes & Écoles -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-stone-900 mb-4">
                    Les artistes & les écoles partenaires
                </h2>
                <div class="w-24 h-1 bg-amber-500 rounded-full mx-auto"></div>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                <!-- Artistes -->
                <div class="space-y-6">
                    <div class="bg-amber-50 rounded-xl p-6 shadow-md hover:shadow-xl transition duration-300">
                        <div class="flex items-start gap-4">
                            <span class="text-4xl">🎨</span>
                            <div>
                                <h3 class="font-semibold text-xl text-stone-900">Oumkal Tione</h3>
                                <p class="text-stone-600">Artiste peintre plasticienne (Sénégal)</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-orange-50 rounded-xl p-6 shadow-md hover:shadow-xl transition duration-300">
                        <div class="flex items-start gap-4">
                            <span class="text-4xl">🎨</span>
                            <div>
                                <h3 class="font-semibold text-xl text-stone-900">Plume S. Cuinet</h3>
                                <p class="text-stone-600">Artiste peintre (France)</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-amber-50 rounded-xl p-6 shadow-md hover:shadow-xl transition duration-300">
                        <div class="flex items-start gap-4">
                            <span class="text-4xl">✍️</span>
                            <div>
                                <h3 class="font-semibold text-xl text-stone-900">Wanda C.</h3>
                                <p class="text-stone-600">Conteuse, écrivaine et présidente de l'association AFO</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Écoles -->
                <div class="space-y-6">
                    <div class="bg-teal-50 rounded-xl p-6 shadow-md hover:shadow-xl transition duration-300">
                        <div class="flex items-start gap-4">
                            <span class="text-4xl">🏫</span>
                            <div>
                                <h3 class="font-semibold text-xl text-stone-900">Écoles partenaires</h3>
                                <ul class="text-stone-600 space-y-2 mt-2">
                                    <li>• École de Donaye Walo</li>
                                    <li>• École de La Petite Côte</li>
                                    <li>• École de Tenedji</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl p-6 shadow-md">
                        <p class="text-stone-700 leading-relaxed italic">
                            Ces collaborations font de chaque projet une <span class="font-semibold text-amber-600">œuvre collective</span>, où l'art devient partage et mémoire vivante.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Notre Philosophie -->
    <section class="py-20 bg-gradient-to-br from-stone-900 to-amber-900 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 left-20 w-96 h-96 bg-amber-400 rounded-full mix-blend-multiply filter blur-3xl"></div>
            <div class="absolute bottom-20 right-20 w-96 h-96 bg-orange-400 rounded-full mix-blend-multiply filter blur-3xl"></div>
        </div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center space-y-8">
                <h2 class="text-4xl md:text-5xl font-serif font-bold mb-8">
                    Notre philosophie
                </h2>
                <div class="w-24 h-1 bg-amber-400 rounded-full mx-auto mb-8"></div>

                <blockquote class="text-3xl md:text-4xl font-serif italic leading-relaxed text-amber-100">
                    « Les couleurs deviennent langage, la peinture un pont entre les cœurs. »
                </blockquote>

                <div class="space-y-6 text-xl text-stone-200 leading-relaxed pt-8">
                    <p>
                        Nous vivons avec les enfants cet élan vers le monde,<br>
                        ce regard sur la beauté cachée qui les entoure.
                    </p>
                    <p class="text-2xl font-semibold text-amber-300 pt-4">
                        Parler du Sénégal, c'est parler d'un petit pays par sa taille,<br>
                        mais d'un géant par sa culture et sa générosité.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-stone-900 text-stone-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8 mb-8">
                <div>
                    <h3 class="text-xl font-serif font-bold text-amber-400 mb-4">La Caverne des Enfants</h3>
                    <p class="text-sm leading-relaxed">
                        Quand l'art devient un pont entre les cœurs.
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4 text-amber-400">Navigation</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-amber-400 transition">Accueil</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-amber-400 transition">À propos</a></li>
                        <li><a href="{{ route('collections.index') }}" class="hover:text-amber-400 transition">Collections</a></li>
                        <li><a href="{{ route('cart.show') }}" class="hover:text-amber-400 transition">Panier</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4 text-amber-400">Contact</h4>
                    <p class="text-sm">
                        Association AFO<br>
                        Sénégal
                    </p>
                </div>
            </div>
            <div class="border-t border-stone-700 pt-8 text-center text-sm">
                <p>&copy; {{ date('Y') }} La Caverne des Enfants. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</body>
</html>

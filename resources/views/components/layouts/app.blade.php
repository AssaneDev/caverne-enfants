<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $metaTitle ?? 'Caverne des Enfants' }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'Boutique d\'art unique - Œuvres originales et collections d\'artistes' }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Edu+NSW+ACT+Cursive:wght@400..700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="text-stone-900 antialiased">
    <nav class="bg-white shadow-sm border-b border-stone-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-amber-600">
                        Caverne des Enfants
                    </a>
                </div>
                
                <div class="flex items-center space-x-8">
                    <a href="{{ route('about') }}" class="text-stone-600 hover:text-stone-900">
                        À propos
                    </a>

                    <a href="{{ route('collections.index') }}" class="text-stone-600 hover:text-stone-900">
                        Collections
                    </a>

                    <a href="{{ route('cart.show') }}" class="text-stone-600 hover:text-stone-900 relative">
                        Panier
                        @livewire('cart-counter')
                    </a>
                    
                    @auth
                        <a href="{{ route('account.index') }}" class="text-stone-600 hover:text-stone-900">
                            Mon compte
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-stone-600 hover:text-stone-900">
                            Connexion
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    {{-- Newsletter Section --}}
    <section class="bg-gradient-to-br from-amber-600 via-orange-500 to-amber-700 py-16 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4" style="font-family: 'Playfair Display', serif;">
                    Restez Connecté à l'Art 🎨
                </h2>
                <p class="text-white/90 text-lg mb-8">
                    Inscrivez-vous à notre newsletter et découvrez en avant-première nos nouvelles créations, collections exclusives et offres spéciales
                </p>

                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-500 text-white rounded-xl shadow-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-500 text-white rounded-xl shadow-lg">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('newsletter.subscribe') }}" class="flex flex-col sm:flex-row gap-3 max-w-xl mx-auto">
                    @csrf
                    <input
                        type="email"
                        name="email"
                        placeholder="Votre adresse email"
                        required
                        class="flex-1 px-6 py-4 rounded-xl border-2 border-white/20 bg-white/10 backdrop-blur-sm text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent transition-all"
                    />
                    <button
                        type="submit"
                        class="px-8 py-4 bg-white text-amber-600 font-bold rounded-xl hover:bg-amber-50 transition-all shadow-xl hover:shadow-2xl hover:scale-105 transform duration-200"
                    >
                        S'inscrire
                    </button>
                </form>
                @error('email')
                    <p class="mt-3 text-white/90 text-sm">{{ $message }}</p>
                @enderror

                <p class="mt-4 text-white/70 text-sm">
                    En vous inscrivant, vous acceptez de recevoir nos emails. Désinscription possible à tout moment.
                </p>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-stone-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <div>
                    <h3 class="text-xl font-bold text-amber-500 mb-4" style="font-family: 'Playfair Display', serif;">
                        Caverne des Enfants
                    </h3>
                    <p class="text-stone-400 leading-relaxed">
                        Association artistique dédiée à la promotion d'œuvres d'art uniques pour éveiller l'imagination des enfants.
                    </p>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Navigation</h4>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('collections.index') }}" class="text-stone-400 hover:text-amber-500 transition-colors inline-flex items-center group">
                                <span class="group-hover:translate-x-1 transition-transform">Collections</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-stone-400 hover:text-amber-500 transition-colors inline-flex items-center group">
                                <span class="group-hover:translate-x-1 transition-transform">À propos</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-stone-400 hover:text-amber-500 transition-colors inline-flex items-center group">
                                <span class="group-hover:translate-x-1 transition-transform">Contact</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Informations</h4>
                    <ul class="space-y-3">
                        <li>
                            <a href="#" class="text-stone-400 hover:text-amber-500 transition-colors inline-flex items-center group">
                                <span class="group-hover:translate-x-1 transition-transform">Mentions légales</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-stone-400 hover:text-amber-500 transition-colors inline-flex items-center group">
                                <span class="group-hover:translate-x-1 transition-transform">CGV</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-stone-400 hover:text-amber-500 transition-colors inline-flex items-center group">
                                <span class="group-hover:translate-x-1 transition-transform">Livraison</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-stone-800 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-stone-500 text-sm">
                    &copy; {{ date('Y') }} Caverne des Enfants. Tous droits réservés.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-stone-500 hover:text-amber-500 transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" class="text-stone-500 hover:text-amber-500 transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="#" class="text-stone-500 hover:text-amber-500 transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
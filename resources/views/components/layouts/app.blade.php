<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $metaTitle ?? 'Caverne des Enfants' }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'Boutique d\'art unique - Œuvres originales et collections d\'artistes' }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-stone-50 text-stone-900 antialiased">
    <nav class="bg-white shadow-sm border-b border-stone-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-amber-600">
                        Caverne des Enfants
                    </a>
                </div>
                
                <div class="flex items-center space-x-8">
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

    <footer class="bg-stone-100 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-stone-900 mb-4">Caverne des Enfants</h3>
                    <p class="text-stone-600">
                        Association artistique dédiée à la promotion d'œuvres d'art uniques.
                    </p>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold text-stone-900 mb-4">Navigation</h3>
                    <ul class="space-y-2 text-stone-600">
                        <li><a href="{{ route('collections.index') }}" class="hover:text-stone-900">Collections</a></li>
                        <li><a href="#" class="hover:text-stone-900">À propos</a></li>
                        <li><a href="#" class="hover:text-stone-900">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold text-stone-900 mb-4">Informations</h3>
                    <ul class="space-y-2 text-stone-600">
                        <li><a href="#" class="hover:text-stone-900">Mentions légales</a></li>
                        <li><a href="#" class="hover:text-stone-900">CGV</a></li>
                        <li><a href="#" class="hover:text-stone-900">Livraison</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-stone-200 mt-8 pt-8 text-center text-stone-500">
                <p>&copy; {{ date('Y') }} Caverne des Enfants. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion - {{ config('app.name') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="https://storage.lacavernedesenfants.com/images-banniere/logo.jpg">
    <link rel="apple-touch-icon" href="https://storage.lacavernedesenfants.com/images-banniere/logo.jpg">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Cloudflare Turnstile -->
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-slate-50 via-amber-50 to-slate-100">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- Logo & Titre -->
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-block mb-6">
                    <img src="https://storage.lacavernedesenfants.com/images-banniere/logo.jpg"
                         alt="Logo AFO - Caverne des Enfants"
                         class="w-24 h-24 rounded-full shadow-lg object-cover border-4 border-white mx-auto">
                </a>
                <h1 class="text-3xl font-bold text-slate-900 mb-2" style="font-family: 'Playfair Display', serif;">Bienvenue</h1>
                <p class="text-slate-600">Connectez-vous à votre compte</p>
            </div>

            <!-- Carte de connexion -->
            <div class="bg-white rounded-3xl shadow-xl border border-slate-200 p-8">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-900 mb-2">
                            Adresse email
                        </label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all outline-none"
                            placeholder="votre@email.com"
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Mot de passe -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-900 mb-2">
                            Mot de passe
                        </label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all outline-none"
                            placeholder="••••••••"
                        />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label class="inline-flex items-center cursor-pointer">
                            <input
                                id="remember_me"
                                type="checkbox"
                                name="remember"
                                class="rounded border-slate-300 text-amber-600 shadow-sm focus:ring-amber-500 cursor-pointer"
                            />
                            <span class="ml-2 text-sm text-slate-600">Se souvenir de moi</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-amber-600 hover:text-amber-700 font-medium">
                                Mot de passe oublié ?
                            </a>
                        @endif
                    </div>

                    <!-- Cloudflare Turnstile -->
                    <div>
                        <div class="cf-turnstile" data-sitekey="{{ config('turnstile.site_key') }}"></div>
                        <x-input-error :messages="$errors->get('cf-turnstile-response')" class="mt-2" />
                    </div>

                    <!-- Bouton de connexion -->
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-amber-600 to-amber-700 text-white font-semibold py-3 px-6 rounded-xl hover:from-amber-700 hover:to-amber-800 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                    >
                        Se connecter
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-slate-500">ou</span>
                    </div>
                </div>

                <!-- Créer un compte -->
                <a
                    href="{{ route('register') }}"
                    class="block w-full text-center bg-slate-100 text-slate-900 font-semibold py-3 px-6 rounded-xl hover:bg-slate-200 transition-all border border-slate-300"
                >
                    Créer un compte
                </a>

                <!-- Retour à l'accueil -->
                <div class="mt-6 text-center">
                    <a href="{{ route('home') }}" class="text-sm text-slate-600 hover:text-slate-900 font-medium inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour à l'accueil
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <p class="text-center text-sm text-slate-500 mt-8">
                © {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.
            </p>
        </div>
    </div>
</body>
</html>

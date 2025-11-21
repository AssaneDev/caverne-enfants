<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/jpeg" href="https://storage.lacavernedesenfants.com/images-banniere/logo.jpg">
        <link rel="apple-touch-icon" href="https://storage.lacavernedesenfants.com/images-banniere/logo.jpg">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Cloudflare Turnstile -->
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    </head>
    <body class="font-sans text-stone-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-amber-50 via-stone-100 to-orange-50">
            <div class="mb-8">
                <a href="/" class="flex flex-col items-center gap-4">
                    <img src="https://storage.lacavernedesenfants.com/images-banniere/logo.jpg"
                         alt="Logo AFO - Caverne des Enfants"
                         class="w-24 h-24 rounded-full shadow-lg object-cover border-4 border-white">
                    <h1 class="text-2xl font-bold text-amber-600" style="font-family: 'Playfair Display', serif;">
                        Caverne des Enfants
                    </h1>
                </a>
            </div>

            <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-2xl overflow-hidden sm:rounded-2xl border border-stone-200">
                {{ $slot }}
            </div>

            <div class="mt-6 text-center">
                <a href="/" class="text-sm text-stone-600 hover:text-amber-600 transition-colors">
                    ← Retour à l'accueil
                </a>
            </div>
        </div>
    </body>
</html>

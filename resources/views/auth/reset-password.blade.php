<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-stone-900 mb-2">Réinitialiser votre mot de passe</h2>
        <p class="text-sm text-stone-600">
            Choisissez un nouveau mot de passe sécurisé pour votre compte.
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Adresse e-mail" class="text-stone-700 font-semibold" />
            <x-text-input id="email"
                          class="block mt-2 w-full rounded-lg border-stone-300 focus:border-amber-500 focus:ring-amber-500"
                          type="email"
                          name="email"
                          :value="old('email', $request->email)"
                          required
                          autofocus
                          autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Nouveau mot de passe" class="text-stone-700 font-semibold" />
            <x-text-input id="password"
                          class="block mt-2 w-full rounded-lg border-stone-300 focus:border-amber-500 focus:ring-amber-500"
                          type="password"
                          name="password"
                          required
                          autocomplete="new-password"
                          placeholder="Minimum 8 caractères" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" value="Confirmer le mot de passe" class="text-stone-700 font-semibold" />
            <x-text-input id="password_confirmation"
                          class="block mt-2 w-full rounded-lg border-stone-300 focus:border-amber-500 focus:ring-amber-500"
                          type="password"
                          name="password_confirmation"
                          required
                          autocomplete="new-password"
                          placeholder="Confirmez votre mot de passe" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Cloudflare Turnstile -->
        <div>
            <div class="flex justify-center">
                <div class="cf-turnstile" data-sitekey="{{ config('turnstile.site_key') }}"></div>
            </div>
            <x-input-error :messages="$errors->get('cf-turnstile-response')" class="mt-2" />
        </div>

        <div class="flex flex-col gap-4">
            <x-primary-button class="w-full justify-center bg-amber-600 hover:bg-amber-700 focus:bg-amber-700 active:bg-amber-900 py-3 text-base">
                Réinitialiser le mot de passe
            </x-primary-button>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-sm text-stone-600 hover:text-amber-600 transition-colors">
                    Retour à la connexion
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>

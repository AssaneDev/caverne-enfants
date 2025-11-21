<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-stone-900 mb-2">Mot de passe oublié ?</h2>
        <p class="text-sm text-stone-600">
            Pas de problème ! Indiquez-nous votre adresse e-mail et nous vous enverrons un lien de réinitialisation.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Adresse e-mail" class="text-stone-700 font-semibold" />
            <x-text-input id="email"
                          class="block mt-2 w-full rounded-lg border-stone-300 focus:border-amber-500 focus:ring-amber-500"
                          type="email"
                          name="email"
                          :value="old('email')"
                          required
                          autofocus
                          placeholder="votre@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
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
                Envoyer le lien de réinitialisation
            </x-primary-button>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-sm text-stone-600 hover:text-amber-600 transition-colors">
                    Retour à la connexion
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>

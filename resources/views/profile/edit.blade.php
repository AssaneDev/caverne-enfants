<x-layouts.app metaTitle="Modifier mon profil - Caverne des Enfants">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-8">
            <a href="{{ route('account.index') }}" 
               class="text-amber-600 hover:text-amber-700 font-medium mb-4 inline-block">
                ← Retour au tableau de bord
            </a>
            <h1 class="text-3xl font-bold text-stone-900">Modifier mon profil</h1>
            <p class="text-stone-600 mt-2">Gérez vos informations personnelles et votre sécurité.</p>
        </div>

        <div class="space-y-8">
            <div class="bg-white rounded-xl shadow-lg border border-stone-100 p-8">
                <h2 class="text-xl font-semibold text-stone-900 mb-6">Informations personnelles</h2>
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-stone-100 p-8">
                <h2 class="text-xl font-semibold text-stone-900 mb-6">Modifier le mot de passe</h2>
                @include('profile.partials.update-password-form')
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-red-100 p-8">
                <h2 class="text-xl font-semibold text-red-900 mb-6">Zone de danger</h2>
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-layouts.app>

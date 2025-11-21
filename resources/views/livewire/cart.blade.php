<div>
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

    {{-- Section Message Solidaire --}}
    <section class="py-8 bg-gradient-to-br from-amber-50 to-orange-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-lg p-6 md:p-8 border-l-4 border-amber-500">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg md:text-xl text-stone-700 leading-relaxed">
                            Acheter une œuvre de la Caverne, c'est soutenir concrètement les sorties scolaires culturelles et les ateliers d'art et de poésie que nous proposons chaque année aux enfants de nos écoles partenaires.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-2xl sm:text-3xl font-bold text-stone-900 mb-8">Mon panier</h1>

    @if(count($cartItems) > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @foreach($cartItems as $item)
                <div class="flex flex-col sm:flex-row sm:items-center p-4 sm:p-6 border-b border-stone-200 last:border-b-0 gap-4">
                    {{-- Image --}}
                    <div class="w-full sm:w-24 h-48 sm:h-24 bg-stone-100 rounded-lg flex-shrink-0">
                        @if($item->artwork->image_path)
                            <img src="{{ asset('storage/' . $item->artwork->image_path) }}"
                                 alt="{{ $item->artwork->title }}"
                                 class="w-full h-full object-cover rounded-lg">
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-semibold text-stone-900 truncate">
                            {{ $item->artwork->title }}
                        </h3>

                        @if($item->artwork->artist)
                            <p class="text-stone-600 text-sm sm:text-base">par {{ $item->artwork->artist->name }}</p>
                        @endif

                        @if($item->artwork->collection)
                            <p class="text-sm text-stone-500">{{ $item->artwork->collection->name }}</p>
                        @endif
                    </div>

                    {{-- Price and Actions --}}
                    <div class="flex flex-col sm:flex-row items-end sm:items-center gap-3 sm:gap-4">
                        <span class="text-xl sm:text-2xl font-bold text-amber-600">
                            {{ number_format($item->artwork->price_cents / 100, 0, ',', ' ') }} €
                        </span>

                        <button wire:click="removeItem('{{ $item->id }}')"
                                class="flex items-center gap-2 text-red-600 hover:text-white bg-red-50 hover:bg-red-600 px-4 py-2 rounded-lg transition-all duration-200 font-medium text-sm group">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span>Supprimer</span>
                        </button>
                    </div>
                </div>
            @endforeach

            {{-- Total Section --}}
            <div class="p-4 sm:p-6 bg-stone-50">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
                    <span class="text-xl font-semibold text-stone-900">Total</span>
                    <span class="text-2xl sm:text-3xl font-bold text-amber-600">
                        {{ number_format($total, 0, ',', ' ') }} €
                    </span>
                </div>

                <a href="{{ route('checkout.show') }}"
                   class="w-full bg-stone-900 text-white py-3 sm:py-4 rounded-lg hover:bg-stone-800 transition-colors text-base sm:text-lg font-semibold text-center block">
                    Procéder au paiement
                </a>
            </div>
        </div>
    @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-stone-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>

            <h2 class="text-xl font-semibold text-stone-900 mb-2">Votre panier est vide</h2>
            <p class="text-stone-600 mb-6">Découvrez nos collections d'œuvres uniques</p>

            <a href="{{ route('collections.index') }}"
               class="inline-block bg-amber-600 text-white px-6 py-3 rounded-lg hover:bg-amber-700 transition-colors">
                Découvrir nos œuvres
            </a>
        </div>
    @endif
    </div>
</div>

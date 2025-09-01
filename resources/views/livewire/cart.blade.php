<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-stone-900 mb-8">Mon panier</h1>
    
    @if(count($cartItems) > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @foreach($cartItems as $item)
                <div class="flex items-center p-6 border-b border-stone-200 last:border-b-0">
                    <div class="w-24 h-24 bg-stone-100 rounded-lg mr-6 flex-shrink-0">
                        @if($item->artwork->image_path)
                            <img src="{{ asset('storage/' . $item->artwork->image_path) }}" 
                                 alt="{{ $item->artwork->getTranslation('title', app()->getLocale()) }}"
                                 class="w-full h-full object-cover rounded-lg">
                        @endif
                    </div>
                    
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-stone-900">
                            {{ $item->artwork->getTranslation('title', app()->getLocale()) }}
                        </h3>
                        
                        @if($item->artwork->artist)
                            <p class="text-stone-600">par {{ $item->artwork->artist->name }}</p>
                        @endif
                        
                        @if($item->artwork->collection)
                            <p class="text-sm text-stone-500">{{ $item->artwork->collection->getTranslation('name', app()->getLocale()) }}</p>
                        @endif
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <span class="text-xl font-bold text-amber-600">
                            {{ number_format($item->artwork->price_cents / 100, 0, ',', ' ') }} €
                        </span>
                        
                        <button wire:click="removeItem('{{ $item->id }}')" 
                                class="text-red-600 hover:text-red-800 p-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach
            
            <div class="p-6 bg-stone-50">
                <div class="flex justify-between items-center mb-6">
                    <span class="text-xl font-semibold text-stone-900">Total</span>
                    <span class="text-2xl font-bold text-amber-600">
                        {{ number_format($total, 0, ',', ' ') }} €
                    </span>
                </div>
                
                <a href="{{ route('checkout.show') }}" 
                   class="w-full bg-stone-900 text-white py-4 rounded-lg hover:bg-stone-800 transition-colors text-lg font-semibold text-center block">
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

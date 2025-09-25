<x-layouts.app metaTitle="Commande confirmée - Caverne des Enfants">
    <div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-stone-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Header avec animation -->
            <div class="text-center mb-12">
                <div class="bg-gradient-to-r from-green-400 to-emerald-500 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-8 shadow-lg animate-pulse">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                
                <h1 class="text-4xl font-bold text-stone-900 mb-4">Paiement réussi !</h1>
                <h2 class="text-2xl font-semibold text-amber-600 mb-2">Commande #{{ $order->order_number }}</h2>
                
                <div class="bg-white rounded-xl shadow-sm border border-green-100 p-6 max-w-md mx-auto">
                    <p class="text-lg text-stone-700 mb-2">
                        <span class="font-semibold text-green-600">✓ Paiement confirmé</span>
                    </p>
                    <p class="text-stone-600">
                        Votre commande a été traitée avec succès
                    </p>
                    <p class="text-sm text-stone-500 mt-2">
                        {{ $order->created_at->format('d/m/Y à H:i') }}
                    </p>
                </div>
            </div>
        
            <!-- Détails de la commande -->
            <div class="bg-white rounded-xl shadow-lg border border-stone-100 p-8 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-semibold text-stone-900">Votre commande</h2>
                    <span class="bg-amber-100 text-amber-800 px-4 py-2 rounded-full text-sm font-medium">
                        {{ $order->items->count() }} œuvre{{ $order->items->count() > 1 ? 's' : '' }}
                    </span>
                </div>
                
                <div class="space-y-6">
                    @foreach($order->items as $item)
                        <div class="flex items-center space-x-6 p-4 bg-stone-50 rounded-xl">
                            <div class="w-20 h-20 bg-stone-200 rounded-xl overflow-hidden shadow-sm">
                                @if($item->artwork->image_path)
                                    <img src="{{ asset('storage/' . $item->artwork->image_path) }}" 
                                         alt="{{ $item->title_snapshot }}"
                                         class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-lg text-stone-900 mb-1">
                                    {{ $item->title_snapshot }}
                                </h3>
                                <p class="text-stone-600 mb-2">par <span class="font-medium">{{ $item->artwork->artist->name }}</span></p>
                                @if($item->artwork->collection)
                                    <p class="text-sm text-amber-600 font-medium">
                                        Collection : {{ $item->artwork->collection->name }}
                                    </p>
                                @endif
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-bold text-stone-900">{{ number_format($item->price_cents / 100, 0, ',', ' ') }} €</span>
                                <p class="text-sm text-stone-500">Qté: {{ $item->qty }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="border-t border-stone-200 mt-8 pt-6">
                    <div class="space-y-2">
                        <div class="flex justify-between text-stone-600">
                            <span>Sous-total</span>
                            <span>{{ number_format($order->subtotal_cents / 100, 0, ',', ' ') }} €</span>
                        </div>
                        @if($order->shipping_cents > 0)
                            <div class="flex justify-between text-stone-600">
                                <span>Livraison</span>
                                <span>{{ number_format($order->shipping_cents / 100, 0, ',', ' ') }} €</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center text-2xl font-bold pt-2 border-t border-stone-100">
                            <span class="text-stone-900">Total</span>
                            <span class="text-amber-600">{{ number_format($order->total_cents / 100, 0, ',', ' ') }} €</span>
                        </div>
                    </div>
                </div>
            </div>
        
            <!-- Informations et actions -->
            <div class="bg-white rounded-xl shadow-lg border border-stone-100 p-8">
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Informations de livraison -->
                    <div>
                        <h3 class="text-lg font-semibold text-stone-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 text-amber-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Confirmation envoyée
                        </h3>
                        <p class="text-stone-600 mb-2">
                            Un email de confirmation a été envoyé à :
                        </p>
                        <p class="font-semibold text-stone-900 bg-stone-50 px-3 py-2 rounded-lg">
                            {{ $order->billing_email }}
                        </p>
                    </div>
                    
                    <!-- Prochaines étapes -->
                    <div>
                        <h3 class="text-lg font-semibold text-stone-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 text-amber-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Prochaines étapes
                        </h3>
                        <div class="space-y-3 text-stone-600">
                            <div class="flex items-start space-x-3">
                                <span class="bg-green-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">1</span>
                                <p class="text-sm">Préparation de votre commande (24-48h)</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <span class="bg-amber-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">2</span>
                                <p class="text-sm">Expédition et notification de suivi</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <span class="bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">3</span>
                                <p class="text-sm">Livraison (3-5 jours ouvrés)</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Boutons d'action -->
                <div class="mt-8 pt-6 border-t border-stone-100">
                    <div class="flex flex-col sm:flex-row justify-center items-center space-y-3 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('collections.index') }}" 
                           class="w-full sm:w-auto bg-stone-100 text-stone-800 px-8 py-4 rounded-xl hover:bg-stone-200 transition-colors font-medium text-center">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                            </svg>
                            Continuer mes achats
                        </a>
                        
                        <a href="{{ route('account.orders.show', $order) }}" 
                           class="w-full sm:w-auto bg-gradient-to-r from-amber-500 to-amber-600 text-white px-8 py-4 rounded-xl hover:from-amber-600 hover:to-amber-700 transition-all duration-200 font-semibold text-center shadow-lg">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Voir ma commande
                        </a>
                    </div>
                    
                    @if(!Auth::check())
                        <div class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                            <p class="text-center text-amber-800">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Un compte a été créé automatiquement avec l'email <strong>{{ $order->billing_email }}</strong>
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
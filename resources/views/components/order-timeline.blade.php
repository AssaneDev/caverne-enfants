@props(['order'])

<div class="bg-gradient-to-br from-white via-amber-50/30 to-stone-50 rounded-2xl shadow-2xl border border-amber-100 p-10 mb-8">
    <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-amber-400 to-amber-600 rounded-full shadow-lg mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
        </div>
        <h2 class="text-3xl font-bold text-stone-900 mb-2">Suivi de votre commande</h2>
        <p class="text-stone-600">Commande #{{ $order->order_number }}</p>
    </div>
    
    <div class="relative max-w-4xl mx-auto">
        <!-- Ligne verticale principale avec gradient -->
        <div class="absolute left-10 top-0 bottom-0 w-1 bg-gradient-to-b from-green-400 via-blue-400 to-purple-400 rounded-full opacity-30"></div>
        
        <div class="space-y-12">
            <!-- 1. Commande passée -->
            <div class="flex items-start space-x-8">
                <div class="relative z-10 w-20 h-20 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center shadow-2xl border-4 border-white animate-pulse">
                    <div class="absolute inset-0 bg-green-500 rounded-full animate-ping opacity-20"></div>
                    <svg class="w-10 h-10 text-white relative z-10" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="flex-1 pt-3 bg-white rounded-2xl shadow-lg p-6 border border-green-100">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xl font-bold text-green-700 flex items-center">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                            Commande passée
                        </h3>
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">TERMINÉ</span>
                    </div>
                    <p class="text-stone-700 mb-3 font-medium">Votre commande a été enregistrée avec succès</p>
                    <div class="flex items-center text-sm text-stone-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $order->created_at->format('d/m/Y à H:i') }}
                    </div>
                </div>
            </div>
            
            <!-- 2. Paiement -->
            @php
                $isPaid = in_array($order->status->value, ['paid', 'preparing', 'shipped', 'delivered']);
                $paidDate = $order->paid_at ?? $order->updated_at;
            @endphp
            <div class="flex items-start space-x-8">
                <div class="relative z-10 w-20 h-20 {{ $isPaid ? 'bg-gradient-to-br from-green-400 to-green-600' : 'bg-gradient-to-br from-stone-300 to-stone-400' }} rounded-full flex items-center justify-center shadow-2xl border-4 border-white transition-all duration-500 {{ $isPaid ? 'animate-bounce' : '' }}">
                    @if($isPaid)
                        <div class="absolute inset-0 bg-green-500 rounded-full animate-ping opacity-20"></div>
                        <svg class="w-10 h-10 text-white relative z-10" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    @else
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @endif
                </div>
                <div class="flex-1 pt-3 bg-white rounded-2xl shadow-lg p-6 border {{ $isPaid ? 'border-green-200 bg-gradient-to-r from-green-50 to-green-50' : 'border-stone-200' }}">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xl font-bold {{ $isPaid ? 'text-green-700' : 'text-stone-500' }} flex items-center">
                            @if($isPaid)
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                            @endif
                            {{ $isPaid ? 'Paiement confirmé' : 'En attente de paiement' }}
                        </h3>
                        @if($isPaid)
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">TERMINÉ</span>
                        @else
                            <span class="bg-stone-100 text-stone-600 px-3 py-1 rounded-full text-xs font-bold">EN ATTENTE</span>
                        @endif
                    </div>
                    <p class="text-stone-700 mb-3 font-medium">
                        {{ $isPaid ? 'Votre paiement a été traité avec succès' : 'Nous attendons la confirmation de votre paiement' }}
                    </p>
                    @if($isPaid && $paidDate)
                        <div class="flex items-center text-sm text-green-600">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $paidDate->format('d/m/Y à H:i') }}
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- 3. Préparation -->
            @php
                $isPreparing = in_array($order->status->value, ['preparing', 'shipped', 'delivered']);
            @endphp
            <div class="flex items-start space-x-8">
                <div class="relative z-10 w-20 h-20 {{ $isPreparing ? 'bg-gradient-to-br from-green-400 to-green-600' : 'bg-gradient-to-br from-stone-300 to-stone-400' }} rounded-full flex items-center justify-center shadow-2xl border-4 border-white transition-all duration-500">
                    @if($isPreparing)
                        <div class="absolute inset-0 bg-green-500 rounded-full animate-ping opacity-20"></div>
                        <svg class="w-10 h-10 text-white relative z-10" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    @else
                        <div class="w-10 h-10 border-4 border-white rounded-full bg-stone-400 flex items-center justify-center">
                            <span class="text-white font-bold text-lg">2</span>
                        </div>
                    @endif
                </div>
                <div class="flex-1 pt-3 bg-white rounded-2xl shadow-lg p-6 border {{ $isPreparing ? 'border-green-200 bg-gradient-to-r from-green-50 to-green-50' : 'border-stone-200' }}">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xl font-bold {{ $isPreparing ? 'text-green-700' : 'text-stone-500' }} flex items-center">
                            @if($isPreparing)
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                            @endif
                            {{ $isPreparing ? 'Préparation en cours' : 'En attente de préparation' }}
                        </h3>
                        @if($isPreparing)
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">TERMINÉ</span>
                        @else
                            <span class="bg-stone-100 text-stone-600 px-3 py-1 rounded-full text-xs font-bold">EN ATTENTE</span>
                        @endif
                    </div>
                    <p class="text-stone-700 mb-3 font-medium">
                        {{ $isPreparing ? 'Nos équipes préparent soigneusement votre commande' : 'La préparation démarrera après confirmation du paiement' }}
                    </p>
                    @if($isPreparing)
                        <div class="flex items-center text-sm text-stone-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Préparation démarrée
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- 4. Expédition -->
            @php
                $isShipped = in_array($order->status->value, ['shipped', 'delivered']);
            @endphp
            <div class="flex items-start space-x-8">
                <div class="relative z-10 w-20 h-20 {{ $isShipped ? 'bg-gradient-to-br from-green-400 to-green-600' : 'bg-gradient-to-br from-stone-300 to-stone-400' }} rounded-full flex items-center justify-center shadow-2xl border-4 border-white transition-all duration-500">
                    @if($isShipped)
                        <div class="absolute inset-0 bg-green-500 rounded-full animate-ping opacity-20"></div>
                        <svg class="w-10 h-10 text-white relative z-10" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    @else
                        <div class="w-10 h-10 border-4 border-white rounded-full bg-stone-400 flex items-center justify-center">
                            <span class="text-white font-bold text-lg">3</span>
                        </div>
                    @endif
                </div>
                <div class="flex-1 pt-3 bg-white rounded-2xl shadow-lg p-6 border {{ $isShipped ? 'border-green-200 bg-gradient-to-r from-green-50 to-green-50' : 'border-stone-200' }}">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xl font-bold {{ $isShipped ? 'text-green-700' : 'text-stone-500' }} flex items-center">
                            @if($isShipped)
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                            @endif
                            {{ $isShipped ? 'Commande expédiée' : 'En attente d\'expédition' }}
                        </h3>
                        @if($isShipped)
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">TERMINÉ</span>
                        @else
                            <span class="bg-stone-100 text-stone-600 px-3 py-1 rounded-full text-xs font-bold">EN ATTENTE</span>
                        @endif
                    </div>
                    <p class="text-stone-700 mb-3 font-medium">
                        {{ $isShipped ? 'Votre commande est en route vers vous' : 'L\'expédition aura lieu après préparation' }}
                    </p>
                    @if($isShipped)
                        <div class="space-y-2">
                            @if($order->shipped_at)
                                <div class="flex items-center text-sm text-stone-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Expédiée le {{ $order->shipped_at->format('d/m/Y à H:i') }}
                                </div>
                            @endif
                            @if($order->tracking_carrier && $order->tracking_number)
                                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mt-3">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-bold text-green-800 mb-1">Informations de suivi</p>
                                            <p class="text-green-700 font-medium">{{ $order->tracking_carrier }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-green-600 mb-1">Numéro de suivi</p>
                                            <p class="font-mono text-sm font-bold text-green-800 bg-white px-3 py-1 rounded-lg border border-green-200">{{ $order->tracking_number }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- 5. Livraison -->
            @php
                $isDelivered = $order->status->value === 'delivered';
            @endphp
            <div class="flex items-start space-x-8">
                <div class="relative z-10 w-20 h-20 {{ $isDelivered ? 'bg-gradient-to-br from-green-400 to-green-600' : 'bg-gradient-to-br from-stone-300 to-stone-400' }} rounded-full flex items-center justify-center shadow-2xl border-4 border-white transition-all duration-500 {{ $isDelivered ? 'animate-bounce' : '' }}">
                    @if($isDelivered)
                        <div class="absolute inset-0 bg-green-500 rounded-full animate-ping opacity-20"></div>
                        <div class="relative z-10 flex items-center justify-center">
                            <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    @else
                        <div class="w-10 h-10 border-4 border-white rounded-full bg-stone-400 flex items-center justify-center">
                            <span class="text-white font-bold text-lg">4</span>
                        </div>
                    @endif
                </div>
                <div class="flex-1 pt-3 bg-white rounded-2xl shadow-lg p-6 border {{ $isDelivered ? 'border-green-200 bg-gradient-to-r from-green-50 to-green-50' : 'border-stone-200' }}">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xl font-bold {{ $isDelivered ? 'text-green-700' : 'text-stone-500' }} flex items-center">
                            @if($isDelivered)
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                                🎉
                            @endif
                            {{ $isDelivered ? 'Commande livrée' : 'En attente de livraison' }}
                        </h3>
                        @if($isDelivered)
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">✓ LIVRÉ</span>
                        @else
                            <span class="bg-stone-100 text-stone-600 px-3 py-1 rounded-full text-xs font-bold">EN ATTENTE</span>
                        @endif
                    </div>
                    <p class="text-stone-700 mb-3 font-medium">
                        {{ $isDelivered ? 'Félicitations ! Votre commande a été livrée avec succès !' : 'Livraison prévue dans 3-5 jours ouvrés après expédition' }}
                    </p>
                    @if($isDelivered && $order->delivered_at)
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4 mt-3">
                            <div class="flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-green-800 font-semibold">Livrée le {{ $order->delivered_at->format('d/m/Y à H:i') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Informations supplémentaires -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Estimation de livraison -->
            @if($isShipped && !$isDelivered)
                <div class="bg-gradient-to-r from-blue-50 via-purple-50 to-pink-50 rounded-2xl border-2 border-blue-200 p-6 shadow-lg">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-purple-600 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-blue-900">🚚 Livraison estimée</h4>
                            <p class="text-blue-800 font-semibold text-lg">
                                @if($order->shipped_at)
                                    {{ $order->shipped_at->addDays(3)->format('d/m/Y') }} - {{ $order->shipped_at->addDays(5)->format('d/m/Y') }}
                                @else
                                    3-5 jours après expédition
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Informations de contact -->
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl border-2 border-amber-200 p-6 shadow-lg">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-400 to-orange-600 rounded-full flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-amber-900">📞 Une question ?</h4>
                        <p class="text-amber-800">Contactez-nous pour toute information</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Statuts spéciaux avec design amélioré -->
        @if($order->status->value === 'canceled')
            <div class="mt-12 bg-gradient-to-r from-red-50 via-pink-50 to-red-50 rounded-2xl border-2 border-red-200 p-8 shadow-xl">
                <div class="flex items-center justify-center space-x-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-red-400 to-red-600 rounded-full flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div class="text-center">
                        <h4 class="text-2xl font-bold text-red-900 mb-2">❌ Commande annulée</h4>
                        <p class="text-red-700 font-medium">Cette commande a été annulée. Si vous avez des questions, contactez-nous.</p>
                    </div>
                </div>
            </div>
        @endif
        
        @if($order->status->value === 'failed')
            <div class="mt-12 bg-gradient-to-r from-red-50 via-orange-50 to-red-50 rounded-2xl border-2 border-red-200 p-8 shadow-xl">
                <div class="flex items-center justify-center space-x-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-red-400 to-orange-600 rounded-full flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="text-center">
                        <h4 class="text-2xl font-bold text-red-900 mb-2">⚠️ Échec de paiement</h4>
                        <p class="text-red-700 font-medium">Le paiement n'a pas pu être traité. Veuillez réessayer ou nous contacter.</p>
                        <a href="{{ route('checkout.show') }}" class="inline-block mt-4 bg-red-600 text-white px-6 py-3 rounded-xl hover:bg-red-700 transition-colors font-semibold">
                            Réessayer le paiement
                        </a>
                    </div>
                </div>
            </div>
        @endif
        
        <!-- Message de félicitations pour les commandes livrées -->
        @if($isDelivered)
            <div class="mt-12 bg-gradient-to-r from-green-50 via-green-50 to-green-50 rounded-2xl border-2 border-green-200 p-8 shadow-xl text-center">
                <div class="mb-4">
                    <span class="text-6xl">🎉</span>
                </div>
                <h3 class="text-2xl font-bold text-green-900 mb-3">Félicitations !</h3>
                <p class="text-green-800 font-medium mb-6">Votre commande a été livrée avec succès. Nous espérons que vous apprécierez vos nouvelles œuvres d'art !</p>
                <div class="flex justify-center space-x-4">
                    <a href="{{ route('collections.index') }}" 
                       class="bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 transition-colors font-semibold">
                        Découvrir d'autres œuvres
                    </a>
                    @if($isPaid)
                        <a href="{{ route('account.invoices.pdf', $order) }}" 
                           class="bg-stone-600 text-white px-6 py-3 rounded-xl hover:bg-stone-700 transition-colors font-semibold" target="_blank">
                            Télécharger la facture
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
<x-layouts.app :metaTitle="'Commande ' . $order->order_number . ' - Caverne des Enfants'">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-8">
            <a href="{{ route('account.orders') }}" 
               class="text-amber-600 hover:text-amber-700 font-medium mb-4 inline-block">
                ← Retour aux commandes
            </a>
            
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-stone-900">Commande {{ $order->order_number }}</h1>
                    <p class="text-stone-600 mt-2">Passée le {{ $order->created_at->format('d/m/Y à H:i') }}</p>
                </div>
                
                <span class="inline-block px-4 py-2 rounded-full text-lg font-medium
                    @if($order->status->value === 'paid') bg-green-100 text-green-800
                    @elseif($order->status->value === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($order->status->value === 'shipped') bg-blue-100 text-blue-800
                    @elseif($order->status->value === 'delivered') bg-green-100 text-green-800
                    @else bg-gray-100 text-gray-800 @endif">
                    @switch($order->status->value)
                        @case('pending') En attente de paiement @break
                        @case('paid') Payé @break
                        @case('shipped') Expédié @break
                        @case('delivered') Livré @break
                        @case('canceled') Annulé @break
                        @default {{ ucfirst($order->status->value) }}
                    @endswitch
                </span>
            </div>
        </div>

        <!-- Timeline de suivi moderne -->
        <x-order-timeline :order="$order" />

        <!-- Détail de la commande -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold text-stone-900 mb-6">Œuvres commandées</h2>
            
            <div class="space-y-4">
                @foreach($order->items as $item)
                    <div class="flex items-center space-x-4 p-4 border border-stone-200 rounded-lg">
                        <div class="w-20 h-20 bg-stone-100 rounded-lg overflow-hidden">
                            @if($item->artwork->image_path)
                                <img src="{{ asset('storage/' . $item->artwork->image_path) }}" 
                                     alt="{{ $item->title_snapshot }}"
                                     class="w-full h-full object-cover">
                            @endif
                        </div>
                        
                        <div class="flex-1">
                            <h3 class="font-semibold text-stone-900">{{ $item->title_snapshot }}</h3>
                            <p class="text-stone-600">par {{ $item->artwork->artist->name }}</p>
                            @if($item->artwork->collection)
                                <p class="text-sm text-stone-500">{{ $item->artwork->collection->getTranslation('name', app()->getLocale()) }}</p>
                            @endif
                            <p class="text-sm text-stone-500">Réf: {{ $item->artwork->sku }}</p>
                        </div>
                        
                        <div class="text-right">
                            <span class="text-lg font-bold text-stone-900">
                                {{ number_format($item->price_cents / 100, 0, ',', ' ') }} €
                            </span>
                            <p class="text-sm text-stone-500">Qté: {{ $item->qty }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Résumé financier -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold text-stone-900 mb-6">Résumé de la commande</h2>
            
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-stone-600">Sous-total</span>
                    <span>{{ number_format($order->subtotal_cents / 100, 0, ',', ' ') }} €</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-stone-600">Livraison</span>
                    <span>{{ $order->shipping_cents > 0 ? number_format($order->shipping_cents / 100, 0, ',', ' ') . ' €' : 'Incluse' }}</span>
                </div>
                @if($order->tax_cents > 0)
                    <div class="flex justify-between">
                        <span class="text-stone-600">TVA</span>
                        <span>{{ number_format($order->tax_cents / 100, 0, ',', ' ') }} €</span>
                    </div>
                @endif
                <div class="border-t border-stone-200 pt-2 flex justify-between text-lg font-bold">
                    <span>Total</span>
                    <span class="text-amber-600">{{ number_format($order->total_cents / 100, 0, ',', ' ') }} €</span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-between items-center">
            <a href="{{ route('account.orders') }}" 
               class="bg-stone-200 text-stone-800 px-6 py-3 rounded-lg hover:bg-stone-300 transition-colors">
                ← Retour aux commandes
            </a>
            
            <div class="flex space-x-4">
                @if($order->status->value === 'paid')
                    <a href="{{ route('account.invoices.pdf', $order) }}" 
                       class="bg-amber-600 text-white px-6 py-3 rounded-lg hover:bg-amber-700 transition-colors"
                       target="_blank">
                        Télécharger la facture
                    </a>
                @endif
                
                <a href="{{ route('collections.index') }}" 
                   class="bg-stone-900 text-white px-6 py-3 rounded-lg hover:bg-stone-800 transition-colors">
                    Continuer mes achats
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
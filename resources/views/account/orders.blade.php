<x-layouts.app metaTitle="Mes commandes - Caverne des Enfants">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-stone-900">Mes commandes</h1>
            <p class="text-stone-600 mt-2">Retrouvez l'historique de toutes vos commandes.</p>
        </div>

        @if($orders->count() > 0)
            <div class="space-y-4">
                @foreach($orders as $order)
                    <div class="bg-gradient-to-r from-white to-amber-50/30 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border-l-4 
                        @if($order->status->value === 'delivered') border-green-500
                        @elseif($order->status->value === 'shipped') border-blue-500  
                        @elseif($order->status->value === 'paid') border-green-400
                        @elseif($order->status->value === 'pending') border-yellow-500
                        @else border-stone-400 @endif 
                        overflow-hidden group">
                        
                        <div class="p-4">
                            <div class="flex items-center justify-between">
                                <!-- Gauche: Info commande + artwork -->
                                <div class="flex items-center space-x-4 flex-1">
                                    @if($order->items->count() > 0)
                                        <div class="relative">
                                            <div class="w-16 h-16 bg-stone-100 rounded-lg overflow-hidden shadow-md">
                                                @if($order->items->first()->artwork->image_path)
                                                    <img src="{{ asset('storage/' . $order->items->first()->artwork->image_path) }}" 
                                                         alt="{{ $order->items->first()->title_snapshot }}"
                                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                                @endif
                                            </div>
                                            @if($order->items->count() > 1)
                                                <span class="absolute -top-2 -right-2 bg-amber-600 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center shadow-lg">
                                                    +{{ $order->items->count() - 1 }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                    
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-3 mb-1">
                                            <h3 class="text-xl font-bold text-stone-900">{{ $order->order_number }}</h3>
                                            <span class="px-2 py-1 rounded-lg text-xs font-bold
                                                @if($order->status->value === 'paid') bg-green-100 text-green-700
                                                @elseif($order->status->value === 'pending') bg-yellow-100 text-yellow-700
                                                @elseif($order->status->value === 'shipped') bg-blue-100 text-blue-700
                                                @elseif($order->status->value === 'delivered') bg-green-100 text-green-700
                                                @else bg-stone-100 text-stone-700 @endif">
                                                @switch($order->status->value)
                                                    @case('pending') ⏳ EN ATTENTE @break
                                                    @case('paid') ✅ PAYÉ @break
                                                    @case('shipped') 🚚 EXPÉDIÉ @break
                                                    @case('delivered') 📦 LIVRÉ @break
                                                    @case('canceled') ❌ ANNULÉ @break
                                                    @default {{ strtoupper($order->status->value) }}
                                                @endswitch
                                            </span>
                                        </div>
                                        
                                        @if($order->items->count() > 0)
                                            <p class="font-bold text-stone-900 text-base mb-1">{{ $order->items->first()->title_snapshot }}</p>
                                            <p class="text-sm text-stone-600 mb-1">par {{ $order->items->first()->artwork->artist->name }}</p>
                                            @if($order->items->first()->artwork->description)
                                                <p class="text-xs text-stone-500 italic">{{ Str::limit($order->items->first()->artwork->description, 60) }}</p>
                                            @endif
                                        @endif
                                        
                                        <p class="text-xs text-stone-400 mt-2">Commandé le {{ $order->created_at->format('d/m/Y à H:i') }}</p>
                                    </div>
                                </div>
                                
                                <!-- Droite: Prix + actions -->
                                <div class="text-right space-y-2">
                                    <p class="text-2xl font-bold text-amber-600">
                                        {{ number_format($order->total_cents / 100, 0, ',', ' ') }}<span class="text-lg">€</span>
                                    </p>
                                    
                                    <div class="flex flex-col space-y-1">
                                        <a href="{{ route('account.orders.show', $order) }}" 
                                           class="bg-amber-600 text-white px-4 py-2 rounded-lg hover:bg-amber-700 transition-colors text-sm font-medium shadow-md hover:shadow-lg">
                                            📋 Voir détails
                                        </a>
                                        @if($order->status->value === 'paid')
                                            <a href="{{ route('account.invoices.pdf', $order) }}" 
                                               class="bg-stone-600 text-white px-4 py-2 rounded-lg hover:bg-stone-700 transition-colors text-sm font-medium shadow-md"
                                               target="_blank">
                                                📄 Facture
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <svg class="w-16 h-16 text-stone-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                
                <h2 class="text-xl font-semibold text-stone-900 mb-2">Aucune commande</h2>
                <p class="text-stone-600 mb-6">Vous n'avez pas encore passé de commande.</p>
                
                <a href="{{ route('collections.index') }}" 
                   class="inline-block bg-amber-600 text-white px-6 py-3 rounded-lg hover:bg-amber-700 transition-colors">
                    Découvrir nos œuvres
                </a>
            </div>
        @endif
    </div>
</x-layouts.app>
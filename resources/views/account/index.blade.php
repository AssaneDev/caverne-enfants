<x-layouts.app metaTitle="Mon compte - Caverne des Enfants">
    <div class="min-h-screen bg-slate-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header moderne style React -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 mb-8">
                <div class="flex items-center space-x-6">
                    <div class="relative">
                        <div class="w-20 h-20 bg-gradient-to-br from-slate-700 to-slate-900 rounded-2xl flex items-center justify-center shadow-lg">
                            <span class="text-2xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-emerald-500 rounded-full border-2 border-white"></div>
                    </div>
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-slate-900 mb-1">{{ $user->name }}</h1>
                        <p class="text-slate-600 mb-2">Votre espace personnel</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Membre depuis {{ $user->created_at->format('M Y') }}
                        </span>
                    </div>
                    <div class="text-right flex items-center space-x-3">
                        <a href="{{ route('profile.edit') }}"
                           class="inline-flex items-center px-4 py-2 bg-slate-900 text-white rounded-xl hover:bg-slate-800 transition-colors text-sm font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Modifier le profil
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors text-sm font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Se déconnecter
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Statistiques style React Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 mb-1">Total des commandes</p>
                            <p class="text-3xl font-bold text-slate-900">{{ $totalOrders }}</p>
                            <p class="text-xs text-slate-500 mt-1">
                                <span class="inline-flex items-center text-emerald-600">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Depuis {{ $user->created_at->format('M Y') }}
                                </span>
                            </p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-xl">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="w-full bg-slate-100 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full transition-all duration-1000" style="width: {{ min($totalOrders * 15, 100) }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 mb-1">Total dépensé</p>
                            <p class="text-3xl font-bold text-slate-900">{{ number_format($totalSpent, 0, ',', ' ') }}€</p>
                            <p class="text-xs text-slate-500 mt-1">
                                <span class="inline-flex items-center text-emerald-600">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Investissement art
                                </span>
                            </p>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-xl">
                            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="w-full bg-slate-100 rounded-full h-2">
                            <div class="bg-emerald-500 h-2 rounded-full transition-all duration-1000" style="width: {{ min($totalSpent / 15, 100) }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 mb-1">Œuvres acquises</p>
                            <p class="text-3xl font-bold text-slate-900">{{ $recentOrders->filter(fn($o) => $o->status->value === 'paid')->count() }}</p>
                            <p class="text-xs text-slate-500 mt-1">
                                <span class="inline-flex items-center text-emerald-600">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Collection personnelle
                                </span>
                            </p>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-xl">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="w-full bg-slate-100 rounded-full h-2">
                            <div class="bg-purple-500 h-2 rounded-full transition-all duration-1000" style="width: {{ $recentOrders->isNotEmpty() ? min($recentOrders->filter(fn($o) => $o->status->value === 'paid')->count() * 25, 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation rapide style React -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <a href="{{ route('account.orders') }}" 
                   class="group bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md hover:border-blue-300 transition-all duration-200">
                    <div class="flex flex-col items-center text-center">
                        <div class="p-3 bg-blue-50 rounded-xl mb-4 group-hover:bg-blue-100 transition-colors">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-slate-900 group-hover:text-blue-600 transition-colors">Commandes</h3>
                        <p class="text-xs text-slate-500 mt-1">{{ $totalOrders }} commande{{ $totalOrders > 1 ? 's' : '' }}</p>
                    </div>
                </a>
                
                <a href="{{ route('collections.index') }}" 
                   class="group bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md hover:border-purple-300 transition-all duration-200">
                    <div class="flex flex-col items-center text-center">
                        <div class="p-3 bg-purple-50 rounded-xl mb-4 group-hover:bg-purple-100 transition-colors">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-slate-900 group-hover:text-purple-600 transition-colors">Collections</h3>
                        <p class="text-xs text-slate-500 mt-1">Explorer les œuvres</p>
                    </div>
                </a>
                
                <a href="{{ route('cart.show') }}" 
                   class="group bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md hover:border-emerald-300 transition-all duration-200">
                    <div class="flex flex-col items-center text-center">
                        <div class="p-3 bg-emerald-50 rounded-xl mb-4 group-hover:bg-emerald-100 transition-colors">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m1.6 8L6 5H3m4 8v6a1 1 0 001 1h1m0 0a1 1 0 002 0m-2 0a1 1 0 012 0"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-slate-900 group-hover:text-emerald-600 transition-colors">Panier</h3>
                        <p class="text-xs text-slate-500 mt-1">Articles en attente</p>
                    </div>
                </a>
                
                <a href="{{ route('home') }}" 
                   class="group bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md hover:border-amber-300 transition-all duration-200">
                    <div class="flex flex-col items-center text-center">
                        <div class="p-3 bg-amber-50 rounded-xl mb-4 group-hover:bg-amber-100 transition-colors">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m0 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10M9 21h6"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-slate-900 group-hover:text-amber-600 transition-colors">Accueil</h3>
                        <p class="text-xs text-slate-500 mt-1">Page principale</p>
                    </div>
                </a>
            </div>

            <!-- Commandes récentes style React -->
            @if($recentOrders->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
                    <div class="p-6 border-b border-slate-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-bold text-slate-900">Commandes récentes</h2>
                                <p class="text-sm text-slate-600">Vos derniers achats</p>
                            </div>
                            <a href="{{ route('account.orders') }}" 
                               class="inline-flex items-center px-4 py-2 bg-slate-900 text-white rounded-xl hover:bg-slate-800 transition-colors text-sm font-medium">
                                Voir tout
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <div class="p-4 space-y-3">
                        @foreach($recentOrders as $order)
                            <div class="bg-gradient-to-r from-white to-amber-50/30 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 border-l-4 
                                @if($order->status->value === 'delivered') border-green-500
                                @elseif($order->status->value === 'shipped') border-blue-500  
                                @elseif($order->status->value === 'paid') border-green-400
                                @elseif($order->status->value === 'pending') border-yellow-500
                                @else border-slate-400 @endif 
                                overflow-hidden group">
                                
                                <div class="p-3">
                                    <div class="flex items-center justify-between">
                                        <!-- Gauche: Info commande + artwork -->
                                        <div class="flex items-center space-x-2 flex-1">
                                            @if($order->items->count() > 0)
                                                <div class="relative">
                                                    <div class="w-8 h-8 bg-slate-100 rounded overflow-hidden flex-shrink-0">
                                                        @if($order->items->first()->artwork->image_path)
                                                            <img src="{{ asset('storage/' . $order->items->first()->artwork->image_path) }}" 
                                                                 alt="{{ $order->items->first()->title_snapshot }}"
                                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                                        @endif
                                                    </div>
                                                    @if($order->items->count() > 1)
                                                        <span class="absolute -top-1 -right-1 bg-amber-600 text-white text-xs font-bold rounded-full w-4 h-4 flex items-center justify-center">
                                                            +{{ $order->items->count() - 1 }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                            
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center space-x-2 mb-1">
                                                    <h3 class="font-bold text-slate-900 text-sm">{{ $order->order_number }}</h3>
                                                    <span class="px-2 py-1 rounded-md text-xs font-bold
                                                        @if($order->status->value === 'paid') bg-green-100 text-green-700
                                                        @elseif($order->status->value === 'pending') bg-yellow-100 text-yellow-700
                                                        @elseif($order->status->value === 'shipped') bg-blue-100 text-blue-700
                                                        @elseif($order->status->value === 'delivered') bg-green-100 text-green-700
                                                        @else bg-slate-100 text-slate-700 @endif">
                                                        @switch($order->status->value)
                                                            @case('pending') ATTENTE @break
                                                            @case('paid') PAYÉ @break
                                                            @case('shipped') EXPÉDIÉ @break
                                                            @case('delivered') LIVRÉ @break
                                                            @case('canceled') ANNULÉ @break
                                                            @default {{ strtoupper($order->status->value) }}
                                                        @endswitch
                                                    </span>
                                                </div>
                                                
                                                @if($order->items->count() > 0)
                                                    <p class="font-bold text-slate-900 text-xs truncate">{{ $order->items->first()->title_snapshot }}</p>
                                                    <p class="text-xs text-slate-500">{{ $order->items->first()->artwork->artist->name }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Droite: Prix + action -->
                                        <div class="flex items-center space-x-3">
                                            <p class="text-base font-bold text-amber-600">
                                                {{ number_format($order->total_cents / 100, 0, ',', ' ') }}€
                                            </p>
                                            <a href="{{ route('account.orders.show', $order) }}" 
                                               class="bg-slate-900 text-white px-3 py-1 rounded hover:bg-slate-800 transition-colors text-xs font-medium">
                                                Voir
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center">
                    <div class="mb-8">
                        <div class="w-20 h-20 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        
                        <h2 class="text-xl font-bold text-slate-900 mb-3">Aucune commande</h2>
                        <p class="text-slate-600 mb-8">
                            Découvrez notre collection d'œuvres d'art uniques.
                        </p>
                    </div>
                    
                    <a href="{{ route('collections.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-slate-900 text-white rounded-xl hover:bg-slate-800 transition-colors font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        Découvrir nos collections
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
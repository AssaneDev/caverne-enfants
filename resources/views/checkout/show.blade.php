<x-layouts.app metaTitle="Paiement - Caverne des Enfants">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-2xl sm:text-3xl font-bold text-stone-900 mb-8">Finaliser la commande</h1>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Formulaire de paiement -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-stone-900 mb-6">Informations de facturation</h2>
                
                <form action="{{ route('checkout.process') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="billing_name" class="block text-sm font-medium text-stone-700 mb-1">
                                Nom complet *
                            </label>
                            <input type="text" 
                                   name="billing_name" 
                                   id="billing_name"
                                   value="{{ old('billing_name', Auth::user()->name ?? '') }}"
                                   required
                                   class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                            @error('billing_name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="billing_email" class="block text-sm font-medium text-stone-700 mb-1">
                                Email *
                            </label>
                            <input type="email" 
                                   name="billing_email" 
                                   id="billing_email"
                                   value="{{ old('billing_email', Auth::user()->email ?? '') }}"
                                   required
                                   class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                            @error('billing_email')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div>
                        <label for="billing_address" class="block text-sm font-medium text-stone-700 mb-1">
                            Adresse *
                        </label>
                        <input type="text" 
                               name="billing_address" 
                               id="billing_address"
                               value="{{ old('billing_address') }}"
                               required
                               class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                        @error('billing_address')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="billing_city" class="block text-sm font-medium text-stone-700 mb-1">
                                Ville *
                            </label>
                            <input type="text" 
                                   name="billing_city" 
                                   id="billing_city"
                                   value="{{ old('billing_city') }}"
                                   required
                                   class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                            @error('billing_city')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="billing_postal_code" class="block text-sm font-medium text-stone-700 mb-1">
                                Code postal *
                            </label>
                            <input type="text" 
                                   name="billing_postal_code" 
                                   id="billing_postal_code"
                                   value="{{ old('billing_postal_code') }}"
                                   required
                                   class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                            @error('billing_postal_code')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="billing_country" class="block text-sm font-medium text-stone-700 mb-1">
                                Pays *
                            </label>
                            <select name="billing_country" 
                                    id="billing_country"
                                    required
                                    class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                                <option value="FR" {{ old('billing_country') === 'FR' ? 'selected' : '' }}>France</option>
                                <option value="BE" {{ old('billing_country') === 'BE' ? 'selected' : '' }}>Belgique</option>
                                <option value="CH" {{ old('billing_country') === 'CH' ? 'selected' : '' }}>Suisse</option>
                                <option value="CA" {{ old('billing_country') === 'CA' ? 'selected' : '' }}>Canada</option>
                            </select>
                            @error('billing_country')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="border-t border-stone-200 pt-6">
                        <h3 class="text-lg font-semibold text-stone-900 mb-4">Méthode de paiement</h3>
                        
                        <div class="space-y-3">
                            <label class="flex items-center p-4 border border-stone-200 rounded-lg cursor-pointer hover:bg-stone-50">
                                <input type="radio" 
                                       name="payment_method" 
                                       value="stripe"
                                       {{ old('payment_method', 'stripe') === 'stripe' ? 'checked' : '' }}
                                       class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-stone-300">
                                <div class="ml-3">
                                    <div class="flex items-center">
                                        <span class="text-sm font-medium text-stone-900">Carte bancaire</span>
                                        <div class="ml-2 flex space-x-1">
                                            <span class="text-xs text-stone-500">Visa</span>
                                            <span class="text-xs text-stone-500">Mastercard</span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-stone-500">Paiement sécurisé par Stripe</p>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-4 border border-stone-200 rounded-lg cursor-pointer hover:bg-stone-50">
                                <input type="radio" 
                                       name="payment_method" 
                                       value="paypal"
                                       {{ old('payment_method') === 'paypal' ? 'checked' : '' }}
                                       class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-stone-300">
                                <div class="ml-3">
                                    <span class="text-sm font-medium text-stone-900">PayPal</span>
                                    <p class="text-xs text-stone-500">Paiement sécurisé par PayPal</p>
                                </div>
                            </label>
                        </div>
                        
                        @error('payment_method')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-amber-600 text-white py-4 rounded-lg hover:bg-amber-700 transition-colors text-lg font-semibold">
                        Procéder au paiement
                    </button>
                </form>
            </div>
            
            <!-- Résumé de commande -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-stone-900 mb-6">Résumé de la commande</h2>
                
                <div class="space-y-4">
                    @foreach($cart->items as $item)
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
                            <div class="w-full sm:w-16 h-40 sm:h-16 bg-stone-100 rounded-lg overflow-hidden flex-shrink-0">
                                @if($item->artwork->image_path)
                                    <img src="{{ asset('storage/' . $item->artwork->image_path) }}"
                                         alt="{{ $item->artwork->title }}"
                                         class="w-full h-full object-cover">
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-stone-900 truncate">
                                    {{ $item->artwork->title }}
                                </h3>
                                <p class="text-sm text-stone-600">par {{ $item->artwork->artist->name }}</p>
                            </div>

                            <span class="font-semibold text-stone-900 self-start sm:self-auto">
                                {{ number_format($item->artwork->price, 0, ',', ' ') }} €
                            </span>
                        </div>
                    @endforeach
                </div>
                
                <div class="border-t border-stone-200 mt-6 pt-6">
                    <div class="flex justify-between items-center text-lg font-semibold">
                        <span>Total</span>
                        <span class="text-amber-600">
                            {{ number_format($cart->items->sum(fn($item) => $item->artwork->price), 0, ',', ' ') }} €
                        </span>
                    </div>
                    <p class="text-sm text-stone-500 mt-1">Livraison incluse</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
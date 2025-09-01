<?php

namespace App\Livewire;

use App\Models\Cart as CartModel;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Cart extends Component
{
    public $cartItems = [];
    public $total = 0;

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $cart = $this->getOrCreateCart();
        
        if ($cart) {
            $this->cartItems = $cart->items()
                ->with(['artwork.artist', 'artwork.collection'])
                ->get();
            
            $this->total = $cart->items->sum(function ($item) {
                return $item->artwork->price_cents * $item->qty;
            }) / 100;
        }
    }

    public function removeItem($cartItemId)
    {
        CartItem::find($cartItemId)?->delete();
        $this->loadCart();
        $this->dispatch('cart-updated');
    }

    public function updateQuantity($cartItemId, $qty)
    {
        if ($qty <= 0) {
            $this->removeItem($cartItemId);
            return;
        }

        $cartItem = CartItem::find($cartItemId);
        if ($cartItem && $qty == 1) {
            $cartItem->update(['qty' => $qty]);
            $this->loadCart();
            $this->dispatch('cart-updated');
        }
    }

    private function getOrCreateCart()
    {
        if (Auth::check()) {
            return CartModel::where('user_id', Auth::id())
                ->where('expires_at', '>', now())
                ->first();
        }

        $sessionId = session()->getId();
        return CartModel::where('session_id', $sessionId)
            ->where('expires_at', '>', now())
            ->first();
    }

    public function render()
    {
        return view('livewire.cart');
    }
}

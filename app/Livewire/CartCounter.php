<?php

namespace App\Livewire;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class CartCounter extends Component
{
    public $count = 0;

    public function mount()
    {
        $this->loadCount();
    }

    #[On('cart-updated')]
    public function loadCount()
    {
        $cart = $this->getCart();
        $this->count = $cart ? $cart->items->sum('qty') : 0;
    }

    private function getCart()
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())
                ->where('expires_at', '>', now())
                ->with('items')
                ->first();
        }

        $sessionId = session()->getId();
        return Cart::where('session_id', $sessionId)
            ->where('expires_at', '>', now())
            ->with('items')
            ->first();
    }

    public function render()
    {
        return view('livewire.cart-counter');
    }
}

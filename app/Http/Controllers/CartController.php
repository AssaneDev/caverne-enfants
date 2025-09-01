<?php

namespace App\Http\Controllers;

use App\ArtworkStatus;
use App\Models\Artwork;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function show()
    {
        return view('cart');
    }

    public function add(Artwork $artwork, Request $request)
    {
        if ($artwork->status !== ArtworkStatus::PUBLISHED) {
            return back()->with('error', 'Cette œuvre n\'est plus disponible.');
        }

        $cart = $this->getOrCreateCart();
        
        $existingItem = $cart->items()->where('artwork_id', $artwork->id)->first();
        
        if ($existingItem) {
            return back()->with('info', 'Cette œuvre est déjà dans votre panier.');
        }

        $cart->items()->create([
            'artwork_id' => $artwork->id,
            'qty' => 1,
        ]);

        return back()->with('success', 'Œuvre ajoutée au panier !');
    }

    public function remove(CartItem $cartItem)
    {
        $cartItem->delete();
        return back()->with('success', 'Œuvre retirée du panier.');
    }

    private function getOrCreateCart()
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(
                ['user_id' => Auth::id()],
                ['expires_at' => now()->addDays(7)]
            );
        }

        $sessionId = session()->getId();
        return Cart::firstOrCreate(
            ['session_id' => $sessionId],
            ['expires_at' => now()->addDays(7)]
        );
    }
}

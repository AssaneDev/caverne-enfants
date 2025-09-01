<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class CheckoutController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private OrderService $orderService
    ) {}

    public function show(Request $request)
    {
        $cart = $this->getActiveCart();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.show')
                ->with('error', 'Votre panier est vide.');
        }

        return view('checkout.show', compact('cart'));
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:stripe,paypal',
            'billing_name' => 'required|string|max:255',
            'billing_email' => 'required|email|max:255',
            'billing_address' => 'required|string|max:500',
            'billing_city' => 'required|string|max:255',
            'billing_postal_code' => 'required|string|max:20',
            'billing_country' => 'required|string|max:2',
        ]);

        $cart = $this->getActiveCart();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.show')
                ->with('error', 'Votre panier est vide.');
        }

        try {
            $user = $this->createOrGetUser($validated);
            
            $order = $this->orderService->createFromCart($cart, $validated, $user);
            
            $paymentUrl = $this->paymentService->createPayment(
                $order,
                $validated['payment_method']
            );

            return redirect($paymentUrl);
        } catch (\Exception $e) {
            Log::error('Checkout error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du processus de paiement.');
        }
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');
        $order = Order::where('payment_reference', $sessionId)->first();

        if (!$order) {
            return redirect()->route('home')
                ->with('error', 'Commande introuvable.');
        }

        if ($order->user_id && !Auth::check()) {
            Auth::login(User::find($order->user_id));
        }

        return view('checkout.success', compact('order'));
    }

    public function cancel(Request $request)
    {
        return redirect()->route('cart.show')
            ->with('info', 'Paiement annulé.');
    }

    public function stripeWebhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('stripe-signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $signature, $endpointSecret);
            
            if ($event->type === 'checkout.session.completed') {
                $session = $event->data->object;
                $this->orderService->confirmPayment($session->id, 'stripe');
            }
            
            return response('OK', 200);
        } catch (SignatureVerificationException $e) {
            Log::error('Stripe webhook signature verification failed: ' . $e->getMessage());
            return response('Invalid signature', 400);
        } catch (\Exception $e) {
            Log::error('Stripe webhook error: ' . $e->getMessage());
            return response('Webhook error', 500);
        }
    }

    public function paypalWebhook(Request $request)
    {
        // TODO: Implémenter le webhook PayPal
        return response('OK', 200);
    }

    private function getActiveCart(): ?Cart
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())
                ->where('expires_at', '>', now())
                ->with(['items.artwork.artist', 'items.artwork.collection'])
                ->first();
        }

        $sessionId = session()->getId();
        return Cart::where('session_id', $sessionId)
            ->where('expires_at', '>', now())
            ->with(['items.artwork.artist', 'items.artwork.collection'])
            ->first();
    }
    
    private function createOrGetUser(array $validated): User
    {
        if (Auth::check()) {
            return Auth::user();
        }
        
        $existingUser = User::where('email', $validated['billing_email'])->first();
        
        if ($existingUser) {
            Auth::login($existingUser);
            return $existingUser;
        }
        
        $user = User::create([
            'name' => $validated['billing_name'],
            'email' => $validated['billing_email'],
            'password' => Hash::make(Str::random(12)),
            'email_verified_at' => now(),
        ]);
        
        $user->assignRole('Client');
        Auth::login($user);
        
        return $user;
    }
}

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
        // Gestion du retour Stripe
        $sessionId = $request->get('session_id');

        // Gestion du retour PayPal
        $paypalToken = $request->get('token');

        $order = null;

        if ($sessionId) {
            // Paiement Stripe
            $order = Order::where('payment_reference', $sessionId)->first();
        } elseif ($paypalToken) {
            // Paiement PayPal - le token est l'order ID PayPal
            $order = Order::where('payment_reference', $paypalToken)->first();

            if ($order && $order->status !== \App\OrderStatus::PAID) {
                // Capturer le paiement PayPal
                $captured = $this->paymentService->capturePayPalOrder($paypalToken);

                if ($captured) {
                    // Confirmer la commande
                    $this->orderService->confirmPayment($paypalToken, 'paypal');

                    // Recharger la commande pour avoir le statut mis à jour
                    $order->refresh();
                } else {
                    return redirect()->route('cart.show')
                        ->with('error', 'Erreur lors de la capture du paiement PayPal.');
                }
            }
        }

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

        // Vérifications de sécurité
        if (empty($payload)) {
            Log::warning('Stripe webhook: Empty payload received');
            return response('Empty payload', 400);
        }

        if (empty($signature)) {
            Log::warning('Stripe webhook: Missing signature header');
            return response('Missing signature', 400);
        }

        if (empty($endpointSecret)) {
            Log::error('Stripe webhook: Missing webhook secret configuration');
            return response('Configuration error', 500);
        }

        try {
            $event = Webhook::constructEvent($payload, $signature, $endpointSecret);

            Log::info('Stripe webhook received', [
                'type' => $event->type,
                'id' => $event->id,
                'created' => $event->created
            ]);

            switch ($event->type) {
                case 'checkout.session.completed':
                    $session = $event->data->object;
                    $this->handleCheckoutSessionCompleted($session);
                    break;

                case 'payment_intent.payment_failed':
                    $paymentIntent = $event->data->object;
                    $this->handlePaymentFailed($paymentIntent);
                    break;

                default:
                    Log::info('Stripe webhook: Unhandled event type', ['type' => $event->type]);
            }

            return response('OK', 200);
        } catch (SignatureVerificationException $e) {
            Log::error('Stripe webhook signature verification failed', [
                'error' => $e->getMessage(),
                'payload_preview' => substr($payload, 0, 100)
            ]);
            return response('Invalid signature', 400);
        } catch (\Exception $e) {
            Log::error('Stripe webhook error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response('Webhook error', 500);
        }
    }

    private function handleCheckoutSessionCompleted($session)
    {
        try {
            $orderId = $session->metadata->order_id ?? null;
            $success = $this->orderService->confirmPayment($session->id, 'stripe');

            Log::info('Checkout session completed', [
                'session_id' => $session->id,
                'order_id' => $orderId,
                'success' => $success
            ]);

        } catch (\Exception $e) {
            Log::error('Error handling checkout session completed', [
                'session_id' => $session->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function handlePaymentFailed($paymentIntent)
    {
        try {
            Log::warning('Payment failed', [
                'payment_intent_id' => $paymentIntent->id,
                'failure_code' => $paymentIntent->last_payment_error->code ?? null,
                'failure_message' => $paymentIntent->last_payment_error->message ?? null
            ]);

            // TODO: Marquer la commande comme échouée si nécessaire

        } catch (\Exception $e) {
            Log::error('Error handling payment failed', [
                'payment_intent_id' => $paymentIntent->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function paypalWebhook(Request $request)
    {
        $payload = $request->getContent();
        $headers = $request->headers->all();

        // Vérification de sécurité
        if (empty($payload)) {
            Log::warning('PayPal webhook: Empty payload received');
            return response('Empty payload', 400);
        }

        try {
            $event = json_decode($payload, true);

            if (!isset($event['event_type'])) {
                Log::warning('PayPal webhook: Missing event_type');
                return response('Invalid payload', 400);
            }

            // Vérifier la signature du webhook
            if (!$this->verifyPayPalWebhook($headers, $payload)) {
                Log::warning('PayPal webhook: Invalid signature');
                return response('Invalid signature', 401);
            }

            Log::info('PayPal webhook received', [
                'event_type' => $event['event_type'],
                'event_id' => $event['id'] ?? null,
            ]);

            switch ($event['event_type']) {
                case 'CHECKOUT.ORDER.APPROVED':
                    $this->handlePayPalOrderApproved($event);
                    break;

                case 'PAYMENT.CAPTURE.COMPLETED':
                    $this->handlePayPalCaptureCompleted($event);
                    break;

                case 'PAYMENT.CAPTURE.DENIED':
                case 'PAYMENT.CAPTURE.REFUNDED':
                    $this->handlePayPalPaymentFailed($event);
                    break;

                default:
                    Log::info('PayPal webhook: Unhandled event type', ['type' => $event['event_type']]);
            }

            return response('OK', 200);
        } catch (\Exception $e) {
            Log::error('PayPal webhook error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response('Webhook error', 500);
        }
    }

    private function verifyPayPalWebhook(array $headers, string $payload): bool
    {
        // Pour la production, vous devriez implémenter la vérification de signature
        // https://developer.paypal.com/api/rest/webhooks/rest/#verify-webhook-signature

        $webhookId = config('services.paypal.webhook_id');

        if (empty($webhookId)) {
            Log::warning('PayPal webhook verification skipped: No webhook ID configured');
            return true; // Skip verification if no webhook ID configured
        }

        // TODO: Implémenter la vérification complète de la signature
        // Pour l'instant, on vérifie seulement que la requête provient de PayPal
        return true;
    }

    private function handlePayPalOrderApproved($event)
    {
        try {
            $orderId = $event['resource']['id'] ?? null;

            if (!$orderId) {
                Log::warning('PayPal order approved: Missing order ID');
                return;
            }

            Log::info('PayPal order approved', [
                'paypal_order_id' => $orderId,
            ]);

            // L'ordre est approuvé mais pas encore capturé
            // La capture se fera lors du retour de l'utilisateur
        } catch (\Exception $e) {
            Log::error('Error handling PayPal order approved', [
                'error' => $e->getMessage()
            ]);
        }
    }

    private function handlePayPalCaptureCompleted($event)
    {
        try {
            $resource = $event['resource'] ?? null;
            $paypalOrderId = $resource['supplementary_data']['related_ids']['order_id'] ?? null;

            if (!$paypalOrderId) {
                Log::warning('PayPal capture completed: Missing PayPal order ID');
                return;
            }

            $success = $this->orderService->confirmPayment($paypalOrderId, 'paypal');

            Log::info('PayPal capture completed', [
                'paypal_order_id' => $paypalOrderId,
                'success' => $success
            ]);
        } catch (\Exception $e) {
            Log::error('Error handling PayPal capture completed', [
                'error' => $e->getMessage()
            ]);
        }
    }

    private function handlePayPalPaymentFailed($event)
    {
        try {
            Log::warning('PayPal payment failed or refunded', [
                'event_type' => $event['event_type'],
                'resource' => $event['resource'] ?? null
            ]);

            // TODO: Mettre à jour le statut de la commande si nécessaire
        } catch (\Exception $e) {
            Log::error('Error handling PayPal payment failed', [
                'error' => $e->getMessage()
            ]);
        }
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

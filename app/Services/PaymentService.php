<?php

namespace App\Services;

use App\Models\Order;
use App\PaymentMethod;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class PaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createStripeCheckout(Order $order): string
    {
        $lineItems = [];

        foreach ($order->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => strtolower($order->currency),
                    'product_data' => [
                        'name' => $item->title_snapshot,
                        'metadata' => [
                            'artwork_id' => $item->artwork_id,
                            'sku' => $item->artwork?->sku,
                        ],
                    ],
                    'unit_amount' => $item->price_cents,
                ],
                'quantity' => $item->qty,
            ];
        }

        if ($order->shipping_cents > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => strtolower($order->currency),
                    'product_data' => [
                        'name' => 'Livraison',
                    ],
                    'unit_amount' => $order->shipping_cents,
                ],
                'quantity' => 1,
            ];
        }

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel'),
            'client_reference_id' => $order->id,
            'metadata' => [
                'order_id' => $order->id,
            ],
        ]);

        $order->update([
            'payment_method' => PaymentMethod::STRIPE,
            'payment_reference' => $session->id,
        ]);

        return $session->url;
    }

    public function createPayPalOrder(Order $order): array
    {
        $url = config('services.paypal.mode') === 'sandbox' 
            ? 'https://api.sandbox.paypal.com'
            : 'https://api.paypal.com';

        $accessToken = $this->getPayPalAccessToken();

        $items = [];
        foreach ($order->items as $item) {
            $items[] = [
                'name' => $item->title_snapshot,
                'quantity' => (string) $item->qty,
                'unit_amount' => [
                    'currency_code' => $order->currency,
                    'value' => number_format($item->price_cents / 100, 2, '.', ''),
                ],
            ];
        }

        $data = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'reference_id' => $order->id,
                    'amount' => [
                        'currency_code' => $order->currency,
                        'value' => number_format($order->total_cents / 100, 2, '.', ''),
                        'breakdown' => [
                            'item_total' => [
                                'currency_code' => $order->currency,
                                'value' => number_format($order->subtotal_cents / 100, 2, '.', ''),
                            ],
                            'shipping' => [
                                'currency_code' => $order->currency,
                                'value' => number_format($order->shipping_cents / 100, 2, '.', ''),
                            ],
                        ],
                    ],
                    'items' => $items,
                ],
            ],
            'application_context' => [
                'return_url' => route('checkout.success'),
                'cancel_url' => route('checkout.cancel'),
            ],
        ];

        $response = $this->paypalRequest('POST', '/v2/checkout/orders', $data, $accessToken);

        $order->update([
            'payment_method' => PaymentMethod::PAYPAL,
            'payment_reference' => $response['id'],
        ]);

        return $response;
    }

    private function getPayPalAccessToken(): string
    {
        $url = config('services.paypal.mode') === 'sandbox' 
            ? 'https://api.sandbox.paypal.com'
            : 'https://api.paypal.com';

        $response = $this->paypalRequest('POST', '/v1/oauth2/token', [
            'grant_type' => 'client_credentials',
        ]);

        return $response['access_token'];
    }

    private function paypalRequest(string $method, string $endpoint, array $data, ?string $accessToken = null): array
    {
        $url = config('services.paypal.mode') === 'sandbox' 
            ? 'https://api.sandbox.paypal.com'
            : 'https://api.paypal.com';

        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $url . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => $method === 'POST',
            CURLOPT_HTTPHEADER => array_filter([
                'Content-Type: application/json',
                'Accept: application/json',
                $accessToken ? "Authorization: Bearer $accessToken" : 'Authorization: Basic ' . base64_encode(config('services.paypal.client_id') . ':' . config('services.paypal.client_secret')),
            ]),
            CURLOPT_POSTFIELDS => $method === 'POST' ? json_encode($data) : null,
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    public function createPayment(Order $order, string $paymentMethod): string
    {
        return match ($paymentMethod) {
            'stripe' => $this->createStripeCheckout($order),
            'paypal' => $this->createPayPalOrder($order)['links'][1]['href'], // approve link
            default => throw new \InvalidArgumentException('Invalid payment method'),
        };
    }

    public function confirmPayment(string $paymentReference, string $paymentMethod): bool
    {
        $order = Order::where('payment_reference', $paymentReference)->first();
        
        if (!$order) {
            return false;
        }

        // Marquer la commande comme payée
        $order->update([
            'status' => \App\OrderStatus::PAID,
            'paid_at' => now(),
        ]);

        // Marquer les œuvres comme vendues
        foreach ($order->items as $item) {
            $item->artwork->update([
                'status' => \App\ArtworkStatus::SOLD,
                'reserved_until' => null,
            ]);
        }

        return true;
    }
}

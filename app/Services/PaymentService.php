<?php

namespace App\Services;

use App\Models\Order;
use App\PaymentMethod;
use Illuminate\Support\Facades\Log;
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

        if (!isset($response['id'])) {
            Log::error('PayPal order creation failed - no ID in response', [
                'response' => $response
            ]);
            throw new \Exception('Failed to create PayPal order');
        }

        $order->update([
            'payment_method' => PaymentMethod::PAYPAL,
            'payment_reference' => $response['id'],
        ]);

        Log::info('PayPal order created successfully', [
            'order_id' => $order->id,
            'paypal_order_id' => $response['id']
        ]);

        return $response;
    }

    private function getPayPalAccessToken(): string
    {
        $url = config('services.paypal.mode') === 'sandbox'
            ? 'https://api.sandbox.paypal.com'
            : 'https://api.paypal.com';

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url . '/v1/oauth2/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Accept-Language: en_US',
                'Authorization: Basic ' . base64_encode(
                    config('services.paypal.client_id') . ':' . config('services.paypal.client_secret')
                ),
            ],
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            Log::error('PayPal OAuth error', ['error' => $error]);
            throw new \Exception('PayPal authentication failed: ' . $error);
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $decoded = json_decode($response, true);

        if ($httpCode !== 200 || !isset($decoded['access_token'])) {
            Log::error('PayPal OAuth failed', [
                'http_code' => $httpCode,
                'response' => $decoded
            ]);
            throw new \Exception('Failed to get PayPal access token');
        }

        Log::info('PayPal access token obtained successfully');
        return $decoded['access_token'];
    }

    private function paypalRequest(string $method, string $endpoint, array $data = [], ?string $accessToken = null): array
    {
        $url = config('services.paypal.mode') === 'sandbox'
            ? 'https://api.sandbox.paypal.com'
            : 'https://api.paypal.com';

        $ch = curl_init();

        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
        ];

        if ($accessToken) {
            $headers[] = "Authorization: Bearer $accessToken";
        } else {
            $headers[] = 'Authorization: Basic ' . base64_encode(
                config('services.paypal.client_id') . ':' . config('services.paypal.client_secret')
            );
        }

        $options = [
            CURLOPT_URL => $url . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
        ];

        if ($method === 'POST') {
            $options[CURLOPT_POST] = true;
            if (!empty($data)) {
                $options[CURLOPT_POSTFIELDS] = json_encode($data);
            } else {
                $options[CURLOPT_POSTFIELDS] = '{}';
            }
        }

        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            Log::error('PayPal cURL error', ['error' => $error]);
            return [];
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $decoded = json_decode($response, true);

        if ($httpCode >= 400) {
            Log::error('PayPal API error', [
                'http_code' => $httpCode,
                'response' => $decoded
            ]);
        }

        return $decoded ?? [];
    }

    public function createPayment(Order $order, string $paymentMethod): string
    {
        return match ($paymentMethod) {
            'stripe' => $this->createStripeCheckout($order),
            'paypal' => $this->createPayPalOrder($order)['links'][1]['href'], // approve link
            default => throw new \InvalidArgumentException('Invalid payment method'),
        };
    }

    public function capturePayPalOrder(string $paypalOrderId): bool
    {
        try {
            $accessToken = $this->getPayPalAccessToken();
            $response = $this->paypalRequest(
                'POST',
                "/v2/checkout/orders/{$paypalOrderId}/capture",
                [],
                $accessToken
            );

            if (isset($response['status']) && $response['status'] === 'COMPLETED') {
                Log::info('PayPal order captured successfully', [
                    'paypal_order_id' => $paypalOrderId,
                    'capture_id' => $response['purchase_units'][0]['payments']['captures'][0]['id'] ?? null
                ]);
                return true;
            }

            Log::warning('PayPal order capture failed', [
                'paypal_order_id' => $paypalOrderId,
                'response' => $response
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Error capturing PayPal order', [
                'paypal_order_id' => $paypalOrderId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function confirmPayment(string $paymentReference, string $paymentMethod): bool
    {
        $order = Order::where('payment_reference', $paymentReference)->first();

        if (!$order) {
            Log::warning('Payment confirmation failed: Order not found', [
                'payment_reference' => $paymentReference,
                'payment_method' => $paymentMethod
            ]);
            return false;
        }

        // Éviter la double confirmation
        if ($order->status === \App\OrderStatus::PAID) {
            Log::info('Payment already confirmed for order', [
                'order_id' => $order->id,
                'payment_reference' => $paymentReference
            ]);
            return true;
        }

        try {
            \DB::transaction(function () use ($order, $paymentMethod) {
                // Marquer la commande comme payée
                $order->update([
                    'status' => \App\OrderStatus::PAID,
                    'paid_at' => now(),
                ]);

                // Marquer les œuvres comme vendues
                foreach ($order->items as $item) {
                    if ($item->artwork) {
                        $item->artwork->update([
                            'status' => \App\ArtworkStatus::SOLD,
                            'reserved_until' => null,
                        ]);

                        Log::info('Artwork marked as sold', [
                            'artwork_id' => $item->artwork->id,
                            'order_id' => $order->id
                        ]);
                    }
                }

                Log::info('Payment confirmed successfully', [
                    'order_id' => $order->id,
                    'payment_method' => $paymentMethod,
                    'total_amount' => $order->total_cents / 100
                ]);
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to confirm payment', [
                'order_id' => $order->id,
                'payment_reference' => $paymentReference,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}

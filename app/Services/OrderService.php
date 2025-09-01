<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use App\OrderStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function createFromCart(Cart $cart, array $billingData, ?User $user = null): Order
    {
        return DB::transaction(function () use ($cart, $billingData, $user) {
            $cart->load('items.artwork');

            $subtotalCents = $cart->items->sum(function ($item) {
                return $item->artwork->price_cents * $item->qty;
            });

            $shippingCents = 1500;

            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => $user?->id,
                'status' => OrderStatus::PENDING,
                'subtotal_cents' => $subtotalCents,
                'shipping_cents' => $shippingCents,
                'tax_cents' => 0,
                'total_cents' => $subtotalCents + $shippingCents,
                'currency' => 'EUR',
                'billing_name' => $billingData['billing_name'],
                'billing_email' => $billingData['billing_email'],
                'billing_address' => $billingData['billing_address'],
                'billing_city' => $billingData['billing_city'],
                'billing_postal_code' => $billingData['billing_postal_code'],
                'billing_country' => $billingData['billing_country'],
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'artwork_id' => $item->artwork_id,
                    'title_snapshot' => $item->artwork->getTranslation('title', 'fr'),
                    'price_cents' => $item->artwork->price_cents,
                    'qty' => $item->qty,
                ]);

                app(ReserveArtworkService::class)->reserve($item->artwork);
            }

            return $order;
        });
    }

    public function markAsPaid(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $order->update(['status' => OrderStatus::PAID]);

            foreach ($order->items as $item) {
                if ($item->artwork) {
                    app(ReserveArtworkService::class)->markAsSold($item->artwork);
                }
            }
        });
    }

    private function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'CE-' . date('Y') . '-' . strtoupper(Str::random(8));
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    public function confirmPayment(string $paymentReference, string $paymentMethod): bool
    {
        $order = Order::where('payment_reference', $paymentReference)->first();
        
        if (!$order) {
            return false;
        }

        $this->markAsPaid($order);
        return true;
    }
}

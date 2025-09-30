<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    public function sendPaymentConfirmation(Order $order): bool
    {
        try {
            // Récupérer l'email du client (soit billing_email soit user.email)
            $customerEmail = $order->billing_email ?: $order->user?->email;
            $customerName = $order->billing_name ?: $order->user?->name ?: 'Client';

            if (empty($customerEmail)) {
                Log::warning('Impossible d\'envoyer l\'email: adresse email manquante', [
                    'order_id' => $order->id
                ]);
                return false;
            }

            $orderData = $this->prepareOrderData($order);

            Mail::send('emails.payment-confirmation', $orderData, function ($message) use ($customerEmail, $customerName, $order) {
                $message->to($customerEmail, $customerName)
                        ->subject('Confirmation de votre commande #' . $order->order_number);
            });

            Log::info('Email de confirmation de paiement envoyé', [
                'order_id' => $order->id,
                'email' => $customerEmail
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de l\'email de confirmation', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function sendOrderPreparing(Order $order): bool
    {
        try {
            $customerEmail = $order->billing_email ?: $order->user?->email;
            $customerName = $order->billing_name ?: $order->user?->name ?: 'Client';

            if (empty($customerEmail)) {
                Log::warning('Impossible d\'envoyer l\'email de préparation: adresse email manquante', [
                    'order_id' => $order->id
                ]);
                return false;
            }

            $orderData = $this->prepareOrderData($order);

            Mail::send('emails.order-preparing', $orderData, function ($message) use ($customerEmail, $customerName, $order) {
                $message->to($customerEmail, $customerName)
                        ->subject('Votre commande #' . $order->order_number . ' est en préparation');
            });

            Log::info('Email de préparation envoyé', [
                'order_id' => $order->id,
                'email' => $customerEmail
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de l\'email de préparation', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function sendOrderShipped(Order $order, string $trackingNumber = null, string $carrier = 'Colissimo'): bool
    {
        try {
            $customerEmail = $order->billing_email ?: $order->user?->email;
            $customerName = $order->billing_name ?: $order->user?->name ?: 'Client';

            if (empty($customerEmail)) {
                Log::warning('Impossible d\'envoyer l\'email d\'expédition: adresse email manquante', [
                    'order_id' => $order->id
                ]);
                return false;
            }
            $orderData = $this->prepareOrderData($order);
            $orderData['trackingNumber'] = $trackingNumber ?? 'N/A';
            $orderData['carrier'] = $carrier;
            $orderData['shippingDate'] = now()->format('d/m/Y');
            $orderData['estimatedDeliveryDate'] = now()->addDays(3)->format('d/m/Y');

            // URL de suivi selon le transporteur
            if ($trackingNumber) {
                switch (strtolower($carrier)) {
                    case 'colissimo':
                        $orderData['trackingUrl'] = 'https://www.laposte.fr/outils/suivre-vos-envois?code=' . $trackingNumber;
                        break;
                    case 'chronopost':
                        $orderData['trackingUrl'] = 'https://www.chronopost.fr/tracking-no-cms/suivi-page?listeNumerosLT=' . $trackingNumber;
                        break;
                    default:
                        $orderData['trackingUrl'] = null;
                }
            }

            Mail::send('emails.order-shipped', $orderData, function ($message) use ($customerEmail, $customerName, $order) {
                $message->to($customerEmail, $customerName)
                        ->subject('Votre commande #' . $order->order_number . ' a été expédiée');
            });

            Log::info('Email d\'expédition envoyé', [
                'order_id' => $order->id,
                'email' => $customerEmail,
                'tracking_number' => $trackingNumber
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de l\'email d\'expédition', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    private function prepareOrderData(Order $order): array
    {
        $order->load('items.artwork.artist', 'items.artwork.collection');

        $items = $order->items->map(function ($item) {
            return [
                'name' => $item->title_snapshot,
                'quantity' => $item->qty,
                'price' => number_format($item->price_cents / 100, 2)
            ];
        })->toArray();

        return [
            'customerName' => $order->billing_name,
            'orderNumber' => $order->order_number,
            'orderDate' => $order->created_at->format('d/m/Y'),
            'totalAmount' => number_format($order->total_cents / 100, 2),
            'shippingAddress' => $this->formatAddress($order),
            'items' => $items
        ];
    }

    private function formatAddress(Order $order): string
    {
        return ($order->billing_name ?: 'Client') . "\n" .
               ($order->billing_address ?: '') . "\n" .
               ($order->billing_postal_code ?: '') . ' ' . ($order->billing_city ?: '') . "\n" .
               strtoupper($order->billing_country ?: '');
    }
}
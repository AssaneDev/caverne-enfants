<?php

namespace App\Observers;

use App\Models\Order;
use App\OrderStatus;
use App\Services\EmailService;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    public function __construct(
        private EmailService $emailService
    ) {}

    public function updated(Order $order): void
    {
        // Vérifier si le statut a changé
        if ($order->isDirty('status')) {
            $oldStatus = $order->getOriginal('status');
            $newStatus = $order->status;

            Log::info('Changement de statut détecté', [
                'order_id' => $order->id,
                'old_status' => $oldStatus?->value,
                'new_status' => $newStatus->value,
            ]);

            // Envoyer l'email approprié selon le nouveau statut
            $this->sendStatusEmail($order, $newStatus);
        }
    }

    private function sendStatusEmail(Order $order, OrderStatus $status): void
    {
        try {
            switch ($status) {
                case OrderStatus::PAID:
                    $this->emailService->sendPaymentConfirmation($order);
                    Log::info('Email de confirmation de paiement envoyé', ['order_id' => $order->id]);
                    break;

                case OrderStatus::PREPARING:
                    $this->emailService->sendOrderPreparing($order);
                    Log::info('Email de préparation envoyé', ['order_id' => $order->id]);
                    break;

                case OrderStatus::SHIPPED:
                    $this->emailService->sendOrderShipped($order, $order->tracking_number, $order->tracking_carrier ?: 'Colissimo');
                    Log::info('Email d\'expédition envoyé', ['order_id' => $order->id]);
                    break;

                default:
                    // Pas d'email pour les autres statuts
                    break;
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi d\'email automatique', [
                'order_id' => $order->id,
                'status' => $status->value,
                'error' => $e->getMessage()
            ]);
        }
    }
}
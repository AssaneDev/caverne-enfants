<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Stripe\Stripe;
use Stripe\Account;
use Stripe\Exception\AuthenticationException;
use Stripe\Exception\ApiErrorException;

class TestStripeConfig extends Command
{
    protected $signature = 'stripe:test';
    protected $description = 'Test la configuration Stripe';

    public function handle()
    {
        $this->info('🔄 Test de la configuration Stripe...');
        $this->newLine();

        // Test des variables d'environnement
        $this->info('📋 Vérification des variables d\'environnement:');

        $stripeKey = config('services.stripe.key');
        $stripeSecret = config('services.stripe.secret');
        $webhookSecret = config('services.stripe.webhook_secret');
        $currency = env('STRIPE_CURRENCY', 'EUR');

        if (empty($stripeKey)) {
            $this->error('❌ STRIPE_KEY manquant');
            return 1;
        } else {
            $this->info('✅ STRIPE_KEY: ' . substr($stripeKey, 0, 12) . '...');
        }

        if (empty($stripeSecret)) {
            $this->error('❌ STRIPE_SECRET manquant');
            return 1;
        } else {
            $this->info('✅ STRIPE_SECRET: ' . substr($stripeSecret, 0, 12) . '...');
        }

        if (empty($webhookSecret)) {
            $this->error('❌ STRIPE_WEBHOOK_SECRET manquant');
            return 1;
        } else {
            $this->info('✅ STRIPE_WEBHOOK_SECRET: ' . substr($webhookSecret, 0, 12) . '...');
        }

        $this->info('✅ STRIPE_CURRENCY: ' . $currency);
        $this->newLine();

        // Test de connexion à l'API Stripe
        $this->info('🌐 Test de connexion à l\'API Stripe:');

        try {
            Stripe::setApiKey($stripeSecret);
            $account = Account::retrieve();

            $this->info('✅ Connexion réussie!');
            $this->info('📊 Compte Stripe:');
            $this->info('   - ID: ' . $account->id);
            $this->info('   - Pays: ' . $account->country);
            $this->info('   - Devise par défaut: ' . $account->default_currency);
            $this->info('   - Email: ' . ($account->email ?? 'Non défini'));

            if (str_starts_with($stripeSecret, 'sk_live_')) {
                $this->warn('⚠️  MODE PRODUCTION DÉTECTÉ');
                $this->warn('   Assurez-vous que c\'est intentionnel!');
            } else {
                $this->info('🧪 Mode test détecté');
            }

        } catch (AuthenticationException $e) {
            $this->error('❌ Erreur d\'authentification: ' . $e->getMessage());
            $this->error('   Vérifiez vos clés API Stripe');
            return 1;
        } catch (ApiErrorException $e) {
            $this->error('❌ Erreur API Stripe: ' . $e->getMessage());
            return 1;
        } catch (\Exception $e) {
            $this->error('❌ Erreur inattendue: ' . $e->getMessage());
            return 1;
        }

        $this->newLine();

        // Test de la configuration webhook
        $this->info('🔗 Configuration webhook:');
        $webhookUrl = env('STRIPE_WEBHOOK_ENDPOINT_URL');
        if ($webhookUrl) {
            $this->info('✅ URL webhook: ' . $webhookUrl);
        } else {
            $this->warn('⚠️  URL webhook non configurée dans STRIPE_WEBHOOK_ENDPOINT_URL');
        }

        $this->newLine();
        $this->info('🚀 Configuration Stripe OK! Prêt pour les paiements.');

        $this->newLine();
        $this->info('📝 Prochaines étapes:');
        $this->info('   1. Configurer le webhook dans votre dashboard Stripe');
        $this->info('   2. URL: ' . ($webhookUrl ?: route('webhooks.stripe')));
        $this->info('   3. Événements à écouter: checkout.session.completed, payment_intent.payment_failed');

        return 0;
    }
}
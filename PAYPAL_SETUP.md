# Configuration PayPal

## Configuration dans le .env

Ajoutez les variables suivantes dans votre fichier `.env` :

```env
PAYPAL_CLIENT_ID=votre_client_id_paypal
PAYPAL_CLIENT_SECRET=votre_client_secret_paypal
PAYPAL_MODE=live
PAYPAL_WEBHOOK_ID=votre_webhook_id
```

**Comment obtenir ces valeurs :**
1. Connectez-vous à https://developer.paypal.com/
2. Allez dans **Apps & Credentials**
3. Créez une application ou sélectionnez une existante
4. Copiez le **Client ID** et le **Client Secret**

## Configuration du Webhook PayPal

### 1. Accéder au Dashboard PayPal
- Connectez-vous à https://developer.paypal.com/
- Allez dans **Apps & Credentials** > Votre application en mode **Live**

### 2. Configurer le Webhook
- Dans la section **Webhooks**, cliquez sur **Add Webhook**
- URL du webhook : `https://lacavernedesenfants.com/webhooks/paypal`
- Sélectionnez les événements suivants :
  - ✅ `CHECKOUT.ORDER.APPROVED` - Quand le client approuve la commande
  - ✅ `PAYMENT.CAPTURE.COMPLETED` - Quand le paiement est capturé avec succès
  - ✅ `PAYMENT.CAPTURE.DENIED` - Quand le paiement est refusé
  - ✅ `PAYMENT.CAPTURE.REFUNDED` - Quand le paiement est remboursé

### 3. Récupérer le Webhook ID
- Après création, notez le **Webhook ID** fourni par PayPal
- Ajoutez-le dans votre fichier `.env` comme `PAYPAL_WEBHOOK_ID`

## Flux de paiement PayPal

1. **Création de la commande** (`CheckoutController::process`)
   - L'utilisateur soumet le formulaire de paiement avec PayPal
   - Une commande est créée dans la base de données (statut: PENDING)
   - Un ordre PayPal est créé via l'API
   - L'utilisateur est redirigé vers PayPal

2. **Approbation par l'utilisateur**
   - L'utilisateur se connecte à PayPal et approuve le paiement
   - PayPal envoie un webhook `CHECKOUT.ORDER.APPROVED`
   - L'utilisateur est redirigé vers `/checkout/success?token={ORDER_ID}`

3. **Capture du paiement** (`CheckoutController::success`)
   - Le système capture le paiement via l'API PayPal
   - La commande est confirmée et marquée comme PAID
   - Les œuvres sont marquées comme SOLD
   - Un email de confirmation est envoyé

4. **Confirmation webhook** (`CheckoutController::paypalWebhook`)
   - PayPal envoie un webhook `PAYMENT.CAPTURE.COMPLETED`
   - Le système confirme à nouveau le paiement (protection contre la double confirmation)

## Tests

### Vérifier la configuration
```bash
# Tester la syntaxe PHP
php -l app/Services/PaymentService.php
php -l app/Http/Controllers/CheckoutController.php

# Vérifier les routes
php artisan route:list | grep paypal
```

### Test en production
1. Créer une commande test avec une petite œuvre
2. Sélectionner PayPal comme méthode de paiement
3. Vérifier la redirection vers PayPal
4. Effectuer le paiement avec un compte PayPal Sandbox
5. Vérifier le retour et la confirmation de commande
6. Consulter les logs : `tail -f storage/logs/laravel.log`

## Logs importants

Les événements suivants sont loggés :
- Création de commande PayPal
- Capture de paiement
- Webhooks reçus
- Erreurs API PayPal

```bash
# Suivre les logs en temps réel
tail -f storage/logs/laravel.log | grep -i paypal
```

## Notes de sécurité

⚠️ **Important** : La vérification de signature webhook est actuellement basique. Pour une sécurité renforcée, implémentez la vérification complète en suivant la documentation PayPal :
https://developer.paypal.com/api/rest/webhooks/rest/#verify-webhook-signature

## URLs importantes

- **Paiement** : `https://lacavernedesenfants.com/checkout`
- **Webhook** : `https://lacavernedesenfants.com/webhooks/paypal`
- **Succès** : `https://lacavernedesenfants.com/checkout/success`
- **Annulation** : `https://lacavernedesenfants.com/checkout/cancel`
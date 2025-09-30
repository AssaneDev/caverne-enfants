<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de paiement - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #e74c3c;
            padding-bottom: 20px;
        }
        .logo {
            color: #e74c3c;
            font-size: 24px;
            font-weight: bold;
        }
        .order-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .total {
            font-weight: bold;
            font-size: 18px;
            color: #e74c3c;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">{{ config('app.name') }}</div>
            <h1>Confirmation de votre commande</h1>
        </div>

        <p>Bonjour {{ $customerName }},</p>

        <p>Nous vous confirmons que votre paiement a été reçu avec succès. Votre commande est maintenant confirmée et sera traitée dans les plus brefs délais.</p>

        <div class="order-info">
            <h3>Détails de votre commande</h3>
            <p><strong>Numéro de commande :</strong> #{{ $orderNumber }}</p>
            <p><strong>Date de commande :</strong> {{ $orderDate }}</p>
            <p><strong>Montant total :</strong> {{ $totalAmount }}€</p>
        </div>

        @if(isset($items) && count($items) > 0)
        <div class="order-info">
            <h3>Articles commandés</h3>
            @foreach($items as $item)
            <div class="order-item">
                <span>{{ $item['name'] }} (x{{ $item['quantity'] }})</span>
                <span>{{ $item['price'] }}€</span>
            </div>
            @endforeach
            <div class="order-item total">
                <span>Total</span>
                <span>{{ $totalAmount }}€</span>
            </div>
        </div>
        @endif

        <div class="order-info">
            <h3>Adresse de livraison</h3>
            <p>{{ $shippingAddress }}</p>
        </div>

        <p><strong>Prochaines étapes :</strong></p>
        <ul>
            <li>Vous recevrez un email de confirmation lorsque votre commande sera en préparation</li>
            <li>Un email de suivi vous sera envoyé lors de l'expédition avec le numéro de suivi</li>
            <li>La livraison s'effectuera sous 3-5 jours ouvrés</li>
        </ul>

        <p>Si vous avez des questions concernant votre commande, n'hésitez pas à nous contacter à <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a></p>

        <p>Merci de votre confiance !</p>

        <div class="footer">
            <p>{{ config('app.name') }} - La Caverne des Enfants</p>
            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>
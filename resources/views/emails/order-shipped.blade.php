<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande expédiée - {{ config('app.name') }}</title>
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
            border-bottom: 2px solid #28a745;
            padding-bottom: 20px;
        }
        .logo {
            color: #28a745;
            font-size: 24px;
            font-weight: bold;
        }
        .status-badge {
            background-color: #28a745;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
            margin: 10px 0;
        }
        .order-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .tracking-box {
            background-color: #e8f5e8;
            border: 2px solid #28a745;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }
        .tracking-number {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
            margin: 10px 0;
            font-family: monospace;
            letter-spacing: 2px;
        }
        .progress-bar {
            background-color: #e9ecef;
            height: 20px;
            border-radius: 10px;
            overflow: hidden;
            margin: 20px 0;
        }
        .progress-fill {
            background-color: #28a745;
            height: 100%;
            width: 75%;
            border-radius: 10px;
        }
        .timeline {
            margin: 20px 0;
        }
        .timeline-item {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }
        .timeline-dot {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 15px;
        }
        .timeline-dot.completed {
            background-color: #28a745;
        }
        .timeline-dot.current {
            background-color: #28a745;
        }
        .timeline-dot.pending {
            background-color: #dee2e6;
        }
        .delivery-info {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 10px 0;
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
            <h1>🚚 Votre commande est en route !</h1>
            <div class="status-badge">Expédiée</div>
        </div>

        <p>Bonjour {{ $customerName }},</p>

        <p>Excellente nouvelle ! Votre commande a été expédiée et est maintenant en route vers votre adresse de livraison.</p>

        <div class="tracking-box">
            <h3>📦 Numéro de suivi</h3>
            <div class="tracking-number">{{ $trackingNumber }}</div>
            @if(isset($trackingUrl))
            <a href="{{ $trackingUrl }}" class="btn" target="_blank">Suivre mon colis</a>
            @endif
        </div>

        <div class="order-info">
            <h3>Informations de votre commande</h3>
            <p><strong>Numéro de commande :</strong> #{{ $orderNumber }}</p>
            <p><strong>Date d'expédition :</strong> {{ $shippingDate }}</p>
            <p><strong>Transporteur :</strong> {{ $carrier ?? 'Colissimo' }}</p>
            <p><strong>Montant total :</strong> {{ $totalAmount }}€</p>
        </div>

        <div class="progress-bar">
            <div class="progress-fill"></div>
        </div>
        <p style="text-align: center; color: #666; font-size: 14px;">Progression : 75%</p>

        <div class="timeline">
            <h3>Suivi de votre commande</h3>
            <div class="timeline-item">
                <div class="timeline-dot completed"></div>
                <span>✅ Commande confirmée et paiement reçu</span>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot completed"></div>
                <span>✅ Commande préparée</span>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot current"></div>
                <span>🚚 Colis expédié et en transit</span>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot pending"></div>
                <span>📍 Livraison (bientôt !)</span>
            </div>
        </div>

        <div class="delivery-info">
            <h3>📅 Informations de livraison</h3>
            <p><strong>Livraison estimée :</strong> {{ $estimatedDeliveryDate }}</p>
            <p><strong>Adresse de livraison :</strong><br>{{ $shippingAddress }}</p>
        </div>

        <div class="order-info">
            <h3>Conseils pour la réception</h3>
            <ul>
                <li>Assurez-vous qu'une personne soit présente à l'adresse de livraison</li>
                <li>Gardez votre numéro de suivi à portée de main</li>
                <li>Vérifiez l'état du colis avant de signer le bordereau de livraison</li>
                <li>En cas d'absence, le transporteur laissera un avis de passage</li>
            </ul>
        </div>

        @if(isset($trackingUrl))
        <p style="text-align: center;">
            <a href="{{ $trackingUrl }}" class="btn" target="_blank">🔍 Suivre votre colis en temps réel</a>
        </p>
        @endif

        <p>Si vous avez des questions concernant votre livraison, n'hésitez pas à nous contacter à <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a></p>

        <p>Merci de votre confiance et bon shopping !</p>

        <div class="footer">
            <p>{{ config('app.name') }} - La Caverne des Enfants</p>
            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>
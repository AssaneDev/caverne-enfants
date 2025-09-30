<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande en préparation - {{ config('app.name') }}</title>
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
            border-bottom: 2px solid #f39c12;
            padding-bottom: 20px;
        }
        .logo {
            color: #f39c12;
            font-size: 24px;
            font-weight: bold;
        }
        .status-badge {
            background-color: #f39c12;
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
        .progress-bar {
            background-color: #e9ecef;
            height: 20px;
            border-radius: 10px;
            overflow: hidden;
            margin: 20px 0;
        }
        .progress-fill {
            background-color: #f39c12;
            height: 100%;
            width: 50%;
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
            background-color: #f39c12;
        }
        .timeline-dot.pending {
            background-color: #dee2e6;
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
            <h1>Votre commande est en préparation</h1>
            <div class="status-badge">En préparation</div>
        </div>

        <p>Bonjour {{ $customerName }},</p>

        <p>Bonne nouvelle ! Votre commande est maintenant en cours de préparation dans nos entrepôts. Nos équipes s'affairent à emballer soigneusement vos articles.</p>

        <div class="order-info">
            <h3>Informations de votre commande</h3>
            <p><strong>Numéro de commande :</strong> #{{ $orderNumber }}</p>
            <p><strong>Date de commande :</strong> {{ $orderDate }}</p>
            <p><strong>Montant total :</strong> {{ $totalAmount }}€</p>
        </div>

        <div class="progress-bar">
            <div class="progress-fill"></div>
        </div>
        <p style="text-align: center; color: #666; font-size: 14px;">Progression : 50%</p>

        <div class="timeline">
            <h3>Suivi de votre commande</h3>
            <div class="timeline-item">
                <div class="timeline-dot completed"></div>
                <span>✅ Commande confirmée et paiement reçu</span>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot current"></div>
                <span>📦 Commande en cours de préparation</span>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot pending"></div>
                <span>🚚 Expédition (prochaine étape)</span>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot pending"></div>
                <span>📍 Livraison</span>
            </div>
        </div>

        <div class="order-info">
            <h3>Temps de préparation estimé</h3>
            <p>Votre commande sera prête à être expédiée dans <strong>1-2 jours ouvrés</strong>.</p>
            <p>Vous recevrez un email de confirmation avec le numéro de suivi dès que votre colis sera expédié.</p>
        </div>

        <div class="order-info">
            <h3>Adresse de livraison</h3>
            <p>{{ $shippingAddress }}</p>
        </div>

        <p><strong>Important :</strong> Assurez-vous que quelqu'un soit présent à l'adresse de livraison lors de la réception du colis.</p>

        <p>Si vous avez des questions ou souhaitez modifier votre commande, contactez-nous rapidement à <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a></p>

        <p>Merci de votre patience et de votre confiance !</p>

        <div class="footer">
            <p>{{ config('app.name') }} - La Caverne des Enfants</p>
            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>
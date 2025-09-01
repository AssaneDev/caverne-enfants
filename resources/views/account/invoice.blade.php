<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture {{ $order->order_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .company-info { float: left; width: 50%; }
        .invoice-info { float: right; width: 50%; text-align: right; }
        .clear { clear: both; }
        .customer-info { margin: 30px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f5f5f5; font-weight: bold; }
        .total-row { font-weight: bold; background-color: #f9f9f9; }
        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>FACTURE</h1>
    </div>

    <div class="company-info">
        <h3>Caverne des Enfants</h3>
        <p>Galerie d'art unique<br>
        123 Rue de l'Art<br>
        75001 Paris, France<br>
        contact@caverne-enfants.com</p>
    </div>

    <div class="invoice-info">
        <h3>Facture N° {{ $order->order_number }}</h3>
        <p><strong>Date :</strong> {{ $order->created_at->format('d/m/Y') }}</p>
        <p><strong>Date de paiement :</strong> {{ $order->paid_at?->format('d/m/Y') ?? 'En attente' }}</p>
        <p><strong>Méthode de paiement :</strong> {{ ucfirst($order->payment_method?->value ?? 'Non définie') }}</p>
    </div>

    <div class="clear"></div>

    <div class="customer-info">
        <h3>Facturé à :</h3>
        <p>{{ $order->billing_name }}<br>
        {{ $order->billing_address }}<br>
        {{ $order->billing_postal_code }} {{ $order->billing_city }}<br>
        {{ $order->billing_country }}</p>
        <p><strong>Email :</strong> {{ $order->billing_email }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Œuvre</th>
                <th>Artiste</th>
                <th>Référence</th>
                <th>Qté</th>
                <th>Prix unitaire</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->title_snapshot }}</td>
                    <td>{{ $item->artwork->artist->name }}</td>
                    <td>{{ $item->artwork->sku }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ number_format($item->price_cents / 100, 2, ',', ' ') }} €</td>
                    <td>{{ number_format(($item->price_cents * $item->qty) / 100, 2, ',', ' ') }} €</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align: right;"><strong>Sous-total :</strong></td>
                <td><strong>{{ number_format($order->subtotal_cents / 100, 2, ',', ' ') }} €</strong></td>
            </tr>
            @if($order->shipping_cents > 0)
                <tr>
                    <td colspan="5" style="text-align: right;">Livraison :</td>
                    <td>{{ number_format($order->shipping_cents / 100, 2, ',', ' ') }} €</td>
                </tr>
            @endif
            @if($order->tax_cents > 0)
                <tr>
                    <td colspan="5" style="text-align: right;">TVA :</td>
                    <td>{{ number_format($order->tax_cents / 100, 2, ',', ' ') }} €</td>
                </tr>
            @endif
            <tr class="total-row">
                <td colspan="5" style="text-align: right; font-size: 14px;"><strong>TOTAL :</strong></td>
                <td style="font-size: 14px;"><strong>{{ number_format($order->total_cents / 100, 2, ',', ' ') }} €</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Merci pour votre confiance !</p>
        <p>Cette facture a été générée automatiquement le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>Caverne des Enfants - Siret: 12345678901234 - TVA: FR12345678901</p>
    </div>
</body>
</html>
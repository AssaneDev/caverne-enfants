<x-mail::message>
# Merci de votre inscription ! 🎨

Vous êtes désormais inscrit(e) à la newsletter de **La Caverne des Enfants**.

## Ce qui vous attend

En tant qu'abonné(e), vous serez informé(e) en avant-première de :

- 🎨 **Nos nouvelles œuvres** : Découvrez nos dernières créations avant tout le monde
- ✨ **Les collections exclusives** : Accédez à des collections spéciales
- 🎁 **Les offres spéciales** : Bénéficiez d'offres réservées aux abonnés
- 📰 **Les actualités** : Restez informé(e) de nos événements et nouveautés

<x-mail::button :url="route('collections.index')">
Découvrir nos collections
</x-mail::button>

## Gérer votre abonnement

Vous pouvez vous désinscrire à tout moment en cliquant sur le lien de désinscription présent dans chaque email.

Merci de faire partie de notre communauté !

---

Cordialement,<br>
**L'équipe de La Caverne des Enfants**

<x-mail::subcopy>
Vous recevez cet email car vous vous êtes inscrit(e) à notre newsletter sur {{ config('app.url') }}
</x-mail::subcopy>
</x-mail::message>

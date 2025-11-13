<x-mail::message>
# Bienvenue {{ $user->name }} ! 🎨

Nous sommes ravis de vous accueillir dans **La Caverne des Enfants**, votre nouvelle destination pour découvrir l'univers magique de l'art destiné aux enfants.

## Qu'allez-vous découvrir ?

- 🖼️ **Des œuvres uniques** : Parcourez notre collection d'art soigneusement sélectionnée
- 🎭 **Des collections thématiques** : Explorez différentes collections adaptées à tous les goûts
- 🛒 **Une expérience d'achat simple** : Commandez vos œuvres préférées en quelques clics
- 📦 **Un suivi personnalisé** : Suivez vos commandes depuis votre compte

<x-mail::button :url="route('collections.index')">
Découvrir nos collections
</x-mail::button>

## Pour commencer

Rendez-vous sur votre compte pour personnaliser votre profil et découvrir nos dernières créations artistiques.

Nous vous souhaitons une merveilleuse aventure dans notre caverne magique !

---

Cordialement,<br>
**L'équipe de La Caverne des Enfants**

<x-mail::subcopy>
Si vous avez des questions, n'hésitez pas à nous contacter. Nous sommes là pour vous aider !
</x-mail::subcopy>
</x-mail::message>

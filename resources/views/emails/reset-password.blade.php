@component('mail::message')
<div style="text-align: center; margin-bottom: 30px;">
    <img src="https://storage.lacavernedesenfants.com/images-banniere/logo.jpg"
         alt="Logo Caverne des Enfants"
         style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; margin: 0 auto;">
</div>

# Réinitialisation de votre mot de passe 🔐

Bonjour,

Vous avez demandé à réinitialiser le mot de passe de votre compte **La Caverne des Enfants**.

Cliquez sur le bouton ci-dessous pour choisir un nouveau mot de passe :

@component('mail::button', ['url' => $url, 'color' => 'primary'])
Réinitialiser mon mot de passe
@endcomponent

Ce lien de réinitialisation expirera dans **{{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} minutes**.

Si vous n'avez pas demandé cette réinitialisation, aucune action n'est requise. Votre mot de passe actuel reste inchangé.

---

**Pour votre sécurité :**
- Ne partagez jamais ce lien avec qui que ce soit
- Choisissez un mot de passe fort et unique
- Assurez-vous d'être sur le site officiel lacavernedesenfants.com

---

Cordialement,<br>
**L'équipe de La Caverne des Enfants**

Association AFO - Art pour les enfants

@component('mail::subcopy')
Si vous rencontrez des difficultés pour cliquer sur le bouton "Réinitialiser mon mot de passe", copiez et collez l'URL ci-dessous dans votre navigateur web :

[{{ $url }}]({{ $url }})
@endcomponent
@endcomponent

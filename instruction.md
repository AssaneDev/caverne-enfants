Important — façon de travailler

Avant d’écrire du code, demande systématiquement les fichiers existants (ex: composer.json, .env.example et/ou .env (sans secrets), routes/web.php, routes/api.php, app/Models, app/Http/Controllers, config/services.php, config/filesystems.php, tailwind.config.js, package.json, vite.config.js, vues Blade, composants Livewire/Filament, etc.).

Propose un plan détaillé (architecture, migrations, routes, vues) et fais valider rapidement.

Livre ensuite des patchs atomiques (migrations → modèles → seeders → routes → contrôleurs → vues → tests) avec explications et commandes à exécuter.

Nous avançons étape par étape : à chaque étape validée, crée un checkpoint (résumé des changements + commandes à lancer). Si la restriction de 5 h approche, enregistre/archiv​e la session (résumé + TODO immédiats) pour pouvoir reprendre sans perte.

Contexte & objectif

Créer une boutique en ligne pour une association nommée Caverne des Enfants vendant des œuvres d’art uniques. Paiement Stripe et PayPal.

Nom de la boutique : Caverne des Enfants

Type de produits : œuvres pièces uniques (stock = 1) avec titres distincts.

Collections initiales :

Les Carrés du Fleuve de Donald wallo

La collection de l’amitié (poésie de l’école de la Petite Côte)

La correction des baobab (école de la Petite Côte)

Règles clés :

Une œuvre ne peut être vendue qu’une seule fois.

Gérer la concurrence (réservation temporaire à la mise en checkout, annulation si paiement non finalisé).

Client : espace personnel (historique de commandes, statut livraison, adresses, facture PDF, lien de suivi).

Admin : gestion des sections publicitaires de la home (bannière, blocs éditoriaux, mises en avant), gestion des produits/œuvres, commandes, expéditions, remboursements.

UX frontend & backend agréable (sobre, chaleureux, artistique, fiable sur mobile).

Stack technique

Laravel 11, PHP 8.2+, MySQL 8

Front : Blade + TailwindCSS + Alpine (ou Livewire v3 pour panier/checkout dynamiques)

Admin : FilamentPHP (gratuit, Tailwind, rapide à mettre en place)

Auth/roles : spatie/laravel-permission

Media : spatie/laravel-medialibrary (conversions: thumb, medium, webp, watermark discret)

i18n contenu produit (FR/EN) : spatie/laravel-translatable

Paiement Stripe : Stripe PHP SDK (Checkout + Webhooks)

Paiement PayPal : PayPal Orders API (Checkout Smart Buttons + Webhooks)

Tests : Pest

Files : stockage local en dev, S3 en prod (prévoir storage:link)

À installer (composer) :

filament/filament

spatie/laravel-permission

spatie/laravel-medialibrary

spatie/laravel-translatable

stripe/stripe-php

vlucas/phpdotenv (déjà) / pestphp/pest

(optionnel) barryvdh/laravel-dompdf pour factures PDF

Modèles & Migrations (proposition)

Utiliser ULID pour les IDs d’entités orientées métier. Champs *_cents pour les montants.

users (Laravel)

name, email, password, etc.

Rôles : Admin, Manager, Client (via spatie-permission)

artists

id (ulid), name, slug, bio (nullable), links (json nullable)

collections

id (ulid), name (translatable), slug, description (translatable, nullable), cover_media_id (medialibrary)

artworks (œuvres, stock = 1)

id (ulid), sku (unique), title (translatable), slug (unique)

artist_id (nullable), collection_id (nullable, index)

year (nullable), medium (translatable, nullable), dimensions (texte)

price_cents (int), currency (string, ex: XOF/EUR)

status enum: draft|published|reserved|sold

reserved_until (nullable datetime)

featured (bool), on_home (bool)

weight_grams (nullable), shipping_class (nullable)

SEO: meta_title, meta_description (nullable)

Medias via MediaLibrary (galerie + image principale)

carts

id (ulid), user_id (nullable), session_id (nullable, unique), expires_at

cart_items

id (ulid), cart_id, artwork_id (unique par cart_id), qty (int=1)

orders

id (ulid), order_number (unique ex: CDE-YYYYMM-#####)

user_id (nullable), status enum: pending|awaiting_payment|paid|failed|canceled|refunded|preparing|shipped|delivered

subtotal_cents, shipping_cents, tax_cents (0 par défaut), total_cents, currency

payment_method enum: stripe|paypal

payment_reference (intent/order id), payment_status

Adresse livraison inlined: ship_first_name, ship_last_name, ship_line1, ship_line2, ship_city, ship_state, ship_postcode, ship_country

Tracking: tracking_carrier, tracking_number, shipped_at, delivered_at

order_items

id (ulid), order_id, artwork_id (nullable si supprimée ensuite), title_snapshot, price_cents, qty (1)

payments

id (ulid), order_id, gateway (stripe|paypal), gateway_payload (json), status

homepage_blocks

id (ulid), key enum: hero|banner_top|banner_mid|featured_collections|spotlight_artist|cta

content (json, translatable pour titres/accroches), is_active (bool), sort_order (int)

shipping_methods (simple)

id (ulid), name, region (ex: SN, International), price_cents, active

webhooks

id (ulid), provider (stripe|paypal), event (string), payload (json), processed_at

Contraintes :

Index/unique: artworks.slug, artworks.sku, orders.order_number.

Vente unique : contrainte applicative + verrouillage (voir « Réservation & concurrence »).

Réservation & concurrence (anti-survente)

À l’entrée du checkout : appeler un ReserveArtworkService

Transaction SQL + verrouillage (SELECT … FOR UPDATE via Eloquent lockForUpdate())

Si status = published → passer à reserved et définir reserved_until = now() + 15 minutes.

Empêcher toute autre réservation/ajout au panier.

Job planifié pour relâcher la réservation si pas de paiement (repasse en published).

Au webhook de paiement réussi → passer l’œuvre en sold et la retirer du catalogue (et rendre non listée).

Si deux paiements concurrents arrivent malgré tout, premier capture = gagnant, le second reçoit un remboursement automatique (log + email d’excuse).

Parcours utilisateur (frontend)

Accueil (hero, bannières, collections mises en avant via homepage_blocks).

Liste des collections → détail collection.

Fiche œuvre (galerie, titre, artiste/collection, dimensions, description, prix, disponibilité).

Panier (Livewire), Checkout (adresse + choix paiement Stripe/PayPal + récap).

Paiement :

Stripe Checkout (PaymentIntent + success_url/cancel_url),

PayPal Smart Buttons (Orders API: create + capture côté serveur).

Confirmation → espace client (commandes, états, tracking, facture PDF).

Dashboard client

Mes commandes (liste + détail)

Statuts, tracking (lien transporteur), facture PDF à télécharger

Adresses (édition), profil

Notifications email : commande reçue, paiement confirmé, expédiée, livrée

Backend (admin via Filament)

CRUD artistes, collections, œuvres (upload multiple images avec MediaLibrary, drapeau featured, on_home)

Gestion commandes (changement statuts, ajout tracking, remboursements)

Homepage Blocks (formulaires clairs pour gérer héro/bannières/blocs éditoriaux)

Livraison (méthodes simples + tarifs)

Paramètres (currency par défaut, pages légales, coordonnées de l’association)

Rôles & permissions via spatie-permission

Paiements & Webhooks
Stripe

Checkout côté serveur (Payment Intent) ; capturer au payment_intent.succeeded.

Endpoint webhook : /webhooks/stripe (CSRF désactivé).

Mise à jour orders + payments + status artworks.sold.

PayPal

Orders API (création serveur, capture serveur post-approval).

Endpoint webhook : /webhooks/paypal (événements CHECKOUT.ORDER.APPROVED, PAYMENT.CAPTURE.COMPLETED, …).

Même logique d’update que Stripe.

États & emails

pending → awaiting_payment → paid → preparing → shipped → delivered (ou failed/canceled/refunded).

Emails Markdown + Notifications Laravel pour chaque transition clé (client & admin).

Routes (exemple, à affiner)

Public : GET / GET /collections GET /collections/{slug} GET /art/{slug}

Panier : POST /cart/add/{artwork} GET/POST /checkout GET /checkout/success GET /checkout/cancel

Webhooks : POST /webhooks/stripe POST /webhooks/paypal

Client (auth) : GET /account GET /account/orders GET /account/orders/{id} GET /account/invoices/{id}.pdf

Admin Filament : /admin + resources

UI/UX (guidelines rapides)

Identité simple & chaleureuse (typographies lisibles, grands visuels).

Palette suggérée (indicative) : tons sable/argile + accents chauds.

Cartes œuvres avec badge Unique, gros CTA « Acheter ».

Skeletons de chargement, focus states accessibles, contrastes AA.

SEO : balises meta/OG par œuvre, sitemap, slugs propres.

Sécurité & conformité

Validation serveur stricte (fichiers images, dimensions, prix > 0).

CSRF partout (sauf webhooks).

Journaliser webhooks (webhooks table).

Politique RGPD basique, pages légales (CGV, Mentions).

Seeders (données démo)

1–2 artistes (dont « Donald wallo »),

Collections : Les Carrés du Fleuve, La collection de l’amitié, La correction des baobab (avec covers),

12–24 œuvres (4–8 par collection) avec images placeholders, prix réalistes, statuts variés.

Tests (Pest) — priorités

Réservation : un même artwork ne peut être réservé que par un utilisateur à la fois.

Expiration de réservation → retour en published.

Webhook réussi → œuvre sold + commande paid.

Double paiement concurrents → un seul paid, l’autre refunded.

Admin: CRUD œuvres + update Homepage Blocks.

Variables d’environnement (exemple)
APP_NAME="Caverne des Enfants"
APP_URL=https://caverne-enfants.test


STRIPE_KEY=pk_live_xxx
STRIPE_SECRET=sk_live_xxx
STRIPE_WEBHOOK_SECRET=whsec_xxx


PAYPAL_CLIENT_ID=xxx
PAYPAL_CLIENT_SECRET=xxx
PAYPAL_MODE=live # ou sandbox
PAYPAL_WEBHOOK_ID=xxx


FILESYSTEM_DISK=public
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=
AWS_BUCKET=
Livrables attendus (ordre conseillé)

Plan d’archi validé (arborescence, packages, schémas DB)

Migrations + Modèles + Policies

Seeders (artistes, collections, œuvres demo)

Filament (resources + pages Homepage Blocks)

Frontend (home, listes, fiche œuvre, panier, checkout)

Réservation + Jobs (release TTL)

Stripe (Checkout + webhook) & PayPal (Orders API + webhook)

Commandes (statuts, tracking, emails, PDF)

Tests Pest (unit + feature clés)

Guide README (install, env, commandes, webhooks tunnel ngrok en dev)

Ce que j’attends de toi maintenant




Me demander les fichiers existants & le contexte (repo, packages déjà installés, versions PHP/MySQL).




Proposer un plan d’implémentation concis (liste des migrations, services, controllers, Livewire components, Filament resources).




Fournir les migrations & modèles (avec casts/translatable, enums), puis un seeder minimal incluant :

Artiste « Donald wallo »

Collections ci‑dessus

9 œuvres (3/collection) status=published avec prix et images placeholder




Esquisser les routes + contrôleurs (stubs) + resource Filament pour Artwork et HomepageBlock.




Expliquer la réservation (service + job) et livrer un test Pest qui la vérifie.

Fais des patchs clairs avec les commandes artisan/composer à exécuter et les fichiers modifiés. Utilise un style Laravel idiomatique, sobre et documenté. Merci !
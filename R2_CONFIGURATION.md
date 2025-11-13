# Configuration Cloudflare R2

## 📋 Résumé

✅ **Site accessible** : https://lacavernedesenfants.com
✅ **Package AWS S3 installé** : league/flysystem-aws-s3-v3
✅ **Configuration filesystem** : Disque 'r2' configuré dans `config/filesystems.php`
✅ **Routes de test** : Disponibles sous `/test-r2/*`

## 🔑 Variables d'environnement à remplir

Ouvrez votre fichier `.env` et remplissez les variables suivantes :

```bash
# Cloudflare R2 Configuration
R2_ACCOUNT_ID=          # Votre ID de compte Cloudflare
R2_ACCESS_KEY_ID=       # Clé d'accès API R2
R2_SECRET_ACCESS_KEY=   # Clé secrète API R2
R2_BUCKET=              # Nom de votre bucket R2
R2_PUBLIC_URL=          # URL publique du bucket (optionnel, si domaine personnalisé configuré)
R2_ENDPOINT=            # Format: https://<ACCOUNT_ID>.r2.cloudflarestorage.com
```

## 📝 Comment obtenir ces valeurs depuis Cloudflare

### 1. R2_ACCOUNT_ID
1. Connectez-vous à votre dashboard Cloudflare
2. Dans la barre latérale droite, vous verrez votre **Account ID**
3. Ou allez dans **R2** > Cliquez sur votre bucket > L'URL contient votre Account ID

### 2. R2_ACCESS_KEY_ID et R2_SECRET_ACCESS_KEY
1. Allez dans **R2** > **Manage R2 API Tokens**
2. Cliquez sur **Create API Token**
3. Choisissez les permissions appropriées (Read & Write recommandé pour les tests)
4. Notez bien la clé secrète, elle ne sera affichée qu'une seule fois !

### 3. R2_BUCKET
1. Le nom de votre bucket R2 (par exemple : `caverne-enfants-media`)
2. Vous pouvez créer un nouveau bucket dans **R2** > **Create bucket**

### 4. R2_PUBLIC_URL (Optionnel)
Si vous avez configuré un domaine personnalisé pour votre bucket :
- Format : `https://media.lacavernedesenfants.com`
- Laissez vide si vous n'avez pas de domaine personnalisé

### 5. R2_ENDPOINT
Format : `https://<VOTRE_ACCOUNT_ID>.r2.cloudflarestorage.com`

Exemple : `https://abc123def456.r2.cloudflarestorage.com`

## 🧪 Tester la configuration

### Étape 1 : Remplir les variables
Après avoir rempli toutes les variables dans `.env`, exécutez :

```bash
php artisan config:clear
```

### Étape 2 : Tester la connexion
Vous devez être connecté au site pour accéder aux routes de test.

**URL de test** : https://lacavernedesenfants.com/test-r2/connection

Cette route va :
- ✅ Vérifier que toutes les variables sont configurées
- ✅ Créer un fichier de test
- ✅ Vérifier son existence
- ✅ Lire son contenu
- ✅ Le supprimer
- ✅ Retourner un résultat JSON

### Étape 3 : Tester l'upload (optionnel)
**URL** : https://lacavernedesenfants.com/test-r2/upload
**Méthode** : POST
**Paramètre** : `file` (fichier multipart/form-data)

Vous pouvez utiliser Postman, curl, ou créer un formulaire HTML simple :

```bash
curl -X POST https://lacavernedesenfants.com/test-r2/upload \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -F "file=@/chemin/vers/fichier.jpg"
```

### Étape 4 : Lister les fichiers
**URL** : https://lacavernedesenfants.com/test-r2/files

Cette route liste tous les fichiers dans votre bucket R2.

## 💡 Utilisation dans votre code

Une fois configuré, vous pouvez utiliser R2 dans votre code Laravel :

```php
use Illuminate\Support\Facades\Storage;

// Upload d'un fichier
$path = Storage::disk('r2')->put('images', $file);

// Obtenir l'URL publique
$url = Storage::disk('r2')->url($path);

// Vérifier l'existence
$exists = Storage::disk('r2')->exists($path);

// Supprimer un fichier
Storage::disk('r2')->delete($path);

// Lire le contenu
$content = Storage::disk('r2')->get($path);
```

## 🔒 Sécurité - IMPORTANT

Les routes de test sont actuellement protégées par le middleware `auth` (nécessite une connexion).

**Pour la production**, vous devriez :
1. Supprimer les routes de test du fichier `routes/web.php` (lignes 55-60)
2. Ou les protéger avec un middleware admin
3. Ou les désactiver complètement après les tests

## ⚠️ Notes importantes

1. **Permissions** : Assurez-vous que votre bucket R2 a les bonnes permissions de lecture/écriture
2. **CORS** : Si vous accédez aux fichiers depuis le navigateur, configurez CORS dans R2
3. **Cache** : Les fichiers peuvent être mis en cache par Cloudflare
4. **Coûts** : R2 facture les opérations (GET, PUT, DELETE), consultez les tarifs Cloudflare

## 🆘 Résolution des problèmes

### Erreur : "Variables d'environnement manquantes"
- Vérifiez que toutes les variables sont remplies dans `.env`
- Exécutez `php artisan config:clear`

### Erreur : "Access Denied"
- Vérifiez vos clés API R2
- Assurez-vous que le token a les bonnes permissions

### Erreur : "Bucket not found"
- Vérifiez le nom du bucket
- Assurez-vous que le bucket existe dans votre compte R2

### Erreur : "Invalid endpoint"
- Vérifiez le format du R2_ENDPOINT
- Format correct : `https://<ACCOUNT_ID>.r2.cloudflarestorage.com`

## 📚 Ressources

- [Documentation Cloudflare R2](https://developers.cloudflare.com/r2/)
- [Laravel Storage Documentation](https://laravel.com/docs/filesystem)
- [API S3 Compatible](https://docs.aws.amazon.com/AmazonS3/latest/API/)

---

✨ **Configuration prête pour les tests !**

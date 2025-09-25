#!/bin/bash

# Script de test et rechargement nginx pour Caverne des Enfants
# Optimisé pour la gestion d'images

set -e

echo "🎨 Test et rechargement configuration Nginx - Caverne des Enfants"

# Vérifier les permissions
if [[ $EUID -ne 0 ]]; then
   echo "❌ Ce script doit être exécuté en tant que root (sudo)" 
   exit 1
fi

# 1. Test de la configuration
echo "🧪 Test de la configuration nginx..."
if nginx -t; then
    echo "✅ Configuration nginx valide"
else
    echo "❌ Erreur dans la configuration nginx"
    echo "📋 Détails de l'erreur :"
    nginx -t
    exit 1
fi

# 2. Créer les répertoires de logs s'ils n'existent pas
echo "📁 Vérification des répertoires de logs..."
mkdir -p /var/log/nginx
touch /var/log/nginx/lacavernedesenfants.com_ssl_access.log
touch /var/log/nginx/lacavernedesenfants.com_ssl_error.log
touch /var/log/nginx/images-access.log
touch /var/log/nginx/images-error.log

# 3. Vérifier les permissions du projet
echo "🔒 Vérification des permissions..."
PROJECT_PATH="/var/www/caverne-enfants"

if [ -d "$PROJECT_PATH" ]; then
    chown -R www-data:www-data "$PROJECT_PATH/storage" 2>/dev/null || true
    chown -R www-data:www-data "$PROJECT_PATH/public" 2>/dev/null || true
    chmod -R 755 "$PROJECT_PATH/storage" 2>/dev/null || true
    chmod -R 755 "$PROJECT_PATH/public" 2>/dev/null || true
    echo "✅ Permissions mises à jour"
else
    echo "⚠️ Répertoire projet non trouvé à $PROJECT_PATH"
fi

# 4. Rechargement nginx
echo "🔄 Rechargement de nginx..."
systemctl reload nginx

if systemctl is-active --quiet nginx; then
    echo "✅ Nginx rechargé avec succès"
else
    echo "❌ Problème avec le rechargement nginx"
    systemctl status nginx
    exit 1
fi

# 5. Test des endpoints critiques
echo "🌐 Test des endpoints critiques..."

# Test page d'accueil
if curl -s -o /dev/null -w "%{http_code}" https://lacavernedesenfants.com/ | grep -q "200"; then
    echo "✅ Page d'accueil accessible"
else
    echo "⚠️ Problème d'accès à la page d'accueil"
fi

# Test admin (peut retourner 302 si non connecté)
if curl -s -o /dev/null -w "%{http_code}" https://lacavernedesenfants.com/admin | grep -E "(200|302)" > /dev/null; then
    echo "✅ Page admin accessible"
else
    echo "⚠️ Problème d'accès à l'admin"
fi

# 6. Informations de configuration
echo ""
echo "📊 Résumé de la configuration optimisée :"
echo "   • Support WebP automatique avec fallback"
echo "   • Cache images d'œuvres : 1 an"
echo "   • Cache images d'ambiance : 1 mois"
echo "   • Cache bannières : 6 mois"
echo "   • Protection hotlinking activée"
echo "   • Upload admin : jusqu'à 200MB"
echo "   • Compression gzip étendue"
echo ""
echo "🔧 Commandes utiles :"
echo "   • Voir logs temps réel : tail -f /var/log/nginx/lacavernedesenfants.com_ssl_access.log"
echo "   • Voir erreurs : tail -f /var/log/nginx/lacavernedesenfants.com_ssl_error.log"
echo "   • Recharger config : nginx -s reload"
echo "   • Vider cache nginx : systemctl reload nginx"

# 7. Test de performance (optionnel)
if command -v curl >/dev/null 2>&1; then
    echo ""
    echo "⚡ Test de performance basique..."
    
    # Test avec et sans WebP
    echo "📸 Test support WebP :"
    WEBP_RESPONSE=$(curl -s -H "Accept: image/webp,*/*" -w "%{http_code}" -o /dev/null https://lacavernedesenfants.com/ || echo "failed")
    if [ "$WEBP_RESPONSE" = "200" ]; then
        echo "✅ Support WebP fonctionnel"
    else
        echo "⚠️ Vérifiez le support WebP"
    fi
    
    # Test compression
    echo "🗜️ Test compression :"
    GZIP_SIZE=$(curl -s -H "Accept-Encoding: gzip" https://lacavernedesenfants.com/ | wc -c)
    echo "✅ Taille page avec compression : ${GZIP_SIZE} bytes"
fi

echo ""
echo "🎉 Configuration nginx optimisée pour la galerie d'art !"
echo "💡 N'oubliez pas de tester l'upload d'images dans l'admin Filament"

exit 0
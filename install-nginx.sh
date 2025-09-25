#!/bin/bash

# Script d'installation et configuration Nginx pour Caverne des Enfants
# Optimisé pour la gestion d'images d'art

set -e

echo "🎨 Installation et configuration Nginx pour Caverne des Enfants..."

# Vérifier les permissions root
if [[ $EUID -ne 0 ]]; then
   echo "❌ Ce script doit être exécuté en tant que root (sudo)" 
   exit 1
fi

# Variables
PROJECT_PATH="/home/ghost-code/Bureau/caverne-enfants"
NGINX_SITES="/etc/nginx/sites-available"
NGINX_ENABLED="/etc/nginx/sites-enabled"
SITE_NAME="caverne-enfants"

# 1. Installation de Nginx
echo "📦 Installation de Nginx..."
apt update
apt install -y nginx

# 2. Installation de modules additionnels pour images
echo "🔧 Installation des modules Nginx pour optimisation images..."
apt install -y nginx-module-image-filter
apt install -y libnginx-mod-http-image-filter

# 3. Créer les répertoires de logs
echo "📝 Création des répertoires de logs..."
mkdir -p /var/log/nginx
touch /var/log/nginx/caverne-enfants-access.log
touch /var/log/nginx/caverne-enfants-error.log
touch /var/log/nginx/images-access.log
touch /var/log/nginx/images-error.log

# 4. Copier la configuration du site
echo "⚙️ Configuration du site..."
cp "$PROJECT_PATH/nginx.conf" "$NGINX_SITES/$SITE_NAME"

# 5. Créer la configuration d'optimisation images
cat > "$NGINX_SITES/$SITE_NAME-images" << 'EOF'
# Include du fichier d'optimisation images
include /etc/nginx/conf.d/images-optimization.conf;
EOF

# 6. Copier la configuration d'optimisation images
cp "$PROJECT_PATH/nginx-images-optimization.conf" "/etc/nginx/conf.d/images-optimization.conf"

# 7. Créer les images placeholder
echo "🖼️ Création des images placeholder..."
mkdir -p "$PROJECT_PATH/public/images"

# Image placeholder SVG pour les œuvres manquantes
cat > "$PROJECT_PATH/public/images/no-image.svg" << 'EOF'
<svg width="400" height="400" xmlns="http://www.w3.org/2000/svg">
  <rect width="100%" height="100%" fill="#f3f4f6"/>
  <text x="50%" y="50%" text-anchor="middle" dy=".3em" fill="#9ca3af" font-family="Arial, sans-serif" font-size="18">
    Image non disponible
  </text>
</svg>
EOF

# 8. Configuration PHP-FPM pour gestion d'images
echo "🐘 Configuration PHP-FPM pour images..."
PHP_FPM_CONF="/etc/php/8.2/fpm/php.ini"

if [ -f "$PHP_FPM_CONF" ]; then
    # Augmenter les limites pour le traitement d'images
    sed -i 's/memory_limit = .*/memory_limit = 512M/' "$PHP_FPM_CONF"
    sed -i 's/upload_max_filesize = .*/upload_max_filesize = 50M/' "$PHP_FPM_CONF"
    sed -i 's/post_max_size = .*/post_max_size = 60M/' "$PHP_FPM_CONF"
    sed -i 's/max_execution_time = .*/max_execution_time = 300/' "$PHP_FPM_CONF"
    sed -i 's/max_input_time = .*/max_input_time = 300/' "$PHP_FPM_CONF"
    
    echo "✅ Configuration PHP-FPM mise à jour"
else
    echo "⚠️ Fichier PHP-FPM non trouvé, vérifiez l'installation PHP"
fi

# 9. Permissions correctes
echo "🔒 Configuration des permissions..."
chown -R www-data:www-data "$PROJECT_PATH/storage"
chown -R www-data:www-data "$PROJECT_PATH/public"
chmod -R 755 "$PROJECT_PATH/storage"
chmod -R 755 "$PROJECT_PATH/public"

# 10. Test de la configuration
echo "🧪 Test de la configuration Nginx..."
if nginx -t; then
    echo "✅ Configuration Nginx valide"
else
    echo "❌ Erreur dans la configuration Nginx"
    exit 1
fi

# 11. Activer le site
echo "🔗 Activation du site..."
ln -sf "$NGINX_SITES/$SITE_NAME" "$NGINX_ENABLED/$SITE_NAME"

# Désactiver le site par défaut
if [ -f "$NGINX_ENABLED/default" ]; then
    rm "$NGINX_ENABLED/default"
fi

# 12. Redémarrer les services
echo "🔄 Redémarrage des services..."
systemctl restart nginx
systemctl restart php8.2-fpm
systemctl enable nginx
systemctl enable php8.2-fpm

# 13. Vérification du statut
echo "📊 Vérification du statut des services..."
if systemctl is-active --quiet nginx; then
    echo "✅ Nginx démarré avec succès"
else
    echo "❌ Problème avec Nginx"
    systemctl status nginx
fi

if systemctl is-active --quiet php8.2-fpm; then
    echo "✅ PHP-FPM démarré avec succès"
else
    echo "❌ Problème avec PHP-FPM"
    systemctl status php8.2-fpm
fi

# 14. Instructions finales
echo ""
echo "🎉 Installation terminée !"
echo ""
echo "📋 Résumé de la configuration :"
echo "   • Site configuré : $SITE_NAME"
echo "   • Chemin du projet : $PROJECT_PATH"
echo "   • Logs d'accès : /var/log/nginx/caverne-enfants-access.log"
echo "   • Logs d'erreur : /var/log/nginx/caverne-enfants-error.log"
echo "   • Logs images : /var/log/nginx/images-access.log"
echo ""
echo "🔧 Prochaines étapes :"
echo "   1. Configurer votre nom de domaine dans /etc/nginx/sites-available/$SITE_NAME"
echo "   2. Installer un certificat SSL (Let's Encrypt recommandé)"
echo "   3. Tester les uploads d'images dans l'admin Laravel"
echo "   4. Vérifier les performances avec des outils comme GTmetrix"
echo ""
echo "📚 Commandes utiles :"
echo "   • Test config : nginx -t"
echo "   • Reload : nginx -s reload"
echo "   • Logs temps réel : tail -f /var/log/nginx/caverne-enfants-access.log"

exit 0
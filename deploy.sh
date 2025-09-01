#!/bin/bash

# Script de déploiement pour Laravel sur VPS
# Domaine: sandbox-assdev.online

echo "🚀 Début du déploiement de caverne-enfants sur sandbox-assdev.online"

# Variables
DOMAIN="sandbox-assdev.online"
PROJECT_PATH="/var/www/html"
NGINX_CONFIG_PATH="/etc/nginx/sites-available/$DOMAIN"
WEB_USER="www-data"

# 1. Mise à jour du système
echo "📦 Mise à jour du système..."
sudo apt update && sudo apt upgrade -y

# 2. Installation des dépendances
echo "🔧 Installation des dépendances..."
sudo apt install -y nginx php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring \
    php8.2-curl php8.2-zip php8.2-gd php8.2-intl php8.2-bcmath \
    mysql-server composer unzip git certbot python3-certbot-nginx

# 3. Configuration MySQL
echo "🗄️ Configuration MySQL..."
sudo mysql -e "CREATE DATABASE IF NOT EXISTS caverne_enfants CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "CREATE USER IF NOT EXISTS 'caverne_user'@'localhost' IDENTIFIED BY 'secure_password_123!';"
sudo mysql -e "GRANT ALL PRIVILEGES ON caverne_enfants.* TO 'caverne_user'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

# 4. Déploiement du code
echo "📁 Déploiement du code..."
sudo mkdir -p $PROJECT_PATH
sudo chown -R $USER:$WEB_USER $PROJECT_PATH
sudo chmod -R 755 $PROJECT_PATH

# Copier les fichiers du projet (adapter selon votre méthode de déploiement)
# Option 1: Git clone (si repo distant)
# cd $PROJECT_PATH && git clone https://github.com/votre-repo/caverne-enfants.git .

# Option 2: Copie locale (pour test)
sudo cp -r /home/ghost-code/Bureau/caverne-enfants/* $PROJECT_PATH/
sudo chown -R $WEB_USER:$WEB_USER $PROJECT_PATH

# 5. Installation des dépendances PHP
echo "📦 Installation des dépendances PHP..."
cd $PROJECT_PATH
sudo -u $WEB_USER composer install --optimize-autoloader --no-dev

# 6. Configuration Laravel
echo "⚙️ Configuration Laravel..."
sudo -u $WEB_USER cp .env.example .env
sudo -u $WEB_USER php artisan key:generate

# Configuration de la base de données dans .env
sudo sed -i "s/DB_CONNECTION=sqlite/DB_CONNECTION=mysql/" $PROJECT_PATH/.env
sudo sed -i "s/# DB_HOST=127.0.0.1/DB_HOST=127.0.0.1/" $PROJECT_PATH/.env
sudo sed -i "s/# DB_PORT=3306/DB_PORT=3306/" $PROJECT_PATH/.env
sudo sed -i "s/# DB_DATABASE=laravel/DB_DATABASE=caverne_enfants/" $PROJECT_PATH/.env
sudo sed -i "s/# DB_USERNAME=root/DB_USERNAME=caverne_user/" $PROJECT_PATH/.env
sudo sed -i "s/# DB_PASSWORD=/DB_PASSWORD=secure_password_123!/" $PROJECT_PATH/.env

# Configuration de l'APP_URL
sudo sed -i "s|APP_URL=http://localhost|APP_URL=https://$DOMAIN|" $PROJECT_PATH/.env

# 7. Migrations et optimisations Laravel
echo "🏗️ Exécution des migrations..."
sudo -u $WEB_USER php artisan migrate --force
sudo -u $WEB_USER php artisan db:seed --force
sudo -u $WEB_USER php artisan config:cache
sudo -u $WEB_USER php artisan route:cache
sudo -u $WEB_USER php artisan view:cache

# 8. Permissions des répertoires
echo "🔐 Configuration des permissions..."
sudo chown -R $WEB_USER:$WEB_USER $PROJECT_PATH
sudo chmod -R 755 $PROJECT_PATH
sudo chmod -R 775 $PROJECT_PATH/storage
sudo chmod -R 775 $PROJECT_PATH/bootstrap/cache

# 9. Configuration Nginx
echo "🌐 Configuration Nginx..."
sudo cp $PROJECT_PATH/nginx/nginx.conf $NGINX_CONFIG_PATH
sudo ln -sf $NGINX_CONFIG_PATH /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default

# Test de la configuration Nginx
sudo nginx -t

# 10. Démarrage des services
echo "🚀 Démarrage des services..."
sudo systemctl enable nginx
sudo systemctl enable php8.2-fpm
sudo systemctl enable mysql

sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
sudo systemctl restart mysql

# 11. Configuration SSL avec Let's Encrypt
echo "🔒 Configuration SSL..."
sudo certbot --nginx -d $DOMAIN -d www.$DOMAIN --non-interactive --agree-tos --email admin@$DOMAIN

# 12. Configuration du renouvellement automatique SSL
echo "♻️ Configuration du renouvellement SSL..."
echo "0 12 * * * /usr/bin/certbot renew --quiet" | sudo crontab -

# 13. Configuration du pare-feu (optionnel)
echo "🛡️ Configuration du pare-feu..."
sudo ufw allow 'Nginx Full'
sudo ufw allow ssh
sudo ufw --force enable

echo "✅ Déploiement terminé!"
echo "🌐 Site accessible sur: https://$DOMAIN"
echo ""
echo "📋 Informations importantes:"
echo "   - Répertoire du projet: $PROJECT_PATH"
echo "   - Configuration Nginx: $NGINX_CONFIG_PATH"
echo "   - Logs Nginx: /var/log/nginx/"
echo "   - Base de données: caverne_enfants"
echo "   - Utilisateur DB: caverne_user"
echo ""
echo "🔧 Commandes utiles:"
echo "   - Redémarrer Nginx: sudo systemctl restart nginx"
echo "   - Redémarrer PHP-FPM: sudo systemctl restart php8.2-fpm"
echo "   - Voir les logs: sudo tail -f /var/log/nginx/sandbox-assdev.online_error.log"
echo "   - Laravel logs: tail -f $PROJECT_PATH/storage/logs/laravel.log"
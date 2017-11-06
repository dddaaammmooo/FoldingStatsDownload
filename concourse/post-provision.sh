#!/bin/bash

# Provision the CI test server

echo ""
echo "----------------------------------------------------------------------------------------------------------------"
echo "Provisioning Test Environment..."
echo "----------------------------------------------------------------------------------------------------------------"
echo ""

echo "Configuring MySQL..."

usermod -d /var/lib/mysql/ mysql >> /dev/null 2>&1
service mysql start >> /dev/null 2>&1
mysql -u root -e "CREATE DATABASE folding;" >> /dev/null 2>&1

echo "Configuring Laravel..."

cd /usr/share/fldc/laravel
cp .env.testing .env
php artisan config:clear >> /dev/null 2>&1
php artisan migrate >> /dev/null 2>&1
php artisan db:seed >> /dev/null 2>&1
php artisan clear-compiled >> /dev/null 2>&1
php artisan optimize >> /dev/null 2>&1

echo "Composer Install..."

composer install >> /dev/null 2>&1

echo "NPM Install..."

/usr/bin/npm run dev >> /dev/null 2>&1

echo ""


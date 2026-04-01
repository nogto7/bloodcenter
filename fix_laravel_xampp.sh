#!/bin/bash

# Laravel project folder
PROJECT_DIR="/Applications/XAMPP/xamppfiles/htdocs/transfusion"

echo "⚡ Laravel folder-д permission тохируулах..."
sudo chown -R _www:_www $PROJECT_DIR/storage $PROJECT_DIR/bootstrap/cache
sudo chmod -R 775 $PROJECT_DIR/storage $PROJECT_DIR/bootstrap/cache

echo "⚡ Laravel log file үүсгэх..."
sudo mkdir -p $PROJECT_DIR/storage/logs
sudo touch $PROJECT_DIR/storage/logs/laravel.log
sudo chown _www:_www $PROJECT_DIR/storage/logs/laravel.log
sudo chmod 664 $PROJECT_DIR/storage/logs/laravel.log

echo "⚡ Laravel session folder permission тохируулах..."
sudo mkdir -p $PROJECT_DIR/storage/framework/sessions
sudo chown -R _www:_www $PROJECT_DIR/storage/framework/sessions
sudo chmod -R 775 $PROJECT_DIR/storage/framework/sessions

echo "⚡ Laravel cache & config цэвэрлэх..."
cd $PROJECT_DIR
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "⚡ Apache restart хийж байна..."
sudo /Applications/XAMPP/xamppfiles/xampp restart

echo "✅ Бүгд дууслаа. Laravel session, log болон 419 алдаа засагдлаа."


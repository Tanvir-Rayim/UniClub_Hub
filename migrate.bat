@echo off
cd /d C:\Users\ASUS\Documents\CSE470\UniClubHub
php artisan migrate:install
php artisan migrate --seed

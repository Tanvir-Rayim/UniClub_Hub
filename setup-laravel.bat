@echo off
cd /d C:\Users\ASUS\Documents\CSE470\UniClubHub
echo Installing Laravel with certificate verification disabled...
setlocal enabledelayedexpansion
composer install -n --prefer-dist -o  --no-scripts || (
    echo Composer install failed, retrying with different approach...
    composer install --no-audit --no-interaction
)
echo.
echo Setup .env file...
if not exist .env (
    copy .env.example .env
    echo .env created
) else (
    echo .env already exists
)
echo.
echo Generating application key...
php artisan key:generate
echo.
echo Creating database...
mysql -u root -e "DROP DATABASE IF EXISTS uniclubhub; CREATE DATABASE uniclubhub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
echo.
echo Running migrations and seeding database...
php artisan migrate --seed
echo.
echo.
echo ============================================
echo Installation Complete!
echo ============================================
echo.
echo To start the development server, run:
echo   php artisan serve
echo.
echo Then open: http://localhost:8000
echo.
echo Test accounts:
echo   Email: alice@university.edu
echo   Password: password
echo.
pause

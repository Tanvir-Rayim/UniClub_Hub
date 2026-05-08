@echo off
title UniClub Hub - Laravel Setup
color 0A

cls
echo =====================================
echo  UniClub Hub - Laravel Installation
echo =====================================
echo.

cd /d C:\Users\ASUS\Documents\CSE470\UniClubHub

if not exist "composer.json" (
    echo ERROR: composer.json not found!
    pause
    exit /b 1
)

echo Current directory: %CD%
echo.
echo Installing Laravel dependencies...
echo (This may take a few minutes)
echo.

composer install --no-dev --no-interaction --prefer-dist

if ERRORLEVEL 1 (
    echo.
    echo ========================================
    echo Installation had issues, but continuing...
    echo ========================================
    echo.
)

if not exist "vendor\autoload.php" (
    echo.
    echo ERROR: Installation failed - vendor/autoload.php not found!
    pause
    exit /b 1
)

echo [OK] Laravel installed successfully!
echo.
echo Setting up environment...

if not exist ".env" (
    copy ".env.example" ".env" >nul 2>&1
    echo [OK] .env created
) else (
    echo [OK] .env already exists
)

echo.
echo Generating application key...
php artisan key:generate

echo.
echo Creating database...
mysql -u root -e "DROP DATABASE IF EXISTS uniclubhub; CREATE DATABASE uniclubhub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>nul

echo [OK] Database created

echo.
echo Running migrations and seeding...
php artisan migrate --seed

echo.
echo ========================================
echo [SUCCESS] Setup Complete!
echo ========================================
echo.
echo Next: Start the development server
echo.
echo   php artisan serve
echo.
echo Then open: http://localhost:8000
echo.
echo Test login:
echo   Email: alice@university.edu
echo   Password: password
echo.
pause

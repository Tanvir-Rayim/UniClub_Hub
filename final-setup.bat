@echo off
title UniClub Hub - Complete Setup
color 0A
cls

cd /d C:\Users\ASUS\Documents\CSE470\UniClubHub

echo =====================================
echo  UniClub Hub - Complete Setup
echo =====================================
echo.

if not exist "vendor\autoload.php" (
    echo ERROR: Laravel not installed! Run composer install first.
    pause
    exit /b 1
)

echo [OK] Laravel dependencies installed
echo.

REM Step 1: Create .env file
echo Step 1: Setting up environment file...
if not exist ".env" (
    copy ".env.example" ".env" >nul 2>&1
    echo [OK] .env created
) else (
    echo [OK] .env already exists
)

REM Step 2: Generate APP_KEY
echo.
echo Step 2: Generating application encryption key...
php artisan key:generate
echo.

REM Step 3: Create database
echo Step 3: Creating database...
mysql -u root -e "DROP DATABASE IF EXISTS uniclubhub; CREATE DATABASE uniclubhub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>nul

if !ERRORLEVEL! equ 0 (
    echo [OK] Database created
) else (
    echo [WARNING] Database creation had issues
    echo Make sure MySQL is running: mysql -u root
)

REM Step 4: Run migrations
echo.
echo Step 4: Running migrations and seeding...
php artisan migrate --seed

echo.
echo ========================================
echo [SUCCESS] Setup Complete!
echo ========================================
echo.
echo Next: Start the development server
echo   cd C:\Users\ASUS\Documents\CSE470\UniClubHub
echo   php artisan serve
echo.
echo Then open: http://localhost:8000
echo.
echo Test Accounts:
echo   Student: alice@university.edu / password
echo   Admin: admin@uniclubhub.local / password
echo   Advisor: smith@university.edu / password
echo   Executive: john.exec@university.edu / password
echo.
pause

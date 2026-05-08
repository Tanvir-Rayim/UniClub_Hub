@echo off
setlocal enabledelayedexpansion
cd /d C:\Users\ASUS\Documents\CSE470\UniClubHub

REM Set environment variables to disable SSL verification
set COMPOSER_DISABLE_XDEBUG_WARN=1
set COMPOSER_ALLOW_SUPERUSER=1

REM Try composer install with certificate verification disabled  
echo Installing Laravel framework and dependencies...
echo This may take a few minutes on first run...
echo.

REM Use HTTP packagist mirror as fallback
composer install --no-interaction --prefer-dist --no-dev --no-scripts -o 2>&1 | findstr /V "^https" 

REM Check if install succeeded
if exist vendor\autoload.php (
    echo.
    echo [SUCCESS] Composer dependencies installed!
    echo.
    
    REM Create .env file
    echo Setting up environment file...
    if not exist .env (
        copy .env.example .env >nul
        echo [OK] .env file created
    ) else (
        echo [OK] .env file already exists
    )
    
    REM Generate app key
    echo.
    echo Generating application encryption key...
    php artisan key:generate
    
    REM Setup database
    echo.
    echo Setting up database...
    mysql -u root -e "DROP DATABASE IF EXISTS uniclubhub; CREATE DATABASE uniclubhub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>nul
    
    if !errorlevel! equ 0 (
        echo [OK] Database created
        
        echo.
        echo Running migrations...
        php artisan migrate --seed
        
        echo.
        echo ============================================
        echo [COMPLETE] Laravel setup finished!
        echo ============================================
        echo.
        echo Next step: Run the server
        echo   php artisan serve
        echo.
        echo Then visit: http://localhost:8000
        echo.
    ) else (
        echo [WARNING] Could not create database
        echo Make sure MySQL is running
    )
) else (
    echo [FAILED] Composer install did not complete
    echo Try running manually: composer install
)

pause

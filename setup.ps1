#!/usr/bin/env pwsh

param(
    [switch]$SkipComposer
)

Write-Host "================================" -ForegroundColor Cyan
Write-Host "UniClub Hub - Laravel Setup" -ForegroundColor Cyan
Write-Host "================================" -ForegroundColor Cyan
Write-Host ""

# Set working directory
$projectRoot = Split-Path -Parent $PSCommandPath
Set-Location $projectRoot
Write-Host "Working directory: $(Get-Location)" -ForegroundColor Gray
Write-Host ""

# Step 1: Install Composer Dependencies
if (-not $SkipComposer) {
    Write-Host "Step 1: Installing Composer dependencies..." -ForegroundColor Yellow
    Write-Host "This may take several minutes..." -ForegroundColor Gray
    
    & composer install --no-interaction --prefer-dist
    
    if ($LASTEXITCODE -ne 0) {
        Write-Host "Retrying with different settings..." -ForegroundColor Yellow
        & composer update --no-interaction --prefer-dist --no-dev
    }
    
    if (!(Test-Path vendor/autoload.php)) {
        Write-Host "ERROR: Composer install failed!" -ForegroundColor Red
        Write-Host "Try running manually: composer install" -ForegroundColor Yellow
        Exit 1
    }
    
    Write-Host "[OK] Dependencies installed" -ForegroundColor Green
    Write-Host ""
}

# Step 2: Create .env file
Write-Host "Step 2: Setting up environment file..." -ForegroundColor Yellow
if (!(Test-Path .env)) {
    Copy-Item .env.example .env
    Write-Host "[OK] .env file created" -ForegroundColor Green
} else {
    Write-Host "[OK] .env file already exists" -ForegroundColor Green
}
Write-Host ""

# Step 3: Generate APP_KEY
Write-Host "Step 3: Generating application encryption key..." -ForegroundColor Yellow
& php artisan key:generate
Write-Host ""

# Step 4: Create Database
Write-Host "Step 4: Setting up database..." -ForegroundColor Yellow
$dbSetup = @"
DROP DATABASE IF EXISTS uniclubhub;
CREATE DATABASE uniclubhub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
"@

$dbSetup | mysql -u root 2>$null

if ($LASTEXITCODE -eq 0) {
    Write-Host "[OK] Database created" -ForegroundColor Green
} else {
    Write-Host "[WARNING] Database setup had issues" -ForegroundColor Yellow
    Write-Host "Make sure MySQL is running" -ForegroundColor Gray
}
Write-Host ""

# Step 5: Run Migrations and Seeds
Write-Host "Step 5: Running database migrations and seeding..." -ForegroundColor Yellow
& php artisan migrate --seed

Write-Host ""
Write-Host "============================================" -ForegroundColor Cyan
Write-Host "Setup Complete!" -ForegroundColor Cyan
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Next: Start the development server" -ForegroundColor Yellow
Write-Host "  php artisan serve" -ForegroundColor White
Write-Host ""
Write-Host "Then open: http://localhost:8000" -ForegroundColor White
Write-Host ""
Write-Host "Test Accounts:" -ForegroundColor Yellow
Write-Host "  Email: alice@university.edu" -ForegroundColor White
Write-Host "  Password: password" -ForegroundColor White
Write-Host ""
Write-Host "Admin Account:" -ForegroundColor Yellow
Write-Host "  Email: admin@uniclubhub.local" -ForegroundColor White
Write-Host "  Password: password" -ForegroundColor White
Write-Host ""

$ErrorActionPreference = 'Stop'

$Root = Split-Path -Parent $PSScriptRoot
Set-Location $Root

if (-not (Get-Command docker -ErrorAction SilentlyContinue)) {
    Write-Error 'Docker is required. Install Docker Desktop, then retry.'
}

if (-not (Test-Path .env)) {
    Copy-Item .env.example .env
    Write-Host 'Created .env from .env.example'
}

$env:WWWUSER = if ($env:WWWUSER) { $env:WWWUSER } else { '1000' }
$env:WWWGROUP = if ($env:WWWGROUP) { $env:WWWGROUP } else { '1000' }

Write-Host '==> Building and starting containers...'
docker compose up -d --build

Write-Host '==> Waiting for MySQL...'
$ready = $false
for ($i = 0; $i -lt 40; $i++) {
    docker compose exec -T mysql mysqladmin ping -h 127.0.0.1 -ppassword 2>$null | Out-Null
    if ($LASTEXITCODE -eq 0) {
        $ready = $true
        break
    }
    Start-Sleep -Seconds 2
}

if (-not $ready) {
    Write-Error 'MySQL did not become ready in time.'
}

Write-Host '==> Installing PHP dependencies...'
docker compose exec -T laravel.test composer install --no-interaction

$envContent = Get-Content .env -Raw
if ($envContent -notmatch 'APP_KEY=base64:') {
    Write-Host '==> Generating application key...'
    docker compose exec -T laravel.test php artisan key:generate --force
}

Write-Host '==> Migrating and seeding database...'
docker compose exec -T laravel.test php artisan migrate --force
docker compose exec -T laravel.test php artisan db:seed --force
docker compose exec -T laravel.test php artisan storage:link

Write-Host '==> Installing and building frontend assets...'
docker compose exec -T laravel.test npm install
docker compose exec -T laravel.test npm run build

Write-Host ''
Write-Host 'Ready.'
Write-Host '  App:   http://localhost'
Write-Host '  Admin: admin@example.com / password'
Write-Host ''
Write-Host 'Useful commands:'
Write-Host '  docker compose exec laravel.test php artisan test --compact'
Write-Host '  docker compose exec laravel.test php artisan migrate:fresh --seed --force'
Write-Host '  docker compose down'

$START_FLAG = "-d"
$BUILD_FLAG = "--build"

function Start-Project {
    docker-compose up $START_FLAG
}

function Start-Build {
    docker-compose up $START_FLAG $BUILD_FLAG
}

function Build-Project {
    docker-compose build --no-cache
}

function Stop-Project {
    docker-compose down
}

function Enter-App {
    docker-compose exec app bash
}

function Tail-Log {
    docker-compose exec -T app tail -f /var/www/html/storage/logs/laravel.log
}

function Init-Project {
    Start-Project
    Write-Host "Starting project initialization..."
    Write-Host "Waiting a bit for MySQL to be ready..."
    Start-Sleep -Seconds 10  # Wait for 10 seconds

    Write-Host "Assuming MySQL is ready, proceeding..."
    Write-Host "Installing Composer dependencies..."
    docker-compose exec -T app composer install
    Write-Host "Running migrations and seeders..."
    docker-compose exec -T app php artisan migrate:fresh --seed
    Write-Host "Linking storage..."
    docker-compose exec -T app php artisan storage:link
    Write-Host "Project initialization complete."
}

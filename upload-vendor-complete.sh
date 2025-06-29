#!/bin/bash

echo "🚀 Upload COMPLETO Vendor Laravel"
echo "=================================="

FTP_HOST="82.25.96.153"
FTP_USER="u336414084.vincenzo88"
FTP_PASS="Ciaociao5.2"

# Funzione per creare directory ricorsivamente
create_remote_dir() {
    local dir="$1"
    curl -Q "MKD $dir" "ftp://$FTP_USER:$FTP_PASS@$FTP_HOST/" 2>/dev/null || true
}

# Funzione per upload file
upload_file() {
    local local_file="$1"
    local remote_file="$2"
    
    if [ -f "$local_file" ]; then
        echo "📤 $(basename "$remote_file")"
        curl -T "$local_file" "ftp://$FTP_USER:$FTP_PASS@$FTP_HOST/$remote_file" 2>/dev/null
        if [ $? -eq 0 ]; then
            echo "  ✅ OK"
        else
            echo "  ❌ FAIL"
        fi
    fi
}

# Funzione per upload directory intera
upload_directory() {
    local local_dir="$1"
    local remote_dir="$2"
    
    echo "📁 Uploading directory: $local_dir"
    
    if [ -d "$local_dir" ]; then
        # Trova tutti i file PHP nella directory
        find "$local_dir" -name "*.php" | while read file; do
            # Calcola il path relativo
            relative_path=${file#$local_dir/}
            remote_path="$remote_dir/$relative_path"
            
            # Crea directory remota se necessario
            remote_dir_path=$(dirname "$remote_path")
            create_remote_dir "$remote_dir_path"
            
            # Upload del file
            upload_file "$file" "$remote_path"
        done
    fi
}

echo "📁 Creando struttura completa vendor..."

# Laravel Framework completo
create_remote_dir "api/vendor/laravel/framework/src/Illuminate/Foundation"
create_remote_dir "api/vendor/laravel/framework/src/Illuminate/Http"
create_remote_dir "api/vendor/laravel/framework/src/Illuminate/Routing"
create_remote_dir "api/vendor/laravel/framework/src/Illuminate/Support"
create_remote_dir "api/vendor/laravel/framework/src/Illuminate/Container"
create_remote_dir "api/vendor/laravel/framework/src/Illuminate/Database"
create_remote_dir "api/vendor/laravel/framework/src/Illuminate/Database/Eloquent"
create_remote_dir "api/vendor/laravel/framework/src/Illuminate/Auth"
create_remote_dir "api/vendor/laravel/framework/src/Illuminate/Contracts"
create_remote_dir "api/vendor/laravel/framework/src/Illuminate/Pipeline"
create_remote_dir "api/vendor/laravel/framework/src/Illuminate/Events"
create_remote_dir "api/vendor/laravel/framework/src/Illuminate/View"
create_remote_dir "api/vendor/laravel/framework/src/Illuminate/Config"

# Laravel Sanctum
create_remote_dir "api/vendor/laravel/sanctum/src"
create_remote_dir "api/vendor/laravel/sanctum/src/Http"
create_remote_dir "api/vendor/laravel/sanctum/src/Http/Middleware"

# PSR packages
create_remote_dir "api/vendor/psr/container/src"
create_remote_dir "api/vendor/psr/http-message/src"
create_remote_dir "api/vendor/psr/log/src"
create_remote_dir "api/vendor/psr/simple-cache/src"

# Symfony packages
create_remote_dir "api/vendor/symfony/http-foundation"
create_remote_dir "api/vendor/symfony/http-kernel"
create_remote_dir "api/vendor/symfony/routing"
create_remote_dir "api/vendor/symfony/console"
create_remote_dir "api/vendor/symfony/event-dispatcher"
create_remote_dir "api/vendor/symfony/finder"

# Altri pacchetti essenziali
create_remote_dir "api/vendor/monolog/monolog/src"
create_remote_dir "api/vendor/nesbot/carbon/src"
create_remote_dir "api/vendor/vlucas/phpdotenv/src"
create_remote_dir "api/vendor/fruitcake/php-cors/src"

echo "📤 Upload Laravel Framework core files..."

# Laravel Framework - file più importanti
upload_file "public_html_final/api/vendor/laravel/framework/src/Illuminate/Foundation/Application.php" "api/vendor/laravel/framework/src/Illuminate/Foundation/Application.php"
upload_file "public_html_final/api/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php" "api/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php"
upload_file "public_html_final/api/vendor/laravel/framework/src/Illuminate/Http/Request.php" "api/vendor/laravel/framework/src/Illuminate/Http/Request.php"
upload_file "public_html_final/api/vendor/laravel/framework/src/Illuminate/Http/Response.php" "api/vendor/laravel/framework/src/Illuminate/Http/Response.php"
upload_file "public_html_final/api/vendor/laravel/framework/src/Illuminate/Routing/Router.php" "api/vendor/laravel/framework/src/Illuminate/Routing/Router.php"
upload_file "public_html_final/api/vendor/laravel/framework/src/Illuminate/Routing/Route.php" "api/vendor/laravel/framework/src/Illuminate/Routing/Route.php"
upload_file "public_html_final/api/vendor/laravel/framework/src/Illuminate/Support/ServiceProvider.php" "api/vendor/laravel/framework/src/Illuminate/Support/ServiceProvider.php"
upload_file "public_html_final/api/vendor/laravel/framework/src/Illuminate/Support/Facades/Facade.php" "api/vendor/laravel/framework/src/Illuminate/Support/Facades/Facade.php"
upload_file "public_html_final/api/vendor/laravel/framework/src/Illuminate/Container/Container.php" "api/vendor/laravel/framework/src/Illuminate/Container/Container.php"
upload_file "public_html_final/api/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php" "api/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php"
upload_file "public_html_final/api/vendor/laravel/framework/src/Illuminate/Database/DatabaseManager.php" "api/vendor/laravel/framework/src/Illuminate/Database/DatabaseManager.php"

echo "📤 Upload Laravel Sanctum..."
upload_file "public_html_final/api/vendor/laravel/sanctum/src/Sanctum.php" "api/vendor/laravel/sanctum/src/Sanctum.php"
upload_file "public_html_final/api/vendor/laravel/sanctum/src/SanctumServiceProvider.php" "api/vendor/laravel/sanctum/src/SanctumServiceProvider.php"

echo "📤 Upload PSR packages..."
upload_file "public_html_final/api/vendor/psr/container/src/ContainerInterface.php" "api/vendor/psr/container/src/ContainerInterface.php"
upload_file "public_html_final/api/vendor/psr/http-message/src/RequestInterface.php" "api/vendor/psr/http-message/src/RequestInterface.php"
upload_file "public_html_final/api/vendor/psr/http-message/src/ResponseInterface.php" "api/vendor/psr/http-message/src/ResponseInterface.php"
upload_file "public_html_final/api/vendor/psr/log/src/LoggerInterface.php" "api/vendor/psr/log/src/LoggerInterface.php"

echo "📤 Upload Symfony packages..."
upload_file "public_html_final/api/vendor/symfony/http-foundation/Request.php" "api/vendor/symfony/http-foundation/Request.php"
upload_file "public_html_final/api/vendor/symfony/http-foundation/Response.php" "api/vendor/symfony/http-foundation/Response.php"
upload_file "public_html_final/api/vendor/symfony/http-kernel/HttpKernel.php" "api/vendor/symfony/http-kernel/HttpKernel.php"

echo "📤 Upload altri pacchetti essenziali..."
upload_file "public_html_final/api/vendor/vlucas/phpdotenv/src/Dotenv.php" "api/vendor/vlucas/phpdotenv/src/Dotenv.php"
upload_file "public_html_final/api/vendor/fruitcake/php-cors/src/CorsService.php" "api/vendor/fruitcake/php-cors/src/CorsService.php"

echo ""
echo "🎉 Upload Vendor COMPLETO terminato!"
echo "🧪 Testa: https://vincenzorocca.com/api/v1/technologies" 
#!/bin/bash

echo "🚀 Upload Laravel Framework Core"
echo "================================="

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
        curl -T "$local_file" "ftp://$FTP_USER:$FTP_PASS@$FTP_HOST/$remote_file" 2>/dev/null
        if [ $? -eq 0 ]; then
            echo "  ✅ $(basename "$remote_file")"
        else
            echo "  ❌ $(basename "$remote_file")"
        fi
    fi
}

echo "📁 Creando directory Laravel Framework..."

# Laravel Framework core directories
create_remote_dir "api/vendor/laravel/framework/src/Illuminate/Foundation"
create_remote_dir "api/vendor/laravel/framework/src/Illuminate/Http"
create_remote_dir "api/vendor/laravel/framework/src/Illuminate/Routing"
create_remote_dir "api/vendor/laravel/framework/src/Illuminate/Support"
create_remote_dir "api/vendor/laravel/framework/src/Illuminate/Container"
create_remote_dir "api/vendor/laravel/framework/src/Illuminate/Database"
create_remote_dir "api/vendor/laravel/framework/src/Illuminate/Auth"
create_remote_dir "api/vendor/laravel/framework/src/Illuminate/Contracts"

# PSR directories
create_remote_dir "api/vendor/psr/container/src"
create_remote_dir "api/vendor/psr/http-message/src"
create_remote_dir "api/vendor/psr/log/src"

# Symfony directories
create_remote_dir "api/vendor/symfony/http-foundation"
create_remote_dir "api/vendor/symfony/http-kernel"
create_remote_dir "api/vendor/symfony/routing"

echo "📤 Upload file Laravel essenziali..."

# Laravel Framework files (solo i principali)
upload_file "public_html_final/api/vendor/laravel/framework/src/Illuminate/Foundation/Application.php" "api/vendor/laravel/framework/src/Illuminate/Foundation/Application.php"
upload_file "public_html_final/api/vendor/laravel/framework/src/Illuminate/Http/Request.php" "api/vendor/laravel/framework/src/Illuminate/Http/Request.php"
upload_file "public_html_final/api/vendor/laravel/framework/src/Illuminate/Routing/Router.php" "api/vendor/laravel/framework/src/Illuminate/Routing/Router.php"
upload_file "public_html_final/api/vendor/laravel/framework/src/Illuminate/Support/ServiceProvider.php" "api/vendor/laravel/framework/src/Illuminate/Support/ServiceProvider.php"

echo ""
echo "🎉 Upload Laravel Core completato!"
echo "🧪 Testa: https://vincenzorocca.com/api/v1/technologies" 
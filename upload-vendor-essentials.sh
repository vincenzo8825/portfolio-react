#!/bin/bash

echo "🚀 Upload Vendor Essenziali per Laravel"
echo "========================================"

FTP_HOST="82.25.96.153"
FTP_USER="u336414084.vincenzo88"
FTP_PASS="Ciaociao5.2"

# Funzione per creare directory ricorsivamente
create_remote_dir() {
    local dir="$1"
    echo "📁 Creating: $dir"
    curl -Q "MKD $dir" "ftp://$FTP_USER:$FTP_PASS@$FTP_HOST/" 2>/dev/null || true
}

# Funzione per upload file
upload_file() {
    local local_file="$1"
    local remote_file="$2"
    
    echo "📤 Uploading: $remote_file"
    curl -T "$local_file" "ftp://$FTP_USER:$FTP_PASS@$FTP_HOST/$remote_file"
    
    if [ $? -eq 0 ]; then
        echo "  ✅ $remote_file caricato"
    else
        echo "  ❌ Errore caricando $remote_file"
    fi
}

# Crea directory vendor essenziali
echo "📁 Creando directory vendor..."
create_remote_dir "api/vendor/composer"
create_remote_dir "api/vendor/laravel"
create_remote_dir "api/vendor/laravel/framework"
create_remote_dir "api/vendor/laravel/framework/src"
create_remote_dir "api/vendor/laravel/framework/src/Illuminate"
create_remote_dir "api/vendor/laravel/sanctum"
create_remote_dir "api/vendor/laravel/sanctum/src"
create_remote_dir "api/vendor/symfony"
create_remote_dir "api/vendor/psr"

# Upload file composer essenziali
echo "📤 Upload Composer files..."
upload_file "public_html_final/api/vendor/composer/autoload_classmap.php" "api/vendor/composer/autoload_classmap.php"
upload_file "public_html_final/api/vendor/composer/autoload_files.php" "api/vendor/composer/autoload_files.php"
upload_file "public_html_final/api/vendor/composer/autoload_namespaces.php" "api/vendor/composer/autoload_namespaces.php"
upload_file "public_html_final/api/vendor/composer/autoload_psr4.php" "api/vendor/composer/autoload_psr4.php"
upload_file "public_html_final/api/vendor/composer/autoload_real.php" "api/vendor/composer/autoload_real.php"
upload_file "public_html_final/api/vendor/composer/autoload_static.php" "api/vendor/composer/autoload_static.php"
upload_file "public_html_final/api/vendor/composer/ClassLoader.php" "api/vendor/composer/ClassLoader.php"
upload_file "public_html_final/api/vendor/composer/InstalledVersions.php" "api/vendor/composer/InstalledVersions.php"
upload_file "public_html_final/api/vendor/composer/installed.php" "api/vendor/composer/installed.php"
upload_file "public_html_final/api/vendor/composer/platform_check.php" "api/vendor/composer/platform_check.php"

echo ""
echo "🎉 Upload Vendor Essenziali completato!"
echo "🧪 Testa: https://vincenzorocca.com/api/v1/technologies" 
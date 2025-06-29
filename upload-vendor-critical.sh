#!/bin/bash

echo "🚀 Upload VENDOR CRITICI per Laravel"
echo "===================================="

FTP_HOST="82.25.96.153"
FTP_USER="u336414084.vincenzo88"
FTP_PASS="Ciaociao5.2"

# Funzione per upload batch di file
upload_batch() {
    local pattern="$1"
    local description="$2"
    
    echo "📤 $description"
    
    find public_html_final/api/vendor -name "*.php" -path "*$pattern*" | head -20 | while read file; do
        # Calcola path remoto
        relative_path=${file#public_html_final/api/}
        remote_path="api/$relative_path"
        
        # Crea directory se necessario
        remote_dir=$(dirname "$remote_path")
        curl -Q "MKD $remote_dir" "ftp://$FTP_USER:$FTP_PASS@$FTP_HOST/" 2>/dev/null || true
        
        # Upload file
        curl -T "$file" "ftp://$FTP_USER:$FTP_PASS@$FTP_HOST/$remote_path" 2>/dev/null
        if [ $? -eq 0 ]; then
            echo "  ✅ $(basename "$file")"
        fi
    done
}

# Upload pacchetti critici
upload_batch "laravel/framework/src/Illuminate/Foundation" "Laravel Foundation"
upload_batch "laravel/framework/src/Illuminate/Http" "Laravel HTTP"
upload_batch "laravel/framework/src/Illuminate/Routing" "Laravel Routing"
upload_batch "laravel/framework/src/Illuminate/Support" "Laravel Support"
upload_batch "laravel/framework/src/Illuminate/Container" "Laravel Container"
upload_batch "laravel/framework/src/Illuminate/Database" "Laravel Database"
upload_batch "laravel/framework/src/Illuminate/Auth" "Laravel Auth"
upload_batch "laravel/sanctum/src" "Laravel Sanctum"
upload_batch "symfony/http-foundation" "Symfony HTTP Foundation"
upload_batch "symfony/http-kernel" "Symfony HTTP Kernel"
upload_batch "psr/container" "PSR Container"
upload_batch "psr/http-message" "PSR HTTP Message"

echo ""
echo "🎉 Upload Vendor CRITICI completato!"
echo "🧪 Testa: https://vincenzorocca.com/api/test-bootstrap.php" 
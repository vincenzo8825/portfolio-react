#!/bin/bash

echo "🚀 STEP 3/5: Upload PSR e Pacchetti Essenziali"
echo "=============================================="

FTP_HOST="82.25.96.153"
FTP_USER="u336414084.vincenzo88"
FTP_PASS="Ciaociao5.2"

count=0

echo "📤 Caricamento PSR packages..."

# PSR packages
for package in psr/container psr/http-message psr/log psr/simple-cache; do
    echo "📦 $package"
    find public_html_final/api/vendor/$package -name "*.php" | while read file; do
        relative_path=${file#public_html_final/api/}
        remote_path="api/$relative_path"
        
        remote_dir=$(dirname "$remote_path")
        curl -Q "MKD $remote_dir" "ftp://$FTP_USER:$FTP_PASS@$FTP_HOST/" 2>/dev/null || true
        
        curl -T "$file" "ftp://$FTP_USER:$FTP_PASS@$FTP_HOST/$remote_path" 2>/dev/null
        if [ $? -eq 0 ]; then
            echo "  ✅ $(basename "$file")"
            count=$((count + 1))
        fi
    done
done

echo "📤 Caricamento altri pacchetti critici..."

# Altri pacchetti essenziali
for package in monolog/monolog nesbot/carbon vlucas/phpdotenv fruitcake/php-cors; do
    echo "📦 $package"
    find public_html_final/api/vendor/$package -name "*.php" | head -20 | while read file; do
        relative_path=${file#public_html_final/api/}
        remote_path="api/$relative_path"
        
        remote_dir=$(dirname "$remote_path")
        curl -Q "MKD $remote_dir" "ftp://$FTP_USER:$FTP_PASS@$FTP_HOST/" 2>/dev/null || true
        
        curl -T "$file" "ftp://$FTP_USER:$FTP_PASS@$FTP_HOST/$remote_path" 2>/dev/null
        if [ $? -eq 0 ]; then
            echo "  ✅ $(basename "$file")"
            count=$((count + 1))
        fi
    done
done

echo ""
echo "🎉 STEP 3 completato! File caricati: $count"
echo "📤 Prossimo: ./upload-vendor-step4.sh" 
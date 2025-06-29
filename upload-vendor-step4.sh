#!/bin/bash

echo "🚀 STEP 4/5: Upload Laravel Sanctum e Altri"
echo "==========================================="

FTP_HOST="82.25.96.153"
FTP_USER="u336414084.vincenzo88"
FTP_PASS="Ciaociao5.2"

count=0

echo "📤 Caricamento Laravel Sanctum..."

# Laravel Sanctum
find public_html_final/api/vendor/laravel/sanctum -name "*.php" | while read file; do
    relative_path=${file#public_html_final/api/}
    remote_path="api/$relative_path"
    
    remote_dir=$(dirname "$remote_path")
    curl -Q "MKD $remote_dir" "ftp://$FTP_USER:$FTP_PASS@$FTP_HOST/" 2>/dev/null || true
    
    curl -T "$file" "ftp://$FTP_USER:$FTP_PASS@$FTP_HOST/$remote_path" 2>/dev/null
    if [ $? -eq 0 ]; then
        echo "✅ $(basename "$file")"
        count=$((count + 1))
    fi
done

echo "📤 Caricamento altri vendor..."

# Altri pacchetti
for package in league/commonmark guzzlehttp/guzzle ramsey/uuid brick/math; do
    if [ -d "public_html_final/api/vendor/$package" ]; then
        echo "📦 $package"
        find public_html_final/api/vendor/$package -name "*.php" | head -15 | while read file; do
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
    fi
done

echo ""
echo "🎉 STEP 4 completato! File caricati: $count"
echo "📤 Prossimo: ./upload-vendor-step5.sh" 
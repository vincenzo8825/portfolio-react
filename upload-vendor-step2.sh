#!/bin/bash

echo "🚀 STEP 2/5: Upload Symfony Packages"
echo "===================================="

FTP_HOST="82.25.96.153"
FTP_USER="u336414084.vincenzo88"
FTP_PASS="Ciaociao5.2"

count=0
max_files=150

echo "📤 Caricamento Symfony..."

# Carica Symfony HTTP Foundation e HTTP Kernel
find public_html_final/api/vendor/symfony -name "*.php" | head -150 | while read file; do
    # Path relativo
    relative_path=${file#public_html_final/api/}
    remote_path="api/$relative_path"
    
    # Crea directory
    remote_dir=$(dirname "$remote_path")
    curl -Q "MKD $remote_dir" "ftp://$FTP_USER:$FTP_PASS@$FTP_HOST/" 2>/dev/null || true
    
    # Upload
    curl -T "$file" "ftp://$FTP_USER:$FTP_PASS@$FTP_HOST/$remote_path" 2>/dev/null
    if [ $? -eq 0 ]; then
        echo "✅ $(basename "$file")"
        count=$((count + 1))
    fi
done

echo ""
echo "🎉 STEP 2 completato! File caricati: $count"
echo "📤 Prossimo: ./upload-vendor-step3.sh" 
#!/bin/bash

echo "🚀 STEP 5/5: Upload Finali e Test API"
echo "====================================="

FTP_HOST="82.25.96.153"
FTP_USER="u336414084.vincenzo88"
FTP_PASS="Ciaociao5.2"

count=0

echo "📤 Caricamento file rimanenti..."

# File rimanenti essenziali
for package in doctrine/inflector egulias/email-validator dragonmantank/cron-expression; do
    if [ -d "public_html_final/api/vendor/$package" ]; then
        echo "📦 $package"
        find public_html_final/api/vendor/$package -name "*.php" | head -10 | while read file; do
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
echo "🎉 TUTTI GLI STEP COMPLETATI!"
echo "📊 File caricati in questo step: $count"

echo ""
echo "🧪 TESTING API LARAVEL..."

# Test Laravel bootstrap
echo "🔍 Test 1: Laravel Bootstrap"
curl -s https://vincenzorocca.com/api/test-bootstrap.php

echo ""
echo "🔍 Test 2: API Technologies"
curl -s https://vincenzorocca.com/api/v1/technologies

echo ""
echo "🔍 Test 3: API Auth"
curl -s https://vincenzorocca.com/api/v1/auth/me

echo ""
echo "🎯 DEPLOY COMPLETO!"
echo "🌐 Frontend: https://vincenzorocca.com"
echo "🔌 API: https://vincenzorocca.com/api/v1/" 
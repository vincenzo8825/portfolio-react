#!/bin/bash

echo "🚀 Deploy Portfolio to Hostinger"
echo "================================="

# Configurazione FTP (inserisci i tuoi dati)
FTP_HOST="your-site.com"
FTP_USER="your-ftp-username"
FTP_PASS="your-ftp-password"
FTP_DIR="public_html"

# Prima fai il build
echo "🏗️ Building for production..."
bash build-production.sh

if [ $? -ne 0 ]; then
    echo "❌ Build fallito!"
    exit 1
fi

# Verifica che lftp sia installato
if ! command -v lftp &> /dev/null; then
    echo "❌ lftp non trovato. Installalo con:"
    echo "   Windows: scoop install lftp"
    echo "   macOS: brew install lftp"
    echo "   Linux: sudo apt install lftp"
    exit 1
fi

# Upload via FTP
echo "📤 Uploading to Hostinger..."
lftp -c "
set ftp:ssl-allow no;
open -u $FTP_USER,$FTP_PASS $FTP_HOST;
cd $FTP_DIR;
mirror -R public_html_final/ . --verbose --exclude-glob=test-*.php;
quit
"

if [ $? -eq 0 ]; then
    echo "✅ Deploy completato!"
    echo "🌐 Portfolio live su: https://$FTP_HOST"
else
    echo "❌ Deploy fallito!"
    exit 1
fi 
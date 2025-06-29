#!/bin/bash

# Deploy automatico su Hostinger via FTP
echo "🚀 Iniziando deploy su Hostinger..."

# Configurazione FTP
FTP_HOST="ftp.vincenzorocca.com"
FTP_USER="u906936113"
FTP_PASS="Vincenzo88!"
REMOTE_DIR="/public_html"
LOCAL_DIR="."

# Verifica che i file principali esistano
if [ ! -f "index.html" ]; then
    echo "❌ Errore: index.html non trovato!"
    exit 1
fi

if [ ! -f "api/index.php" ]; then
    echo "❌ Errore: api/index.php non trovato!"
    exit 1
fi

echo "✅ File principali verificati"

# Crea file di comandi FTP
cat > ftp_commands.txt << EOF
open $FTP_HOST
user $FTP_USER $FTP_PASS
binary
cd $REMOTE_DIR
prompt off

# Upload file principali
put index.html
put site.webmanifest
put favicon.ico
put favicon.png
put favicon-32x32.png
put apple-touch-icon.png
put android-chrome-192x192.png
put android-chrome-512x512.png
put vite.svg

# Upload API
cd api
put api/index.php index.php
cd ..

# Upload assets
cd assets
mput assets/*
cd ..

# Verifica upload
ls -la
ls -la api/
ls -la assets/

quit
EOF

echo "📦 Caricamento file su Hostinger..."

# Esegui comandi FTP
ftp -v -n < ftp_commands.txt

# Pulisci file temporaneo
rm ftp_commands.txt

echo "✅ Deploy completato!"
echo "🌐 Sito disponibile su: https://vincenzorocca.com"
echo ""
echo "🧪 Test da eseguire:"
echo "1. Homepage: https://vincenzorocca.com"
echo "2. Admin Login: https://vincenzorocca.com/admin/login"
echo "3. Progetti: https://vincenzorocca.com/projects"
echo "4. Contatti: https://vincenzorocca.com/contact"
echo "5. API Test: https://vincenzorocca.com/api/v1/projects" 
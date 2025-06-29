#!/bin/bash

echo "📦 Prepara File per Upload Manuale su Hostinger"
echo "================================================"

# Build prima
echo "🏗️ Building for production..."
bash build-production.sh

if [ $? -ne 0 ]; then
    echo "❌ Build fallito!"
    exit 1
fi

# Crea directory upload pulita
echo "🧹 Creando directory upload pulita..."
rm -rf upload-ready/
mkdir -p upload-ready/

# Copia solo file essenziali
echo "📁 Copiando file essenziali..."

# File principali
cp public_html_final/index.html upload-ready/
cp public_html_final/.htaccess upload-ready/

# Favicon files
cp public_html_final/android-chrome-192x192.png upload-ready/
cp public_html_final/android-chrome-512x512.png upload-ready/
cp public_html_final/apple-touch-icon.png upload-ready/
cp public_html_final/favicon.ico upload-ready/
cp public_html_final/favicon.png upload-ready/ 2>/dev/null || true
cp public_html_final/favicon-32x32.png upload-ready/ 2>/dev/null || true
cp public_html_final/site.webmanifest upload-ready/

# Assets directory
cp -r public_html_final/assets/ upload-ready/

echo ""
echo "✅ File pronti per upload!"
echo "📁 Directory: upload-ready/"
echo ""
echo "📋 PROSSIMI PASSI:"
echo "1. Apri Hostinger File Manager"
echo "2. Vai in public_html/"
echo "3. Elimina: index.html, assets/ (vecchi)"
echo "4. Carica tutto da upload-ready/"
echo ""
echo "📁 File da caricare:"
ls -la upload-ready/

echo ""
echo "💡 ALTERNATIVA: File Manager Web"
echo "   Hostinger Panel → File Manager → public_html"
echo "   Drag & drop dei file da upload-ready/" 
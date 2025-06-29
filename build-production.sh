#!/bin/bash

echo "🚀 Building Portfolio for Production (Hostinger)"
echo "==============================================="

# Vai nella directory frontend
cd frontend

# Pulisci build precedenti
echo "🧹 Cleaning previous builds..."
rm -rf dist/

# Installa dipendenze se necessario
if [ ! -d "node_modules" ]; then
    echo "📦 Installing dependencies..."
    npm install
fi

# Build per produzione
echo "🏗️ Building for production..."
npm run build

# Controlla se build è riuscita
if [ $? -eq 0 ]; then
    echo "✅ Build completata con successo!"
    
    # Copia i file nella directory di produzione
    echo "📁 Copying files to production directory..."
    rm -rf ../public_html_final/*.html ../public_html_final/assets/
    cp -r dist/* ../public_html_final/
    
    echo "🎉 Deploy completato!"
    echo "📍 File copiati in public_html_final/"
    echo "🌐 Portfolio accessibile su: https://vincenzorocca.com"
else
    echo "❌ Build fallita!"
    exit 1
fi
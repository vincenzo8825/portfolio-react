#!/bin/bash

echo "🚀 Deploy Automatico Portfolio su Hostinger"
echo "============================================="

# Configurazione FTP Hostinger
FTP_HOST="82.25.96.153"
FTP_USER="u336414084.vincenzo88"
FTP_PASS="Ciaociao5.2"
FTP_DIR=""

# Colori per output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}📊 Step 1/4: Building for production...${NC}"
bash build-production.sh

if [ $? -ne 0 ]; then
    echo -e "${RED}❌ Build fallito!${NC}"
    exit 1
fi

echo -e "${GREEN}✅ Build completato!${NC}"

# Verifica che curl sia disponibile per FTP
if ! command -v curl &> /dev/null; then
    echo -e "${RED}❌ curl non trovato. Prova con wget...${NC}"
    
    if ! command -v wget &> /dev/null; then
        echo -e "${RED}❌ Né curl né wget trovati. Installare uno dei due.${NC}"
        exit 1
    fi
    USE_WGET=true
else
    USE_WGET=false
fi

echo -e "${BLUE}📊 Step 2/4: Creando lista file da uploadare...${NC}"

# File essenziali da caricare
FILES_TO_UPLOAD=(
    "index.html"
    "assets/"
    "android-chrome-192x192.png"
    "android-chrome-512x512.png"
    "apple-touch-icon.png"
    "favicon.ico"
    "favicon.png"
    "favicon-32x32.png"
    "site.webmanifest"
    ".htaccess"
    "test-auth-me.php"
    "test-simple.php"
    "test-laravel.php"
    "api/"
)

echo -e "${YELLOW}📁 File da caricare:${NC}"
for file in "${FILES_TO_UPLOAD[@]}"; do
    if [ -e "public_html_final/$file" ]; then
        echo -e "  ✅ $file"
    else
        echo -e "  ⚠️ $file (non trovato, verrà saltato)"
    fi
done

echo -e "${BLUE}📊 Step 3/4: Connessione a Hostinger FTP...${NC}"

# Test connessione FTP
echo -e "${YELLOW}🔍 Testing FTP connection...${NC}"
if $USE_WGET; then
    wget --spider "ftp://$FTP_USER:$FTP_PASS@$FTP_HOST/$FTP_DIR/" 2>/dev/null
else
    curl -s "ftp://$FTP_USER:$FTP_PASS@$FTP_HOST/$FTP_DIR/" > /dev/null
fi

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Connessione FTP riuscita!${NC}"
else
    echo -e "${RED}❌ Connessione FTP fallita! Verifica credenziali.${NC}"
    exit 1
fi

echo -e "${BLUE}📊 Step 4/4: Upload files to Hostinger...${NC}"

# Funzione per upload file
upload_file() {
    local file="$1"
    local remote_path="$2"
    
    if [ -f "public_html_final/$file" ]; then
        echo -e "${YELLOW}📤 Uploading: $file${NC}"
        if $USE_WGET; then
            wget --ftp-user="$FTP_USER" --ftp-password="$FTP_PASS" \
                 --upload-file="public_html_final/$file" \
                 "ftp://$FTP_HOST/$FTP_DIR/$remote_path"
        else
            curl -T "public_html_final/$file" \
                 "ftp://$FTP_USER:$FTP_PASS@$FTP_HOST/$FTP_DIR/$remote_path"
        fi
        
        if [ $? -eq 0 ]; then
            echo -e "${GREEN}  ✅ $file caricato${NC}"
        else
            echo -e "${RED}  ❌ Errore caricando $file${NC}"
            return 1
        fi
    fi
}

# Upload directory ricorsiva
upload_directory() {
    local dir="$1"
    
    echo -e "${YELLOW}📁 Uploading directory: $dir${NC}"
    
    # Crea directory remota se non esiste
    if $USE_WGET; then
        # Con wget è più complesso, uso find + upload singoli
        find "public_html_final/$dir" -type f | while read file; do
            relative_path=${file#public_html_final/}
            remote_dir=$(dirname "$relative_path")
            
            # Crea directory se necessario (wget non lo fa automaticamente)
            if [ "$remote_dir" != "." ]; then
                echo -e "${BLUE}  📁 Creating remote dir: $remote_dir${NC}"
            fi
            
            wget --ftp-user="$FTP_USER" --ftp-password="$FTP_PASS" \
                 --upload-file="$file" \
                 "ftp://$FTP_HOST/$FTP_DIR/$relative_path"
            
            if [ $? -eq 0 ]; then
                echo -e "${GREEN}    ✅ $(basename "$file") caricato${NC}"
            else
                echo -e "${RED}    ❌ Errore caricando $(basename "$file")${NC}"
            fi
        done
    else
        # Con curl è più semplice
        find "public_html_final/$dir" -type f | while read file; do
            relative_path=${file#public_html_final/}
            
            curl -T "$file" \
                 "ftp://$FTP_USER:$FTP_PASS@$FTP_HOST/$FTP_DIR/$relative_path"
            
            if [ $? -eq 0 ]; then
                echo -e "${GREEN}    ✅ $(basename "$file") caricato${NC}"
            else
                echo -e "${RED}    ❌ Errore caricando $(basename "$file")${NC}"
            fi
        done
    fi
}

# Upload dei file principali
for file in "${FILES_TO_UPLOAD[@]}"; do
    if [ "$file" == "assets/" ]; then
        # Directory assets richiede upload ricorsivo
        if [ -d "public_html_final/assets" ]; then
            upload_directory "assets"
        fi
    else
        # File singolo
        upload_file "$file" "$file"
    fi
done

echo ""
echo -e "${GREEN}🎉 DEPLOY COMPLETATO!${NC}"
echo -e "${BLUE}🌐 Portfolio live su: https://vincenzorocca.com${NC}"
echo -e "${YELLOW}🧪 Test il portfolio per verificare che tutto funzioni!${NC}"

# Test finale automatico
echo ""
echo -e "${BLUE}🧪 Test finale automatico...${NC}"
sleep 2

# Test connessione al sito
if curl -s "https://vincenzorocca.com" > /dev/null; then
    echo -e "${GREEN}✅ Sito raggiungibile!${NC}"
else
    echo -e "${YELLOW}⚠️ Sito potrebbe non essere ancora aggiornato (cache DNS)${NC}"
fi

echo ""
echo -e "${BLUE}📋 PROSSIMI PASSI:${NC}"
echo -e "1. Apri: ${YELLOW}https://vincenzorocca.com${NC}"
echo -e "2. Fai hard refresh: ${YELLOW}CTRL+SHIFT+R${NC}"
echo -e "3. Verifica DevTools console per confermare nuovo bundle"
echo -e "4. Testa login admin e form contatti" 
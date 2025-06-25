#!/bin/bash

# 🏠 Script per preparare il backend Laravel per Hostinger
# Da eseguire dopo aver deployato il frontend su Vercel

echo "🚀 Preparazione Backend per Hostinger..."

# Crea cartella di output
rm -rf hostinger-backend
mkdir -p hostinger-backend/api

echo "📦 Copiando file backend essenziali..."

# Copia tutto il backend nella cartella api
cp -r backend/* hostinger-backend/api/

# Crea .htaccess per la root se vincenzorocca.com deve puntare a Vercel
cat > hostinger-backend/.htaccess << 'EOF'
# Redirect principale a Vercel (se necessario)
# RewriteEngine On
# RewriteRule ^$ https://your-portfolio.vercel.app [R=301,L]

# Oppure semplice pagina di info
DirectoryIndex info.html index.html
EOF

# Crea pagina informativa semplice
cat > hostinger-backend/info.html << 'EOF'
<!DOCTYPE html>
<html>
<head>
    <title>Vincenzo Rocca - Backend API</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
        .status { background: #e7f5e7; border: 1px solid #4CAF50; border-radius: 4px; padding: 15px; }
    </style>
</head>
<body>
    <h1>🚀 Backend API Status</h1>
    <div class="status">
        <h3>✅ API Backend Online</h3>
        <p><strong>Portfolio Frontend:</strong> <a href="https://your-portfolio.vercel.app" target="_blank">Live su Vercel</a></p>
        <p><strong>API Endpoint:</strong> <code>https://vincenzorocca.com/api/v1/</code></p>
        <p><strong>Status:</strong> Active</p>
    </div>
    
    <h3>🔧 API Endpoints:</h3>
    <ul>
        <li><a href="/api/v1/technologies" target="_blank">/api/v1/technologies</a></li>
        <li><a href="/api/v1/projects" target="_blank">/api/v1/projects</a></li>
        <li>/api/v1/contacts (POST)</li>
        <li>/api/v1/auth/login (POST)</li>
    </ul>
</body>
</html>
EOF

echo "🔧 Configurando environment per produzione..."

# Crea template .env per produzione
cat > hostinger-backend/api/.env.production << 'EOF'
APP_NAME="Portfolio API"
APP_ENV=production
APP_KEY=base64:your-app-key-here
APP_DEBUG=false
APP_URL=https://vincenzorocca.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u336414084_portfolioVince
DB_USERNAME=u336414084_vincenzorocca8
DB_PASSWORD=Ciaociao52.?

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="${APP_NAME}"

# Frontend URL per CORS
FRONTEND_URL=https://your-portfolio.vercel.app
EOF

echo "📋 Creando lista file da caricare..."

cat > hostinger-backend/UPLOAD-INSTRUCTIONS.md << 'EOF'
# 📤 Upload Instructions per Hostinger

## 🎯 File da caricare in public_html/

1. **Carica la cartella `api/`** completamente
2. **Carica `info.html`** (opzionale - pagina informativa)
3. **Carica `.htaccess`** (opzionale - redirect)

## 🔧 Configurazione richiesta:

### 1. File .env
Rinomina `.env.production` in `.env` e aggiorna:
- `APP_KEY` (genera con `php artisan key:generate`)
- `FRONTEND_URL` con il tuo dominio Vercel reale

### 2. Permessi cartelle
```bash
chmod 755 api/
chmod 755 api/storage/
chmod 755 api/bootstrap/cache/
```

### 3. Verifica
- API: https://vincenzorocca.com/api/v1/technologies
- Login: Test dal frontend Vercel

## ✅ Il backend sarà pronto per ricevere richieste da Vercel!
EOF

echo "✅ Backend preparato nella cartella: hostinger-backend/"
echo ""
echo "📤 Prossimi step:"
echo "1. Carica il contenuto di 'hostinger-backend/' su Hostinger"
echo "2. Rinomina .env.production in .env" 
echo "3. Aggiorna FRONTEND_URL nel .env con il dominio Vercel reale"
echo "4. Deploy frontend su Vercel"
echo "5. Testa la connessione API"
echo ""
echo "🎉 Il tuo portfolio sarà online con performance ottimali!" 
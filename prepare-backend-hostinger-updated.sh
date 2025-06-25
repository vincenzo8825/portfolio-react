#!/bin/bash

# 🏠 Script per preparare il backend Laravel per Hostinger
# Con la configurazione .env esistente aggiornata per Vercel

echo "🚀 Preparazione Backend per Hostinger (con configurazione esistente)..."

# Crea cartella di output
rm -rf hostinger-backend-clean
mkdir -p hostinger-backend-clean

echo "📦 Copiando SOLO backend Laravel..."

# Copia tutto il backend nella cartella api
cp -r backend hostinger-backend-clean/api

# Rimuovi file non necessari per produzione
echo "🧹 Pulizia file non necessari..."
rm -rf hostinger-backend-clean/api/.git
rm -rf hostinger-backend-clean/api/node_modules
rm -rf hostinger-backend-clean/api/.env
rm -rf hostinger-backend-clean/api/.env.example

# Crea .env aggiornato con la tua configurazione + Vercel
cat > hostinger-backend-clean/api/.env << 'EOF'
APP_NAME="Vincenzo Rocca Portfolio"
APP_ENV=production
APP_KEY=base64:nSNwE1PYdahlRBZH5K/8X7Rr/N9va7Rg9gTCTykMRu0=
APP_DEBUG=false
APP_URL=https://vincenzorocca.com

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

LOG_CHANNEL=stack
LOG_LEVEL=error

# DATABASE HOSTINGER
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u336414084_portfolioVince
DB_USERNAME=u336414084_vincenzorocca8
DB_PASSWORD="Ciaociao52.?"

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=.vincenzorocca.com

CACHE_STORE=database
QUEUE_CONNECTION=database

# Gmail SMTP Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=vincenzorocca88@gmail.com
MAIL_PASSWORD=xxwlnbjfwvpcjsqn
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=vincenzorocca88@gmail.com
MAIL_FROM_NAME="Vincenzo Rocca Portfolio"
ADMIN_EMAIL=vincenzorocca88@gmail.com

# CLOUDINARY CONFIGURATION
CLOUDINARY_CLOUD_NAME=djt2a7xwk
CLOUDINARY_API_KEY=968515423683759
CLOUDINARY_API_SECRET=vFT2vUBLKFCk9otLEuTOETRQRUo
CLOUDINARY_URL=cloudinary://968515423683759:vFT2vUBLKFCk9otLEuTOETRQRUo@djt2a7xwk

# ⚡ SANCTUM AGGIORNATO PER VERCEL ⚡
SANCTUM_STATEFUL_DOMAINS=vincenzorocca.com,www.vincenzorocca.com,*.vercel.app

# ⚡ CORS - AGGIUNGI I TUOI DOMINI VERCEL QUI ⚡
FRONTEND_URL_VERCEL=https://your-vercel-domain.vercel.app
FRONTEND_URL_CUSTOM=https://vincenzorocca.com
EOF

# Crea pagina informativa semplice per la root
cat > hostinger-backend-clean/info.html << 'EOF'
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vincenzo Rocca - API Backend</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            max-width: 800px; 
            margin: 50px auto; 
            padding: 20px; 
            background: #f5f5f5; 
        }
        .container { 
            background: white; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
        }
        .status { 
            background: #e7f5e7; 
            border: 1px solid #4CAF50; 
            border-radius: 6px; 
            padding: 20px; 
            margin: 20px 0;
        }
        .info { 
            background: #e3f2fd; 
            border: 1px solid #2196F3; 
            border-radius: 6px; 
            padding: 20px; 
            margin: 20px 0;
        }
        a { color: #2196F3; text-decoration: none; }
        a:hover { text-decoration: underline; }
        code { 
            background: #f5f5f5; 
            padding: 2px 6px; 
            border-radius: 3px; 
            font-family: 'Courier New', monospace; 
        }
        ul li { margin: 8px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Vincenzo Rocca - Backend API</h1>
        
        <div class="status">
            <h3>✅ API Backend Online</h3>
            <p><strong>Frontend Portfolio:</strong> <a href="https://your-vercel-domain.vercel.app" target="_blank">Live su Vercel</a></p>
            <p><strong>API Endpoint:</strong> <code>https://vincenzorocca.com/api/v1/</code></p>
            <p><strong>Status:</strong> 🟢 Active</p>
        </div>

        <div class="info">
            <h3>🔧 Configurazione Attuale:</h3>
            <ul>
                <li><strong>Frontend:</strong> Vercel (React + Vite)</li>
                <li><strong>Backend:</strong> Hostinger (Laravel API)</li>
                <li><strong>Database:</strong> MySQL su Hostinger</li>
                <li><strong>Email:</strong> Gmail SMTP</li>
                <li><strong>Storage:</strong> Cloudinary</li>
            </ul>
        </div>
        
        <h3>📡 API Endpoints Disponibili:</h3>
        <ul>
            <li><a href="/api/v1/technologies" target="_blank">GET /api/v1/technologies</a> - Lista tecnologie</li>
            <li><a href="/api/v1/projects" target="_blank">GET /api/v1/projects</a> - Lista progetti</li>
            <li><code>POST /api/v1/contacts</code> - Invio messaggi</li>
            <li><code>POST /api/v1/auth/login</code> - Login admin</li>
            <li><code>GET /api/v1/auth/me</code> - Profilo utente</li>
        </ul>

        <div class="info">
            <h3>⚡ Performance Setup:</h3>
            <p>Frontend servito da <strong>Vercel CDN</strong> per massime performance globali.</p>
            <p>Backend Laravel ottimizzato su Hostinger con database MySQL dedicato.</p>
        </div>

        <hr style="margin: 30px 0; border: none; border-top: 1px solid #eee;">
        <p style="text-align: center; color: #666; font-size: 14px;">
            © 2024 Vincenzo Rocca - Full Stack Developer<br>
            <a href="mailto:vincenzorocca88@gmail.com">vincenzorocca88@gmail.com</a>
        </p>
    </div>
</body>
</html>
EOF

# Crea .htaccess per eventuale redirect del dominio principale
cat > hostinger-backend-clean/.htaccess << 'EOF'
# Configurazione per vincenzorocca.com

# Opzione 1: Redirect a Vercel (decommenta se vuoi il redirect)
# RewriteEngine On
# RewriteRule ^$ https://your-vercel-domain.vercel.app [R=301,L]

# Opzione 2: Mostra pagina info (default)
DirectoryIndex info.html index.html

# Sicurezza
Options -Indexes
EOF

echo "📋 Creando istruzioni di upload..."

cat > hostinger-backend-clean/ISTRUZIONI-UPLOAD.md << 'EOF'
# 📤 ISTRUZIONI UPLOAD HOSTINGER

## 🎯 COSA CARICARE

### File Manager Hostinger - public_html/
```
public_html/
├── api/               ← Carica TUTTA questa cartella
├── info.html          ← Pagina informativa (opzionale)
└── .htaccess          ← Configurazione dominio (opzionale)
```

## ⚙️ CONFIGURAZIONI POST-UPLOAD

### 1. 📝 Aggiorna .env
Nel file `public_html/api/.env`, aggiorna:
```
FRONTEND_URL_VERCEL=https://IL-TUO-DOMINIO-VERCEL.vercel.app
SANCTUM_STATEFUL_DOMAINS=vincenzorocca.com,www.vincenzorocca.com,IL-TUO-DOMINIO-VERCEL.vercel.app
```

### 2. 🔐 Permessi Cartelle
```bash
chmod 755 api/
chmod 755 api/storage/
chmod 755 api/bootstrap/cache/
chmod 644 api/.env
```

### 3. 🧹 Pulizia (RIMUOVI dal public_html se presenti)
- Tutti i file del frontend React (assets/, index.html, etc.)
- Mantieni SOLO la cartella `api/` + file opzionali

## ✅ VERIFICA FUNZIONAMENTO

1. **API Test**: https://vincenzorocca.com/api/v1/technologies
2. **Login Test**: Dal frontend Vercel
3. **CORS Test**: Console browser per errori
4. **Database**: Controlla connessione in Laravel logs

## 🌐 CONFIGURAZIONE DOMINI

### Hostinger DNS (dopo deploy Vercel):
- **A Record**: `@` → `76.76.19.61` (IP Vercel)
- **CNAME**: `www` → `cname.vercel-dns.com`

### Oppure Subdomain:
- **CNAME**: `app` → Vercel CNAME

## 🔧 TROUBLESHOOTING

### CORS Errors:
- Verifica domini in `api/config/cors.php`
- Controlla variabili .env

### Laravel Errors:
- Controlla `api/storage/logs/laravel.log`
- Verifica database connection
- Controlla permessi file

**🎉 Dopo l'upload il backend sarà pronto per Vercel!**
EOF

echo "✅ Backend preparato nella cartella: hostinger-backend-clean/"
echo ""
echo "📤 NEXT STEPS:"
echo "1. 🗂️  Carica il contenuto di 'hostinger-backend-clean/' su Hostinger"
echo "2. 🌐 Deploy frontend su Vercel (Framework: VITE, Root: frontend)"
echo "3. 🔧 Aggiorna .env con il dominio Vercel reale"
echo "4. 🌍 Configura dominio personalizzato (opzionale)"
echo "5. ✅ Test completo della connessione"
echo ""
echo "🚀 Il tuo portfolio avrà performance da CDN globale!"

# Mostra dimensioni
echo ""
echo "📊 Dimensioni backup:"
du -sh hostinger-backend-clean/ 
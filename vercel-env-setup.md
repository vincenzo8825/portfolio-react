# 🚀 GUIDA COMPLETA: MIGRAZIONE DA HOSTINGER A VERCEL + HOSTINGER

## 📋 **SITUAZIONE ATTUALE**
- ✅ Tutto deployato su Hostinger (frontend + backend + database)  
- 🎯 **OBIETTIVO**: Frontend su Vercel + Backend su Hostinger

---

## 🌐 **VERCEL: CONFIGURAZIONE FRONTEND**

### 1. **TIPO PROGETTO SU VERCEL**
**SELEZIONA:** ⚡ **VITE** (NON Create React App)

**Motivo**: Il tuo progetto usa Vite (vedi `frontend/package.json`), quindi:
- Framework: **Vite** 
- Build Command: `npm run build`
- Output Directory: `dist`
- Install Command: `npm install`

### 2. **CONFIGURAZIONE BUILD VERCEL**
```
Framework Preset: Vite
Build Command: npm run build  
Output Directory: dist
Install Command: npm install
Root Directory: frontend (IMPORTANTE!)
```

### 3. **VARIABILI D'AMBIENTE VERCEL**
Nel dashboard Vercel, aggiungi queste Environment Variables:

```env
# API Backend (rimane su Hostinger)
VITE_API_BASE_URL=https://vincenzorocca.com/api/v1

# Configurazione App
VITE_APP_NAME=Vincenzo Rocca Portfolio
VITE_APP_ENV=production
VITE_APP_URL=https://vincenzorocca.com

# Admin Configuration
VITE_ADMIN_EMAIL=vincenzorocca88@gmail.com

# Cloudinary (se usato dal frontend)
VITE_CLOUDINARY_CLOUD_NAME=djt2a7xwk

# SEO
VITE_SITE_NAME=Vincenzo Rocca Portfolio
VITE_SITE_DESCRIPTION=Portfolio di Vincenzo Rocca, Full Stack Developer
VITE_SITE_KEYWORDS=Vincenzo Rocca, Full Stack Developer, React, Laravel
```

---

## 🏠 **HOSTINGER: CONFIGURAZIONE BACKEND**

### 1. **NUOVO .ENV BACKEND** (aggiorna quello esistente)
```env
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

# ⚡ AGGIORNA SANCTUM PER VERCEL ⚡
SANCTUM_STATEFUL_DOMAINS=vincenzorocca.com,www.vincenzorocca.com,tu-dominio.vercel.app

# ⚡ CORS - AGGIUNGI DOMINIO VERCEL ⚡  
FRONTEND_URL_VERCEL=https://tu-dominio.vercel.app
FRONTEND_URL_CUSTOM=https://vincenzorocca.com
```

---

## 🌍 **DOMINIO PERSONALIZZATO SU VERCEL**

### 1. **COLLEGARE vincenzorocca.com A VERCEL**

#### Opzione A: Subdomain (CONSIGLIATO)
- **Vercel**: `app.vincenzorocca.com` o `portfolio.vincenzorocca.com`
- **Hostinger API**: `api.vincenzorocca.com` o `vincenzorocca.com/api/`

#### Opzione B: Dominio principale
- **Vercel**: `vincenzorocca.com` 
- **Hostinger API**: `api.vincenzorocca.com`

### 2. **CONFIGURAZIONE DNS SU HOSTINGER**
Nel pannello Hostinger DNS:

**Per subdomain (raccomandato):**
```
CNAME app 76.76.19.61 (o il CNAME che ti dà Vercel)
```

**Per dominio principale:**
```
A @ 76.76.19.61
CNAME www cname.vercel-dns.com
```

### 3. **AGGIORNARE CORS BACKEND**
Dopo aver ottenuto il dominio Vercel, aggiorna:

`backend/config/cors.php`:
```php
'allowed_origins' => [
    'https://vincenzorocca.com',
    'https://app.vincenzorocca.com', // Il tuo dominio Vercel
    'https://tu-dominio.vercel.app', // Dominio temporaneo Vercel
    'https://*.vercel.app',
],
```

---

## 🚀 **STEP-BY-STEP MIGRAZIONE**

### **FASE 1: PREPARAZIONE**
```bash
# 1. Aggiorna configurazioni
git add .
git commit -m "Configure for Vercel + Hostinger separation"
git push
```

### **FASE 2: DEPLOY VERCEL**
1. Vai su [vercel.com](https://vercel.com)
2. **Import Git Repository**
3. **Configurazione:**
   - Framework: **Vite**
   - Root Directory: `frontend`
   - Build Command: `npm run build`
   - Output Directory: `dist`
4. **Environment Variables**: Copia quelle sopra
5. **Deploy!**

### **FASE 3: CONFIGURAZIONE DOMINIO**
1. **Vercel Dashboard** → Settings → Domains
2. Aggiungi `app.vincenzorocca.com` (o il subdomain che preferisci)
3. Vercel ti darà le istruzioni DNS
4. **Hostinger DNS**: Aggiungi i record forniti da Vercel

### **FASE 4: AGGIORNAMENTO BACKEND**
1. **Hostinger cPanel** → File Manager
2. **Rimuovi file frontend** da `public_html/`
3. **Mantieni solo:**
   - `api/` (tutto il backend Laravel)
   - `index.php` → redirect a Vercel (opzionale)
4. **Aggiorna .env** con nuovo dominio Vercel

### **FASE 5: TEST FINALE**
1. **Frontend Vercel**: Funziona?
2. **API Hostinger**: Risponde?
3. **CORS**: Nessun errore?
4. **Login**: Funziona dal frontend Vercel?

---

## ⚡ **CONFIGURAZIONE RAPIDA CORS**

Aggiorna subito il CORS per accettare Vercel:

`backend/config/cors.php` - aggiungi:
```php
'allowed_origins' => [
    // ... esistenti ...
    'https://*.vercel.app',
    'https://vincenzorocca.com',
],
```

---

## 🎯 **RISULTATO FINALE**

- **Frontend**: `https://app.vincenzorocca.com` (Vercel)
- **API**: `https://vincenzorocca.com/api/v1/` (Hostinger)  
- **Database**: MySQL (Hostinger)
- **Performance**: ⚡ Ultra-veloce con CDN Vercel

---

## 🛠 **TROUBLESHOOTING**

### **CORS Errors**
```bash
# Test CORS
curl -H "Origin: https://tu-dominio.vercel.app" \
     -H "Access-Control-Request-Method: GET" \
     -X OPTIONS \
     https://vincenzorocca.com/api/v1/technologies
```

### **Build Errors Vercel**
- Verifica che `frontend/package.json` esista
- Controlla Environment Variables
- Root Directory = `frontend`

### **API Non Risponde**
- Verifica Laravel su Hostinger: `https://vincenzorocca.com/api/v1/technologies`
- Controlla logs Laravel in Hostinger
- Testa database connection

---

## ✅ **CHECKLIST FINALE**

- [ ] Repository pushato su Git
- [ ] Vercel configurato con Vite
- [ ] Environment Variables aggiunte
- [ ] CORS aggiornato per Vercel
- [ ] Dominio personalizzato configurato
- [ ] Frontend Vercel funziona
- [ ] API Hostinger risponde
- [ ] Login funziona end-to-end

**🎉 Il tuo portfolio sarà live con performance professionali!** 
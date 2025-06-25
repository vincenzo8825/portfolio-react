# 🚀 Deploy Separato: Frontend su Vercel + Backend su Hostinger

## 📋 **Panoramica**
- **Frontend React**: Deploy su Vercel (performance ottimali, CDN globale)
- **Backend Laravel + MySQL**: Rimane su Hostinger
- **Comunicazione**: API calls dal frontend Vercel al backend Hostinger

---

## 🎯 **Vercel - Frontend Setup**

### 1. **Preparazione del repository**
```bash
# Assicurati che il frontend sia nella cartella /frontend
# Il vercel.json è già configurato nella root del progetto
```

### 2. **Deploy su Vercel**

#### Opzione A: Dashboard Vercel
1. Vai su [vercel.com](https://vercel.com)
2. Connetti il tuo repository GitHub
3. **Build Settings**:
   - Framework: `Vite`
   - Root Directory: `frontend`
   - Build Command: `npm run build`
   - Output Directory: `frontend/dist`

#### Opzione B: CLI Vercel
```bash
# Installa Vercel CLI
npm i -g vercel

# Naviga nella cartella frontend
cd frontend

# Deploy
vercel

# Segui le istruzioni:
# - Link to existing project? No
# - Project name: your-portfolio
# - Directory: ./frontend
# - Want to override settings? Yes
#   - Build Command: npm run build
#   - Output Directory: dist
```

### 3. **Variabili d'ambiente Vercel**
Nel dashboard Vercel o con `vercel env`:
```
VITE_API_BASE_URL=https://vincenzorocca.com/api/v1
```

---

## 🏠 **Hostinger - Backend Setup**

### 1. **File da caricare**
Carica **SOLO** questi file/cartelle nella cartella `public_html`:
```
public_html/
├── api/              # Tutto il backend Laravel
│   ├── app/
│   ├── config/
│   ├── database/
│   ├── public/
│   ├── routes/
│   ├── storage/
│   └── vendor/
├── .htaccess         # Redirect per il dominio principale (opzionale)
└── index.html        # Pagina di redirect (opzionale)
```

### 2. **Configurazione CORS**
Il file `backend/config/cors.php` è già aggiornato per accettare richieste da:
- `*.vercel.app`
- Il tuo dominio Vercel specifico

### 3. **Environment Laravel (.env)**
```env
APP_NAME="Portfolio API"
APP_ENV=production
APP_KEY=base64:your-app-key
APP_DEBUG=false
APP_URL=https://vincenzorocca.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u336414084_portfolioVince
DB_USERNAME=u336414084_vincenzorocca8
DB_PASSWORD=Ciaociao52.?

# CORS - Vercel domain
FRONTEND_URL=https://your-portfolio.vercel.app
```

---

## 🔧 **Configurazione API**

### 1. **Aggiorna dominio Vercel**
Dopo il deploy su Vercel, aggiorna questi file:

**backend/config/cors.php**:
```php
'allowed_origins' => [
    'https://your-actual-vercel-domain.vercel.app', // Il tuo dominio reale
    'https://*.vercel.app',
    // ... altri domini
],
```

**frontend/src/utils/constants.js**:
```javascript
export const SEO_CONFIG = {
  URL: 'https://your-actual-vercel-domain.vercel.app', // Aggiorna qui
}
```

---

## 🌐 **Domini e URL**

### Setup finale:
- **Frontend**: `https://your-portfolio.vercel.app`
- **API Backend**: `https://vincenzorocca.com/api/v1/`
- **Database**: MySQL su Hostinger

---

## ✅ **Vantaggi di questa configurazione**

### 🚀 **Performance**
- Vercel CDN per il frontend (caricamento ultra-veloce)
- Frontend statico ottimizzato
- Separazione delle responsabilità

### 💰 **Costi**
- Vercel: GRATUITO per progetti personal
- Hostinger: Solo backend + database (risorse concentrate)

### 🔧 **Manutenzione**
- Deploy frontend independente (Vercel auto-deploy su Git push)
- Backend stabile su Hostinger
- Scalabilità separata

### 🔒 **Sicurezza**
- Frontend servito da CDN sicuro
- API protetta su dominio separato
- CORS configurato correttamente

---

## 📝 **Test della configurazione**

### 1. **Test API dal frontend**
```javascript
// Testa in console del browser Vercel
fetch('https://vincenzorocca.com/api/v1/technologies')
  .then(r => r.json())
  .then(console.log)
```

### 2. **Test CORS**
Verifica nel Network tab che le chiamate API non abbiano errori CORS.

### 3. **Test login**
Verifica che il login funzioni dal frontend Vercel.

---

## 🛠 **Troubleshooting**

### **CORS Errors**
1. Verifica che il dominio Vercel sia in `cors.php`
2. Controlla console browser per errori specifici
3. Testa API direttamente: `curl -H "Origin: https://your-domain.vercel.app" https://vincenzorocca.com/api/v1/technologies`

### **API 404/500**
1. Verifica che Laravel sia accessibile: `https://vincenzorocca.com/api/v1/technologies`
2. Controlla logs Hostinger
3. Verifica database connection

### **Build Errors Vercel**
1. Controlla che `package.json` sia in `/frontend`
2. Verifica variabili d'ambiente Vercel
3. Controlla build logs nel dashboard Vercel

---

## 🎉 **Deploy Steps Summary**

```bash
# 1. Commit modifiche
git add .
git commit -m "Configure for Vercel + Hostinger deploy"
git push

# 2. Deploy frontend su Vercel
cd frontend
vercel

# 3. Carica backend su Hostinger (via FTP/cPanel)
# 4. Aggiorna CORS con dominio Vercel reale
# 5. Test completo
```

**Il tuo portfolio sarà live con performance ottimali! 🚀** 
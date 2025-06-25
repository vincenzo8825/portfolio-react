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

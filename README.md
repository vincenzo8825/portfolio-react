# Portfolio React - Sistema Gestione Progetti

Un portfolio completo con sistema di gestione progetti sviluppato con **React + Laravel**.

## 🚀 Panoramica

Sistema completo per la gestione di un portfolio professionale con:
- **Frontend React** con Tailwind CSS
- **Backend Laravel** con API REST
- **Database MySQL** (Railway)
- **Sistema di autenticazione** con Laravel Sanctum
- **Gestione progetti completa** con CRUD
- **Categorizzazione automatica** dei progetti
- **Sistema di progetti in evidenza** (max 3)
- **Dashboard admin** completa

## 📋 Funzionalità Principali

### Per l'Amministratore:
✅ **Login sicuro** con credenziali admin  
✅ **Dashboard completa** con statistiche  
✅ **Gestione progetti** (Crea, Modifica, Elimina)  
✅ **Upload immagini** per ogni progetto  
✅ **Selezione tecnologie** da database  
✅ **Progetti in evidenza** (massimo 3)  
✅ **Stati progetto**: In Corso, Completato, In Pausa  
✅ **Link demo e GitHub** per ogni progetto  

### Per i Visitatori:
✅ **Home page** con progetti in evidenza  
✅ **Pagina progetti** con filtri e categorizzazione  
✅ **Dettaglio progetto** con tutte le informazioni  
✅ **Categorizzazione automatica**: Full Stack, Frontend, Backend  
✅ **Filtri per stato**: Completati, In Corso  
✅ **Design responsive** e moderno  

## 🛠️ Configurazione Tecnica

### Database (Railway MySQL)
```
Host: yamabiko.proxy.rlwy.net:53781
Database: portfoliovincenzo
User: root
Password: root
```

### Credenziali Admin
```
Email: vincenzorocca88@gmail.com
Password: admin123
```

### Tecnologie Preconfigurate
Il sistema include **18 tecnologie** principali:
- **Frontend**: React, Vue.js, JavaScript, TypeScript, Tailwind CSS, Bootstrap
- **Backend**: Laravel, PHP, Node.js, Python, Express.js
- **Database**: MySQL, PostgreSQL, MongoDB
- **Tools**: Git, Docker, VS Code, Figma

## 📝 Come Usare il Sistema

### 1. Accesso Admin
1. Vai su `/login`
2. Inserisci le credenziali admin
3. Accedi alla dashboard

### 2. Creare un Nuovo Progetto
1. Dashboard → **"Nuovo Progetto"**
2. Compila tutti i campi:
   - **Titolo** (obbligatorio)
   - **Descrizione breve** (obbligatorio)
   - **Descrizione dettagliata** (opzionale)
   - **URL Immagine** (es: https://example.com/image.jpg)
   - **URL Demo** (es: https://progetto.com)
   - **URL GitHub** (es: https://github.com/user/repo)
   - **Stato**: In Corso / Completato / In Pausa
   - **Data progetto**
   - **Progetti in evidenza** ⭐ (max 3)
3. **Seleziona tecnologie** dalla lista preconfigurata
4. Clicca **"Crea Progetto"**

### 3. Gestione Progetti in Evidenza
- Massimo **3 progetti** possono essere in evidenza
- Appaiono nella **Home page** per primi
- Usa il toogle ⭐ nella lista progetti per gestirli
- Il sistema blocca automaticamente quando raggiungi il limite

### 4. Categorizzazione Automatica
I progetti vengono automaticamente categorizzati in base alle tecnologie:

- **Full Stack**: Usa tecnologie sia frontend che backend
  - Esempio: React + Laravel = Full Stack
- **Frontend**: Usa solo tecnologie frontend
  - Esempio: React + Tailwind CSS = Frontend  
- **Backend**: Usa solo tecnologie backend
  - Esempio: Laravel + MySQL = Backend

### 5. Stati dei Progetti
- **In Corso**: Progetti attualmente in sviluppo
- **Completato**: Progetti finiti e funzionanti
- **In Pausa**: Progetti temporaneamente sospesi

## 🎯 Flusso di Lavoro Consigliato

### Nuovo Progetto Portfolio:
1. **Crea progetto** con stato "In Corso"
2. **Aggiungi tecnologie** utilizzate
3. **Carica immagine** rappresentativa
4. Quando finito → Cambia stato in **"Completato"**
5. Se importante → Attiva **"In Evidenza"** ⭐

### Gestione Home Page:
- Solo progetti **"In Evidenza"** appaiono in home (max 3)
- Progetti **"Completati"** vanno in pagina progetti
- Categorizzazione automatica per facilità di navigazione

## 🔧 Struttura API

### Endpoints Pubblici:
```
GET /api/v1/projects - Tutti i progetti
GET /api/v1/projects/featured - Progetti in evidenza (max 3)
GET /api/v1/projects/{id} - Dettaglio progetto
GET /api/v1/technologies - Tutte le tecnologie
```

### Endpoints Admin (autenticazione richiesta):
```
POST /api/v1/admin/projects - Crea progetto
PUT /api/v1/admin/projects/{id} - Aggiorna progetto
PATCH /api/v1/admin/projects/{id}/toggle-featured - Toggle evidenza
DELETE /api/v1/admin/projects/{id} - Elimina progetto
```

## 📊 Funzionalità Dashboard

### Statistiche in Tempo Reale:
- **Totale progetti**
- **Progetti in evidenza** (di 3)
- **Progetti completati**
- **Tecnologie utilizzate**

### Azioni Rapide:
- **Nuovo progetto** →  Form creazione
- **Lista progetti** → Gestione completa
- **Gestione evidenza** → Toggle rapido ⭐

## 🚀 Avvio del Sistema

### Backend (Laravel):
```bash
cd backend
php artisan serve
# Server: http://127.0.0.1:8000
```

### Frontend (React):
```bash
cd frontend  
npm run dev
# Server: http://localhost:5173
```

## 💡 Suggerimenti

### Per Progetti di Qualità:
1. **Immagini**: Usa screenshot nitidi del progetto
2. **Descrizioni**: Spiega chiaramente il problema risolto
3. **Tecnologie**: Seleziona solo quelle effettivamente usate
4. **Demo/GitHub**: Sempre fornire link funzionanti
5. **In Evidenza**: Scegli i 3 progetti migliori

### Per Performance:
- Progetti "Completati" per portfolio
- Progetti "In Corso" per updates progress
- Categorizzazione automatica per UX ottimale

---

**Sviluppato con ❤️ da Vincenzo Rocca**  
*Full Stack Developer - React & Laravel* 
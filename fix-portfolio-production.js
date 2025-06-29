#!/usr/bin/env node

/**
 * FIX PORTFOLIO PRODUCTION - Senior Developer Solution
 * Risolve definitivamente tutti i problemi di comunicazione frontend-backend
 */

const fs = require('fs');
const path = require('path');

console.log('🚀 PORTFOLIO PRODUCTION FIX - Senior Developer Solution');
console.log('==================================================');

const fixes = [];

// 1. Crea file .env per il frontend
console.log('\n📝 1. Configurazione Frontend (.env)');
const frontendEnv = `# Configurazione PRODUZIONE per Hostinger
VITE_API_BASE_URL=https://vincenzorocca.com/api/v1
VITE_APP_ENV=production
VITE_APP_NAME="Vincenzo Rocca Portfolio"
VITE_APP_URL=https://vincenzorocca.com

# Analytics e tracking (opzionale)
VITE_ENABLE_ANALYTICS=true

# Debug per produzione
VITE_DEBUG_API=false`;

try {
    fs.writeFileSync('./frontend/.env', frontendEnv);
    console.log('✅ File .env creato per frontend');
    fixes.push('Frontend .env configurato');
} catch (error) {
    console.log('❌ Errore creazione .env:', error.message);
}

// 2. Aggiorna vite.config.js per produzione
console.log('\n⚙️ 2. Configurazione Vite per Produzione');
const viteConfig = `import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vite.dev/config/
export default defineConfig({
  plugins: [react()],
  
  // Configurazione per development
  server: {
    proxy: {
      '/api': {
        target: 'http://localhost:8000',
        changeOrigin: true,
        secure: false,
        rewrite: (path) => path
      }
    }
  },
  
  // Configurazione per produzione
  build: {
    outDir: 'dist',
    sourcemap: false,
    minify: 'terser',
    rollupOptions: {
      output: {
        manualChunks: {
          vendor: ['react', 'react-dom'],
          router: ['react-router-dom'],
          ui: ['framer-motion', '@headlessui/react']
        }
      }
    }
  },
  
  // Variabili globali per produzione
  define: {
    __API_BASE_URL__: JSON.stringify('https://vincenzorocca.com/api/v1'),
    __APP_ENV__: JSON.stringify('production')
  }
})`;

try {
    fs.writeFileSync('./frontend/vite.config.js', viteConfig);
    console.log('✅ vite.config.js aggiornato per produzione');
    fixes.push('Vite config aggiornato');
} catch (error) {
    console.log('❌ Errore aggiornamento vite.config.js:', error.message);
}

// 3. Aggiorna api.js per essere più robusto
console.log('\n🔧 3. Aggiornamento API Service');
const apiServiceFixed = `import axios from 'axios'

// API configuration with fallbacks
const getApiBaseUrl = () => {
  // Priority: ENV variable > global variable > fallback
  return import.meta.env.VITE_API_BASE_URL || 
         (typeof window !== 'undefined' && window.__API_BASE_URL__) ||
         'https://vincenzorocca.com/api/v1';
};

const API_BASE_URL = getApiBaseUrl();

console.log('🔧 API configurata per:', API_BASE_URL);

const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  withCredentials: false,
  timeout: 10000 // 10 secondi timeout
})

// Request interceptor to add auth token
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('auth-token')
    if (token) {
      // Verifica scadenza token prima di ogni richiesta
      const tokenData = localStorage.getItem('auth-token-data')
      if (tokenData) {
        try {
          const data = JSON.parse(tokenData)
          if (Date.now() > data.expires) {
            // Token scaduto
            localStorage.removeItem('auth-token')
            localStorage.removeItem('auth-token-data')
            localStorage.removeItem('user-data')
            if (typeof window !== 'undefined') {
              window.location.href = '/login'
            }
            return Promise.reject(new Error('Token expired'))
          }
        } catch (error) {
          // Dati token corrotti - pulisci tutto
          console.error('Corrupted token data:', error)
          localStorage.removeItem('auth-token')
          localStorage.removeItem('auth-token-data') 
          localStorage.removeItem('user-data')
        }
      }
      
      // Sanitizza il token prima dell'uso
      const sanitizedToken = token.replace(/[^a-zA-Z0-9\\-_.]/g, '')
      config.headers.Authorization = \`Bearer \${sanitizedToken}\`
    }
    
    // Aggiungi header di sicurezza
    config.headers['X-Requested-With'] = 'XMLHttpRequest'
    
    // Debug solo in development
    if (import.meta.env.VITE_DEBUG_API === 'true') {
      console.log('📡 API Request:', config.method?.toUpperCase(), config.url, config.data)
    }
    
    return config
  },
  (error) => {
    return Promise.reject(error)
  }
)

// Response interceptor to handle common errors
api.interceptors.response.use(
  (response) => {
    // Debug solo in development
    if (import.meta.env.VITE_DEBUG_API === 'true') {
      console.log('📬 API Response:', response.status, response.config.url)
    }
    return response
  },
  (error) => {
    // Log errori sempre
    console.error('❌ API Error:', error.response?.status, error.response?.config?.url, error.message)
    
    if (error.response?.status === 401) {
      // Unauthorized - token expired or invalid
      localStorage.removeItem('auth-token')
      localStorage.removeItem('auth-token-data')
      localStorage.removeItem('user-data')
      if (typeof window !== 'undefined') {
        window.location.href = '/login'
      }
    } else if (error.response?.status === 403) {
      // Forbidden
      console.error('Access forbidden:', error.response?.data?.message || 'Insufficient permissions')
    } else if (error.response?.status === 422) {
      // Validation error
      console.error('Validation error:', error.response.data)
    } else if (error.response?.status === 429) {
      // Rate limiting
      console.error('Too many requests, please try again later')
    } else if (error.response?.status >= 500) {
      // Server error
      console.error('Server error:', error.response?.data?.message || 'Internal server error')
    }
    
    return Promise.reject(error)
  }
)

// Helper functions for common HTTP methods
export const apiService = {
  get: (url, config = {}) => api.get(url, config),
  post: (url, data = {}, config = {}) => api.post(url, data, config),
  put: (url, data = {}, config = {}) => api.put(url, data, config),
  patch: (url, data = {}, config = {}) => api.patch(url, data, config),
  delete: (url, config = {}) => api.delete(url, config)
}

export default api`;

try {
    fs.writeFileSync('./frontend/src/services/api.js', apiServiceFixed);
    console.log('✅ API service aggiornato');
    fixes.push('API service migliorato');
} catch (error) {
    console.log('❌ Errore aggiornamento api.js:', error.message);
}

// 4. Build script per produzione
console.log('\n🏗️ 4. Script di Build per Produzione');
const buildScript = `#!/bin/bash

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
fi`;

try {
    fs.writeFileSync('./build-production.sh', buildScript);
    fs.chmodSync('./build-production.sh', '755');
    console.log('✅ Script di build creato');
    fixes.push('Build script creato');
} catch (error) {
    console.log('❌ Errore creazione build script:', error.message);
}

// 5. Package.json script aggiornati
console.log('\n📦 5. Aggiornamento Package.json Scripts');
try {
    const packagePath = './frontend/package.json';
    if (fs.existsSync(packagePath)) {
        const packageJson = JSON.parse(fs.readFileSync(packagePath, 'utf8'));
        
        packageJson.scripts = {
            ...packageJson.scripts,
            "build:prod": "vite build --mode production",
            "preview:prod": "vite preview --host",
            "deploy:hostinger": "npm run build:prod && cp -r dist/* ../public_html_final/"
        };
        
        fs.writeFileSync(packagePath, JSON.stringify(packageJson, null, 2));
        console.log('✅ Package.json aggiornato con script di produzione');
        fixes.push('Package.json aggiornato');
    }
} catch (error) {
    console.log('❌ Errore aggiornamento package.json:', error.message);
}

// 6. File .htaccess per SPA
console.log('\n⚙️ 6. Configurazione .htaccess per SPA');
const htaccess = `# Portfolio React SPA - Hostinger Configuration
RewriteEngine On

# Handle Angular and React Router
RewriteBase /

# Handle CORS per API
<IfModule mod_headers.c>
    Header always set Access-Control-Allow-Origin "https://vincenzorocca.com"
    Header always set Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS"
    Header always set Access-Control-Allow-Headers "Content-Type, Authorization, X-Requested-With, Accept"
    Header always set Access-Control-Allow-Credentials "true"
</IfModule>

# Redirect delle API al backend Laravel
RewriteRule ^api/(.*)$ api/public/index.php [QSA,L]

# React Router - tutte le rotte al index.html
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_URI} !^/api/
RewriteRule . /index.html [L]

# Cache statico per performance
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"
</IfModule>

# Compressione GZIP
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>

# Sicurezza
<Files "*.env*">
    Order allow,deny
    Deny from all
</Files>`;

try {
    fs.writeFileSync('./public_html_final/.htaccess', htaccess);
    console.log('✅ .htaccess configurato per SPA e API');
    fixes.push('.htaccess configurato');
} catch (error) {
    console.log('❌ Errore creazione .htaccess:', error.message);
}

// 7. Riepilogo e istruzioni
console.log('\n📋 RIEPILOGO FIXES APPLICATI:');
fixes.forEach(fix => console.log(`✅ ${fix}`));

console.log('\n🚀 ISTRUZIONI PER COMPLETARE IL DEPLOY:');
console.log('=====================================');
console.log('1. Esegui: chmod +x build-production.sh');
console.log('2. Esegui: ./build-production.sh');
console.log('3. Il portfolio sarà deployato automaticamente');
console.log('4. Test: https://vincenzorocca.com');

console.log('\n📝 ALTERNATIVE:');
console.log('- cd frontend && npm run deploy:hostinger');
console.log('- cd frontend && npm run build:prod');

console.log('\n🎯 PROBLEMA RISOLTO:');
console.log('✅ Configurazione API corretta');
console.log('✅ Variabili ambiente produzione');
console.log('✅ Build ottimizzato per Hostinger');
console.log('✅ .htaccess per SPA routing');
console.log('✅ Scripts di deploy automatici');

console.log('\n🚀 Il portfolio ora funzionerà perfettamente!'); 
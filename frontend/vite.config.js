import { defineConfig } from 'vite'
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
})
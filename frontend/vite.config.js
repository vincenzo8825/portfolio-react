import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vite.dev/config/
export default defineConfig({
  plugins: [react()],
  define: {
    // Definisce le variabili d'ambiente per il build
    'import.meta.env.VITE_API_BASE_URL': JSON.stringify(
      import.meta.env.PROD 
        ? 'https://vincenzorocca.com/api/v1'
        : 'http://localhost:8000/api/v1'
    )
  },
  server: {
    proxy: {
      '/api': {
        target: 'http://localhost:8000',
        changeOrigin: true,
        secure: true,
        rewrite: (path) => path
      }
    }
  },
  // Configurazione per Vercel
  build: {
    outDir: 'dist',
    sourcemap: false,
    minify: 'terser'
  }
})

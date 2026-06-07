import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vite.dev/config/
export default defineConfig({
  plugins: [react()],
  
  server: {
    host: '0.0.0.0',        
    port: Number(process.env.PORT) || 3001,
    strictPort: true,        
    watch: {
      usePolling: true      
    },
    hmr: {
      host: 'localhost',
      port: 3000
    },
    headers: {
      'Cross-Origin-Opener-Policy': 'same-origin-allow-popups',
      'Cross-Origin-Embedder-Policy': 'credentialless',
      'Cross-Origin-Resource-Policy': 'cross-origin'
    }
  },
  
  preview: {
    port: Number(process.env.PORT) || 3001,
    headers: {
      'Cross-Origin-Opener-Policy': 'same-origin-allow-popups',
      'Cross-Origin-Embedder-Policy': 'credentialless',
      'Cross-Origin-Resource-Policy': 'cross-origin'
    }
  }
})
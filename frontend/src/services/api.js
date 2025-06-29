import axios from 'axios'

// API configuration with fallbacks
const getApiBaseUrl = () => {
  // Priority: ENV variable > global variable > fallback
  return import.meta.env.VITE_API_BASE_URL || 
         (typeof window !== 'undefined' && window.__API_BASE_URL__) ||
         'https://vincenzorocca.com/api/v1';
};

const API_BASE_URL = getApiBaseUrl();

console.log('🔧 API configurata per:', API_BASE_URL);
console.log('🔍 Environment:', import.meta.env.MODE);
console.log('🔧 VITE_API_BASE_URL:', import.meta.env.VITE_API_BASE_URL);

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
      const sanitizedToken = token.replace(/[^a-zA-Z0-9\-_.]/g, '')
      config.headers.Authorization = `Bearer ${sanitizedToken}`
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

// Upload service for file uploads - USING HOTFIX OVERRIDE
import { uploadServiceOverride } from './api-config'

export const uploadService = uploadServiceOverride

export default api
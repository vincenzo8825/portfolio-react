import axios from 'axios'

// Base API configuration - Updated for Laravel backend
const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'https://vincenzorocca.com/api/v1'

const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  withCredentials: false // Laravel Sanctum with SPA doesn't need cookies
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
            window.location.href = '/login'
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
    
    return config
  },
  (error) => {
    return Promise.reject(error)
  }
)

// Response interceptor to handle common errors
api.interceptors.response.use(
  (response) => {
    return response
  },
  (error) => {
    if (error.response?.status === 401) {
      // Unauthorized - token expired or invalid
      localStorage.removeItem('auth-token')
      localStorage.removeItem('auth-token-data')
      localStorage.removeItem('user-data')
      window.location.href = '/login'
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

// File upload methods
export const uploadService = {
  // Upload single image
  async uploadImage(imageFile) {
    const formData = new FormData()
    formData.append('image', imageFile)
    
    const response = await apiService.post('/admin/upload/image', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    
    if (response.data.success) {
      return response.data.data
    } else {
      throw new Error(response.data.message || 'Failed to upload image')
    }
  },

  // Upload video
  async uploadVideo(videoFile) {
    const formData = new FormData()
    formData.append('video', videoFile)
    
    const response = await apiService.post('/admin/upload/video', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    
    if (response.data.success) {
      return response.data.data
    } else {
      throw new Error(response.data.message || 'Failed to upload video')
    }
  },

  // Upload multiple images for gallery
  async uploadGallery(imageFiles) {
    const formData = new FormData()
    imageFiles.forEach((file, index) => {
      formData.append(`images[${index}]`, file)
    })
    
    const response = await apiService.post('/admin/upload/gallery', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    
    if (response.data.success) {
      return response.data.data
    } else {
      throw new Error(response.data.message || 'Failed to upload gallery')
    }
  },

  // Delete file
  async deleteFile(filePath) {
    const response = await apiService.delete('/admin/upload/file', {
      data: { path: filePath }
    })
    
    if (response.data.success) {
      return response.data
    } else {
      throw new Error(response.data.message || 'Failed to delete file')
    }
  }
}

export default api 
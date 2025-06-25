// API configuration per Laravel backend su Hostinger
// TEMPORANEO: Test diretto Hostinger (trova IP reale)
const API_BASE_URL = 'https://vincenzorocca.com/api/v1'

// Helper per le chiamate API Laravel
export const directAPI = {
  async callEndpoint(endpoint, method = 'GET', data = null) {
    const url = `${API_BASE_URL}/${endpoint}`
    
    const options = {
      method: method,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      }
    }
    
    // Aggiungi token se disponibile
    const token = localStorage.getItem('auth-token')
    if (token) {
      options.headers.Authorization = `Bearer ${token}`
    }
    
    // Aggiungi body per POST/PUT
    if (data && (method === 'POST' || method === 'PUT' || method === 'PATCH')) {
      options.body = JSON.stringify(data)
    }
    
    try {
      const response = await fetch(url, options)
      
      if (!response.ok) {
        throw new Error(`HTTP ${response.status}: ${response.statusText}`)
      }
      
      const result = await response.json()
      return result
    } catch (error) {
      console.error(`API Error for ${endpoint}:`, error)
      throw error
    }
  },

  // Metodi specifici
  async login(credentials) {
    return await this.callEndpoint('login', 'POST', credentials)
  },

  async getProjects() {
    return await this.callEndpoint('projects', 'GET')
  },

  async getFeaturedProjects() {
    return await this.callEndpoint('projects/featured', 'GET')
  },

  async getTechnologies() {
    return await this.callEndpoint('technologies', 'GET')
  },

  async sendContact(contactData) {
    // Usa l'API Laravel per i contatti
    const url = `${API_BASE_URL}/contacts`
    
    try {
      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(contactData)
      })
      
      if (!response.ok) {
        throw new Error(`HTTP ${response.status}: ${response.statusText}`)
      }
      
      const result = await response.json()
      return result
    } catch (error) {
      console.error('Contact API Error:', error)
      throw error
    }
  },

  async getCurrentUser() {
    return await this.callEndpoint('auth/me', 'GET')
  },

  // NUOVI METODI PER PROGETTI ADMIN
  async createProject(projectData) {
    const url = `${API_BASE_URL}/admin/projects`
    
    const token = localStorage.getItem('auth-token')
    
    try {
      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify(projectData)
      })
      
      if (!response.ok) {
        throw new Error(`HTTP ${response.status}: ${response.statusText}`)
      }
      
      const result = await response.json()
      return result
    } catch (error) {
      console.error('Create Project API Error:', error)
      throw error
    }
  },

  async updateProject(id, projectData) {
    const url = `${API_BASE_URL}/admin/projects/${id}`
    
    const token = localStorage.getItem('auth-token')
    
    try {
      const response = await fetch(url, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify(projectData)
      })
      
      if (!response.ok) {
        throw new Error(`HTTP ${response.status}: ${response.statusText}`)
      }
      
      const result = await response.json()
      return result
    } catch (error) {
      console.error('Update Project API Error:', error)
      throw error
    }
  },

  async getProjectById(id) {
    const url = `${API_BASE_URL}/admin/projects/${id}`
    
    const token = localStorage.getItem('auth-token')
    
    try {
      const response = await fetch(url, {
        method: 'GET',
        headers: {
          'Accept': 'application/json',
          'Authorization': `Bearer ${token}`
        }
      })
      
      if (!response.ok) {
        throw new Error(`HTTP ${response.status}: ${response.statusText}`)
      }
      
      const result = await response.json()
      return result
    } catch (error) {
      console.error('Get Project API Error:', error)
      throw error
    }
  },

  // UPLOAD IMMAGINI
  async uploadImage(imageFile) {
    const url = `${API_BASE_URL}/admin/upload/image`
    
    const token = localStorage.getItem('auth-token')
    const formData = new FormData()
    formData.append('image', imageFile)
    
    try {
      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`
        },
        body: formData
      })
      
      if (!response.ok) {
        throw new Error(`HTTP ${response.status}: ${response.statusText}`)
      }
      
      const result = await response.json()
      return result
    } catch (error) {
      console.error('Upload Image API Error:', error)
      throw error
    }
  },

  async uploadVideo(videoFile) {
    const url = `${API_BASE_URL}/api/v1/admin/upload/image` // Usa lo stesso endpoint
    
    const token = localStorage.getItem('auth-token')
    const formData = new FormData()
    formData.append('image', videoFile) // L'endpoint accetta qualsiasi file
    
    try {
      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`
        },
        body: formData
      })
      
      if (!response.ok) {
        throw new Error(`HTTP ${response.status}: ${response.statusText}`)
      }
      
      const result = await response.json()
      return result
    } catch (error) {
      console.error('Upload Video API Error:', error)
      throw error
    }
  },

  async uploadGallery(imageFiles) {
    // Per la galleria, carica un'immagine alla volta
    const results = []
    
    for (const file of imageFiles) {
      try {
        const result = await this.uploadImage(file)
        if (result.success) {
          results.push(result.data)
        }
      } catch (error) {
        console.error('Gallery upload error for file:', file.name, error)
      }
    }
    
    return {
      success: true,
      data: results
    }
  }
}

// Override del servizio di autenticazione per usare l'API diretta
export const authServiceOverride = {
  async login(credentials) {
    try {
      const response = await directAPI.login(credentials)
      
      if (response.success) {
        // Salva il token
        if (response.token) {
          this.setToken(response.token)
        }
        return {
          success: true,
          data: {
            user: response.user,
            token: response.token
          }
        }
      } else {
        return {
          success: false,
          message: response.message || 'Login fallito'
        }
      }
    } catch (error) {
      console.error('Login error:', error)
      return {
        success: false,
        message: 'Errore di connessione. Riprova più tardi.'
      }
    }
  },

  async getCurrentUser() {
    try {
      const response = await directAPI.getCurrentUser()
      
      if (response.success) {
        return response.user
      } else {
        throw new Error(response.message || 'Errore nel recupero utente')
      }
    } catch (error) {
      console.error('Get user error:', error)
      throw error
    }
  },

  async logout() {
    // Per ora solo rimuove il token localmente
    this.removeToken()
  },

  // Gestione token (copiata dal servizio originale)
  setToken(token) {
    const tokenData = {
      token: token,
      timestamp: Date.now(),
      expires: Date.now() + (24 * 60 * 60 * 1000) // 24 ore
    }
    localStorage.setItem('auth-token', token)
    localStorage.setItem('auth-token-data', JSON.stringify(tokenData))
  },

  removeToken() {
    localStorage.removeItem('auth-token')
    localStorage.removeItem('auth-token-data')
  },

  getToken() {
    return localStorage.getItem('auth-token')
  },

  isAuthenticated() {
    return !!localStorage.getItem('auth-token')
  }
}

// Override del servizio delle tecnologie
export const technologiesServiceOverride = {
  async getAll() {
    try {
      const response = await directAPI.getTechnologies()
      
      if (response.success) {
        return response.data
      } else {
        throw new Error(response.message || 'Errore nel caricamento tecnologie')
      }
    } catch (error) {
      console.error('Technologies error:', error)
      throw error
    }
  }
}

// Override del servizio progetti
export const projectsServiceOverride = {
  async getAll() {
    try {
      const response = await directAPI.getProjects()
      
      if (response.success) {
        return response.data
      } else {
        throw new Error(response.message || 'Errore nel caricamento progetti')
      }
    } catch (error) {
      console.error('Projects error:', error)
      throw error
    }
  },

  async getById(id) {
    try {
      const response = await directAPI.getProjectById(id)
      
      if (response.success) {
        return response.data
      } else {
        throw new Error(response.message || 'Errore nel caricamento progetto')
      }
    } catch (error) {
      console.error('Project by ID error:', error)
      throw error
    }
  },

  async create(projectData) {
    try {
      const response = await directAPI.createProject(projectData)
      
      if (response.success) {
        return response.data
      } else {
        throw new Error(response.message || 'Errore nella creazione progetto')
      }
    } catch (error) {
      console.error('Create project error:', error)
      throw error
    }
  },

  async update(id, projectData) {
    try {
      const response = await directAPI.updateProject(id, projectData)
      
      if (response.success) {
        return response.data
      } else {
        throw new Error(response.message || 'Errore nell\'aggiornamento progetto')
      }
    } catch (error) {
      console.error('Update project error:', error)
      throw error
    }
  }
}

// Override del servizio upload
export const uploadServiceOverride = {
  async uploadImage(imageFile) {
    try {
      const response = await directAPI.uploadImage(imageFile)
      
      if (response.success) {
        return response.data
      } else {
        throw new Error(response.message || 'Errore nell\'upload immagine')
      }
    } catch (error) {
      console.error('Upload image error:', error)
      throw error
    }
  },

  async uploadVideo(videoFile) {
    try {
      const response = await directAPI.uploadVideo(videoFile)
      
      if (response.success) {
        return response.data
      } else {
        throw new Error(response.message || 'Errore nell\'upload video')
      }
    } catch (error) {
      console.error('Upload video error:', error)
      throw error
    }
  },

  async uploadGallery(imageFiles) {
    try {
      const response = await directAPI.uploadGallery(imageFiles)
      
      if (response.success) {
        return response.data
      } else {
        throw new Error(response.message || 'Errore nell\'upload galleria')
      }
    } catch (error) {
      console.error('Upload gallery error:', error)
      throw error
    }
  }
}

export default directAPI 
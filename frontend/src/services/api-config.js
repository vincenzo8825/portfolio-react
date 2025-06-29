// Configurazione API per comunicazione DIRETTA con Laravel Backend (senza interceptor)
const API_BASE_URL = 'https://vincenzorocca.com/api/v1'

// API diretta senza simulazioni - Comunicazione reale con Laravel
const directAPI = {
  async callEndpoint(endpoint, method = 'GET', data = null) {
    try {
      const url = `${API_BASE_URL}/${endpoint}`
      
      const options = {
        method,
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        }
      }
      
      // Aggiungi token di autenticazione se disponibile
      const token = localStorage.getItem('auth-token')
      if (token) {
        options.headers['Authorization'] = `Bearer ${token}`
      }
      
      if (data) {
        options.body = JSON.stringify(data)
      }
      
      const response = await fetch(url, options)
      const result = await response.json()
      
      return result
    } catch (error) {
      throw error
    }
  },

  async login(credentials) {
    return this.callEndpoint('auth/login', 'POST', credentials)
  },

  async getProjects() {
    return this.callEndpoint('projects')
  },

  async getFeaturedProjects() {
    return this.callEndpoint('projects/featured')
  },

  async getTechnologies() {
    return this.callEndpoint('technologies')
  },

  async sendContact(contactData) {
    return this.callEndpoint('contacts', 'POST', contactData)
  },

  async getCurrentUser() {
    return this.callEndpoint('auth/me')
  },

  async createProject(projectData) {
    return this.callEndpoint('admin/projects', 'POST', projectData)
  },

  async updateProject(id, projectData) {
    return this.callEndpoint(`admin/projects/${id}`, 'PUT', projectData)
  },

  async getProjectById(id) {
    return this.callEndpoint(`projects/${id}`)
  },

  async uploadImage(imageFile) {
    const formData = new FormData()
    formData.append('image', imageFile)
    
    const token = localStorage.getItem('auth-token')
    const url = `${API_BASE_URL}/admin/upload/image`
    
    const response = await fetch(url, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      },
      body: formData
    })
    
    return response.json()
  },

  async uploadVideo(videoFile) {
    const formData = new FormData()
    formData.append('video', videoFile)
    
    const token = localStorage.getItem('auth-token')
    const url = `${API_BASE_URL}/admin/upload/video`
    
    const response = await fetch(url, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      },
      body: formData
    })
    
    return response.json()
  },

  async uploadGallery(imageFiles) {
    const formData = new FormData()
    imageFiles.forEach((file, index) => {
      formData.append(`images[${index}]`, file)
    })
    
    const token = localStorage.getItem('auth-token')
    const url = `${API_BASE_URL}/admin/upload/gallery`
    
    const response = await fetch(url, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      },
      body: formData
    })
    
    return response.json()
  }
}

// Servizio di autenticazione reale
export const authServiceOverride = {
  async login(credentials) {
    try {
      const response = await directAPI.login(credentials)
      
      // L'API risponde direttamente con {user: {...}, token: "..."} 
      if (response.user && response.token) {
        localStorage.setItem('auth-token', response.token)
        localStorage.setItem('current-user', JSON.stringify(response.user))
        return { success: true, data: response }
      } else {
        throw new Error('Login failed - Invalid response format')
      }
    } catch (error) {
      throw error
    }
  },

  async getCurrentUser() {
    try {
      const response = await directAPI.getCurrentUser()
      
      // L'API risponde direttamente con {id: 1, name: "...", is_admin: true}
      if (response.id && response.email) {
        localStorage.setItem('current-user', JSON.stringify(response))
        return response
      } else {
        throw new Error('User not found - Invalid response format')
      }
    } catch (error) {
      throw error
    }
  },

  async logout() {
    localStorage.removeItem('auth-token')
    localStorage.removeItem('current-user')
  },

  setToken(token) {
    if (token) {
      localStorage.setItem('auth-token', token)
    }
  },

  removeToken() {
    localStorage.removeItem('auth-token')
  },

  getToken() {
    return localStorage.getItem('auth-token')
  },

  isAuthenticated() {
    const token = this.getToken()
    return !!token
  }
}

// Servizio tecnologie reale
export const technologiesServiceOverride = {
  async getAll() {
    try {
      const response = await directAPI.getTechnologies()
      
      if (Array.isArray(response)) {
        return response
      }
      
      throw new Error('Failed to load technologies - Invalid response format')
    } catch (error) {
      throw error
    }
  }
}

// Servizio progetti reale
export const projectsServiceOverride = {
  async getAll() {
    try {
      const response = await directAPI.getProjects()
      
      if (Array.isArray(response)) {
        return response
      }
      
      throw new Error('Failed to load projects - Invalid response format')
    } catch (error) {
      throw error
    }
  },

  async getById(id) {
    try {
      const response = await directAPI.getProjectById(id)
      
      if (response.id) {
        return response
      }
      
      throw new Error('Failed to load project - Invalid response format')
    } catch (error) {
      throw error
    }
  },

  async create(projectData) {
    try {
      const response = await directAPI.createProject(projectData)
      
      if (response.id) {
        return response
      }
      
      throw new Error('Failed to create project - Invalid response format')
    } catch (error) {
      throw error
    }
  },

  async update(id, projectData) {
    try {
      const response = await directAPI.updateProject(id, projectData)
      
      if (response.success) {
        return response.data
      }
      
      throw new Error(response.message || 'Failed to update project')
    } catch (error) {
      throw error
    }
  },

  async delete(id) {
    try {
      const response = await directAPI.callEndpoint(`admin/projects/${id}`, 'DELETE')
      
      if (response.success) {
        return response
      }
      
      throw new Error(response.message || 'Failed to delete project')
    } catch (error) {
      throw error
    }
  },

  async toggleFeatured(id) {
    try {
      const response = await directAPI.callEndpoint(`admin/projects/${id}/toggle-featured`, 'PATCH')
      
      if (response.success && response.data) {
        return response.data
      }
      
      throw new Error('Failed to toggle featured status')
    } catch (error) {
      throw error
    }
  },

  async getFeatured() {
    try {
      const response = await directAPI.getFeaturedProjects()
      
      if (response.success && Array.isArray(response.data)) {
        return response.data
      }
      
      if (Array.isArray(response)) {
        return response
      }
      
      throw new Error('Failed to load featured projects - Invalid response format')
    } catch (error) {
      throw error
    }
  }
}

// Servizio upload reale
export const uploadServiceOverride = {
  async uploadImage(imageFile) {
    try {
      const response = await directAPI.uploadImage(imageFile)
      
      if (response.success && response.url) {
        return { url: response.url }
      }
      
      throw new Error('Failed to upload image - Invalid response format')
    } catch (error) {
      throw error
    }
  },

  async uploadVideo(videoFile) {
    try {
      const response = await directAPI.uploadVideo(videoFile)
      
      if (response.success && response.url) {
        return { url: response.url }
      }
      
      throw new Error('Failed to upload video - Invalid response format')
    } catch (error) {
      throw error
    }
  },

  async uploadGallery(imageFiles) {
    try {
      const response = await directAPI.uploadGallery(imageFiles)
      
      if (response.success && response.urls) {
        return { urls: response.urls }
      }
      
      throw new Error('Failed to upload gallery - Invalid response format')
    } catch (error) {
      throw error
    }
  }
}

// Servizio contatti reale
export const contactServiceOverride = {
  async sendMessage(contactData) {
    try {
      const response = await directAPI.sendContact(contactData)
      
      if (response.success) {
        return response
      }
      
      throw new Error('Failed to send message - Invalid response format')
    } catch (error) {
      throw error
    }
  }
}

export default directAPI 
import { apiService } from './api'

export const projectsService = {
  // Get all projects with optional filters
  async getProjects(params = {}) {
    const response = await apiService.get('/projects', { params })
    return response.data
  },

  // Get single project by ID
  async getProject(id) {
    const response = await apiService.get(`/projects/${id}`)
    return response.data
  },

  // Create new project (admin only)
  async createProject(projectData) {
    const response = await apiService.post('/projects', projectData)
    return response.data
  },

  // Update project (admin only)
  async updateProject(id, projectData) {
    const response = await apiService.put(`/projects/${id}`, projectData)
    return response.data
  },

  // Delete project (admin only)
  async deleteProject(id) {
    await apiService.delete(`/projects/${id}`)
  },

  // Upload image to Cloudinary (admin only)
  async uploadImage(imageFile) {
    const formData = new FormData()
    formData.append('image', imageFile)
    
    const response = await apiService.post('/upload', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    return response.data
  },

  // Get projects for admin with pagination
  async getAdminProjects(params = {}) {
    const response = await apiService.get('/admin/projects', { params })
    return response.data
  },

  // Toggle featured status
  async toggleFeatured(id) {
    const response = await apiService.patch(`/projects/${id}/toggle-featured`)
    return response.data
  }
} 
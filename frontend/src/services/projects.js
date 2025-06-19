import { apiService } from './api'
import { API_ENDPOINTS } from '../utils/constants'

export const projectsService = {
  // Get all projects with optional filters
  async getAll(params = {}) {
    const response = await apiService.get(API_ENDPOINTS.PROJECTS, { params })
    
    if (response.data.success) {
      return response.data.data
    } else {
      throw new Error(response.data.message || 'Failed to fetch projects')
    }
  },

  // Alias for getAll
  async getProjects(params = {}) {
    return this.getAll(params)
  },

  // Get featured projects
  async getFeatured() {
    const response = await apiService.get(API_ENDPOINTS.PROJECTS_FEATURED)
    
    if (response.data.success) {
      return response.data.data
    } else {
      throw new Error(response.data.message || 'Failed to fetch featured projects')
    }
  },

  // Alias for getFeatured
  async getFeaturedProjects() {
    return this.getFeatured()
  },

  // Get single project by ID or slug
  async getById(id) {
    console.log('ProjectsService: Fetching project with ID:', id)
    console.log('ProjectsService: API endpoint:', `${API_ENDPOINTS.PROJECTS}/${id}`)
    
    const response = await apiService.get(`${API_ENDPOINTS.PROJECTS}/${id}`)
    
    console.log('ProjectsService: Response received:', response)
    
    if (response.data.success) {
      console.log('ProjectsService: Success, returning data:', response.data.data)
      return response.data.data
    } else {
      console.log('ProjectsService: API returned success=false:', response.data)
      throw new Error(response.data.message || 'Failed to fetch project')
    }
  },

  // Alias for getById
  async getProject(id) {
    return this.getById(id)
  },

  // Create new project (admin only)
  async create(projectData) {
    const response = await apiService.post(API_ENDPOINTS.ADMIN.PROJECTS, projectData)
    
    if (response.data.success) {
      return response.data.data
    } else {
      throw new Error(response.data.message || 'Failed to create project')
    }
  },

  // Alias for create
  async createProject(projectData) {
    return this.create(projectData)
  },

  // Update project (admin only)
  async update(id, projectData) {
    const response = await apiService.put(`${API_ENDPOINTS.ADMIN.PROJECTS}/${id}`, projectData)
    
    if (response.data.success) {
      return response.data.data
    } else {
      throw new Error(response.data.message || 'Failed to update project')
    }
  },

  // Alias for update
  async updateProject(id, projectData) {
    return this.update(id, projectData)
  },

  // Delete project (admin only)
  async delete(id) {
    const response = await apiService.delete(`${API_ENDPOINTS.ADMIN.PROJECTS}/${id}`)
    
    if (response.data.success) {
      return response.data
    } else {
      throw new Error(response.data.message || 'Failed to delete project')
    }
  },

  // Alias for delete
  async deleteProject(id) {
    return this.delete(id)
  },

  // Upload image to Cloudinary (admin only)
  async uploadImage(imageFile) {
    const formData = new FormData()
    formData.append('image', imageFile)
    
    const response = await apiService.post('/admin/upload', formData, {
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

  // Get projects for admin with pagination
  async getAdminProjects(params = {}) {
    const response = await apiService.get(API_ENDPOINTS.ADMIN.PROJECTS, { params })
    
    if (response.data.success) {
      return response.data.data
    } else {
      throw new Error(response.data.message || 'Failed to fetch admin projects')
    }
  },

  // Toggle featured status
  async toggleFeatured(id) {
    const response = await apiService.patch(`${API_ENDPOINTS.ADMIN.PROJECTS_TOGGLE_FEATURED}/${id}/toggle-featured`)
    
    if (response.data.success) {
      return response.data.data
    } else {
      throw new Error(response.data.message || 'Failed to toggle featured status')
    }
  }
} 
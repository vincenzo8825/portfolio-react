import { apiService } from './api'
import { API_ENDPOINTS } from '../utils/constants'

export const technologiesService = {
  // Get all technologies with optional filters
  async getAll(params = {}) {
    const response = await apiService.get(API_ENDPOINTS.TECHNOLOGIES, { params })
    
    if (response.data.success) {
      return response.data.data
    } else {
      throw new Error(response.data.message || 'Failed to fetch technologies')
    }
  },

  // Alias for getAll
  async getTechnologies(params = {}) {
    return this.getAll(params)
  },

  // Get featured technologies
  async getFeatured() {
    const response = await apiService.get(API_ENDPOINTS.TECHNOLOGIES_FEATURED)
    
    if (response.data.success) {
      return response.data.data
    } else {
      throw new Error(response.data.message || 'Failed to fetch featured technologies')
    }
  },

  // Alias for getFeatured
  async getFeaturedTechnologies() {
    return this.getFeatured()
  },

  // Get technologies grouped by category
  async getByCategory() {
    const response = await apiService.get(API_ENDPOINTS.TECHNOLOGIES_BY_CATEGORY)
    
    if (response.data.success) {
      return response.data.data
    } else {
      throw new Error(response.data.message || 'Failed to fetch technologies by category')
    }
  },

  // Alias for getByCategory
  async getTechnologiesByCategory() {
    return this.getByCategory()
  },

  // Get single technology by ID or slug
  async getById(id) {
    const response = await apiService.get(`${API_ENDPOINTS.TECHNOLOGIES}/${id}`)
    
    if (response.data.success) {
      return response.data.data
    } else {
      throw new Error(response.data.message || 'Failed to fetch technology')
    }
  },

  // Alias for getById
  async getTechnology(id) {
    return this.getById(id)
  },

  // Create new technology (admin only)
  async create(technologyData) {
    const response = await apiService.post(API_ENDPOINTS.ADMIN.TECHNOLOGIES, technologyData)
    
    if (response.data.success) {
      return response.data.data
    } else {
      throw new Error(response.data.message || 'Failed to create technology')
    }
  },

  // Alias for create
  async createTechnology(technologyData) {
    return this.create(technologyData)
  },

  // Update technology (admin only)
  async update(id, technologyData) {
    const response = await apiService.put(`${API_ENDPOINTS.ADMIN.TECHNOLOGIES}/${id}`, technologyData)
    
    if (response.data.success) {
      return response.data.data
    } else {
      throw new Error(response.data.message || 'Failed to update technology')
    }
  },

  // Alias for update
  async updateTechnology(id, technologyData) {
    return this.update(id, technologyData)
  },

  // Delete technology (admin only)
  async delete(id) {
    const response = await apiService.delete(`${API_ENDPOINTS.ADMIN.TECHNOLOGIES}/${id}`)
    
    if (response.data.success) {
      return response.data
    } else {
      throw new Error(response.data.message || 'Failed to delete technology')
    }
  },

  // Alias for delete
  async deleteTechnology(id) {
    return this.delete(id)
  }
} 
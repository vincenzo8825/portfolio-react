import { apiService } from './api'
import { API_ENDPOINTS } from '../utils/constants'
import { contactServiceOverride } from './api-config'

export const contactService = {
  // Send contact message (public) - ora usa l'API diretta
  async sendMessage(contactData) {
    return await contactServiceOverride.sendMessage(contactData)
  },

  // Get all contact messages (admin only)
  async getContacts(params = {}) {
    const response = await apiService.get(API_ENDPOINTS.ADMIN.CONTACTS, { params })
    
    if (response.data.success) {
      return response.data.data
    } else {
      throw new Error(response.data.message || 'Failed to fetch contacts')
    }
  },

  // Alias for getContacts (for consistency)
  async getAll(params = {}) {
    return this.getContacts(params)
  },

  // Get contact statistics (admin only)
  async getContactStats() {
    const response = await apiService.get(API_ENDPOINTS.ADMIN.CONTACTS_STATS)
    
    if (response.data.success) {
      return response.data.data
    } else {
      throw new Error(response.data.message || 'Failed to fetch contact stats')
    }
  },

  // Get single contact message (admin only)
  async getContact(id) {
    const response = await apiService.get(`${API_ENDPOINTS.ADMIN.CONTACTS}/${id}`)
    
    if (response.data.success) {
      return response.data.data
    } else {
      throw new Error(response.data.message || 'Failed to fetch contact')
    }
  },

  // Update contact status (admin only)
  async updateContact(id, updateData) {
    const response = await apiService.put(`${API_ENDPOINTS.ADMIN.CONTACTS}/${id}`, updateData)
    
    if (response.data.success) {
      return response.data.data
    } else {
      throw new Error(response.data.message || 'Failed to update contact')
    }
  },

  // Delete contact message (admin only)
  async deleteContact(id) {
    const response = await apiService.delete(`${API_ENDPOINTS.ADMIN.CONTACTS}/${id}`)
    
    if (response.data.success) {
      return response.data
    } else {
      throw new Error(response.data.message || 'Failed to delete contact')
    }
  }
} 
import { apiService } from './api'

export const authService = {
  // Login user
  async login(credentials) {
    const response = await apiService.post('/auth/login', credentials)
    
    // Handle Laravel response format
    if (response.data.success) {
      // Store token from Laravel response
      if (response.data.data?.token) {
        this.setToken(response.data.data.token)
      }
      return response.data
    } else {
      throw new Error(response.data.message || 'Login failed')
    }
  },

  // Logout user
  async logout() {
    try {
      await apiService.post('/auth/logout')
    } catch (error) {
      // Don't throw error on logout, just log it
      console.error('Logout error:', error)
    } finally {
      // Always remove token on logout
      this.removeToken()
    }
  },

  // Get current authenticated user
  async getCurrentUser() {
    const response = await apiService.get('/auth/me')
    
    if (response.data.success) {
      return response.data.data
    } else {
      throw new Error(response.data.message || 'Failed to get user')
    }
  },

  // Check if user is authenticated
  isAuthenticated() {
    return !!localStorage.getItem('auth-token')
  },

  // Get auth token
  getToken() {
    return localStorage.getItem('auth-token')
  },

  // Set auth token
  setToken(token) {
    localStorage.setItem('auth-token', token)
  },

  // Remove auth token
  removeToken() {
    localStorage.removeItem('auth-token')
  },

  // Change password
  async changePassword(passwords) {
    const response = await apiService.post('/auth/change-password', passwords)
    
    if (response.data.success) {
      return response.data
    } else {
      throw new Error(response.data.message || 'Failed to change password')
    }
  }
} 
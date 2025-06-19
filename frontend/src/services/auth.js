import { apiService } from './api'

export const authService = {
  // Login user
  async login(credentials) {
    const response = await apiService.post('/login', credentials)
    return response.data
  },

  // Logout user
  async logout() {
    try {
      await apiService.post('/logout')
    } catch (error) {
      // Don't throw error on logout, just log it
      console.error('Logout error:', error)
    }
  },

  // Get current authenticated user
  async getCurrentUser() {
    const response = await apiService.get('/user')
    return response.data
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
  }
} 
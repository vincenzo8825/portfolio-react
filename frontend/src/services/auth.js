import { apiService } from './api'
import { authServiceOverride } from './api-config'

export const authService = {
  // Login user - ora usa l'API diretta
  async login(credentials) {
    // Usa l'override per l'API diretta
    return await authServiceOverride.login(credentials)
  },

  // Logout user - ora usa l'API diretta
  async logout() {
    await authServiceOverride.logout()
  },

  // Get current authenticated user - ora usa l'API diretta
  async getCurrentUser() {
    return await authServiceOverride.getCurrentUser()
  },

  // Check if user is authenticated
  isAuthenticated() {
    return authServiceOverride.isAuthenticated()
  },

  // Get auth token
  getToken() {
    return authServiceOverride.getToken()
  },

  // Set auth token with expiration
  setToken(token) {
    authServiceOverride.setToken(token)
  },

  // Remove auth token
  removeToken() {
    authServiceOverride.removeToken()
  },

  // Check if token is expired
  isTokenExpired() {
    const tokenData = localStorage.getItem('auth-token-data')
    if (!tokenData) return true
    
    try {
      const data = JSON.parse(tokenData)
      return Date.now() > data.expires
    } catch {
      return true
    }
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
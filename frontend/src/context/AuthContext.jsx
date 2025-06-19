import { createContext, useContext, useEffect, useState } from 'react'
import { authService } from '../services/auth'

const AuthContext = createContext()

export const useAuth = () => {
  const context = useContext(AuthContext)
  if (!context) {
    throw new Error('useAuth must be used within an AuthProvider')
  }
  return context
}

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null)
  const [token, setToken] = useState(localStorage.getItem('auth-token'))
  const [isLoading, setIsLoading] = useState(true)
  const [isAuthenticated, setIsAuthenticated] = useState(false)

  useEffect(() => {
    const initAuth = async () => {
      if (token) {
        try {
          const userData = await authService.getCurrentUser()
          setUser(userData)
          setIsAuthenticated(true)
        } catch (error) {
          // Token invalid or expired
          console.error('Auth init error:', error)
          localStorage.removeItem('auth-token')
          setToken(null)
          setUser(null)
          setIsAuthenticated(false)
        }
      }
      setIsLoading(false)
    }

    initAuth()
  }, [token])

  const login = async (credentials) => {
    try {
      setIsLoading(true)
      const response = await authService.login(credentials)
      
      // Laravel response structure: { success: true, data: { user: {}, token: '' } }
      if (response.success && response.data) {
        const { user: userData, token: authToken } = response.data
        
        setUser(userData)
        setToken(authToken)
        setIsAuthenticated(true)
        
        return { success: true }
      } else {
        return { 
          success: false, 
          message: response.message || 'Errore durante il login' 
        }
      }
    } catch (error) {
      console.error('Login error in context:', error)
      
      // Handle different error types
      let errorMessage = 'Errore durante il login'
      
      if (error.response?.status === 422) {
        // Validation error - credentials wrong
        errorMessage = 'Credenziali non valide. Verifica email e password.'
      } else if (error.response?.status === 403) {
        // Forbidden - not admin
        errorMessage = 'Accesso negato. Solo gli amministratori possono accedere.'
      } else if (error.response?.data?.message) {
        errorMessage = error.response.data.message
      } else if (error.message) {
        errorMessage = error.message
      }
      
      return { 
        success: false, 
        message: errorMessage
      }
    } finally {
      setIsLoading(false)
    }
  }

  const logout = async () => {
    try {
      await authService.logout()
    } catch (error) {
      // Ignore logout errors
      console.error('Logout error:', error)
    } finally {
      setUser(null)
      setToken(null)
      setIsAuthenticated(false)
      localStorage.removeItem('auth-token')
    }
  }

  const isAdmin = () => {
    return user?.is_admin === true
  }

  const value = {
    user,
    token,
    isLoading,
    isAuthenticated,
    login,
    logout,
    isAdmin
  }

  return (
    <AuthContext.Provider value={value}>
      {children}
    </AuthContext.Provider>
  )
} 
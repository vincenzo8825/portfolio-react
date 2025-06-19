import { useState, useCallback } from 'react'

export const useApi = () => {
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState(null)

  const executeRequest = useCallback(async (apiCall) => {
    setLoading(true)
    setError(null)
    
    try {
      const result = await apiCall()
      return { data: result, error: null }
    } catch (err) {
      const errorMessage = err.response?.data?.message || err.message || 'Si è verificato un errore'
      setError(errorMessage)
      return { data: null, error: errorMessage }
    } finally {
      setLoading(false)
    }
  }, [])

  const clearError = useCallback(() => {
    setError(null)
  }, [])

  return {
    loading,
    error,
    executeRequest,
    clearError
  }
} 
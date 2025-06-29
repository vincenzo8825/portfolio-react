import { useState, useEffect, useCallback } from 'react'
import { projectsService } from '../services/projects'
import { useApi } from './useApi'

export const useProjects = (initialParams = {}) => {
  const [projects, setProjects] = useState([])
  const [meta, setMeta] = useState(null)
  const [filters, setFilters] = useState({
    category: '',
    search: '',
    page: 1,
    per_page: 6,
    ...initialParams
  })
  
  const { loading, error, executeRequest } = useApi()

  const fetchProjects = useCallback(async () => {
    const { data } = await executeRequest(() => 
      projectsService.getAll(filters)
    )
    
    if (data) {
      setProjects(Array.isArray(data) ? data : data.data || [])
      setMeta(data.meta || null)
    }
  }, [executeRequest, filters])

  const updateFilters = useCallback((newFilters) => {
    setFilters(prev => ({
      ...prev,
      ...newFilters,
      page: newFilters.page || 1 // Reset page when filters change (except explicit page change)
    }))
  }, [])

  const resetFilters = useCallback(() => {
    setFilters({
      category: '',
      search: '',
      page: 1,
      per_page: 6
    })
  }, [])

  const nextPage = useCallback(() => {
    if (meta && meta.current_page < meta.last_page) {
      updateFilters({ page: meta.current_page + 1 })
    }
  }, [meta, updateFilters])

  const prevPage = useCallback(() => {
    if (meta && meta.current_page > 1) {
      updateFilters({ page: meta.current_page - 1 })
    }
  }, [meta, updateFilters])

  const goToPage = useCallback((page) => {
    updateFilters({ page })
  }, [updateFilters])

  // Load projects when filters change
  useEffect(() => {
    fetchProjects()
  }, [fetchProjects])

  return {
    projects,
    meta,
    filters,
    loading,
    error,
    updateFilters,
    resetFilters,
    nextPage,
    prevPage,
    goToPage,
    refetch: fetchProjects
  }
} 
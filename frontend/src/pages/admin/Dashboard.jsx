import { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import { projectsService } from '../../services/projects'
import { useLanguage } from '../../context/LanguageContext'

const Dashboard = () => {
  const { t } = useLanguage()
  const [stats, setStats] = useState({
    totalProjects: 0,
    featuredProjects: 0,
    completedProjects: 0
  })
  const [recentProjects, setRecentProjects] = useState([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    loadDashboardData()
    
    // Aggiorna i dati ogni 30 secondi
    const interval = setInterval(() => {
      loadDashboardData()
    }, 30000)
    
    return () => {
      clearInterval(interval)
    }
  }, [])

  const loadDashboardData = async () => {
    try {
      setLoading(true)
      
      // Carica progetti
      const projects = await projectsService.getAll()
      const featured = projects.filter(p => p.is_featured || p.featured)
      const completed = projects.filter(p => p.status === 'completed' || !p.status)
      
      const stats = {
        totalProjects: projects.length,
        featuredProjects: featured.length,
        completedProjects: completed.length
      }
      
      console.log('Dashboard stats updated:', {
        totalProjects: projects.length
      })
      
      // Progetti recenti (ultimi 5)
      const sortedProjects = projects
        .sort((a, b) => new Date(b.created_at || b.project_date) - new Date(a.created_at || a.project_date))
        .slice(0, 5)

      setStats(stats)
      
      setRecentProjects(sortedProjects)
      
    } catch (error) {
      console.error('Error loading dashboard data:', error)
    } finally {
      setLoading(false)
    }
  }
  
  return (
    <div className="min-h-screen py-8">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Header */}
        <div className="mb-8 flex justify-between items-center">
          <div>
            <h1 className="text-3xl font-bold text-gray-900 dark:text-white">
              {t('dashboard')}
            </h1>
            <p className="text-gray-600 dark:text-gray-400 mt-2">
              {t('welcome')} nel pannello di controllo del portfolio
            </p>
          </div>
          <button
            onClick={() => window.location.reload()}
            className="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors duration-200 flex items-center"
          >
            <i className="fas fa-sync-alt mr-2"></i>
            Aggiorna
          </button>
        </div>

        {/* Stats Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
          <div className="bg-white dark:bg-dark-800 rounded-lg p-6 shadow-lg border border-gray-200 dark:border-dark-600">
            <div className="flex items-center">
              <div className="p-3 rounded-full bg-blue-100 dark:bg-blue-900/30">
                <i className="fas fa-folder text-blue-600 dark:text-blue-400 text-xl"></i>
              </div>
              <div className="ml-4">
                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Totale Progetti</p>
                <p className="text-2xl font-semibold text-gray-900 dark:text-white">
                  {loading ? '-' : stats.totalProjects}
                </p>
              </div>
            </div>
          </div>

          <div className="bg-white dark:bg-dark-800 rounded-lg p-6 shadow-lg border border-gray-200 dark:border-dark-600">
            <div className="flex items-center">
              <div className="p-3 rounded-full bg-green-100 dark:bg-green-900/30">
                <i className="fas fa-star text-green-600 dark:text-green-400 text-xl"></i>
              </div>
              <div className="ml-4">
                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Progetti in Evidenza</p>
                <p className="text-2xl font-semibold text-gray-900 dark:text-white">
                  {loading ? '-' : stats.featuredProjects}
                </p>
              </div>
            </div>
          </div>

          <div className="bg-white dark:bg-dark-800 rounded-lg p-6 shadow-lg border border-gray-200 dark:border-dark-600">
            <div className="flex items-center">
              <div className="p-3 rounded-full bg-purple-100 dark:bg-purple-900/30">
                <i className="fas fa-check-circle text-purple-600 dark:text-purple-400 text-xl"></i>
              </div>
              <div className="ml-4">
                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Progetti Completati</p>
                <p className="text-2xl font-semibold text-gray-900 dark:text-white">
                  {loading ? '-' : stats.completedProjects}
                </p>
              </div>
            </div>
          </div>


        </div>

        {/* Quick Actions */}
        <div className="bg-white dark:bg-dark-800 rounded-lg p-6 shadow-lg border border-gray-200 dark:border-dark-600 mb-8">
          <h2 className="text-xl font-semibold text-gray-900 dark:text-white mb-4">
            {t('quickActions')}
          </h2>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <Link
              to="/admin/projects/create"
              className="flex items-center p-4 bg-primary-50 dark:bg-primary-900/20 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/30 transition-colors"
            >
              <i className="fas fa-plus text-primary-600 dark:text-primary-400 text-xl mr-3"></i>
              <div>
                <p className="font-medium text-primary-700 dark:text-primary-300">{t('newProject')}</p>
                <p className="text-sm text-primary-600 dark:text-primary-400">{t('addNewProject')}</p>
              </div>
            </Link>

            <Link
              to="/admin/projects"
              className="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors"
            >
              <i className="fas fa-list text-green-600 dark:text-green-400 text-xl mr-3"></i>
              <div>
                <p className="font-medium text-green-700 dark:text-green-300">{t('projectManagement')}</p>
                <p className="text-sm text-green-600 dark:text-green-400">{t('manageProjects')}</p>
              </div>
            </Link>

            <Link
              to="/admin/change-password"
              className="flex items-center p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-900/30 transition-colors"
            >
              <i className="fas fa-key text-orange-600 dark:text-orange-400 text-xl mr-3"></i>
              <div>
                <p className="font-medium text-orange-700 dark:text-orange-300">Cambia Password</p>
                <p className="text-sm text-orange-600 dark:text-orange-400">Aggiorna credenziali</p>
              </div>
            </Link>

            <Link
              to="/"
              className="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors"
            >
              <i className="fas fa-external-link-alt text-blue-600 dark:text-blue-400 text-xl mr-3"></i>
              <div>
                <p className="font-medium text-blue-700 dark:text-blue-300">{t('viewSite')}</p>
                <p className="text-sm text-blue-600 dark:text-blue-400">Visualizza sito pubblico</p>
              </div>
            </Link>
          </div>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
          {/* Recent Projects */}
          <div className="bg-white dark:bg-dark-800 rounded-lg p-6 shadow-lg border border-gray-200 dark:border-dark-600">
            <div className="flex items-center justify-between mb-6">
              <h2 className="text-xl font-semibold text-gray-900 dark:text-white">
                Progetti Recenti
              </h2>
              <Link 
                to="/admin/projects"
                className="text-primary-600 dark:text-primary-400 hover:text-primary-500 dark:hover:text-primary-300 text-sm font-medium"
              >
                Vedi tutti
              </Link>
            </div>

            {loading ? (
              <div className="flex items-center justify-center py-8">
                <div className="spinner mr-3"></div>
                <span className="text-gray-600 dark:text-gray-400">Caricamento...</span>
              </div>
            ) : recentProjects.length > 0 ? (
              <div className="space-y-4">
                {recentProjects.map((project) => (
                  <div key={project.id} className="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <div className="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mr-3">
                      {project.image_url ? (
                        <img src={project.image_url} alt={project.title} className="w-full h-full object-cover rounded-lg" />
                      ) : (
                        <i className="fas fa-folder text-gray-500 dark:text-gray-400"></i>
                      )}
                    </div>
                    <div className="flex-1 min-w-0">
                      <p className="text-sm font-medium text-gray-900 dark:text-white truncate">
                        {project.title}
                      </p>
                      <p className="text-xs text-gray-500 dark:text-gray-400">
                        {project.status === 'completed' ? 'Completato' : 
                         project.status === 'in-progress' ? 'In Corso' : 'In Pausa'}
                      </p>
                    </div>
                    <div className="flex items-center space-x-2">
                      {project.featured && (
                        <i className="fas fa-star text-yellow-500" title="In evidenza"></i>
                      )}
                      <Link
                        to={`/admin/projects/${project.id}/edit`}
                        className="text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300"
                      >
                        <i className="fas fa-edit text-sm"></i>
                      </Link>
                    </div>
                  </div>
                ))}
              </div>
            ) : (
              <div className="text-center py-8">
                <i className="fas fa-folder-open text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
                <p className="text-gray-500 dark:text-gray-400 mb-4">
                  Nessun progetto trovato
                </p>
                <Link
                  to="/admin/projects/create"
                  className="btn-primary text-sm"
                >
                  <i className="fas fa-plus mr-2"></i>
                  Crea primo progetto
                </Link>
              </div>
            )}
          </div>

          {/* System Status */}
          <div className="bg-white dark:bg-dark-800 rounded-lg p-6 shadow-lg border border-gray-200 dark:border-dark-600">
            <h2 className="text-xl font-semibold text-gray-900 dark:text-white mb-6">
              Stato Sistema
            </h2>
            
            <div className="space-y-4">
              <div className="flex items-center justify-between">
                <div className="flex items-center">
                  <div className="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                  <span className="text-sm text-gray-900 dark:text-white">Database</span>
                </div>
                <span className="text-sm text-green-600 dark:text-green-400 font-medium">Online</span>
              </div>
              
              <div className="flex items-center justify-between">
                <div className="flex items-center">
                  <div className="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                  <span className="text-sm text-gray-900 dark:text-white">API Backend</span>
                </div>
                <span className="text-sm text-green-600 dark:text-green-400 font-medium">Online</span>
              </div>
              
              <div className="flex items-center justify-between">
                <div className="flex items-center">
                  <div className="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                  <span className="text-sm text-gray-900 dark:text-white">Versione</span>
                </div>
                <span className="text-sm text-blue-600 dark:text-blue-400 font-medium">v1.0.0</span>
              </div>
            </div>

            <div className="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
              <button 
                onClick={loadDashboardData}
                className="w-full btn-secondary text-sm"
              >
                <i className="fas fa-sync-alt mr-2"></i>
                Aggiorna Dati
              </button>
            </div>
          </div>
        </div>

      </div>
    </div>
  )
}

export default Dashboard 
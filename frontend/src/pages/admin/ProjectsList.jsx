import { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import { projectsService } from '../../services/projects'
import { useNotification } from '../../context/NotificationContext'

const ProjectsList = () => {
  const [projects, setProjects] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState(null)
  const [deleteLoading, setDeleteLoading] = useState({})
  const [message, setMessage] = useState(null)

  const { showSuccess, showError } = useNotification()

  // Carica i progetti
  useEffect(() => {
    loadProjects()
  }, [])

  const loadProjects = async () => {
    try {
      setLoading(true)
      const data = await projectsService.getAll()
      setProjects(data)
    } catch (err) {
      setError('Errore nel caricamento dei progetti')
    } finally {
      setLoading(false)
    }
  }

  // Elimina progetto
  const handleDelete = async (id, title) => {
    if (!window.confirm(`Sei sicuro di voler eliminare "${title}"?`)) {
      return
    }

    setDeleteLoading(prev => ({ ...prev, [id]: true }))
    
    try {
      await projectsService.delete(id)
      await loadProjects()
      setMessage('Progetto eliminato con successo')
    } catch (err) {
      setError('Errore nell\'eliminazione del progetto')
    } finally {
      setDeleteLoading(prev => ({ ...prev, [id]: false }))
    }
  }

  // Toggle featured
  const handleToggleFeatured = async (id) => {
    setDeleteLoading(prev => ({ ...prev, [id]: true }))
    
    try {
      await projectsService.toggleFeatured(id)
      await loadProjects()
      setMessage('Stato "in evidenza" aggiornato con successo')
    } catch (err) {
      if (err.message && err.message.includes('Massimo 3 progetti')) {
        setError('Massimo 3 progetti possono essere in evidenza. Rimuovi prima un altro progetto.')
      } else {
        setError('Errore nell\'aggiornamento dello stato "in evidenza"')
      }
    } finally {
      setDeleteLoading(prev => ({ ...prev, [id]: false }))
    }
  }

  if (loading) {
    return (
      <div className="min-h-screen py-8">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex items-center justify-center py-12">
            <div className="spinner mr-3"></div>
            <span className="text-gray-600 dark:text-gray-400">Caricamento progetti...</span>
          </div>
        </div>
      </div>
    )
  }

  return (
    <div className="min-h-screen py-8">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Header */}
        <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
          <div>
            <h1 className="text-3xl font-bold text-gray-900 dark:text-white">
              Gestione Progetti
            </h1>
            <p className="text-gray-600 dark:text-gray-400 mt-2">
              Visualizza e gestisci tutti i progetti del portfolio
            </p>
          </div>
          <div className="mt-4 sm:mt-0">
            <Link
              to="/admin/projects/create"
              className="btn-primary"
            >
              <i className="fas fa-plus mr-2"></i>
              Nuovo Progetto
            </Link>
          </div>
        </div>

        {/* Error State */}
        {error && (
          <div className="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6">
            <div className="flex items-center">
              <i className="fas fa-exclamation-triangle text-red-600 dark:text-red-400 mr-3"></i>
              <div>
                <p className="text-red-800 dark:text-red-300 font-medium">{error}</p>
                <button 
                  onClick={loadProjects}
                  className="text-red-600 dark:text-red-400 text-sm hover:underline mt-1"
                >
                  Riprova
                </button>
              </div>
            </div>
          </div>
        )}

        {/* Projects List */}
        {projects.length === 0 ? (
          /* Empty State */
          <div className="bg-white dark:bg-dark-800 rounded-lg shadow-lg border border-gray-200 dark:border-dark-600">
            <div className="text-center py-12">
              <div className="w-24 h-24 mx-auto mb-8 bg-gray-100 dark:bg-dark-700 rounded-full flex items-center justify-center">
                <i className="fas fa-folder-open text-3xl text-gray-400"></i>
              </div>
              <h3 className="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                Nessun progetto trovato
              </h3>
              <p className="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                Non hai ancora creato nessun progetto. Inizia creando il tuo primo progetto.
              </p>
              <Link
                to="/admin/projects/create"
                className="btn-primary"
              >
                <i className="fas fa-plus mr-2"></i>
                Crea il tuo primo progetto
              </Link>
            </div>
          </div>
        ) : (
          /* Projects Grid */
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {projects.map((project) => (
              <div 
                key={project.id}
                className="bg-white dark:bg-dark-800 rounded-lg shadow-lg border border-gray-200 dark:border-dark-600 overflow-hidden"
              >
                {/* Project Image */}
                <div className="aspect-video bg-gray-100 dark:bg-dark-700 relative">
                  {project.image_url ? (
                    <img 
                      src={project.image_url} 
                      alt={project.title}
                      className="w-full h-full object-cover"
                    />
                  ) : (
                    <div className="w-full h-full flex items-center justify-center">
                      <i className="fas fa-image text-4xl text-gray-400"></i>
                    </div>
                  )}
                  
                  {/* Featured Badge */}
                  {project.featured && (
                    <div className="absolute top-3 right-3 bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                      <i className="fas fa-star mr-1"></i>
                      In Evidenza
                    </div>
                  )}
                </div>

                {/* Project Content */}
                <div className="p-6">
                  <div className="flex items-start justify-between mb-4">
                    <h3 className="text-lg font-semibold text-gray-900 dark:text-white line-clamp-2">
                      {project.title}
                    </h3>
                    <span className={`px-2 py-1 rounded-full text-xs font-medium ${
                      project.status === 'completed' 
                        ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
                        : project.status === 'in-progress'
                        ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400'
                        : 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400'
                    }`}>
                      {project.status === 'completed' ? 'Completato' : 
                       project.status === 'in-progress' ? 'In Corso' : 'In Pausa'}
                    </span>
                  </div>

                  <p className="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-3">
                    {project.description}
                  </p>

                  {/* Technologies */}
                  {project.technologies && project.technologies.length > 0 && (
                    <div className="flex flex-wrap gap-2 mb-4">
                      {(project.technologies || []).slice(0, 3).map((tech, index) => (
                        <span 
                          key={index}
                          className="px-2 py-1 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 rounded text-xs font-medium"
                        >
                          {tech}
                        </span>
                      ))}
                      {project.technologies.length > 3 && (
                        <span className="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded text-xs">
                          +{project.technologies.length - 3}
                        </span>
                      )}
                    </div>
                  )}

                  {/* Actions */}
                  <div className="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-dark-600">
                    <div className="flex items-center space-x-2">
                      {/* Featured Toggle */}
                      <button
                        onClick={() => handleToggleFeatured(project.id)}
                        className={`p-2 rounded-lg transition-colors ${
                          project.featured 
                            ? 'bg-yellow-100 text-yellow-600 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-400'
                            : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-400'
                        }`}
                        title={project.featured ? 'Rimuovi da evidenza' : 'Metti in evidenza'}
                      >
                        <i className="fas fa-star text-sm"></i>
                      </button>

                      {/* Edit */}
                      <Link
                        to={`/admin/projects/${project.id}/edit`}
                        className="p-2 bg-blue-100 text-blue-600 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-400 rounded-lg transition-colors"
                        title="Modifica progetto"
                      >
                        <i className="fas fa-edit text-sm"></i>
                      </Link>

                      {/* Delete */}
                      <button
                        onClick={() => handleDelete(project.id, project.title)}
                        disabled={deleteLoading[project.id]}
                        className="p-2 bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400 rounded-lg transition-colors disabled:opacity-50"
                        title="Elimina progetto"
                      >
                        {deleteLoading[project.id] ? (
                          <div className="w-4 h-4 border-2 border-red-600 border-t-transparent rounded-full animate-spin"></div>
                        ) : (
                          <i className="fas fa-trash text-sm"></i>
                        )}
                      </button>
                    </div>

                    {/* View Project */}
                    <Link
                      to={`/projects/${project.slug || project.id}`}
                      className="text-primary-600 dark:text-primary-400 hover:text-primary-500 dark:hover:text-primary-300 text-sm font-medium"
                      target="_blank"
                    >
                      Visualizza <i className="fas fa-external-link-alt ml-1"></i>
                    </Link>
                  </div>
                </div>
              </div>
            ))}
          </div>
        )}

      </div>
    </div>
  )
}

export default ProjectsList 
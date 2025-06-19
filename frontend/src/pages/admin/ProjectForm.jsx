import { useState, useEffect } from 'react'
import { useParams, useNavigate, Link } from 'react-router-dom'
import { useAuth } from '../../context/AuthContext'

const ProjectForm = () => {
  const { id } = useParams() // Per edit mode
  const navigate = useNavigate()
  const { user, isAuthenticated } = useAuth()
  
  const isEditMode = Boolean(id)
  const isAdmin = isAuthenticated && user?.role === 'admin'

  // Redirect se non admin
  useEffect(() => {
    if (!isAdmin) {
      navigate('/login')
    }
  }, [isAdmin, navigate])

  const [submitStatus, setSubmitStatus] = useState(null)

  // Available options
  const categories = [
    { value: 'fullstack', label: 'Full Stack', icon: 'fas fa-layers', color: 'from-blue-500 to-purple-500' },
    { value: 'frontend', label: 'Frontend', icon: 'fas fa-laptop-code', color: 'from-green-500 to-teal-500' },
    { value: 'backend', label: 'Backend', icon: 'fas fa-server', color: 'from-red-500 to-pink-500' },
    { value: 'mobile', label: 'Mobile', icon: 'fas fa-mobile-alt', color: 'from-purple-500 to-indigo-500' }
  ]

  const statuses = [
    { value: 'in-progress', label: 'In Corso', icon: 'fas fa-clock', color: 'text-yellow-600' },
    { value: 'completed', label: 'Completato', icon: 'fas fa-check-circle', color: 'text-green-600' },
    { value: 'paused', label: 'In Pausa', icon: 'fas fa-pause-circle', color: 'text-orange-600' }
  ]



  const getTechColor = (tech) => {
    const colors = {
      'React': 'from-blue-400 to-blue-600',
      'Laravel': 'from-red-400 to-red-600',
      'Vue.js': 'from-green-400 to-green-600',
      'Node.js': 'from-green-500 to-green-700',
      'MySQL': 'from-orange-400 to-orange-600',
      'PostgreSQL': 'from-blue-500 to-indigo-600',
      'MongoDB': 'from-green-600 to-green-800',
      'Tailwind CSS': 'from-teal-400 to-teal-600',
      'Firebase': 'from-yellow-400 to-orange-500',
      'Stripe': 'from-purple-400 to-purple-600'
    }
    return colors[tech] || 'from-gray-400 to-gray-600'
  }

  if (!isAdmin) {
    return null // Will redirect
  }

  return (
    <div className="min-h-screen pt-20 bg-gradient-to-br from-gray-50 via-white to-gray-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
      
      {/* Header Section */}
      <section className="py-12 sm:py-16">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          
          {/* Breadcrumb */}
          <nav className="flex items-center space-x-2 text-sm mb-6 sm:mb-8">
            <Link to="/admin" className="text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors duration-300">
              Admin
            </Link>
            <i className="fas fa-chevron-right text-gray-400 text-xs"></i>
            <Link to="/admin/projects" className="text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors duration-300">
              Progetti
            </Link>
            <i className="fas fa-chevron-right text-gray-400 text-xs"></i>
            <span className="text-gray-900 dark:text-white font-medium">
              {isEditMode ? 'Modifica Progetto' : 'Nuovo Progetto'}
            </span>
          </nav>

          {/* Header */}
          <div className="text-center mb-8 sm:mb-12">
            <div className="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 rounded-full bg-gradient-to-r from-primary-500/10 to-accent-500/10 border border-primary-200/30 dark:border-primary-700/30 text-primary-700 dark:text-primary-300 text-sm font-medium mb-6">
              <i className={`fas ${isEditMode ? 'fa-edit' : 'fa-plus'} mr-2 sm:mr-3 text-base`}></i>
              {isEditMode ? 'Modifica Progetto' : 'Nuovo Progetto'}
            </div>
            
            <h1 className="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4">
              <span className="text-gray-900 dark:text-white">
                {isEditMode ? 'Modifica ' : 'Crea '}
              </span>
              <span className="bg-gradient-to-r from-primary-600 via-accent-500 to-pink-500 bg-clip-text text-transparent">
                Progetto
              </span>
            </h1>
            
            <p className="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
              {isEditMode 
                ? 'Modifica i dettagli del progetto e aggiorna le informazioni'
                : 'Aggiungi un nuovo progetto al tuo portfolio con tutti i dettagli necessari'
              }
            </p>
          </div>

        </div>
      </section>

      {/* Form Section - Preview Mode */}
      <section className="pb-20">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          
          {/* Status Messages */}
          {submitStatus === 'success' && (
            <div className="mb-8 p-6 bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-3xl text-green-800 dark:text-green-400">
              <div className="flex items-center">
                <i className="fas fa-check-circle mr-3 text-2xl"></i>
                <div>
                  <p className="font-medium text-lg">Form Ultra-Moderno Pronto!</p>
                  <p className="text-sm opacity-80">Il form completo sarà integrato con Laravel API</p>
                </div>
              </div>
            </div>
          )}

          {/* Preview Form Cards */}
          <div className="space-y-8">
            
            {/* Basic Information Preview */}
            <div className="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-3xl p-6 sm:p-8 shadow-xl shadow-black/5 dark:shadow-black/20 border border-white/20 dark:border-slate-700/50">
              <h2 className="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                <div className="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center text-white mr-3">
                  <i className="fas fa-info-circle text-sm sm:text-base"></i>
                </div>
                Informazioni Base
              </h2>

              <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div className="lg:col-span-2">
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Titolo del Progetto *
                  </label>
                  <div className="w-full px-4 py-3 sm:py-4 rounded-2xl border border-gray-200 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm text-lg font-medium text-gray-500 dark:text-gray-400">
                    Es. E-Commerce Platform Innovativo
                  </div>
                </div>

                <div className="lg:col-span-2">
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Descrizione *
                  </label>
                  <div className="w-full px-4 py-3 sm:py-4 rounded-2xl border border-gray-200 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm h-24 text-gray-500 dark:text-gray-400">
                    Descrivi dettagliatamente il progetto...
                  </div>
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Categoria
                  </label>
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {categories.map((category) => (
                      <div
                        key={category.value}
                        className="p-3 sm:p-4 rounded-2xl border-2 border-gray-200 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm opacity-60"
                      >
                        <div className="flex items-center">
                          <div className={`w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-r ${category.color} flex items-center justify-center text-white mr-3`}>
                            <i className={`${category.icon} text-sm sm:text-base`}></i>
                          </div>
                          <span className="font-medium text-gray-600 dark:text-gray-400 text-sm sm:text-base">
                            {category.label}
                          </span>
                        </div>
                      </div>
                    ))}
                  </div>
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Stato del Progetto
                  </label>
                  <div className="space-y-3">
                    {statuses.map((status) => (
                      <div
                        key={status.value}
                        className="w-full p-3 sm:p-4 rounded-2xl border-2 border-gray-200 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm opacity-60"
                      >
                        <div className="flex items-center">
                          <i className={`${status.icon} ${status.color} mr-3 text-base`}></i>
                          <span className="font-medium text-gray-600 dark:text-gray-400">
                            {status.label}
                          </span>
                        </div>
                      </div>
                    ))}
                  </div>
                </div>

                {/* Featured Project Toggle */}
                <div className="lg:col-span-2">
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                    Progetto in Evidenza
                  </label>
                  <div className="p-4 sm:p-6 rounded-2xl border-2 border-dashed border-yellow-300 dark:border-yellow-600 bg-gradient-to-r from-yellow-50 to-amber-50 dark:from-yellow-900/20 dark:to-amber-900/20">
                    <div className="flex items-center justify-between">
                      <div className="flex items-center">
                        <div className="w-10 h-10 rounded-xl bg-gradient-to-r from-yellow-500 to-amber-500 flex items-center justify-center text-white mr-4">
                          <i className="fas fa-star"></i>
                        </div>
                        <div>
                          <h4 className="font-semibold text-gray-900 dark:text-white">
                            Mostra in Homepage
                          </h4>
                          <p className="text-sm text-gray-600 dark:text-gray-400">
                            Verrà mostrato tra i 3 progetti in evidenza sulla homepage
                          </p>
                        </div>
                      </div>
                      <div className="relative inline-block w-12 h-6 bg-gray-200 dark:bg-slate-700 rounded-full opacity-60">
                        <div className="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow transition-transform duration-300"></div>
                      </div>
                    </div>
                    <div className="mt-3 text-xs text-yellow-700 dark:text-yellow-400 bg-yellow-100 dark:bg-yellow-900/30 px-3 py-2 rounded-lg">
                      <i className="fas fa-info-circle mr-2"></i>
                      Massimo 3 progetti possono essere in evidenza contemporaneamente
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {/* Technologies Preview */}
            <div className="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-3xl p-6 sm:p-8 shadow-xl shadow-black/5 dark:shadow-black/20 border border-white/20 dark:border-slate-700/50">
              <h2 className="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                <div className="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-r from-orange-500 to-red-500 flex items-center justify-center text-white mr-3">
                  <i className="fas fa-code text-sm sm:text-base"></i>
                </div>
                Tecnologie & Caratteristiche
              </h2>

              <div className="space-y-6">
                <div className="flex gap-3">
                  <div className="flex-1 px-4 py-3 rounded-2xl border border-gray-200 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm text-gray-500 dark:text-gray-400">
                    Aggiungi tecnologie...
                  </div>
                  <div className="px-6 py-3 bg-gradient-to-r from-primary-500 to-accent-500 text-white rounded-2xl font-medium shadow-lg opacity-60">
                    <i className="fas fa-plus mr-2"></i>
                    Aggiungi
                  </div>
                </div>

                <div className="flex flex-wrap gap-3">
                  {['React', 'Laravel', 'MySQL'].map((tech, index) => (
                    <div
                      key={index}
                      className={`px-4 py-2 rounded-2xl bg-gradient-to-r ${getTechColor(tech)} text-white font-medium shadow-lg flex items-center opacity-80`}
                    >
                      <span className="mr-2">{tech}</span>
                      <div className="w-5 h-5 bg-white/20 rounded-full flex items-center justify-center">
                        <i className="fas fa-times text-xs"></i>
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            </div>

            {/* Action Buttons */}
            <div className="flex flex-col sm:flex-row gap-4 pt-8">
              <Link
                to="/admin/projects"
                className="flex-1 sm:flex-none px-6 sm:px-8 py-3 sm:py-4 bg-gray-100 dark:bg-slate-700 text-gray-900 dark:text-white rounded-2xl hover:bg-gray-200 dark:hover:bg-slate-600 transition-all duration-300 font-medium text-center shadow-lg hover:scale-105"
              >
                <i className="fas fa-arrow-left mr-2"></i>
                Torna alla Lista
              </Link>
              
              <button
                onClick={() => setSubmitStatus('success')}
                className="flex-1 px-6 sm:px-8 py-3 sm:py-4 bg-gradient-to-r from-primary-500 to-accent-500 text-white rounded-2xl font-medium shadow-lg hover:from-primary-600 hover:to-accent-600 hover:scale-105 transition-all duration-300"
              >
                <i className="fas fa-rocket mr-2"></i>
                Preview Form Completo
              </button>
            </div>

            {/* Feature Preview */}
            <div className="bg-gradient-to-r from-primary-600 via-accent-500 to-pink-500 rounded-3xl p-6 sm:p-8 text-white">
              <h3 className="text-xl sm:text-2xl font-bold mb-4">🚀 Form Ultra-Moderno in Arrivo</h3>
              <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                <div className="flex items-center">
                  <i className="fas fa-check-circle mr-2"></i>
                  Upload immagini drag & drop
                </div>
                <div className="flex items-center">
                  <i className="fas fa-check-circle mr-2"></i>
                  Validazione in tempo reale
                </div>
                <div className="flex items-center">
                  <i className="fas fa-check-circle mr-2"></i>
                  Design ultra-responsive
                </div>
                <div className="flex items-center">
                  <i className="fas fa-check-circle mr-2"></i>
                  Animazioni fluide
                </div>
                <div className="flex items-center">
                  <i className="fas fa-check-circle mr-2"></i>
                  Integrazione Laravel API
                </div>
                <div className="flex items-center">
                  <i className="fas fa-check-circle mr-2"></i>
                  Gestione errori avanzata
                </div>
              </div>
            </div>

          </div>

        </div>
      </section>

    </div>
  )
}

export default ProjectForm 
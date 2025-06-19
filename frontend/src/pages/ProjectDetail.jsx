import { useState, useEffect } from 'react'
import { useParams, Link } from 'react-router-dom'
import { projectsService } from '../services/projects'
import { useAuth } from '../context/AuthContext'
import { useLanguage } from '../context/LanguageContext'
import { useNotification } from '../context/NotificationContext'

const ProjectDetail = () => {
  const { id } = useParams()
  const { user } = useAuth()
  const { language } = useLanguage()
  const { showError } = useNotification()
  const [project, setProject] = useState(null)
  const [loading, setLoading] = useState(true)
  const [currentImageIndex, setCurrentImageIndex] = useState(0)
  const [showVideo, setShowVideo] = useState(false)

  const translations = {
    it: {
      loading: "Caricamento...",
      projectNotFound: "Progetto non trovato",
      backToProjects: "Torna ai Progetti",
      details: "Dettagli del Progetto",
      client: "Cliente",
      duration: "Durata", 
      year: "Anno",
      status: "Stato",
      category: "Categoria",
      technologies: "Tecnologie Utilizzate",
      features: "Caratteristiche Principali",
      challenges: "Sfide e Soluzioni",
      projectGallery: "Galleria del Progetto",
      watchVideo: "Guarda il Video",
      viewDemo: "Vedi Demo",
      viewCode: "Vedi Codice",
      editProject: "Modifica Progetto",
      completed: "Completato",
      inProgress: "In Corso",
      onHold: "In Pausa",
      description: "Descrizione",
      projectDate: "Data Progetto",
      noImage: "Nessuna immagine disponibile",
      errorLoading: "Errore nel caricamento del progetto"
    },
    en: {
      loading: "Loading...",
      projectNotFound: "Project not found",
      backToProjects: "Back to Projects",
      details: "Project Details",
      client: "Client",
      duration: "Duration",
      year: "Year", 
      status: "Status",
      category: "Category",
      technologies: "Technologies Used",
      features: "Key Features",
      challenges: "Challenges & Solutions",
      projectGallery: "Project Gallery",
      watchVideo: "Watch Video",
      viewDemo: "View Demo",
      viewCode: "View Code",
      editProject: "Edit Project",
      completed: "Completed",
      inProgress: "In Progress",
      onHold: "On Hold",
      description: "Description",
      projectDate: "Project Date",
      noImage: "No image available",
      errorLoading: "Error loading project"
    }
  }

  const getText = (key) => translations[language]?.[key] || translations.it[key]

  useEffect(() => {
    const fetchProject = async () => {
      try {
        console.log('Fetching project with ID:', id)
        setLoading(true)
        const projectData = await projectsService.getById(id)
        console.log('Project data received:', projectData)
        
        // Use real data from database instead of mock data
        const enhancedProject = {
          ...projectData,
          // Images - use gallery if available, otherwise fallback to single image
          images: projectData.gallery && projectData.gallery.length > 0 
            ? projectData.gallery 
            : (projectData.image_url ? [projectData.image_url] : []),
          // Technologies
          tech: projectData.technologies || [],
          // URLs
          demo: projectData.demo_url,
          github: projectData.github_url,
          // Descriptions
          longDescription: projectData.long_description || projectData.description,
          // Project details from database
          client: projectData.client || "Portfolio Project",
          duration: projectData.duration || "Variable",
          category: projectData.category || "Web Development",
          // Arrays from database
          features: projectData.features || [],
          challenges: projectData.challenges || [],
          results: projectData.results || [],
          // Additional data
          video: projectData.video_url,
          additionalLinks: projectData.additional_links || []
        }
        
        console.log('Enhanced project:', enhancedProject)
        setProject(enhancedProject)
      } catch (error) {
        console.error('Error fetching project:', error)
        console.error('Error details:', error.response?.data || error.message)
        showError(translations[language]?.errorLoading || translations.it.errorLoading)
        setProject(null)
      } finally {
        setLoading(false)
      }
    }

    if (id) {
      fetchProject()
    }
  }, [id, showError, language])

  const nextImage = () => {
    if (project && project.images && project.images.length > 0) {
      setCurrentImageIndex((prev) => 
        prev === project.images.length - 1 ? 0 : prev + 1
      )
    }
  }

  const prevImage = () => {
    if (project && project.images && project.images.length > 0) {
      setCurrentImageIndex((prev) => 
        prev === 0 ? project.images.length - 1 : prev - 1
      )
    }
  }

  const getStatusColor = (status) => {
    switch (status) {
      case 'completed':
        return 'bg-green-500/20 text-green-400 border-green-500/30'
      case 'in-progress':
        return 'bg-blue-500/20 text-blue-400 border-blue-500/30'
      case 'paused':
        return 'bg-orange-500/20 text-orange-400 border-orange-500/30'
      default:
        return 'bg-gray-500/20 text-gray-400 border-gray-500/30'
    }
  }

  const getStatusText = (status) => {
    switch (status) {
      case 'completed':
        return getText('completed')
      case 'in-progress':
        return getText('inProgress')
      case 'paused':
        return getText('onHold')
      default:
        return status
    }
  }

  if (loading) {
    return (
      <div className="min-h-screen bg-gray-50 dark:bg-slate-900 flex items-center justify-center">
        <div className="text-center">
          <div className="w-16 h-16 border-4 border-primary-500 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
          <p className="text-gray-600 dark:text-gray-400">{getText('loading')}</p>
        </div>
      </div>
    )
  }

  if (!project) {
    return (
      <div className="min-h-screen bg-gray-50 dark:bg-slate-900 flex items-center justify-center">
        <div className="text-center">
          <h1 className="text-2xl font-bold text-gray-900 dark:text-white mb-4">
            {getText('projectNotFound')}
          </h1>
          <Link
            to="/projects"
            className="inline-flex items-center px-6 py-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-colors duration-300"
          >
            <i className="fas fa-arrow-left mr-2"></i>
            {getText('backToProjects')}
          </Link>
        </div>
      </div>
    )
  }

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-slate-900">
      {/* Hero Section */}
      <section className="pt-20 pb-12 bg-gradient-to-br from-primary-50 via-white to-accent-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 relative overflow-hidden">
        <div className="absolute inset-0">
          <div className="absolute top-20 left-20 w-80 h-80 bg-gradient-to-r from-primary-400/10 to-accent-400/10 rounded-full blur-3xl"></div>
          <div className="absolute bottom-20 right-20 w-96 h-96 bg-gradient-to-r from-accent-400/10 to-pink-400/10 rounded-full blur-3xl"></div>
        </div>

        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
          {/* Breadcrumb */}
          <nav className="mb-8">
            <ol className="flex items-center space-x-2 text-sm">
              <li>
                <Link to="/" className="text-gray-500 hover:text-primary-600 transition-colors duration-300">
                  Home
                </Link>
              </li>
              <li className="text-gray-400">/</li>
              <li>
                <Link to="/projects" className="text-gray-500 hover:text-primary-600 transition-colors duration-300">
                  Progetti
                </Link>
              </li>
              <li className="text-gray-400">/</li>
              <li className="text-gray-900 dark:text-white font-medium">
                {project.title}
              </li>
            </ol>
          </nav>

          <div className="grid lg:grid-cols-2 gap-12 items-center">
            {/* Project Info */}
            <div>
              <div className="flex items-center gap-4 mb-6">
                <span className={`px-4 py-2 rounded-full text-sm font-medium border backdrop-blur-sm ${getStatusColor(project.status)}`}>
                  {getStatusText(project.status)}
                </span>
                {project.featured && (
                  <span className="px-4 py-2 rounded-full text-sm font-medium bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 backdrop-blur-sm flex items-center">
                    <i className="fas fa-star mr-2"></i>
                    In Evidenza
                  </span>
                )}
              </div>

              <h1 className="text-4xl lg:text-5xl font-bold mb-6">
                <span className="bg-gradient-to-r from-primary-600 via-accent-500 to-pink-500 bg-clip-text text-transparent">
                  {project.title}
                </span>
              </h1>

              <p className="text-xl text-gray-600 dark:text-gray-300 mb-8 leading-relaxed">
                {project.description}
              </p>

              {/* Action Buttons */}
              <div className="flex flex-wrap gap-4">
                {project.demo && (
                  <a
                    href={project.demo}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-500 to-accent-500 text-white rounded-xl font-medium hover:from-primary-600 hover:to-accent-600 transition-all duration-300 shadow-lg shadow-primary-500/25 hover:scale-105"
                  >
                    <i className="fas fa-external-link-alt mr-2"></i>
                    {getText('viewDemo')}
                  </a>
                )}
                
                {project.github && (
                  <a
                    href={project.github}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="inline-flex items-center px-6 py-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-xl font-medium hover:bg-gray-800 dark:hover:bg-gray-100 transition-all duration-300 shadow-lg hover:scale-105"
                  >
                    <i className="fab fa-github mr-2"></i>
                    {getText('viewCode')}
                  </a>
                )}

                {project.video && (
                  <button
                    onClick={() => setShowVideo(true)}
                    className="inline-flex items-center px-6 py-3 bg-red-500 text-white rounded-xl font-medium hover:bg-red-600 transition-all duration-300 shadow-lg hover:scale-105"
                  >
                    <i className="fas fa-play mr-2"></i>
                    {getText('watchVideo')}
                  </button>
                )}

                {user?.role === 'admin' && (
                  <Link
                    to={`/admin/projects/edit/${project.id}`}
                    className="inline-flex items-center px-6 py-3 bg-orange-500 text-white rounded-xl font-medium hover:bg-orange-600 transition-all duration-300 shadow-lg hover:scale-105"
                  >
                    <i className="fas fa-edit mr-2"></i>
                    {getText('editProject')}
                  </Link>
                )}
              </div>
            </div>

            {/* Main Project Image */}
            <div className="relative">
              <div className="relative h-96 rounded-3xl overflow-hidden shadow-2xl shadow-black/20 dark:shadow-black/40">
                <img
                  src={project.images[0]}
                  alt={project.title}
                  className="w-full h-full object-cover"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Project Details */}
      <section className="py-20">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid lg:grid-cols-3 gap-12">
            {/* Main Content */}
            <div className="lg:col-span-2 space-y-12">
              {/* Project Description */}
              <div className="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-xl shadow-black/5 dark:shadow-black/20 border border-gray-100 dark:border-slate-700">
                <h2 className="text-2xl font-bold mb-6 text-gray-900 dark:text-white">
                  {getText('details')}
                </h2>
                <div className="prose prose-lg dark:prose-invert max-w-none">
                  <p className="text-gray-600 dark:text-gray-300 leading-relaxed whitespace-pre-line">
                    {project.longDescription}
                  </p>
                </div>
              </div>

              {/* Features */}
              <div className="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-xl shadow-black/5 dark:shadow-black/20 border border-gray-100 dark:border-slate-700">
                <h2 className="text-2xl font-bold mb-6 text-gray-900 dark:text-white">
                  {getText('features')}
                </h2>
                <div className="grid sm:grid-cols-2 gap-4">
                  {project.features.map((feature, index) => (
                    <div key={index} className="flex items-center">
                      <div className="w-6 h-6 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                        <i className="fas fa-check text-white text-xs"></i>
                      </div>
                      <span className="text-gray-700 dark:text-gray-300">{feature}</span>
                    </div>
                  ))}
                </div>
              </div>

              {/* Challenges and Solutions */}
              {project.challenges && project.challenges.length > 0 && (
                <div className="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-xl shadow-black/5 dark:shadow-black/20 border border-gray-100 dark:border-slate-700">
                  <h2 className="text-2xl font-bold mb-6 text-gray-900 dark:text-white flex items-center">
                    <div className="w-8 h-8 bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl flex items-center justify-center mr-3">
                      <i className="fas fa-lightbulb text-white text-sm"></i>
                    </div>
                    {getText('challenges')}
                  </h2>
                  <div className="space-y-4">
                    {project.challenges.map((challenge, index) => (
                      <div key={index} className="flex items-start space-x-4 p-4 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/10 dark:to-orange-900/10 rounded-2xl border border-amber-100 dark:border-amber-800/30">
                        <div className="w-8 h-8 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full flex items-center justify-center flex-shrink-0">
                          <i className="fas fa-lightbulb text-white text-sm"></i>
                        </div>
                        <p className="text-gray-700 dark:text-gray-300 leading-relaxed">
                          {challenge}
                        </p>
                      </div>
                    ))}
                  </div>
                </div>
              )}

              {/* Results */}
              {project.results && project.results.length > 0 && (
                <div className="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-xl shadow-black/5 dark:shadow-black/20 border border-gray-100 dark:border-slate-700">
                  <h2 className="text-2xl font-bold mb-6 text-gray-900 dark:text-white flex items-center">
                    <div className="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mr-3">
                      <i className="fas fa-trophy text-white text-sm"></i>
                    </div>
                    Risultati Ottenuti
                  </h2>
                  <div className="space-y-4">
                    {project.results.map((result, index) => (
                      <div key={index} className="flex items-start space-x-4 p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/10 dark:to-emerald-900/10 rounded-2xl border border-green-100 dark:border-green-800/30">
                        <div className="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center flex-shrink-0">
                          <i className="fas fa-trophy text-white text-sm"></i>
                        </div>
                        <p className="text-gray-700 dark:text-gray-300 leading-relaxed">
                          {result}
                        </p>
                      </div>
                    ))}
                  </div>
                </div>
              )}

              {/* Image Gallery */}
              {project.images && project.images.length > 0 && (
                <div className="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-xl shadow-black/5 dark:shadow-black/20 border border-gray-100 dark:border-slate-700">
                  <h2 className="text-2xl font-bold mb-6 text-gray-900 dark:text-white">
                    {getText('projectGallery')}
                  </h2>
                  
                  {/* Main Gallery Image */}
                  <div className="relative h-96 rounded-2xl overflow-hidden mb-6">
                    <img
                      src={project.images[currentImageIndex] || '/placeholder-image.jpg'}
                      alt={`${project.title} - Image ${currentImageIndex + 1}`}
                      className="w-full h-full object-cover"
                      onError={(e) => {
                        e.target.src = '/placeholder-image.jpg'
                      }}
                    />
                    
                    {/* Navigation Arrows - Solo se più di un'immagine */}
                    {project.images.length > 1 && (
                      <>
                        <button
                          onClick={prevImage}
                          className="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-black/50 hover:bg-black/70 text-white rounded-full flex items-center justify-center transition-all duration-300 backdrop-blur-sm"
                        >
                          <i className="fas fa-chevron-left"></i>
                        </button>
                        <button
                          onClick={nextImage}
                          className="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-black/50 hover:bg-black/70 text-white rounded-full flex items-center justify-center transition-all duration-300 backdrop-blur-sm"
                        >
                          <i className="fas fa-chevron-right"></i>
                        </button>

                        {/* Image Counter */}
                        <div className="absolute bottom-4 left-1/2 -translate-x-1/2 bg-black/50 text-white px-4 py-2 rounded-full text-sm backdrop-blur-sm">
                          {currentImageIndex + 1} / {project.images.length}
                        </div>
                      </>
                    )}
                  </div>

                  {/* Thumbnail Gallery - Solo se più di un'immagine */}
                  {project.images.length > 1 && (
                    <div className="flex gap-3 overflow-x-auto pb-2">
                      {project.images.map((image, index) => (
                        <button
                          key={index}
                          onClick={() => setCurrentImageIndex(index)}
                          className={`flex-shrink-0 w-20 h-20 rounded-xl overflow-hidden border-2 transition-all duration-300 ${
                            index === currentImageIndex 
                              ? 'border-primary-500 scale-105' 
                              : 'border-gray-300 dark:border-gray-600 hover:border-primary-400'
                          }`}
                        >
                          <img
                            src={image}
                            alt={`Thumbnail ${index + 1}`}
                            className="w-full h-full object-cover"
                            onError={(e) => {
                              e.target.src = '/placeholder-image.jpg'
                            }}
                          />
                        </button>
                      ))}
                    </div>
                  )}
                </div>
              )}

              {/* Video Section */}
              {project.video && (
                <div className="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-xl shadow-black/5 dark:shadow-black/20 border border-gray-100 dark:border-slate-700">
                  <h2 className="text-2xl font-bold mb-6 text-gray-900 dark:text-white flex items-center">
                    <div className="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mr-3">
                      <i className="fas fa-play text-white text-sm"></i>
                    </div>
                    Video Dimostrativo
                  </h2>
                  
                  <div className="relative pt-[56.25%] rounded-2xl overflow-hidden">
                    <video
                      className="absolute inset-0 w-full h-full object-cover"
                      controls
                      poster={project.images && project.images[0] ? project.images[0] : undefined}
                    >
                      <source src={project.video} type="video/mp4" />
                      Il tuo browser non supporta il tag video.
                    </video>
                  </div>
                </div>
              )}


            </div>

            {/* Sidebar */}
            <div className="space-y-8">
              {/* Project Meta */}
              <div className="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-xl shadow-black/5 dark:shadow-black/20 border border-gray-100 dark:border-slate-700 sticky top-8">
                <h3 className="text-lg font-bold mb-6 text-gray-900 dark:text-white">
                  Informazioni Progetto
                </h3>
                
                <div className="space-y-4">
                  <div>
                    <label className="text-sm font-medium text-gray-500 dark:text-gray-400">
                      {getText('client')}
                    </label>
                    <div className="text-gray-900 dark:text-white font-medium">
                      {project.client}
                    </div>
                  </div>
                  
                  <div>
                    <label className="text-sm font-medium text-gray-500 dark:text-gray-400">
                      {getText('category')}
                    </label>
                    <div className="text-gray-900 dark:text-white font-medium">
                      {project.category}
                    </div>
                  </div>
                  
                  <div>
                    <label className="text-sm font-medium text-gray-500 dark:text-gray-400">
                      {getText('duration')}
                    </label>
                    <div className="text-gray-900 dark:text-white font-medium">
                      {project.duration}
                    </div>
                  </div>
                  
                  <div>
                    <label className="text-sm font-medium text-gray-500 dark:text-gray-400">
                      {getText('year')}
                    </label>
                    <div className="text-gray-900 dark:text-white font-medium">
                      {project.year}
                    </div>
                  </div>
                </div>
              </div>

              {/* Technologies */}
              <div className="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-xl shadow-black/5 dark:shadow-black/20 border border-gray-100 dark:border-slate-700">
                <h3 className="text-lg font-bold mb-6 text-gray-900 dark:text-white">
                  {getText('technologies')}
                </h3>
                
                <div className="flex flex-wrap gap-2">
                  {project.tech.map((tech, index) => (
                    <span
                      key={index}
                      className="px-3 py-1 bg-gradient-to-r from-primary-500/10 to-accent-500/10 text-primary-700 dark:text-primary-300 text-sm rounded-full border border-primary-200/30 dark:border-primary-700/30"
                    >
                      {tech}
                    </span>
                  ))}
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Video Modal */}
      {showVideo && (
        <div className="fixed inset-0 bg-black/80 flex items-center justify-center z-50 p-4">
          <div className="relative w-full max-w-4xl">
            <button
              onClick={() => setShowVideo(false)}
              className="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors duration-300"
            >
              <i className="fas fa-times text-2xl"></i>
            </button>
            
            <div className="relative pt-[56.25%] rounded-xl overflow-hidden">
              <iframe
                className="absolute inset-0 w-full h-full"
                src={project.video}
                title="Project Video"
                frameBorder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowFullScreen
              ></iframe>
            </div>
          </div>
        </div>
      )}
    </div>
  )
}

export default ProjectDetail 
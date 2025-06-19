import { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import { projectsService } from '../services/projects'
import { useAuth } from '../context/AuthContext'
import { useLanguage } from '../context/LanguageContext'
import { useNotification } from '../context/NotificationContext'

const Projects = () => {
  const [selectedFilter, setSelectedFilter] = useState('all')
  const [hoveredProject, setHoveredProject] = useState(null)
  const [isLoading, setIsLoading] = useState(true)
  const [viewMode, setViewMode] = useState('grid') // grid, list, cards, timeline
  const [showDeleteConfirm, setShowDeleteConfirm] = useState(null)
  const [projects, setProjects] = useState([])
  const [currentPage, setCurrentPage] = useState(1)
  const projectsPerPage = 6 // 6 progetti per pagina con paginazione attiva
  const [currentSlide, setCurrentSlide] = useState(0)
  const [isCarouselAutoplay, setIsCarouselAutoplay] = useState(true)

  const { user, isAuthenticated } = useAuth()
  const { language } = useLanguage()
  const { showError } = useNotification()

  // Check if user is admin
  const isAdmin = isAuthenticated && user?.is_admin

  // Translations
  const translations = {
    it: {
      portfolioProjects: "Portfolio Progetti",
      innovativeProjects: "Progetti Innovativi",
      portfolioDescription: "Esplora una selezione dei miei progetti più significativi. Ogni progetto rappresenta una sfida superata e una soluzione innovativa creata con tecnologie moderne e best practices.",
      completedProjects: "Progetti Completati",
      technologiesUsed: "Tecnologie Usate",
      satisfiedClients: "Clienti Soddisfatti",
      yearsExperience: "Anni di Esperienza",
      allProjects: "Tutti i Progetti",
      fullStack: "Full Stack",
      frontend: "Frontend",
      completed: "Completati",
      inProgress: "In Corso",
      completed_status: "Completato",
      inProgress_status: "In Corso",
      demo: "Demo",
      code: "Code",
      linkedin: "LinkedIn",
      mainFeatures: "Caratteristiche principali:",
      noProjectsFound: "Nessun progetto trovato",
      noProjectsDescription: "Non ci sono progetti che corrispondono al filtro selezionato. Prova a selezionare un altro filtro.",
      showAllProjects: "Mostra Tutti i Progetti",
      projectInMind: "Hai un Progetto in Mente?",
      collaborateDescription: "Collaboriamo per trasformare la tua idea in una soluzione digitale innovativa e funzionale.",
      startYourProject: "Inizia il Tuo Progetto",
      learnMoreAboutMe: "Scopri di Più su di Me",
      addNewProject: "Aggiungi Nuovo Progetto",
      editProject: "Modifica Progetto",
      deleteProject: "Elimina Progetto",
      confirmDelete: "Sei sicuro di voler eliminare questo progetto?",
      cancel: "Annulla",
      delete: "Elimina",
      viewDemo: "Vedi Demo",
      viewCode: "Vedi Codice",
      edit: "Modifica",
      gridView: "Vista Griglia",
      listView: "Vista Lista",
      cardsView: "Vista Carosello",
      timelineView: "Vista Timeline",
      previous: "Precedente",
      next: "Successivo",
      page: "Pagina",
      of: "di",
      projectsFound: "progetti trovati",
      viewDetails: "Vedi Dettagli"
    },
    en: {
      portfolioProjects: "Portfolio Projects",
      innovativeProjects: "Innovative Projects",
      portfolioDescription: "Explore a selection of my most significant projects. Each project represents an overcome challenge and an innovative solution created with modern technologies and best practices.",
      completedProjects: "Completed Projects",
      technologiesUsed: "Technologies Used",
      satisfiedClients: "Satisfied Clients",
      yearsExperience: "Years of Experience",
      allProjects: "All Projects",
      fullStack: "Full Stack",
      frontend: "Frontend",
      completed: "Completed",
      inProgress: "In Progress",
      completed_status: "Completed",
      inProgress_status: "In Progress",
      demo: "Demo",
      code: "Code",
      linkedin: "LinkedIn",
      mainFeatures: "Main features:",
      noProjectsFound: "No projects found",
      noProjectsDescription: "There are no projects matching the selected filter. Try selecting another filter.",
      showAllProjects: "Show All Projects",
      projectInMind: "Have a Project in Mind?",
      collaborateDescription: "Let's collaborate to transform your idea into an innovative and functional digital solution.",
      startYourProject: "Start Your Project",
      learnMoreAboutMe: "Learn More About Me",
      addNewProject: "Add New Project",
      editProject: "Edit Project",
      deleteProject: "Delete Project",
      confirmDelete: "Are you sure you want to delete this project?",
      cancel: "Cancel",
      delete: "Delete",
      viewDemo: "View Demo",
      viewCode: "View Code",
      edit: "Edit",
      gridView: "Grid View",
      listView: "List View",
      cardsView: "Carousel View",
      timelineView: "Timeline View",
      previous: "Previous",
      next: "Next",
      page: "Page",
      of: "of",
      projectsFound: "projects found",
      viewDetails: "View Details"
    }
  }

  const getText = (key) => translations[language]?.[key] || translations.it[key]

  useEffect(() => {
    loadProjects()
  }, [])

  const loadProjects = async () => {
    try {
      setIsLoading(true)
      const data = await projectsService.getAll()
      setProjects(data)
    } catch (error) {
      console.error('Error loading projects:', error)
      showError('Errore nel caricamento dei progetti')
      setProjects([])
    } finally {
      setIsLoading(false)
    }
  }

  // Helper function to determine category based on technologies
  const getProjectCategory = (technologies) => {
    if (!technologies || technologies.length === 0) return 'other'
    
    const frontendTechs = ['React', 'Vue.js', 'JavaScript', 'TypeScript', 'Tailwind CSS', 'Bootstrap']
    const backendTechs = ['Laravel', 'PHP', 'Node.js', 'Python']
    
    const hasFrontend = technologies.some(tech => frontendTechs.includes(tech))
    const hasBackend = technologies.some(tech => backendTechs.includes(tech))
    
    if (hasFrontend && hasBackend) return 'fullstack'
    if (hasFrontend) return 'frontend'
    if (hasBackend) return 'backend'
    return 'other'
  }

  // Enhanced projects with derived category
  const enhancedProjects = projects.map(project => ({
    ...project,
    category: getProjectCategory(project.technologies),
    demo: project.demo_url,
    github: project.github_url,
    linkedin: project.linkedin_url || "https://linkedin.com/in/vincenzorocca", // Default value
    year: project.project_date ? new Date(project.project_date).getFullYear() : new Date().getFullYear(),
    client: "Portfolio Project", // Default value
    // duration: "Variable", // Default value
    features: [], // Could be derived from long_description if needed
    image: project.image_url
  }))

  const filters = [
    { id: 'all', label: getText('allProjects'), count: enhancedProjects.length },
    { id: 'fullstack', label: getText('fullStack'), count: enhancedProjects.filter(p => p.category === 'fullstack').length },
    { id: 'frontend', label: getText('frontend'), count: enhancedProjects.filter(p => p.category === 'frontend').length },
    { id: 'completed', label: getText('completed'), count: enhancedProjects.filter(p => p.status === 'completed').length },
    { id: 'inProgress', label: getText('inProgress'), count: enhancedProjects.filter(p => p.status === 'in-progress').length }
  ]

  const filteredProjects = enhancedProjects.filter(project => {
    if (selectedFilter === 'all') return true
    if (selectedFilter === 'completed') return project.status === 'completed'
    if (selectedFilter === 'inProgress') return project.status === 'in-progress'
    return project.category === selectedFilter
  })

  // Pagination logic
  const totalPages = Math.ceil(filteredProjects.length / projectsPerPage)
  const startIndex = (currentPage - 1) * projectsPerPage
  const endIndex = startIndex + projectsPerPage
  const currentProjects = filteredProjects.slice(startIndex, endIndex)

  // Reset page when filter changes
  useEffect(() => {
    setCurrentPage(1)
  }, [selectedFilter])

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
      'Stripe': 'from-purple-400 to-purple-600',
      'AWS S3': 'from-orange-500 to-red-500',
      'Socket.io': 'from-gray-600 to-gray-800'
    }
    return colors[tech] || 'from-gray-400 to-gray-600'
  }

  const getStatusIcon = (status) => {
    switch (status) {
      case 'completed':
        return <i className="fas fa-check-circle"></i>
      case 'in-progress':
        return <i className="fas fa-clock"></i>
      case 'paused':
        return <i className="fas fa-pause-circle"></i>
      default:
        return <i className="fas fa-info-circle"></i>
    }
  }

  const getStatusText = (status) => {
    switch (status) {
      case 'completed':
        return 'Completato'
      case 'in-progress':
        return 'In Corso'
      case 'paused':
        return 'In Pausa'
      default:
        return 'Sconosciuto'
    }
  }

  const handleDeleteProject = async (projectId) => {
    if (!isAdmin) return
    
    try {
      // In real app, call API to delete project
      setProjects(prev => prev.filter(p => p.id !== projectId))
      setShowDeleteConfirm(null)
      
      // Show success notification
      console.log('Project deleted successfully')
    } catch (error) {
      console.error('Error deleting project:', error)
    }
  }

  const LoadingSkeleton = () => (
    <div className={`grid gap-4 sm:gap-6 lg:gap-8 ${
      viewMode === 'grid' 
        ? 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3' 
        : viewMode === 'list'
        ? 'grid-cols-1'
        : 'grid-cols-1 md:grid-cols-2'
    }`}>
      {[...Array(6)].map((_, index) => (
        <div key={index} className="bg-white dark:bg-slate-800 rounded-3xl p-4 sm:p-6 shadow-xl animate-pulse">
          <div className="w-full h-32 sm:h-48 bg-gray-200 dark:bg-slate-700 rounded-2xl mb-4 sm:mb-6"></div>
          <div className="h-4 bg-gray-200 dark:bg-slate-700 rounded mb-3"></div>
          <div className="h-3 bg-gray-200 dark:bg-slate-700 rounded mb-4 w-3/4"></div>
          <div className="flex gap-2 mb-4">
            <div className="h-6 w-16 bg-gray-200 dark:bg-slate-700 rounded-full"></div>
            <div className="h-6 w-20 bg-gray-200 dark:bg-slate-700 rounded-full"></div>
          </div>
          <div className="flex gap-3">
            <div className="h-8 w-20 bg-gray-200 dark:bg-slate-700 rounded-lg"></div>
            <div className="h-8 w-20 bg-gray-200 dark:bg-slate-700 rounded-lg"></div>
          </div>
        </div>
      ))}
    </div>
  )

  const ProjectCard = ({ project, index }) => (
    <div
      className="group relative bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-3xl overflow-hidden shadow-xl shadow-black/5 dark:shadow-black/20 border border-white/20 dark:border-slate-700/50 hover:scale-105 transition-all duration-500 flex flex-col h-full"
      onMouseEnter={() => setHoveredProject(project.id)}
      onMouseLeave={() => setHoveredProject(null)}
      style={{ animationDelay: `${index * 100}ms` }}
    >
      {/* Project Image */}
      <div className="relative h-32 sm:h-48 overflow-hidden">
        <Link to={`/projects/${project.id}`}>
          <img
            src={project.image || project.image_url || '/placeholder-image.jpg'}
            alt={project.title}
            className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 cursor-pointer"
            onError={(e) => {
              e.target.src = 'https://via.placeholder.com/600x400/3B82F6/ffffff?text=' + encodeURIComponent(project.title)
            }}
          />
        </Link>
        
        {/* Status Badge */}
        <div className={`absolute top-2 sm:top-4 left-2 sm:left-4 px-2 sm:px-3 py-1 rounded-full text-xs font-medium ${
          project.status === 'completed'
            ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-400'
            : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-400'
        } backdrop-blur-sm`}>
          {project.status === 'completed' ? getText('completed_status') : getText('inProgress_status')}
        </div>

        {/* Admin Actions */}
        {isAdmin && (
          <div className="absolute top-2 sm:top-4 right-2 sm:right-4 flex gap-2">
            <Link
              to={`/admin/projects/${project.id}/edit`}
              className="p-2 bg-blue-500/80 hover:bg-blue-600/80 text-white rounded-lg transition-colors duration-300 backdrop-blur-sm z-10"
              title={getText('editProject')}
            >
              <i className="fas fa-edit text-sm"></i>
            </Link>
            <button
              onClick={(e) => {
                e.preventDefault()
                e.stopPropagation()
                setShowDeleteConfirm(project.id)
              }}
              className="p-2 bg-red-500/80 hover:bg-red-600/80 text-white rounded-lg transition-colors duration-300 backdrop-blur-sm z-10"
              title={getText('deleteProject')}
            >
              <i className="fas fa-trash text-sm"></i>
            </button>
          </div>
        )}

        {/* Overlay on hover */}
        <div className={`absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent transition-opacity duration-300 ${
          hoveredProject === project.id ? 'opacity-100' : 'opacity-0'
        }`}>
          <div className="absolute bottom-2 sm:bottom-4 left-2 sm:left-4 right-2 sm:right-4">
            <div className="flex gap-1 sm:gap-2">
              {project.demo ? (
                <a
                  href={project.demo}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="flex-1 flex items-center justify-center px-2 sm:px-3 py-2 bg-white/20 backdrop-blur-sm text-white rounded-xl hover:bg-white/30 transition-colors duration-300 text-xs sm:text-sm"
                >
                  <i className="fas fa-external-link-alt mr-1"></i>
                  <span className="hidden sm:inline">{getText('demo')}</span>
                </a>
              ) : (
                <button
                  onClick={() => alert('Demo non ancora disponibile per questo progetto. Controlla il repository GitHub per maggiori dettagli!')}
                  className="flex-1 flex items-center justify-center px-2 sm:px-3 py-2 bg-gray-500/20 backdrop-blur-sm text-white/70 rounded-xl hover:bg-gray-400/30 transition-colors duration-300 text-xs sm:text-sm cursor-not-allowed"
                >
                  <i className="fas fa-external-link-alt mr-1"></i>
                  <span className="hidden sm:inline">{getText('demo')}</span>
                </button>
              )}
              {project.github && (
                <a
                  href={project.github}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="flex-1 flex items-center justify-center px-2 sm:px-3 py-2 bg-white/20 backdrop-blur-sm text-white rounded-xl hover:bg-white/30 transition-colors duration-300 text-xs sm:text-sm"
                >
                  <i className="fab fa-github mr-1"></i>
                  <span className="hidden sm:inline">{getText('code')}</span>
                </a>
              )}
              {project.linkedin && (
                <a
                  href={project.linkedin}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="flex-1 flex items-center justify-center px-2 sm:px-3 py-2 bg-white/20 backdrop-blur-sm text-white rounded-xl hover:bg-white/30 transition-colors duration-300 text-xs sm:text-sm"
                >
                  <i className="fab fa-linkedin mr-1"></i>
                  <span className="hidden sm:inline">LinkedIn</span>
                </a>
              )}
            </div>
          </div>
        </div>
      </div>

      {/* Project Content - Flex grow per riempire lo spazio */}
      <div className="p-4 sm:p-6 flex flex-col flex-grow">
        {/* Project Meta */}
        <div className="flex items-center justify-between mb-3">
          <span className="text-sm text-gray-500 dark:text-gray-400">{project.year}</span>
          <span className="text-sm text-primary-600 dark:text-primary-400 font-medium">{project.client || 'Portfolio Project'}</span>
        </div>

        {/* Title & Description */}
        <Link 
          to={`/projects/${project.id}`}
          className="block mb-3"
        >
          <h3 className="text-lg sm:text-xl font-bold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-300 hover:text-primary-600 dark:hover:text-primary-400">
            {project.title}
          </h3>
        </Link>
        
        <p className="text-gray-600 dark:text-gray-300 mb-4 leading-relaxed text-sm sm:text-base line-clamp-3">
          {project.description}
        </p>

        {/* Technologies */}
        <div className="flex flex-wrap gap-1 sm:gap-2 mb-4">
          {project.technologies.slice(0, 3).map((tech) => (
            <span
              key={tech}
              className={`px-2 sm:px-3 py-1 bg-gradient-to-r ${getTechColor(tech)} text-white text-xs rounded-full font-medium shadow-lg`}
            >
              {tech}
            </span>
          ))}
          {project.technologies.length > 3 && (
            <span className="px-2 sm:px-3 py-1 bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-400 text-xs rounded-full font-medium">
              +{project.technologies.length - 3}
            </span>
          )}
        </div>

        {/* Features - Solo se esistono */}
        {project.features && project.features.length > 0 && (
          <div className="mb-4">
            <div className="text-sm text-gray-500 dark:text-gray-400 mb-2">{getText('mainFeatures')}</div>
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-1">
              {project.features.slice(0, 4).map((feature, idx) => (
                <div key={idx} className="flex items-center text-xs text-gray-600 dark:text-gray-300">
                  <i className="fas fa-check text-green-500 mr-2"></i>
                  {feature}
                </div>
              ))}
            </div>
          </div>
        )}

        {/* Spacer per spingere il footer in basso */}
        <div className="flex-grow"></div>

        {/* Footer - Sempre in fondo */}
        <div className="pt-4 border-t border-gray-200/50 dark:border-slate-700/50 mt-auto">
          <div className="flex items-center justify-between mb-3">
            <span className="text-sm text-gray-500 dark:text-gray-400">
              <i className="fas fa-clock mr-1"></i>
              {project.duration || 'Variable'}
            </span>
            <div className="flex gap-2">
              {project.demo ? (
                <a
                  href={project.demo}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="p-2 text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300"
                  title={getText('viewDemo')}
                >
                  <i className="fas fa-external-link-alt"></i>
                </a>
              ) : (
                <button
                  onClick={() => alert('Demo non ancora disponibile per questo progetto. Controlla il repository GitHub per maggiori dettagli!')}
                  className="p-2 text-gray-300 hover:text-gray-400 transition-colors duration-300 cursor-not-allowed"
                  title="Demo non disponibile"
                >
                  <i className="fas fa-external-link-alt"></i>
                </button>
              )}
              {project.github && (
                <a
                  href={project.github}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="p-2 text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300"
                  title={getText('viewCode')}
                >
                  <i className="fab fa-github"></i>
                </a>
              )}
              {project.linkedin && (
                <a
                  href={project.linkedin}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="p-2 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-300"
                  title="LinkedIn"
                >
                  <i className="fab fa-linkedin"></i>
                </a>
              )}
            </div>
          </div>
          
          {/* Details Button - Sempre in fondo */}
          <Link
            to={`/projects/${project.id}`}
            className="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-primary-500 to-accent-500 text-white rounded-xl hover:from-primary-600 hover:to-accent-600 transition-all duration-300 shadow-lg hover:scale-105 text-sm font-medium"
          >
            <i className="fas fa-info-circle mr-2"></i>
            {getText('viewDetails')}
          </Link>
        </div>
      </div>
    </div>
  )

  const ProjectTimelineItem = ({ project, index }) => (
    <div 
      className="relative mb-8 sm:mb-12 group animate-slideInUp"
      style={{ animationDelay: `${index * 200}ms` }}
    >
      <div className={`flex flex-col lg:flex-row gap-6 lg:gap-8 ${index % 2 === 0 ? 'lg:flex-row' : 'lg:flex-row-reverse'}`}>
        {/* Timeline Dot */}
        <div className="hidden lg:flex absolute left-1/2 transform -translate-x-1/2 items-center justify-center">
          <div className={`w-6 h-6 rounded-full border-4 border-white dark:border-slate-900 ${
            project.status === 'completed' ? 'bg-green-500' : 'bg-blue-500'
          } shadow-lg z-10`}>
          </div>
        </div>

        {/* Timeline Line */}
        {index < currentProjects.length - 1 && (
          <div className="hidden lg:block absolute left-1/2 top-8 w-1 h-full bg-gradient-to-b from-gray-200 via-gray-300 to-transparent dark:from-slate-700 dark:via-slate-600 dark:to-transparent transform -translate-x-1/2"></div>
        )}

        {/* Project Image */}
        <div className={`flex-1 ${index % 2 === 0 ? 'lg:order-1' : 'lg:order-2'}`}>
          <div className="relative bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-3xl overflow-hidden shadow-xl border border-white/20 dark:border-slate-700/50 hover:scale-105 transition-all duration-500">
            <div className="relative h-64 sm:h-80 overflow-hidden">
              <Link to={`/projects/${project.id}`}>
                <img
                  src={project.image || project.image_url || '/placeholder-image.jpg'}
                  alt={project.title}
                  className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 cursor-pointer"
                  onError={(e) => {
                    e.target.src = 'https://via.placeholder.com/600x400/3B82F6/ffffff?text=' + encodeURIComponent(project.title)
                  }}
                />
              </Link>
              
              {/* Status Badge */}
              <div className={`absolute top-4 left-4 px-3 py-1 rounded-full text-sm font-medium ${
                project.status === 'completed'
                  ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-400'
                  : 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-400'
              } backdrop-blur-sm`}>
                {getStatusIcon(project.status)}
                <span className="ml-2">{getStatusText(project.status)}</span>
              </div>

              {/* Admin Actions */}
              {isAdmin && (
                <div className="absolute top-4 right-4 flex gap-2">
                  <button
                    onClick={() => window.location.href = `/admin/projects/edit/${project.id}`}
                    className="p-2 bg-blue-500/80 hover:bg-blue-600/80 text-white rounded-lg transition-colors duration-300 backdrop-blur-sm"
                    title={getText('editProject')}
                  >
                    <i className="fas fa-edit"></i>
                  </button>
                  <button
                    onClick={() => setShowDeleteConfirm(project.id)}
                    className="p-2 bg-red-500/80 hover:bg-red-600/80 text-white rounded-lg transition-colors duration-300 backdrop-blur-sm"
                    title={getText('deleteProject')}
                  >
                    <i className="fas fa-trash"></i>
                  </button>
                </div>
              )}
            </div>
          </div>
        </div>

        {/* Project Content */}
        <div className={`flex-1 ${index % 2 === 0 ? 'lg:order-2' : 'lg:order-1'}`}>
          <div className="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-3xl p-6 sm:p-8 shadow-xl border border-white/20 dark:border-slate-700/50 h-full">
            {/* Project Meta */}
            <div className="flex items-center justify-between mb-4">
              <span className="px-3 py-1 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 rounded-full text-sm font-medium">
                {project.year}
              </span>
              <span className="text-sm text-gray-500 dark:text-gray-400 font-medium">
                {project.client}
              </span>
            </div>

            {/* Title and Description */}
            <Link 
              to={`/projects/${project.id}`}
              className="block mb-4"
            >
              <h3 className="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-300 hover:text-primary-600 dark:hover:text-primary-400">
                {project.title}
              </h3>
            </Link>
            <p className="text-gray-600 dark:text-gray-300 leading-relaxed mb-6 text-lg">
              {project.description}
            </p>

            {/* Technologies */}
            <div className="mb-6">
              <h4 className="text-sm font-semibold text-gray-900 dark:text-white mb-3 uppercase tracking-wide">
                Tecnologie
              </h4>
              <div className="flex flex-wrap gap-2">
                {project.technologies.map((tech, idx) => (
                  <span
                    key={idx}
                    className={`px-3 py-1 text-sm font-medium rounded-full bg-gradient-to-r ${getTechColor(tech)} text-white shadow-sm hover:scale-105 transition-transform duration-300`}
                  >
                    {tech}
                  </span>
                ))}
              </div>
            </div>

            {/* Features */}
            <div className="mb-8">
              <h4 className="text-sm font-semibold text-gray-900 dark:text-white mb-3 uppercase tracking-wide">
                {getText('mainFeatures')}
              </h4>
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-2">
                {project.features.map((feature, idx) => (
                  <div key={idx} className="flex items-center text-gray-600 dark:text-gray-300">
                    <i className="fas fa-check text-green-500 mr-3"></i>
                    <span>{feature}</span>
                  </div>
                ))}
              </div>
            </div>

            {/* Action Buttons */}
            <div className="flex flex-col gap-3">
              <Link
                to={`/projects/${project.id}`}
                className="w-full inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary-500 to-accent-500 text-white rounded-xl hover:from-primary-600 hover:to-accent-600 transition-all duration-300 shadow-lg hover:scale-105 font-medium"
              >
                <i className="fas fa-info-circle mr-2"></i>
                {getText('viewDetails')}
              </Link>
              
              <div className="flex gap-3">
                {project.demo ? (
                  <a
                    href={project.demo}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="flex-1 inline-flex items-center justify-center px-6 py-3 bg-white dark:bg-slate-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-all duration-300 shadow-lg hover:scale-105 font-medium border border-gray-200 dark:border-slate-600"
                  >
                    <i className="fas fa-external-link-alt mr-2"></i>
                    {getText('viewDemo')}
                  </a>
                ) : (
                  <button
                    onClick={() => alert('Demo non ancora disponibile per questo progetto. Controlla il repository GitHub per maggiori dettagli!')}
                    className="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 rounded-xl cursor-not-allowed font-medium border border-gray-200 dark:border-gray-600"
                  >
                    <i className="fas fa-external-link-alt mr-2"></i>
                    {getText('viewDemo')}
                  </button>
                )}
                {project.github && (
                  <a
                    href={project.github}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="flex-1 inline-flex items-center justify-center px-6 py-3 bg-white dark:bg-slate-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-all duration-300 shadow-lg hover:scale-105 font-medium border border-gray-200 dark:border-slate-600"
                  >
                    <i className="fab fa-github mr-2"></i>
                    {getText('viewCode')}
                  </a>
                )}
                {project.linkedin && (
                  <a
                    href={project.linkedin}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="flex-1 inline-flex items-center justify-center px-6 py-3 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-all duration-300 shadow-lg hover:scale-105 font-medium border border-blue-200 dark:border-blue-800"
                  >
                    <i className="fab fa-linkedin mr-2"></i>
                    LinkedIn
                  </a>
                )}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  )

  // Project Carousel Component
  const ProjectCarousel = ({ projects }) => {
    const nextSlide = () => {
      setCurrentSlide((prev) => (prev + 1) % projects.length)
    }

    const prevSlide = () => {
      setCurrentSlide((prev) => (prev - 1 + projects.length) % projects.length)
    }

    const goToSlide = (index) => {
      setCurrentSlide(index)
    }

    // Auto-play functionality
    useEffect(() => {
      if (!isCarouselAutoplay) return

      const interval = setInterval(() => {
        nextSlide()
      }, 4000) // Change slide every 4 seconds

      return () => clearInterval(interval)
    }, [currentSlide, isCarouselAutoplay])

    return (
      <div className="relative max-w-7xl mx-auto">
        {/* Main Carousel Container */}
        <div 
          className="relative overflow-hidden rounded-3xl shadow-2xl shadow-black/10 dark:shadow-black/30"
          onMouseEnter={() => setIsCarouselAutoplay(false)}
          onMouseLeave={() => setIsCarouselAutoplay(true)}
        >
          {/* Slides Container */}
          <div 
            className="flex transition-transform duration-700 ease-in-out"
            style={{ transform: `translateX(-${currentSlide * 100}%)` }}
          >
                         {projects.map((project) => (
              <div key={project.id} className="w-full flex-shrink-0">
                <div className="relative h-[500px] sm:h-[600px] lg:h-[700px]">
                  {/* Background Image */}
                  <div className="absolute inset-0">
                    <img
                      src={project.image || project.image_url || '/placeholder-image.jpg'}
                      alt={project.title}
                      className="w-full h-full object-cover"
                      onError={(e) => {
                        e.target.src = 'https://via.placeholder.com/600x400/3B82F6/ffffff?text=' + encodeURIComponent(project.title)
                      }}
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-black/20"></div>
                  </div>

                  {/* Content Overlay */}
                  <div className="relative h-full flex items-end">
                    <div className="w-full p-6 sm:p-8 lg:p-12 text-white">
                      <div className="max-w-4xl">
                        {/* Project Meta */}
                        <div className="flex items-center gap-4 mb-4">
                          <span className={`px-4 py-2 rounded-full text-sm font-medium ${
                            project.status === 'completed'
                              ? 'bg-green-500/20 text-green-300 border border-green-500/30'
                              : 'bg-yellow-500/20 text-yellow-300 border border-yellow-500/30'
                          } backdrop-blur-sm`}>
                            {project.status === 'completed' ? getText('completed_status') : getText('inProgress_status')}
                          </span>
                          <span className="text-gray-300 text-sm">{project.year}</span>
                          <span className="text-gray-300 text-sm">•</span>
                          <span className="text-gray-300 text-sm">{project.client}</span>
                        </div>

                        {/* Title & Description */}
                        <h3 className="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4 lg:mb-6">
                          {project.title}
                        </h3>
                        <p className="text-lg sm:text-xl text-gray-200 mb-6 lg:mb-8 leading-relaxed max-w-3xl">
                          {project.description}
                        </p>

                        {/* Technologies */}
                        <div className="flex flex-wrap gap-2 mb-6 lg:mb-8">
                          {project.technologies.slice(0, 4).map((tech) => (
                            <span
                              key={tech}
                              className="px-3 py-1 bg-white/10 backdrop-blur-sm text-white text-sm rounded-full border border-white/20"
                            >
                              {tech}
                            </span>
                          ))}
                          {project.technologies.length > 4 && (
                            <span className="px-3 py-1 bg-white/10 backdrop-blur-sm text-gray-300 text-sm rounded-full border border-white/20">
                              +{project.technologies.length - 4}
                            </span>
                          )}
                        </div>

                        {/* Action Buttons */}
                        <div className="flex flex-wrap gap-4">
                          <Link
                            to={`/projects/${project.id}`}
                            className="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-500 to-accent-500 text-white rounded-xl font-medium hover:from-primary-600 hover:to-accent-600 transition-all duration-300 shadow-lg hover:scale-105"
                          >
                            <i className="fas fa-info-circle mr-2"></i>
                            {getText('viewDetails')}
                          </Link>
                          
                          <a
                            href={project.demo}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="inline-flex items-center px-6 py-3 bg-white/10 backdrop-blur-sm text-white rounded-xl font-medium hover:bg-white/20 transition-all duration-300 border border-white/20"
                          >
                            <i className="fas fa-external-link-alt mr-2"></i>
                            {getText('demo')}
                          </a>

                          <a
                            href={project.github}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="inline-flex items-center px-6 py-3 bg-white/10 backdrop-blur-sm text-white rounded-xl font-medium hover:bg-white/20 transition-all duration-300 border border-white/20"
                          >
                            <i className="fab fa-github mr-2"></i>
                            {getText('code')}
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            ))}
          </div>

          {/* Navigation Arrows */}
          <button
            onClick={prevSlide}
            className="absolute left-4 sm:left-6 top-1/2 -translate-y-1/2 w-12 h-12 sm:w-14 sm:h-14 bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white rounded-full flex items-center justify-center transition-all duration-300 border border-white/20 hover:scale-110"
          >
            <i className="fas fa-chevron-left text-lg"></i>
          </button>
          
          <button
            onClick={nextSlide}
            className="absolute right-4 sm:right-6 top-1/2 -translate-y-1/2 w-12 h-12 sm:w-14 sm:h-14 bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white rounded-full flex items-center justify-center transition-all duration-300 border border-white/20 hover:scale-110"
          >
            <i className="fas fa-chevron-right text-lg"></i>
          </button>

          {/* Dots Indicator */}
          <div className="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2">
            {projects.map((_, index) => (
              <button
                key={index}
                onClick={() => goToSlide(index)}
                className={`w-3 h-3 rounded-full transition-all duration-300 ${
                  index === currentSlide
                    ? 'bg-white scale-125'
                    : 'bg-white/40 hover:bg-white/60'
                }`}
              />
            ))}
          </div>

          {/* Progress Bar */}
          <div className="absolute bottom-0 left-0 right-0 h-1 bg-white/10">
            <div 
              className="h-full bg-gradient-to-r from-primary-500 to-accent-500 transition-all duration-300 ease-linear"
              style={{ width: `${((currentSlide + 1) / projects.length) * 100}%` }}
            />
          </div>
        </div>

        {/* Project Counter */}
        <div className="flex justify-center mt-6">
          <div className="px-6 py-3 bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl border border-white/20 dark:border-slate-700/50 shadow-lg">
            <span className="text-sm font-medium text-gray-700 dark:text-gray-300">
              {currentSlide + 1} / {projects.length} progetti
            </span>
          </div>
        </div>
      </div>
    )
  }

  const ProjectListItem = ({ project }) => (
    <div className="group relative bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-3xl overflow-hidden shadow-xl shadow-black/5 dark:shadow-black/20 border border-white/20 dark:border-slate-700/50 hover:scale-[1.02] transition-all duration-500 p-4 sm:p-6">
      <div className="flex flex-col lg:flex-row gap-4 lg:gap-6">
        {/* Project Image */}
        <div className="relative w-full lg:w-64 xl:w-80 h-32 sm:h-40 lg:h-32 xl:h-40 flex-shrink-0 overflow-hidden rounded-2xl">
          <img
            src={project.image || project.image_url || '/placeholder-image.jpg'}
            alt={project.title}
            className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
            onError={(e) => {
              e.target.src = 'https://via.placeholder.com/600x400/3B82F6/ffffff?text=' + encodeURIComponent(project.title)
            }}
          />
          
          {/* Status Badge */}
          <div className={`absolute top-2 left-2 px-2 sm:px-3 py-1 rounded-full text-xs font-medium ${
            project.status === 'completed'
              ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-400'
              : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-400'
          } backdrop-blur-sm`}>
            {project.status === 'completed' ? getText('completed_status') : getText('inProgress_status')}
          </div>

          {/* Admin Actions */}
          {isAdmin && (
            <div className="absolute top-2 right-2 flex gap-2">
              <button
                onClick={() => window.location.href = `/admin/projects/edit/${project.id}`}
                className="p-1.5 sm:p-2 bg-blue-500/80 hover:bg-blue-600/80 text-white rounded-lg transition-colors duration-300 backdrop-blur-sm"
                title={getText('editProject')}
              >
                <i className="fas fa-edit text-xs sm:text-sm"></i>
              </button>
              <button
                onClick={() => setShowDeleteConfirm(project.id)}
                className="p-1.5 sm:p-2 bg-red-500/80 hover:bg-red-600/80 text-white rounded-lg transition-colors duration-300 backdrop-blur-sm"
                title={getText('deleteProject')}
              >
                <i className="fas fa-trash text-xs sm:text-sm"></i>
              </button>
            </div>
          )}
        </div>

        {/* Project Content */}
        <div className="flex-1 min-w-0">
          {/* Project Meta */}
          <div className="flex flex-wrap items-center justify-between mb-3 gap-2">
            <span className="text-sm text-gray-500 dark:text-gray-400">{project.year}</span>
            <span className="text-sm text-primary-600 dark:text-primary-400 font-medium">{project.client}</span>
            <span className="text-sm text-gray-500 dark:text-gray-400">
              <i className="fas fa-clock mr-1"></i>
              {project.duration}
            </span>
          </div>

          {/* Title & Description */}
          <Link 
            to={`/projects/${project.id}`}
            className="block mb-3"
          >
            <h3 className="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-300 hover:text-primary-600 dark:hover:text-primary-400">
              {project.title}
            </h3>
          </Link>
          
          <p className="text-gray-600 dark:text-gray-300 mb-4 leading-relaxed">
            {project.description}
          </p>

          {/* Technologies */}
          <div className="flex flex-wrap gap-2 mb-4">
            {project.technologies.map((tech) => (
              <span
                key={tech}
                className={`px-3 py-1 bg-gradient-to-r ${getTechColor(tech)} text-white text-xs rounded-full font-medium shadow-lg`}
              >
                {tech}
              </span>
            ))}
          </div>

          {/* Features */}
          <div className="mb-4">
            <div className="text-sm text-gray-500 dark:text-gray-400 mb-2">{getText('mainFeatures')}</div>
            <div className="grid grid-cols-2 lg:grid-cols-4 gap-1">
              {project.features.map((feature, idx) => (
                <div key={idx} className="flex items-center text-xs text-gray-600 dark:text-gray-300">
                  <i className="fas fa-check text-green-500 mr-2"></i>
                  {feature}
                </div>
              ))}
            </div>
          </div>

          {/* Actions */}
          <div className="flex gap-3">
            {project.demo ? (
              <a
                href={project.demo}
                target="_blank"
                rel="noopener noreferrer"
                className="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary-500 to-accent-500 text-white rounded-xl hover:from-primary-600 hover:to-accent-600 transition-all duration-300 shadow-lg hover:scale-105 text-sm font-medium"
              >
                <i className="fas fa-external-link-alt mr-2"></i>
                {getText('viewDemo')}
              </a>
            ) : (
              <button
                onClick={() => alert('Demo non ancora disponibile per questo progetto. Controlla il repository GitHub per maggiori dettagli!')}
                className="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 rounded-xl cursor-not-allowed text-sm font-medium"
              >
                <i className="fas fa-external-link-alt mr-2"></i>
                {getText('viewDemo')}
              </button>
            )}
            {project.github && (
              <a
                href={project.github}
                target="_blank"
                rel="noopener noreferrer"
                className="inline-flex items-center px-4 py-2 bg-white dark:bg-slate-700/80 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-all duration-300 shadow-lg hover:scale-105 text-sm font-medium border border-gray-200 dark:border-slate-600/50"
              >
                <i className="fab fa-github mr-2"></i>
                {getText('viewCode')}
              </a>
            )}
            {project.linkedin && (
              <a
                href={project.linkedin}
                target="_blank"
                rel="noopener noreferrer"
                className="inline-flex items-center px-4 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-all duration-300 shadow-lg hover:scale-105 text-sm font-medium border border-blue-200 dark:border-blue-800"
              >
                <i className="fab fa-linkedin mr-2"></i>
                LinkedIn
              </a>
            )}
          </div>
        </div>
      </div>
    </div>
  )

  return (
    <div className="min-h-screen pt-16 sm:pt-20 bg-gradient-to-br from-gray-50 via-white to-gray-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
      
      {/* Hero Section Premium */}
      <section className="relative py-12 sm:py-20 overflow-hidden">
        {/* Animated background elements */}
        <div className="absolute inset-0">
          <div className="absolute top-20 left-10 w-48 sm:w-72 h-48 sm:h-72 bg-gradient-to-r from-primary-400/10 to-accent-400/10 rounded-full blur-3xl animate-float"></div>
          <div className="absolute bottom-20 right-10 w-64 sm:w-96 h-64 sm:h-96 bg-gradient-to-r from-purple-400/10 to-pink-400/10 rounded-full blur-3xl animate-float-delay"></div>
          <div className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-48 sm:w-80 h-48 sm:h-80 bg-gradient-to-r from-green-400/5 to-blue-400/5 rounded-full blur-3xl"></div>
        </div>

        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
          <div className="text-center">
            {/* Badge */}
            <div className="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 rounded-full bg-gradient-to-r from-primary-500/10 to-accent-500/10 border border-primary-200/30 dark:border-primary-700/30 text-primary-700 dark:text-primary-300 text-sm font-medium mb-6 sm:mb-8 hover:scale-105 transition-transform duration-300">
              <i className="fas fa-code mr-2 sm:mr-3 text-base sm:text-lg"></i>
              {getText('portfolioProjects')}
            </div>

            {/* Main Title */}
            <h1 className="text-3xl sm:text-5xl lg:text-7xl font-bold mb-6 sm:mb-8">
              <span className="text-gray-900 dark:text-white">Progetti </span>
              <span className="bg-gradient-to-r from-primary-600 via-accent-500 to-pink-500 bg-clip-text text-transparent">
                {getText('innovativeProjects').split(' ')[1]}
              </span>
            </h1>

            {/* Enhanced Description */}
            <div className="max-w-4xl mx-auto text-lg sm:text-xl text-gray-600 dark:text-gray-300 leading-relaxed mb-8 sm:mb-12 px-4">
              <p>
                {getText('portfolioDescription')}
              </p>
            </div>

            {/* Stats Cards */}
            <div className="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 max-w-4xl mx-auto mb-8 sm:mb-12">
              {[
                { label: getText('completedProjects'), value: projects.filter(p => p.status === 'completed').length, icon: "fas fa-check-circle", color: "from-green-500 to-emerald-500" },
                { label: getText('technologiesUsed'), value: "15+", icon: "fas fa-tools", color: "from-blue-500 to-cyan-500" },
                { label: getText('satisfiedClients'), value: "10+", icon: "fas fa-users", color: "from-purple-500 to-pink-500" },
                { label: getText('yearsExperience'), value: "2+", icon: "fas fa-calendar", color: "from-orange-500 to-red-500" }
              ].map((stat, index) => (
                <div
                  key={index}
                  className="group relative bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl sm:rounded-3xl p-3 sm:p-6 text-center shadow-xl shadow-black/5 dark:shadow-black/20 border border-white/20 dark:border-slate-700/50 hover:scale-105 transition-all duration-500"
                >
                  <div className={`w-8 h-8 sm:w-12 sm:h-12 mx-auto mb-2 sm:mb-4 rounded-xl sm:rounded-2xl bg-gradient-to-r ${stat.color} flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform duration-300`}>
                    <i className={`${stat.icon} text-sm sm:text-xl`}></i>
                  </div>
                  <div className="text-lg sm:text-2xl font-bold mb-1 text-gray-900 dark:text-white">{stat.value}</div>
                  <div className="text-xs sm:text-sm text-gray-600 dark:text-gray-400">{stat.label}</div>
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* Controls Section */}
      <section className="py-6 sm:py-12 bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          
          {/* Admin Controls */}
          {isAdmin && (
            <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 sm:mb-8">
              <h2 className="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">
                Gestione Progetti (Admin)
              </h2>
              <a
                href="/admin/projects/create"
                className="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl hover:from-green-600 hover:to-emerald-600 transition-all duration-300 shadow-lg hover:scale-105 font-medium"
              >
                <i className="fas fa-plus mr-2"></i>
                {getText('addNewProject')}
              </a>
            </div>
          )}

          <div className="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            
            {/* Filters */}
            <div className="flex flex-wrap gap-2 sm:gap-4 order-2 lg:order-1 w-full lg:w-auto">
              {filters.map((filter) => (
                <button
                  key={filter.id}
                  onClick={() => setSelectedFilter(filter.id)}
                  className={`group relative px-3 sm:px-6 py-2 sm:py-3 rounded-xl sm:rounded-2xl font-medium transition-all duration-300 hover:scale-105 text-sm sm:text-base ${
                    selectedFilter === filter.id
                      ? 'bg-gradient-to-r from-primary-500 to-accent-500 text-white shadow-lg shadow-primary-500/25'
                      : 'bg-white/80 dark:bg-slate-800/80 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 border border-gray-200/50 dark:border-slate-700/50'
                  } backdrop-blur-xl shadow-lg`}
                >
                  <span className="relative z-10">{filter.label}</span>
                  <span className={`ml-1 sm:ml-2 px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full text-xs ${
                    selectedFilter === filter.id
                      ? 'bg-white/20 text-white'
                      : 'bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-400'
                  }`}>
                    {filter.count}
                  </span>
                </button>
              ))}
            </div>

            {/* View Mode Toggle */}
            <div className="flex gap-1 sm:gap-2 bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-xl p-1 border border-gray-200/50 dark:border-slate-700/50 order-1 lg:order-2">
              {[
                { mode: 'grid', icon: 'fas fa-th', title: getText('gridView') },
                { mode: 'list', icon: 'fas fa-list', title: getText('listView') },
                { mode: 'cards', icon: 'fas fa-images', title: getText('cardsView') },
                { mode: 'timeline', icon: 'fas fa-stream', title: getText('timelineView') }
              ].map((view) => (
                <button
                  key={view.mode}
                  onClick={() => setViewMode(view.mode)}
                  title={view.title}
                  className={`p-2 sm:p-3 rounded-lg transition-all duration-300 ${
                    viewMode === view.mode
                      ? 'bg-gradient-to-r from-primary-500 to-accent-500 text-white shadow-lg'
                      : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-slate-700'
                  }`}
                >
                  <i className={`${view.icon} text-sm sm:text-base`}></i>
                </button>
              ))}
            </div>

          </div>
        </div>
      </section>

      {/* Projects Grid */}
      <section className="py-12 sm:py-20">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          {isLoading ? (
            <LoadingSkeleton />
          ) : (
            <>
              {viewMode === 'cards' ? (
                <ProjectCarousel projects={currentProjects} />
              ) : (
                <div className={`${
                  viewMode === 'grid' 
                    ? 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8 max-w-7xl mx-auto' 
                    : viewMode === 'timeline'
                    ? 'relative max-w-6xl mx-auto'
                    : 'space-y-6 sm:space-y-8'
                }`}>
                  {currentProjects.map((project, index) => (
                    viewMode === 'list' ? (
                      <ProjectListItem key={project.id} project={project} />
                    ) : viewMode === 'timeline' ? (
                      <ProjectTimelineItem key={project.id} project={project} index={index} />
                    ) : (
                      <ProjectCard key={project.id} project={project} index={index} />
                    )
                  ))}
                </div>
              )}

              {/* Pagination */}
              {totalPages > 1 && (
                <div className="flex flex-col sm:flex-row items-center justify-between gap-4 mt-12">
                  {/* Results Info */}
                  <div className="text-sm text-gray-600 dark:text-gray-400">
                    {getText('page')} {currentPage} {getText('of')} {totalPages} • {filteredProjects.length} {getText('projectsFound')}
                  </div>

                  {/* Pagination Controls */}
                  <div className="flex items-center gap-2">
                    <button
                      onClick={() => setCurrentPage(prev => Math.max(prev - 1, 1))}
                      disabled={currentPage === 1}
                      className="px-4 py-2 bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 rounded-xl border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                      <i className="fas fa-chevron-left mr-2"></i>
                      {getText('previous')}
                    </button>

                    {/* Page Numbers */}
                    <div className="flex gap-1">
                      {[...Array(totalPages)].map((_, i) => (
                        <button
                          key={i + 1}
                          onClick={() => setCurrentPage(i + 1)}
                          className={`w-10 h-10 rounded-xl transition-all duration-300 ${
                            currentPage === i + 1
                              ? 'bg-gradient-to-r from-primary-500 to-accent-500 text-white shadow-lg'
                              : 'bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700'
                          }`}
                        >
                          {i + 1}
                        </button>
                      ))}
                    </div>

                    <button
                      onClick={() => setCurrentPage(prev => Math.min(prev + 1, totalPages))}
                      disabled={currentPage === totalPages}
                      className="px-4 py-2 bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 rounded-xl border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                      {getText('next')}
                      <i className="fas fa-chevron-right ml-2"></i>
                    </button>
                  </div>
                </div>
              )}
            </>
          )}

          {/* Empty State */}
          {!isLoading && currentProjects.length === 0 && (
            <div className="text-center py-12 sm:py-20">
              <div className="w-16 sm:w-24 h-16 sm:h-24 mx-auto mb-6 sm:mb-8 bg-gradient-to-r from-gray-100 to-gray-200 dark:from-slate-700 dark:to-slate-600 rounded-full flex items-center justify-center">
                <i className="fas fa-filter text-2xl sm:text-3xl text-gray-400"></i>
              </div>
              <h3 className="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-4">
                {getText('noProjectsFound')}
              </h3>
              <p className="text-gray-600 dark:text-gray-400 mb-6 sm:mb-8 max-w-md mx-auto text-sm sm:text-base">
                {getText('noProjectsDescription')}
              </p>
              <button
                onClick={() => setSelectedFilter('all')}
                className="btn-primary text-sm sm:text-base"
              >
                <i className="fas fa-list mr-2"></i>
                {getText('showAllProjects')}
              </button>
            </div>
          )}
        </div>
      </section>

      {/* Delete Confirmation Modal */}
      {showDeleteConfirm && (
        <div className="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
          <div className="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-white/20 dark:border-slate-700/50">
            <div className="text-center">
              <div className="w-16 h-16 mx-auto mb-6 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                <i className="fas fa-exclamation-triangle text-2xl text-red-600 dark:text-red-400"></i>
              </div>
              
              <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-4">
                {getText('deleteProject')}
              </h3>
              
              <p className="text-gray-600 dark:text-gray-300 mb-8">
                {getText('confirmDelete')}
              </p>
              
              <div className="flex gap-4">
                <button
                  onClick={() => setShowDeleteConfirm(null)}
                  className="flex-1 px-6 py-3 bg-gray-100 dark:bg-slate-700 text-gray-900 dark:text-white rounded-xl hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors duration-300 font-medium"
                >
                  {getText('cancel')}
                </button>
                <button
                  onClick={() => handleDeleteProject(showDeleteConfirm)}
                  className="flex-1 px-6 py-3 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-colors duration-300 font-medium"
                >
                  {getText('delete')}
                </button>
              </div>
            </div>
          </div>
        </div>
      )}

    </div>
  )
}

export default Projects 
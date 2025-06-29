import { useState, useEffect } from 'react'
import { useParams, useNavigate, Link } from 'react-router-dom'
import { projectsService } from '../../services/projects'
import { technologiesService } from '../../services/technologies'
import { useNotification } from '../../context/NotificationContext'
import FileUpload from '../../components/common/FileUpload'

const ProjectForm = () => {
  const { id } = useParams()
  const navigate = useNavigate()
  const { showSuccess, showError } = useNotification()
  
  const isEditMode = Boolean(id)
  
  // Form State
  const [formData, setFormData] = useState({
    title: '',
    description: '',
    long_description: '',
    image_url: '',
    demo_url: '',
    github_url: '',
    linkedin_url: '',
    video_url: '',
    technologies: [],
    status: 'in-progress',
    featured: false,
    project_date: new Date().toISOString().split('T')[0],
    client: '',
    duration: '',
    category: '',
    features: [],
    challenges: [],
    results: [],
    gallery: [],
    additional_links: []
  })
  
  const [loading, setLoading] = useState(false)
  const [initialLoading, setInitialLoading] = useState(isEditMode)
  const [availableTechnologies, setAvailableTechnologies] = useState([])

  // Temporary states for array inputs
  const [newFeature, setNewFeature] = useState('')
  const [newChallenge, setNewChallenge] = useState('')
  const [newResult, setNewResult] = useState('')
  const [newLink, setNewLink] = useState({ name: '', url: '' })

  // Load data
  useEffect(() => {
    loadTechnologies()
    if (isEditMode) {
      loadProject()
    }
  }, [id, isEditMode])

  const loadTechnologies = async () => {
    try {
      const data = await technologiesService.getAll();
      setAvailableTechnologies(data);
    } catch (err) {
      console.error('Error loading technologies:', err);
    }
  };

  const loadProject = async () => {
    try {
      setLoading(true);
      const project = await projectsService.getById(id);
      
      setFormData({
        title: project.title || '',
        description: project.description || '',
        long_description: project.long_description || '',
        image_url: project.image_url || '',
        demo_url: project.demo_url || '',
        github_url: project.github_url || '',
        linkedin_url: project.linkedin_url || '',
        video_url: project.video_url || '',
        technologies: project.technologies?.map(tech => tech.id) || [],
        status: project.status || 'active',
        featured: Boolean(project.featured),
        project_date: project.project_date || new Date().toISOString().split('T')[0],
        client: project.client || '',
        duration: project.duration || '',
        category: project.category || '',
        features: project.features || [],
        challenges: project.challenges || [],
        results: project.results || [],
        gallery: project.gallery || [],
        additional_links: project.additional_links || []
      });
    } catch (err) {
      console.error('Error loading project:', err);
      setError('Errore nel caricamento del progetto');
      navigate('/admin/projects');
    } finally {
      setLoading(false);
    }
  };

  // Handle form changes
  const handleChange = (e) => {
    const { name, value, type, checked } = e.target
    setFormData(prev => ({
      ...prev,
      [name]: type === 'checkbox' ? checked : value
    }))
  }

  // Handle technology toggle
  const toggleTechnology = (techName) => {
    setFormData(prev => ({
      ...prev,
      technologies: prev.technologies.includes(techName)
        ? prev.technologies.filter(t => t !== techName)
        : [...prev.technologies, techName]
    }))
  }

  // Handle file uploads
  const handleImageUpload = (result) => {
    setFormData(prev => ({
      ...prev,
      image_url: result.url
    }))
  }

  const handleVideoUpload = (result) => {
    setFormData(prev => ({
      ...prev,
      video_url: result.url
    }))
  }

  const handleGalleryUpload = async (results) => {
    console.log('🔧 handleGalleryUpload - Received results:', results);
    
    if (!results || results.length === 0) return;

    const newImages = results
      .filter(result => result && (result.url || result.secure_url))
      .map(result => result.url || result.secure_url);

    if (newImages.length > 0) {
      console.log('✅ Adding images to gallery:', newImages);
      setFormData(prev => ({
        ...prev,
        gallery: [...(prev.gallery || []), ...newImages]
      }));
    } else {
      console.error('❌ Formato risultato non riconosciuto:', results);
    }
  };

  // Handle array additions
  const addFeature = () => {
    if (newFeature.trim()) {
      setFormData(prev => ({
        ...prev,
        features: [...prev.features, newFeature.trim()]
      }))
      setNewFeature('')
    }
  }

  const addChallenge = () => {
    if (newChallenge.trim()) {
      setFormData(prev => ({
        ...prev,
        challenges: [...prev.challenges, newChallenge.trim()]
      }))
      setNewChallenge('')
    }
  }

  const addResult = () => {
    if (newResult.trim()) {
      setFormData(prev => ({
        ...prev,
        results: [...prev.results, newResult.trim()]
      }))
      setNewResult('')
    }
  }

  const addLink = () => {
    if (newLink.name.trim() && newLink.url.trim()) {
      setFormData(prev => ({
        ...prev,
        additional_links: [...prev.additional_links, { ...newLink }]
      }))
      setNewLink({ name: '', url: '' })
    }
  }

  // Handle array removals
  const removeFeature = (index) => {
    setFormData(prev => ({
      ...prev,
      features: prev.features.filter((_, i) => i !== index)
    }))
  }

  const removeChallenge = (index) => {
    setFormData(prev => ({
      ...prev,
      challenges: prev.challenges.filter((_, i) => i !== index)
    }))
  }

  const removeResult = (index) => {
    setFormData(prev => ({
      ...prev,
      results: prev.results.filter((_, i) => i !== index)
    }))
  }

  const removeLink = (index) => {
    setFormData(prev => ({
      ...prev,
      additional_links: prev.additional_links.filter((_, i) => i !== index)
    }))
  }

  const removeGalleryImage = (index) => {
    setFormData(prev => ({
      ...prev,
      gallery: prev.gallery.filter((_, i) => i !== index)
    }))
  }

  // Handle form submit
  const handleSubmit = async (e) => {
    e.preventDefault();
    
    try {
      setLoading(true);
      
      if (isEditMode) {
        await projectsService.update(id, formData);
      } else {
        await projectsService.create(formData);
      }
      
      setSuccess(`Progetto ${isEditMode ? 'aggiornato' : 'creato'} con successo!`);
      navigate('/admin/projects');
    } catch (err) {
      console.error('Error saving project:', err);
      setError(err.message || `Errore nel ${isEditMode ? 'aggiornamento' : 'salvataggio'} del progetto`);
    } finally {
      setLoading(false);
    }
  };

  if (initialLoading) {
    return (
      <div className="min-h-screen py-8">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex items-center justify-center py-12">
            <div className="spinner mr-3"></div>
            <span className="text-gray-600 dark:text-gray-400">Caricamento progetto...</span>
          </div>
        </div>
      </div>
    )
  }

  return (
    <div className="min-h-screen py-8">
      <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Header */}
        <div className="mb-8">
          {/* Breadcrumb */}
          <nav className="flex items-center space-x-2 text-sm mb-4">
            <Link to="/admin" className="text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400">
              Admin
            </Link>
            <i className="fas fa-chevron-right text-gray-400 text-xs"></i>
            <Link to="/admin/projects" className="text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400">
              Progetti
            </Link>
            <i className="fas fa-chevron-right text-gray-400 text-xs"></i>
            <span className="text-gray-900 dark:text-white font-medium">
              {isEditMode ? 'Modifica Progetto' : 'Nuovo Progetto'}
            </span>
          </nav>

          <h1 className="text-3xl font-bold text-gray-900 dark:text-white">
            {isEditMode ? 'Modifica Progetto' : 'Nuovo Progetto'}
          </h1>
          <p className="text-gray-600 dark:text-gray-400 mt-2">
            {isEditMode 
              ? 'Modifica i dettagli del progetto' 
              : 'Compila tutti i campi per creare un nuovo progetto'}
          </p>
        </div>

        {/* Form */}
        <form onSubmit={handleSubmit} className="space-y-8">
          
          {/* Basic Information */}
          <div className="bg-white dark:bg-dark-800 rounded-lg p-6 shadow-lg border border-gray-200 dark:border-dark-600">
            <h2 className="text-xl font-semibold text-gray-900 dark:text-white mb-6">
              Informazioni Base
            </h2>
            
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
              {/* Title */}
              <div className="lg:col-span-2">
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Titolo del Progetto *
                </label>
                <input
                  type="text"
                  name="title"
                  value={formData.title}
                  onChange={handleChange}
                  required
                  className="form-input"
                  placeholder="Es. E-Commerce Platform"
                />
              </div>

              {/* Description */}
              <div className="lg:col-span-2">
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Descrizione Breve *
                </label>
                <textarea
                  name="description"
                  value={formData.description}
                  onChange={handleChange}
                  required
                  rows={3}
                  className="form-input"
                  placeholder="Breve descrizione del progetto..."
                />
              </div>

              {/* Long Description */}
              <div className="lg:col-span-2">
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Descrizione Dettagliata
                </label>
                <textarea
                  name="long_description"
                  value={formData.long_description}
                  onChange={handleChange}
                  rows={5}
                  className="form-input"
                  placeholder="Descrizione dettagliata del progetto, funzionalità, obiettivi..."
                />
              </div>

              {/* Status */}
              <div>
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Stato del Progetto
                </label>
                <select
                  name="status"
                  value={formData.status}
                  onChange={handleChange}
                  className="form-input"
                >
                  <option value="in-progress">In Corso</option>
                  <option value="completed">Completato</option>
                  <option value="paused">In Pausa</option>
                </select>
              </div>

              {/* Project Date */}
              <div>
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Data Progetto
                </label>
                <input
                  type="date"
                  name="project_date"
                  value={formData.project_date}
                  onChange={handleChange}
                  className="form-input"
                />
              </div>

              {/* Featured */}
              <div className="lg:col-span-2">
                <label className="flex items-center">
                  <input
                    type="checkbox"
                    name="featured"
                    checked={formData.featured}
                    onChange={handleChange}
                    className="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                  />
                  <span className="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Progetto in evidenza
                  </span>
                </label>
                <p className="text-xs text-gray-500 dark:text-gray-400 mt-1">
                  I progetti in evidenza verranno mostrati in primo piano
                </p>
              </div>
            </div>
          </div>

          {/* Project Details */}
          <div className="bg-white dark:bg-dark-800 rounded-lg p-6 shadow-lg border border-gray-200 dark:border-dark-600">
            <h2 className="text-xl font-semibold text-gray-900 dark:text-white mb-6">
              Dettagli Progetto
            </h2>
            
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
              {/* Client */}
              <div>
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Cliente
                </label>
                <input
                  type="text"
                  name="client"
                  value={formData.client}
                  onChange={handleChange}
                  className="form-input"
                  placeholder="Nome del cliente o azienda"
                />
              </div>

              {/* Duration */}
              <div>
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Durata
                </label>
                <input
                  type="text"
                  name="duration"
                  value={formData.duration}
                  onChange={handleChange}
                  className="form-input"
                  placeholder="Es. 3 mesi, 2 settimane"
                />
              </div>

              {/* Category */}
              <div className="lg:col-span-2">
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Categoria
                </label>
                <select
                  name="category"
                  value={formData.category}
                  onChange={handleChange}
                  className="form-input"
                >
                  <option value="">Seleziona categoria</option>
                  <option value="Web Development">Web Development</option>
                  <option value="Mobile App">Mobile App</option>
                  <option value="E-Commerce">E-Commerce</option>
                  <option value="Dashboard">Dashboard</option>
                  <option value="Landing Page">Landing Page</option>
                  <option value="Full Stack">Full Stack</option>
                  <option value="Frontend">Frontend</option>
                  <option value="Backend">Backend</option>
                </select>
              </div>
            </div>
          </div>

          {/* Media Upload */}
          <div className="bg-white dark:bg-dark-800 rounded-lg p-6 shadow-lg border border-gray-200 dark:border-dark-600">
            <h2 className="text-xl font-semibold text-gray-900 dark:text-white mb-6">
              Media e Upload
            </h2>
            
            <div className="space-y-6">
              {/* Main Image */}
              <div>
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Immagine Principale
                </label>
                {formData.image_url ? (
                  <div className="mb-4">
                    <img 
                      src={formData.image_url} 
                      alt="Preview" 
                      className="w-full h-48 object-cover rounded-lg"
                    />
                    <button
                      type="button"
                      onClick={() => setFormData(prev => ({ ...prev, image_url: '' }))}
                      className="mt-2 text-sm text-red-600 hover:text-red-700"
                    >
                      Rimuovi immagine
                    </button>
                  </div>
                ) : (
                  <FileUpload
                    type="image"
                    onUploadSuccess={handleImageUpload}
                    maxSize={10}
                    className="mb-4"
                  />
                )}
                <input
                  type="url"
                  name="image_url"
                  value={formData.image_url}
                  onChange={handleChange}
                  className="form-input"
                  placeholder="Oppure inserisci URL immagine"
                />
              </div>

              {/* Video */}
              <div>
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Video Dimostrativo
                </label>
                {formData.video_url ? (
                  <div className="mb-4">
                    <video 
                      src={formData.video_url} 
                      controls 
                      className="w-full h-48 rounded-lg"
                    />
                    <button
                      type="button"
                      onClick={() => setFormData(prev => ({ ...prev, video_url: '' }))}
                      className="mt-2 text-sm text-red-600 hover:text-red-700"
                    >
                      Rimuovi video
                    </button>
                  </div>
                ) : (
                  <FileUpload
                    type="video"
                    onUploadSuccess={handleVideoUpload}
                    maxSize={100}
                    className="mb-4"
                  />
                )}
                <input
                  type="url"
                  name="video_url"
                  value={formData.video_url}
                  onChange={handleChange}
                  className="form-input"
                  placeholder="Oppure inserisci URL video"
                />
              </div>

              {/* Gallery */}
              <div>
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Galleria Immagini
                </label>
                {formData.gallery.length > 0 && (
                  <div className="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4">
                    {formData.gallery.map((image, index) => (
                      <div key={index} className="relative group">
                        <img 
                          src={image} 
                          alt={`Gallery ${index + 1}`} 
                          className="w-full h-32 object-cover rounded-lg"
                        />
                        <button
                          type="button"
                          onClick={() => removeGalleryImage(index)}
                          className="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
                        >
                          <i className="fas fa-times text-xs"></i>
                        </button>
                      </div>
                    ))}
                  </div>
                )}
                <FileUpload
                  type="gallery"
                  multiple={true}
                  onUploadSuccess={handleGalleryUpload}
                  maxSize={10}
                />
              </div>
            </div>
          </div>

          {/* Links */}
          <div className="bg-white dark:bg-dark-800 rounded-lg p-6 shadow-lg border border-gray-200 dark:border-dark-600">
            <h2 className="text-xl font-semibold text-gray-900 dark:text-white mb-6">
              Link e URL
            </h2>
            
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
              {/* Demo URL */}
              <div>
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  URL Demo
                </label>
                <input
                  type="url"
                  name="demo_url"
                  value={formData.demo_url}
                  onChange={handleChange}
                  className="form-input"
                  placeholder="https://example.com"
                />
              </div>

              {/* GitHub URL */}
              <div>
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  URL GitHub
                </label>
                <input
                  type="url"
                  name="github_url"
                  value={formData.github_url}
                  onChange={handleChange}
                  className="form-input"
                  placeholder="https://github.com/username/repo"
                />
              </div>

              {/* LinkedIn URL */}
              <div>
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  URL LinkedIn
                </label>
                <input
                  type="url"
                  name="linkedin_url"
                  value={formData.linkedin_url}
                  onChange={handleChange}
                  className="form-input"
                  placeholder="https://linkedin.com/in/username"
                />
              </div>

              {/* Additional Links */}
              <div className="lg:col-span-2">
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Link Aggiuntivi
                </label>
                {formData.additional_links.length > 0 && (
                  <div className="space-y-2 mb-4">
                    {formData.additional_links.map((link, index) => (
                      <div key={index} className="flex items-center gap-2 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span className="font-medium text-sm">{link.name}:</span>
                        <a href={link.url} target="_blank" rel="noopener noreferrer" className="text-primary-600 hover:text-primary-700 text-sm flex-1 truncate">
                          {link.url}
                        </a>
                        <button
                          type="button"
                          onClick={() => removeLink(index)}
                          className="text-red-600 hover:text-red-700"
                        >
                          <i className="fas fa-times"></i>
                        </button>
                      </div>
                    ))}
                  </div>
                )}
                <div className="flex gap-2">
                  <input
                    type="text"
                    value={newLink.name}
                    onChange={(e) => setNewLink(prev => ({ ...prev, name: e.target.value }))}
                    className="form-input flex-1"
                    placeholder="Nome link"
                  />
                  <input
                    type="url"
                    value={newLink.url}
                    onChange={(e) => setNewLink(prev => ({ ...prev, url: e.target.value }))}
                    className="form-input flex-1"
                    placeholder="URL"
                  />
                  <button
                    type="button"
                    onClick={addLink}
                    className="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700"
                  >
                    <i className="fas fa-plus"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>

          {/* Technologies */}
          <div className="bg-white dark:bg-dark-800 rounded-lg p-6 shadow-lg border border-gray-200 dark:border-dark-600">
            <h2 className="text-xl font-semibold text-gray-900 dark:text-white mb-6">
              Tecnologie Utilizzate
            </h2>
            
            {availableTechnologies.length > 0 ? (
              <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                {availableTechnologies.map((tech) => (
                  <label
                    key={tech.id}
                    className={`flex items-center p-3 rounded-lg border cursor-pointer transition-all ${
                      formData.technologies.includes(tech.name)
                        ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20'
                        : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600'
                    }`}
                  >
                    <input
                      type="checkbox"
                      checked={formData.technologies.includes(tech.name)}
                      onChange={() => toggleTechnology(tech.name)}
                      className="sr-only"
                    />
                    <div className="flex items-center flex-1 min-w-0">
                      {tech.icon && (
                        <i className={`${tech.icon} text-lg mr-2 flex-shrink-0`} style={{ color: tech.color }}></i>
                      )}
                      <span className="text-sm font-medium text-gray-900 dark:text-white truncate">
                        {tech.name}
                      </span>
                    </div>
                  </label>
                ))}
              </div>
            ) : (
              <div className="text-center py-8">
                <div className="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                  <i className="fas fa-cogs text-2xl text-gray-400"></i>
                </div>
                <h3 className="text-lg font-medium text-gray-900 dark:text-white mb-2">
                  Tecnologie Preconfigurate
                </h3>
                <p className="text-gray-500 dark:text-gray-400 mb-4">
                  Le tecnologie sono già state configurate nel sistema.<br/>
                  Sono disponibili 18 tecnologie principali per i tuoi progetti.
                </p>
                <div className="text-sm text-gray-600 dark:text-gray-400">
                  <p><strong>Frontend:</strong> React, Vue.js, JavaScript, TypeScript, Tailwind CSS, Bootstrap</p>
                  <p><strong>Backend:</strong> Laravel, PHP, Node.js, Python, Express.js</p>
                  <p><strong>Database:</strong> MySQL, PostgreSQL, MongoDB</p>
                  <p><strong>Tools:</strong> Git, Docker, VS Code, Figma</p>
                </div>
              </div>
            )}

            {/* Selected Technologies */}
            {formData.technologies.length > 0 && (
              <div className="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <h3 className="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                  Tecnologie Selezionate ({formData.technologies.length})
                </h3>
                <div className="flex flex-wrap gap-2">
                  {formData.technologies.map((techName) => (
                    <span
                      key={techName}
                      className="inline-flex items-center px-3 py-1 rounded-full text-sm bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300"
                    >
                      {techName}
                      <button
                        type="button"
                        onClick={() => toggleTechnology(techName)}
                        className="ml-2 text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-200"
                      >
                        ×
                      </button>
                    </span>
                  ))}
                </div>
              </div>
            )}
          </div>

          {/* Features */}
          <div className="bg-white dark:bg-dark-800 rounded-lg p-6 shadow-lg border border-gray-200 dark:border-dark-600">
            <h2 className="text-xl font-semibold text-gray-900 dark:text-white mb-6">
              Caratteristiche Principali
            </h2>
            
            {formData.features.length > 0 && (
              <div className="space-y-2 mb-4">
                {formData.features.map((feature, index) => (
                  <div key={index} className="flex items-center gap-2 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <i className="fas fa-check text-green-500"></i>
                    <span className="flex-1">{feature}</span>
                    <button
                      type="button"
                      onClick={() => removeFeature(index)}
                      className="text-red-600 hover:text-red-700"
                    >
                      <i className="fas fa-times"></i>
                    </button>
                  </div>
                ))}
              </div>
            )}
            
            <div className="flex gap-2">
              <input
                type="text"
                value={newFeature}
                onChange={(e) => setNewFeature(e.target.value)}
                className="form-input flex-1"
                placeholder="Aggiungi una caratteristica..."
                onKeyPress={(e) => e.key === 'Enter' && (e.preventDefault(), addFeature())}
              />
              <button
                type="button"
                onClick={addFeature}
                className="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
              >
                <i className="fas fa-plus"></i>
              </button>
            </div>
          </div>

          {/* Challenges */}
          <div className="bg-white dark:bg-dark-800 rounded-lg p-6 shadow-lg border border-gray-200 dark:border-dark-600">
            <h2 className="text-xl font-semibold text-gray-900 dark:text-white mb-6">
              Sfide e Soluzioni
            </h2>
            
            {formData.challenges.length > 0 && (
              <div className="space-y-2 mb-4">
                {formData.challenges.map((challenge, index) => (
                  <div key={index} className="flex items-start gap-2 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <i className="fas fa-lightbulb text-yellow-500 mt-1"></i>
                    <span className="flex-1">{challenge}</span>
                    <button
                      type="button"
                      onClick={() => removeChallenge(index)}
                      className="text-red-600 hover:text-red-700"
                    >
                      <i className="fas fa-times"></i>
                    </button>
                  </div>
                ))}
              </div>
            )}
            
            <div className="flex gap-2">
              <textarea
                value={newChallenge}
                onChange={(e) => setNewChallenge(e.target.value)}
                className="form-input flex-1"
                placeholder="Descrivi una sfida e come l'hai risolta..."
                rows={2}
              />
              <button
                type="button"
                onClick={addChallenge}
                className="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 self-start"
              >
                <i className="fas fa-plus"></i>
              </button>
            </div>
          </div>

          {/* Results */}
          <div className="bg-white dark:bg-dark-800 rounded-lg p-6 shadow-lg border border-gray-200 dark:border-dark-600">
            <h2 className="text-xl font-semibold text-gray-900 dark:text-white mb-6">
              Risultati Ottenuti
            </h2>
            
            {formData.results.length > 0 && (
              <div className="space-y-2 mb-4">
                {formData.results.map((result, index) => (
                  <div key={index} className="flex items-center gap-2 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <i className="fas fa-trophy text-orange-500"></i>
                    <span className="flex-1">{result}</span>
                    <button
                      type="button"
                      onClick={() => removeResult(index)}
                      className="text-red-600 hover:text-red-700"
                    >
                      <i className="fas fa-times"></i>
                    </button>
                  </div>
                ))}
              </div>
            )}
            
            <div className="flex gap-2">
              <input
                type="text"
                value={newResult}
                onChange={(e) => setNewResult(e.target.value)}
                className="form-input flex-1"
                placeholder="Aggiungi un risultato ottenuto..."
                onKeyPress={(e) => e.key === 'Enter' && (e.preventDefault(), addResult())}
              />
              <button
                type="button"
                onClick={addResult}
                className="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700"
              >
                <i className="fas fa-plus"></i>
              </button>
            </div>
          </div>

          {/* Actions */}
          <div className="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
            <Link
              to="/admin/projects"
              className="btn-secondary"
            >
              <i className="fas fa-arrow-left mr-2"></i>
              Annulla
            </Link>

            <button
              type="submit"
              disabled={loading}
              className="btn-primary disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {loading ? (
                <>
                  <div className="spinner mr-2"></div>
                  {isEditMode ? 'Aggiornamento...' : 'Creazione...'}
                </>
              ) : (
                <>
                  <i className={`fas ${isEditMode ? 'fa-save' : 'fa-plus'} mr-2`}></i>
                  {isEditMode ? 'Aggiorna Progetto' : 'Crea Progetto'}
                </>
              )}
            </button>
          </div>

        </form>

      </div>
    </div>
  )
}

export default ProjectForm 
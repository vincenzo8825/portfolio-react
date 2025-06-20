import { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import { projectsService } from '../services/projects'
import { useInView } from 'react-intersection-observer'
import { useLanguage } from '../context/LanguageContext'
import Hero from '../components/sections/Hero'
import TechStack from '../components/sections/TechStack'

const Home = () => {
  const { t } = useLanguage()
  const [visibleStats, setVisibleStats] = useState(false)
  const [featuredProjects, setFeaturedProjects] = useState([])
  const [loading, setLoading] = useState(true)
  const [statsRef, statsInView] = useInView({
    threshold: 0.3,
    triggerOnce: true
  })

  useEffect(() => {
    if (statsInView) {
      setVisibleStats(true)
    }
  }, [statsInView])

  useEffect(() => {
    loadFeaturedProjects()
  }, [])

  const loadFeaturedProjects = async () => {
    try {
      setLoading(true)
      const projects = await projectsService.getAll()
      // Filtra solo progetti featured e prendi i primi 3
      const featured = projects.filter(project => project.featured).slice(0, 3)
      setFeaturedProjects(featured)
    } catch (error) {
      console.error('Error loading featured projects:', error)
      setFeaturedProjects([]) // Fallback a array vuoto
    } finally {
      setLoading(false)
    }
  }

  const stats = [
    { label: t('projectsCompleted'), value: "20", icon: "fas fa-check-circle", color: "from-green-500 to-emerald-500" },
    { label: t('yearsOfPassion'), value: "2", icon: "fas fa-calendar", color: "from-blue-500 to-cyan-500" },
    { label: t('technologies'), value: "15+", icon: "fas fa-code", color: "from-orange-500 to-red-500" }
  ]

  const CountUpNumber = ({ end, duration = 2000 }) => {
    const [count, setCount] = useState(0)

    useEffect(() => {
      if (!visibleStats) return

      let startValue = 0
      const endValue = parseInt(end.replace(/\D/g, ''))
      const increment = endValue / (duration / 10)

      const timer = setInterval(() => {
        startValue += increment
        if (startValue >= endValue) {
          setCount(endValue)
          clearInterval(timer)
        } else {
          setCount(Math.floor(startValue))
        }
      }, 10)

      return () => clearInterval(timer)
    }, [visibleStats, end, duration])

    return <span>{count}{end.replace(/\d/g, '')}</span>
  }

  return (
    <div className="min-h-screen">
      {/* Hero Section - Full Screen */}
      <Hero />
      
      {/* Stats Section Premium */}
      <section className="py-20 bg-gradient-to-br from-gray-50 via-white to-gray-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 relative overflow-hidden">
        {/* Animated background */}
        <div className="absolute inset-0">
          <div className="absolute top-20 left-10 w-72 h-72 bg-gradient-to-r from-blue-400/10 to-purple-400/10 rounded-full blur-3xl animate-float"></div>
          <div className="absolute bottom-20 right-10 w-96 h-96 bg-gradient-to-r from-green-400/10 to-cyan-400/10 rounded-full blur-3xl animate-float-delay"></div>
        </div>

        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
          <div className="text-center mb-16">
            <h2 className="text-4xl lg:text-5xl font-bold mb-6">
              <span className="bg-gradient-to-r from-primary-600 via-accent-500 to-pink-500 bg-clip-text text-transparent">
                {t('resultsThatSpeak')}
              </span>
            </h2>
            <p className="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
              {t('resultsThatSpeakDesc')}
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8" ref={statsRef}>
            {stats.map((stat, index) => (
              <div
                key={index}
                className="group relative bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-3xl p-8 text-center shadow-xl shadow-black/5 dark:shadow-black/20 border border-white/20 dark:border-slate-700/50 hover:scale-105 transition-all duration-500"
                style={{ animationDelay: `${index * 100}ms` }}
              >
                {/* Icon with gradient */}
                <div className={`w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-r ${stat.color} flex items-center justify-center text-white shadow-lg shadow-black/20 group-hover:scale-110 transition-transform duration-300`}>
                  <i className={`${stat.icon} text-2xl`}></i>
                </div>

                {/* Number */}
                <div className="text-4xl lg:text-5xl font-bold mb-2 bg-gradient-to-r from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                  <CountUpNumber end={stat.value} />
                </div>

                {/* Label */}
                <p className="text-gray-600 dark:text-gray-400 font-medium">
                  {stat.label}
                </p>

                {/* Glow effect */}
                <div className={`absolute inset-0 rounded-3xl bg-gradient-to-r ${stat.color} opacity-0 group-hover:opacity-10 transition-opacity duration-300 blur-xl`}></div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Featured Projects Section */}
      <section className="py-20 bg-white dark:bg-slate-900 relative overflow-hidden">
        <div className="absolute inset-0">
          <div className="absolute top-40 left-20 w-80 h-80 bg-gradient-to-r from-purple-400/5 to-pink-400/5 rounded-full blur-3xl"></div>
          <div className="absolute bottom-40 right-20 w-96 h-96 bg-gradient-to-r from-blue-400/5 to-cyan-400/5 rounded-full blur-3xl"></div>
        </div>

        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
          <div className="text-center mb-16">
            <h2 className="text-4xl lg:text-5xl font-bold mb-6">
              <span className="bg-gradient-to-r from-primary-600 via-accent-500 to-pink-500 bg-clip-text text-transparent">
                {t('featuredProjects')}
              </span>
            </h2>
            <p className="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
              {t('featuredProjectsDesc')}
            </p>
          </div>

          {loading ? (
            /* Loading State */
            <div className="flex justify-center items-center py-20">
              <div className="spinner mr-4"></div>
              <span className="text-gray-600 dark:text-gray-400">{t('loadingProjects')}</span>
            </div>
          ) : featuredProjects.length > 0 ? (
            /* Projects Grid */
            <div className="grid lg:grid-cols-3 gap-8">
              {featuredProjects.map((project) => (
                <div
                  key={project.id}
                  className="group relative bg-white dark:bg-slate-800 rounded-3xl overflow-hidden shadow-2xl shadow-black/10 dark:shadow-black/30 border border-gray-100 dark:border-slate-700 hover:scale-105 transition-all duration-500 flex flex-col h-full"
                >
                  {/* Project Image */}
                  <div className="relative h-48 overflow-hidden">
                    {project.image_url ? (
                      <img
                        src={project.image_url || '/placeholder-image.jpg'}
                      onError={(e) => {
                        e.target.src = 'https://via.placeholder.com/600x400/3B82F6/ffffff?text=' + encodeURIComponent(project.title)
                      }}
                        alt={project.title}
                        className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                      />
                    ) : (
                      <div className="w-full h-full bg-gradient-to-br from-primary-100 to-accent-100 dark:from-primary-900/20 dark:to-accent-900/20 flex items-center justify-center">
                        <i className="fas fa-image text-4xl text-primary-400 dark:text-primary-600"></i>
                      </div>
                    )}
                    <div className="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    
                    {/* Status Badge */}
                    <div className={`absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-medium ${
                      project.status === 'completed' ? 'bg-green-500/20 text-green-400 border border-green-500/30' :
                      project.status === 'in-progress' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' :
                      'bg-orange-500/20 text-orange-400 border border-orange-500/30'
                    } backdrop-blur-sm`}>
                      {project.status === 'completed' ? t('completedStatus') : 
                       project.status === 'in-progress' ? t('inProgressStatus') : t('pausedStatus')}
                    </div>

                    {/* Featured Badge */}
                    <div className="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-medium bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 backdrop-blur-sm flex items-center">
                      <i className="fas fa-star mr-1"></i>
                      {t('featured')}
                    </div>

                    {/* Year */}
                    {project.project_date && (
                      <div className="absolute bottom-4 left-4 text-white/80 text-sm font-medium">
                        {new Date(project.project_date).getFullYear()}
                      </div>
                    )}
                  </div>

                  {/* Project Content */}
                  <div className="p-6 flex flex-col h-full">
                    <div className="flex-grow">
                      <h3 className="text-xl font-bold mb-3 text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-300">
                        {project.title}
                      </h3>
                      
                      <p className="text-gray-600 dark:text-gray-300 mb-4 leading-relaxed line-clamp-3">
                        {project.description}
                      </p>

                      {/* Tech Stack */}
                      {project.technologies && project.technologies.length > 0 && (
                        <div className="flex flex-wrap gap-2 mb-4">
                          {project.technologies.slice(0, 4).map((tech, techIndex) => (
                            <span
                              key={techIndex}
                              className="px-3 py-1 bg-gradient-to-r from-primary-500/10 to-accent-500/10 text-primary-700 dark:text-primary-300 text-xs rounded-full border border-primary-200/30 dark:border-primary-700/30"
                            >
                              {tech}
                            </span>
                          ))}
                          {project.technologies.length > 4 && (
                            <span className="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs rounded-full">
                              +{project.technologies.length - 4}
                            </span>
                          )}
                        </div>
                      )}
                    </div>

                    {/* Action Buttons - Fixed at bottom */}
                    <div className="flex gap-3 mt-auto">
                      <Link
                        to={`/projects/${project.slug || project.id}`}
                        className="flex-1 py-3 bg-gradient-to-r from-primary-500 to-accent-500 text-white rounded-2xl font-medium hover:from-primary-600 hover:to-accent-600 transition-all duration-300 shadow-lg shadow-primary-500/25 hover:shadow-xl hover:shadow-primary-500/30 text-center flex items-center justify-center cursor-pointer relative z-10"
                      >
                        {t('details')}
                        <i className="fas fa-info-circle ml-2"></i>
                      </Link>
                      {project.demo_url && (
                        <a
                          href={project.demo_url}
                          target="_blank"
                          rel="noopener noreferrer"
                          className="px-4 py-3 bg-white dark:bg-slate-700 text-gray-700 dark:text-gray-300 rounded-2xl font-medium hover:bg-gray-50 dark:hover:bg-slate-600 transition-all duration-300 shadow-lg border border-gray-200 dark:border-slate-600 flex items-center justify-center"
                          title="Vedi Demo"
                        >
                          <i className="fas fa-external-link-alt"></i>
                        </a>
                      )}
                    </div>
                  </div>

                  {/* Hover glow effect */}
                  <div className="absolute inset-0 rounded-3xl bg-gradient-to-r from-primary-500/5 to-accent-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                </div>
              ))}
            </div>
          ) : (
            /* Empty State */
            <div className="text-center py-20">
              <div className="w-24 h-24 mx-auto mb-8 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                <i className="fas fa-folder-open text-3xl text-gray-400"></i>
              </div>
              <h3 className="text-2xl font-semibold text-gray-900 dark:text-white mb-4">
                {t('noFeaturedProjects')}
              </h3>
              <p className="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                {t('noFeaturedProjectsDesc')}
              </p>
            </div>
          )}

          {/* View All Projects Button */}
          <div className="text-center mt-12">
            <Link
              to="/projects"
              className="inline-flex items-center px-8 py-4 bg-gradient-to-r from-gray-900 to-gray-800 dark:from-white dark:to-gray-100 text-white dark:text-gray-900 rounded-2xl font-medium hover:scale-105 transition-all duration-300 shadow-xl shadow-black/20 dark:shadow-white/20"
            >
              {t('viewAllProjects')}
              <i className="fas fa-arrow-right ml-3"></i>
            </Link>
          </div>
        </div>
      </section>

      {/* Tech Stack Section */}
      <TechStack />

      {/* CTA Section */}
      <section className="py-20 bg-gradient-to-r from-primary-600 via-accent-500 to-pink-500 relative overflow-hidden">
        {/* Animated background patterns */}
        <div className="absolute inset-0">
          <div className="absolute top-0 left-0 w-full h-full opacity-20"></div>
        </div>

        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">
          <h2 className="text-4xl lg:text-6xl font-bold text-white mb-6">
            {t('projectInMind')}
          </h2>
          <p className="text-xl text-white/90 mb-8 leading-relaxed">
            {t('projectInMindDesc')}
          </p>
          
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <Link
              to="/contact"
              className="group inline-flex items-center px-8 py-4 bg-white text-primary-600 rounded-2xl font-bold hover:bg-gray-50 transition-all duration-300 shadow-2xl shadow-black/20 hover:scale-105"
            >
              {t('startNow')}
              <i className="fas fa-rocket ml-3 group-hover:translate-x-1 transition-transform duration-300"></i>
            </Link>
            
            <Link
              to="/projects"
              className="group inline-flex items-center px-8 py-4 bg-white/10 text-white border-2 border-white/30 rounded-2xl font-bold hover:bg-white/20 transition-all duration-300 backdrop-blur-sm hover:scale-105"
            >
              {t('viewPortfolio')}
              <i className="fas fa-eye ml-3 group-hover:translate-x-1 transition-transform duration-300"></i>
            </Link>
          </div>
        </div>
      </section>
    </div>
  )
}

export default Home 
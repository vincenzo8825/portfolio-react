import { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import { useLanguage } from '../../context/LanguageContext'

const Hero = () => {
  const { t } = useLanguage()
  const [typingText, setTypingText] = useState('')
  const [currentIndex, setCurrentIndex] = useState(0)
  const [isVisible, setIsVisible] = useState(false)
  
  // Contatori animati per le statistiche
  const [projectsCount, setProjectsCount] = useState(0)
  const [bootcampsCount, setBootcampsCount] = useState(0)
  const [techCount, setTechCount] = useState(0)
  const [showCounters, setShowCounters] = useState(false)
  
  const specializations = [
    'Angular & React',
    'JavaScript & TypeScript',
    'Laravel & PHP',
    'MySQL & Database',
    'SASS & Tailwind CSS',
    
  ]

  useEffect(() => {
    setIsVisible(true)
    
    // Animazione contatori che parte all'apertura del sito
    const startCounters = () => {
      // Mostra i contatori quando inizia l'animazione
      setShowCounters(true)
      
      // Animazione progetti (target: 10)
      let projectsCurrent = 0
      const projectsTimer = setInterval(() => {
        projectsCurrent += 1
        setProjectsCount(projectsCurrent)
        if (projectsCurrent >= 10) {
          clearInterval(projectsTimer)
        }
      }, 150)

      // Animazione bootcamps (target: 3) - parte dopo 500ms
      setTimeout(() => {
        let bootcampsCurrent = 0
        const bootcampsTimer = setInterval(() => {
          bootcampsCurrent += 1
          setBootcampsCount(bootcampsCurrent)
          if (bootcampsCurrent >= 3) {
            clearInterval(bootcampsTimer)
          }
        }, 400)
      }, 500)

      // Animazione tecnologie (target: 12) - parte dopo 1000ms
      setTimeout(() => {
        let techCurrent = 0
        const techTimer = setInterval(() => {
          techCurrent += 1
          setTechCount(techCurrent)
          if (techCurrent >= 12) {
            clearInterval(techTimer)
          }
        }, 120)
      }, 1000)
    }

    // Inizia l'animazione dopo 2 secondi dall'apertura del sito
    setTimeout(startCounters, 2000)
  }, [])

  // Typing animation effect
  useEffect(() => {
    const currentSpecialization = specializations[currentIndex]
    let charIndex = 0
    
    const typingInterval = setInterval(() => {
      if (charIndex <= currentSpecialization.length) {
        setTypingText(currentSpecialization.substring(0, charIndex))
        charIndex++
      } else {
        clearInterval(typingInterval)
        setTimeout(() => {
          setCurrentIndex((prev) => (prev + 1) % specializations.length)
        }, 2000)
      }
    }, 100)

    return () => clearInterval(typingInterval)
  }, [currentIndex])

  // Generate particles con colori dinamici
  const generateParticles = () => {
    return Array.from({ length: 80 }, (_, i) => (
      <div
        key={i}
        className="particle"
        style={{
          left: `${Math.random() * 100}%`,
          animationDelay: `${Math.random() * 15}s`,
          animationDuration: `${15 + Math.random() * 10}s`,
          '--particle-color': i % 3 === 0 ? '#3b82f6' : i % 3 === 1 ? '#8b5cf6' : '#ec4899'
        }}
      />
    ))
  }

  // Generate floating orbs
  const generateOrbs = () => {
    return Array.from({ length: 12 }, (_, i) => (
      <div
        key={`orb-${i}`}
        className={`
          absolute rounded-full opacity-20 animate-float
          ${i % 4 === 0 ? 'w-32 h-32 bg-blue-500' : 
            i % 4 === 1 ? 'w-24 h-24 bg-purple-500' : 
            i % 4 === 2 ? 'w-20 h-20 bg-pink-500' : 'w-28 h-28 bg-indigo-500'}
        `}
        style={{
          top: `${Math.random() * 100}%`,
          left: `${Math.random() * 100}%`,
          animationDelay: `${Math.random() * 5}s`,
          animationDuration: `${8 + Math.random() * 4}s`
        }}
      />
    ))
  }

  return (
    <section className="relative min-h-screen flex items-center justify-center overflow-hidden">
      {/* Advanced Background Elements */}
      <div className="absolute inset-0">
        {/* Multi-layer gradient mesh */}
        <div className="absolute inset-0 bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900"></div>
        <div className="absolute inset-0 bg-gradient-to-tr from-transparent via-primary-500/5 to-accent-500/5"></div>
        <div className="absolute inset-0 bg-gradient-to-bl from-pink-500/5 via-transparent to-blue-500/5"></div>
        
        {/* Dynamic grid overlay */}
        <div className="absolute inset-0 opacity-30">
          <div 
            className="h-full w-full"
            style={{
              backgroundImage: `
                radial-gradient(circle at 25% 25%, rgba(59, 130, 246, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(139, 92, 246, 0.3) 0%, transparent 50%),
                linear-gradient(rgba(59, 130, 246, 0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(59, 130, 246, 0.1) 1px, transparent 1px)
              `,
              backgroundSize: '100% 100%, 100% 100%, 80px 80px, 80px 80px'
            }}
          />
        </div>
        
        {/* Floating orbs */}
        {generateOrbs()}
        
        {/* Enhanced particles */}
        <div className="particles">
          {generateParticles()}
        </div>

        {/* Glow effects */}
        <div className="absolute top-20 left-20 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl animate-pulse"></div>
        <div className="absolute bottom-20 right-20 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl animate-pulse" style={{ animationDelay: '2s' }}></div>
        <div className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-pink-500/5 rounded-full blur-3xl animate-pulse" style={{ animationDelay: '4s' }}></div>
      </div>

      {/* Main Content */}
      <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
          
          {/* Left Side - Enhanced Text Content */}
          <div className={`text-center lg:text-left transition-all duration-1000 ${isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'}`}>
            
            {/* Premium Badge */}
            <div className="inline-flex items-center px-6 py-3 rounded-2xl bg-white/60 dark:bg-slate-800/60 backdrop-blur-xl border border-white/20 dark:border-slate-700/30 text-primary-700 dark:text-primary-300 text-sm font-semibold mb-8 shadow-lg hover:shadow-xl transition-all duration-300 group">
              <div className="w-2 h-2 bg-green-500 rounded-full mr-3 animate-pulse"></div>
              <span className="mr-2" style={{ color: '#1e40af !important', display: 'inline-block' }}>
                <i className="fas fa-code group-hover:scale-110 transition-transform duration-300" style={{ color: 'inherit !important' }}></i>
              </span>
              {/* Icona BLU FORZATA */}
              {t('heroBadge')}
              <div className="ml-2 text-xs bg-primary-100 dark:bg-primary-900/50 px-2 py-1 rounded-lg">2025</div>
            </div>

            {/* Enhanced Main Title */}
            <h1 className="text-5xl sm:text-6xl lg:text-7xl font-black text-gray-900 dark:text-white mb-8 leading-tight">
              <span className="block" style={{ animationDelay: '0.2s' }}>
                {t('heroTitle').split(' ').slice(0, 2).join(' ')}
              </span>
              <span className="block bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent animate-gradient bg-300% mt-2" style={{ animationDelay: '0.4s' }}>
                {t('heroTitle').split(' ').slice(2).join(' ')}
              </span>
            </h1>

            {/* Enhanced Subtitle */}
            <p className="text-xl lg:text-2xl text-gray-600 dark:text-gray-300 mb-10 max-w-2xl mx-auto lg:mx-0 leading-relaxed" style={{ animationDelay: '0.6s' }}>
              {t('heroSubtitle')}
            </p>

            {/* Premium Typing Animation */}
            <div className="mb-12 p-6 rounded-2xl bg-white/40 dark:bg-slate-800/40 backdrop-blur-xl border border-white/20 dark:border-slate-700/30 shadow-xl" style={{ animationDelay: '0.8s' }}>
              <div className="flex items-center justify-center lg:justify-start mb-4">
                <div className="flex space-x-2 mr-4">
                  <div className="w-3 h-3 bg-red-500 rounded-full"></div>
                  <div className="w-3 h-3 bg-yellow-500 rounded-full"></div>
                  <div className="w-3 h-3 bg-green-500 rounded-full"></div>
                </div>
                <span className="text-sm text-gray-500 dark:text-gray-400 font-mono">portfolio.js</span>
              </div>
              <div className="text-left font-mono text-lg">
                <span className="text-gray-700 dark:text-gray-300">const specialist = "</span>
                <span className="text-blue-600 dark:text-blue-400 font-bold">
                  {typingText}
                </span>
                <span className="text-gray-700 dark:text-gray-300">"</span>
                <span className="animate-pulse text-primary-500 ml-1">|</span>
              </div>
            </div>

            {/* Premium CTA Buttons */}
            <div className="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start mb-16" style={{ animationDelay: '1s' }}>
              <Link
                to="/projects"
                className="group relative overflow-hidden px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-2xl text-center"
              >
                <div className="absolute inset-0 bg-gradient-to-r from-blue-400 to-purple-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div className="relative flex items-center justify-center">
                  <i className="fas fa-rocket mr-3 group-hover:scale-110 transition-transform duration-300"></i>
                  {t('exploreWork')}
                </div>
              </Link>
              <Link
                to="/contact"
                className="group relative overflow-hidden px-8 py-4 bg-white/60 dark:bg-slate-800/60 backdrop-blur-xl border-2 border-primary-600 text-primary-600 dark:text-primary-400 hover:bg-primary-600 hover:text-white font-bold rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-2xl text-center"
              >
                <div className="absolute inset-0 bg-gradient-to-r from-primary-600 to-accent-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div className="relative flex items-center justify-center">
                  <i className="fas fa-paper-plane mr-3 group-hover:scale-110 transition-transform duration-300"></i>
                  {t('contactMe')}
                </div>
              </Link>
            </div>

            {/* Enhanced Stats Cards con Animazione */}
            <div className="grid grid-cols-3 gap-4" style={{ animationDelay: '1.2s' }}>
              {[
                { count: projectsCount, target: 10, label: 'Progetti', icon: 'fas fa-project-diagram', colors: { bg: 'from-blue-500 to-blue-600', text: 'from-blue-600 to-blue-700' } },
                { count: bootcampsCount, target: 3, label: 'Bootcamp', icon: 'fas fa-graduation-cap', colors: { bg: 'from-purple-500 to-purple-600', text: 'from-purple-600 to-purple-700' } },
                { count: techCount, target: 12, label: 'Tecnologie', icon: 'fas fa-code', colors: { bg: 'from-pink-500 to-pink-600', text: 'from-pink-600 to-pink-700' } }
              ].map((stat, index) => (
                <div key={index} className="group text-center p-6 bg-white/70 dark:bg-slate-800/70 backdrop-blur-xl rounded-2xl border border-white/30 dark:border-slate-700/40 shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105 cursor-pointer">
                  <div className={`w-14 h-14 rounded-xl bg-gradient-to-r ${stat.colors.bg} flex items-center justify-center text-white mb-4 mx-auto group-hover:scale-110 transition-transform duration-300 shadow-lg`}>
                    <i className={`${stat.icon} text-xl`}></i>
                  </div>
                  <div className={`text-3xl font-black bg-gradient-to-r ${stat.colors.text} bg-clip-text text-transparent mb-2 transition-opacity duration-300 ${showCounters ? 'opacity-100' : 'opacity-0'}`}>
                    {showCounters ? `${stat.count}+` : '0+'}
                  </div>
                  <div className="text-sm text-gray-700 dark:text-gray-300 font-semibold">
                    {stat.label}
                  </div>
                </div>
              ))}
            </div>
          </div>

          {/* Right Side - Interactive Premium Elements */}
          <div className={`flex flex-col space-y-8 transition-all duration-1000 delay-300 ${isVisible ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-10'}`}>
            
            {/* Enhanced Profile Card */}
            <div className="group relative p-8 bg-white/60 dark:bg-slate-800/60 backdrop-blur-xl rounded-3xl border border-white/20 dark:border-slate-700/30 shadow-2xl hover:shadow-3xl transition-all duration-500 hover:scale-105 overflow-hidden">
              {/* Background glow effect */}
              <div className="absolute inset-0 bg-gradient-to-br from-blue-500/10 via-purple-500/10 to-pink-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
              
              <div className="relative">
                <div className="flex items-center space-x-6 mb-6">
                  <div className="relative">
                    <div className="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 p-1 shadow-xl">
                      <div className="w-full h-full rounded-2xl bg-white dark:bg-slate-800 flex items-center justify-center">
                        <span className="text-2xl font-black bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                          VR
                        </span>
                      </div>
                    </div>
                    {/* Pulse ring */}
                    <div className="absolute -inset-2 bg-gradient-to-r from-blue-500 to-purple-500 rounded-2xl opacity-20 animate-ping"></div>
                    {/* Status indicator */}
                    <div className="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 rounded-full border-4 border-white dark:border-slate-800 flex items-center justify-center shadow-lg">
                      <div className="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                    </div>
                  </div>
                  
                  <div className="flex-1">
                    <h3 className="text-2xl font-black text-gray-900 dark:text-white mb-1">
                      Vincenzo Rocca
                    </h3>
                    <p className="text-lg bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent font-bold mb-2">
                      {t('fullStackDeveloper')}
                    </p>
                    <div className="flex items-center text-sm text-gray-600 dark:text-gray-400">
                      <div className="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                                              {t('availableForProjects')}
                    </div>
                  </div>
                </div>
                
                {/* Enhanced social buttons */}
                <div className="grid grid-cols-2 gap-4">
                  <a
                    href="https://github.com/vincenzo8825"
                    target="_blank"
                    rel="noopener noreferrer"
                    className="group/btn flex items-center justify-center py-4 px-6 bg-gradient-to-r from-gray-800 to-gray-900 hover:from-gray-700 hover:to-gray-800 text-white rounded-xl transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-xl"
                  >
                    <i className="fab fa-github text-xl mr-3 group-hover/btn:scale-110 transition-transform duration-300"></i>
                    <span className="font-semibold">GitHub</span>
                  </a>
                                    <a 
                    href="https://www.linkedin.com/in/webdevfullstack/"
                    target="_blank"
                    rel="noopener noreferrer"
                    className="group/btn flex items-center justify-center py-4 px-6 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white rounded-xl transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-xl"
                  >
                    <i className="fab fa-linkedin text-xl mr-3 group-hover/btn:scale-110 transition-transform duration-300"></i>
                    <span className="font-semibold">LinkedIn</span>
                  </a>
                </div>
              </div>
            </div>

            {/* Enhanced Code Window */}
            <div className="group relative bg-white/60 dark:bg-slate-800/60 backdrop-blur-xl rounded-3xl border border-white/20 dark:border-slate-700/30 shadow-2xl hover:shadow-3xl transition-all duration-500 hover:scale-105 overflow-hidden">
              {/* Background glow effect */}
              <div className="absolute inset-0 bg-gradient-to-br from-green-500/10 via-blue-500/10 to-purple-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
              
              <div className="relative">
                {/* Enhanced window header */}
                <div className="flex items-center justify-between p-6 border-b border-gray-200/50 dark:border-slate-700/50">
                  <div className="flex space-x-3">
                    <div className="w-4 h-4 bg-red-500 rounded-full shadow-lg hover:scale-110 transition-transform duration-300 cursor-pointer"></div>
                    <div className="w-4 h-4 bg-yellow-500 rounded-full shadow-lg hover:scale-110 transition-transform duration-300 cursor-pointer"></div>
                    <div className="w-4 h-4 bg-green-500 rounded-full shadow-lg hover:scale-110 transition-transform duration-300 cursor-pointer"></div>
                  </div>
                  <div className="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400 font-mono">
                    <i className="fab fa-js-square text-yellow-500"></i>
                    <span>vincenzo.js</span>
                  </div>
                  <div className="w-6"></div>
                </div>
                
                {/* Enhanced code content */}
                <div className="p-6 font-mono text-sm leading-relaxed">
                  <div className="space-y-2">
                    <div><span className="text-purple-600 dark:text-purple-400">const</span> <span className="text-blue-600 dark:text-blue-400">developer</span> <span className="text-gray-600 dark:text-gray-400">=</span> {`{`}</div>
                    <div className="pl-4"><span className="text-green-600 dark:text-green-400">name</span><span className="text-gray-600 dark:text-gray-400">:</span> <span className="text-orange-600 dark:text-orange-400">'Vincenzo Rocca'</span>,</div>
                    <div className="pl-4"><span className="text-green-600 dark:text-green-400">skills</span><span className="text-gray-600 dark:text-gray-400">:</span> [<span className="text-orange-600 dark:text-orange-400">'React'</span>, <span className="text-orange-600 dark:text-orange-400">'Laravel'</span>],</div>
                    <div className="pl-4"><span className="text-green-600 dark:text-green-400">passion</span><span className="text-gray-600 dark:text-gray-400">:</span> <span className="text-orange-600 dark:text-orange-400">'Clean Code'</span>,</div>
                    <div className="pl-4"><span className="text-green-600 dark:text-green-400">status</span><span className="text-gray-600 dark:text-gray-400">:</span> <span className="text-orange-600 dark:text-orange-400">'Available for hire'</span></div>
                    <div>{`}`}</div>
                  </div>
                </div>
              </div>
            </div>

            {/* New: Skills radar chart visualization */}
            <div className="group relative p-8 bg-white/60 dark:bg-slate-800/60 backdrop-blur-xl rounded-3xl border border-white/20 dark:border-slate-700/30 shadow-2xl hover:shadow-3xl transition-all duration-500 hover:scale-105">
              <h4 className="text-xl font-bold text-gray-900 dark:text-white mb-6 text-center">{t('skillsOverview')}</h4>
              <div className="grid grid-cols-2 gap-6">
                {[
                  { skill: 'Frontend', level: 90, color: 'blue' },
                  { skill: 'Backend', level: 85, color: 'green' },
                  { skill: 'Database', level: 80, color: 'purple' },
                  { skill: 'UI/UX Design', level: 75, color: 'orange' }
                ].map((item, index) => (
                  <div key={index} className="text-center">
                    <div className={`w-16 h-16 mx-auto mb-3 relative rounded-full bg-gradient-to-r from-${item.color}-500 to-${item.color}-600 flex items-center justify-center shadow-lg`}>
                      <div className="text-white font-bold text-lg">
                        {item.level}%
                      </div>
                      <div className={`absolute inset-0 rounded-full border-4 border-${item.color}-300 opacity-50 animate-pulse`}></div>
                    </div>
                    <div className="text-sm font-semibold text-gray-700 dark:text-gray-300">
                      {item.skill}
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Enhanced Scroll indicator with auto scroll */}
      <div className="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-center z-20">
        <button
          onClick={() => {
            const nextSection = document.querySelector('#tech-stack') || document.querySelector('section:nth-of-type(2)')
            if (nextSection) {
              nextSection.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
              })
            } else {
              window.scrollBy({ 
                top: window.innerHeight, 
                behavior: 'smooth' 
              })
            }
          }}
          className="group relative flex flex-col items-center justify-center p-4 rounded-2xl bg-white/60 dark:bg-slate-800/60 backdrop-blur-xl border border-white/20 dark:border-slate-700/30 shadow-lg hover:shadow-2xl transition-all duration-300 hover:scale-110 hover:bg-primary-500/20"
        >
          <div className="animate-bounce group-hover:animate-none transition-all duration-300">
            <i className="fas fa-chevron-down text-2xl text-gray-600 dark:text-gray-300 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-300"></i>
          </div>
          <p className="text-xs text-gray-500 dark:text-gray-400 mt-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-300 font-medium">
            {t('scrollToExplore')}
          </p>
          
          {/* Glow effect on hover */}
          <div className="absolute inset-0 rounded-2xl bg-gradient-to-r from-primary-500/20 to-accent-500/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
        </button>
      </div>
    </section>
  )
}

export default Hero 
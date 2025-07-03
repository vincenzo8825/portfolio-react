import { useState, useEffect, useRef } from 'react'
import { useInView } from 'react-intersection-observer'
import { useLanguage } from '../../context/LanguageContext'

const TechStack = () => {
  const { t } = useLanguage()
  const [activeTab, setActiveTab] = useState('frontend')
  const sectionRef = useRef(null)
  
  // Configurazione ottimizzata per mobile e desktop
  const [ref, inView] = useInView({
    threshold: [0.1, 0.2, 0.3], 
    rootMargin: '-50px 0px -50px 0px', 
    triggerOnce: true
  })

  // Counter states for animated numbers
  const [techCount, setTechCount] = useState(0)
  const [avgSkillCount, setAvgSkillCount] = useState(0)
  const [frontendCount, setFrontendCount] = useState(0)
  const [backendCount, setBackendCount] = useState(0)
  const [toolsCount, setToolsCount] = useState(0)
  const [showCounters, setShowCounters] = useState(false)
  const [animationStarted, setAnimationStarted] = useState(false)

  // Fallback scroll-based detection ottimizzato per mobile
  useEffect(() => {
    const handleScroll = () => {
      if (animationStarted) return // Se già animato, non rifare

      const element = sectionRef.current || ref.current
      if (!element) return

      const rect = element.getBoundingClientRect()
      const windowHeight = window.innerHeight || document.documentElement.clientHeight
      
      // Controllo più ampio per mobile - se qualsiasi parte della sezione è visibile
      const isVisible = rect.top < windowHeight * 0.9 && rect.bottom > windowHeight * 0.1
      
      if (isVisible && !animationStarted) {
        startCounterAnimation()
      }
    }

    // Controllo immediato al mount
    setTimeout(handleScroll, 100)
    
    // Aggiunge listener solo se l'animazione non è ancora partita
    if (!animationStarted) {
      window.addEventListener('scroll', handleScroll, { passive: true })
      window.addEventListener('resize', handleScroll, { passive: true })
    }

    return () => {
      window.removeEventListener('scroll', handleScroll)
      window.removeEventListener('resize', handleScroll)
    }
  }, [animationStarted, ref])

  // Funzione per iniziare l'animazione dei contatori
  const startCounterAnimation = () => {
    if (animationStarted) return // Previene doppia animazione
    
    setAnimationStarted(true)
    setShowCounters(true)
    
    const totalTech = techData.frontend.length + techData.backend.length + techData.tools.length
    const avgSkill = Math.round([...techData.frontend, ...techData.backend, ...techData.tools].reduce((acc, tech) => acc + tech.level, 0) / totalTech)
    
    // Animate counters with different delays
    setTimeout(() => {
      animateNumber(setTechCount, totalTech, 1500)
    }, 200)
    
    setTimeout(() => {
      animateNumber(setAvgSkillCount, avgSkill, 1500)
    }, 400)
    
    setTimeout(() => {
      animateNumber(setFrontendCount, techData.frontend.length, 1000)
    }, 600)
    
    setTimeout(() => {
      animateNumber(setBackendCount, techData.backend.length, 1000)
    }, 800)
    
    setTimeout(() => {
      animateNumber(setToolsCount, techData.tools.length, 1000)
    }, 1000)
  }

  // Animation logic for counters (migliorato per useInView)
  useEffect(() => {
    if (inView && !animationStarted) {
      startCounterAnimation()
    }
  }, [inView, animationStarted])

  // Helper function to animate numbers (migliorata e più robusta)
  const animateNumber = (setter, target, duration) => {
    let start = 0
    const increment = target / (duration / 16)
    let animationFrameId
    
    const animate = () => {
      start += increment
      if (start >= target) {
        setter(target)
        return
      } else {
        setter(Math.floor(start))
        animationFrameId = requestAnimationFrame(animate)
      }
    }
    
    animationFrameId = requestAnimationFrame(animate)
    
    // Cleanup e fallback
    setTimeout(() => {
      if (animationFrameId) {
        cancelAnimationFrame(animationFrameId)
      }
      setter(target)
    }, duration + 500)
  }

  const tabs = [
    {
      id: 'frontend',
      label: 'Frontend',
      icon: 'fas fa-paint-brush',
      title: 'Tecnologie Frontend',
      description: 'Creo interfacce utente responsive, intuitive e visivamente accattivanti'
    },
    {
      id: 'backend',
      label: 'Backend',
      icon: 'fas fa-server',
      title: 'Tecnologie Backend',
      description: 'Sviluppo API robuste e scalabili con architettura moderna'
    },
    {
      id: 'tools',
      label: 'Strumenti',
      icon: 'fas fa-tools',
      title: 'Strumenti e Utilities',
      description: 'Workflow moderni per sviluppo efficiente e gestione progetti'
    }
  ]

  const techData = {
    frontend: [
      { name: 'HTML5', level: 95, icon: 'devicon-html5-plain', description: 'Markup semantico, accessibilità, SEO' },
      { name: 'CSS3', level: 90, icon: 'devicon-css3-plain', description: 'Flexbox, Grid, Animazioni, Responsive' },
      { name: 'JavaScript', level: 85, icon: 'devicon-javascript-plain', description: 'ES6+, DOM, Async/Await, API' },
      { name: 'TypeScript', level: 80, icon: 'devicon-typescript-plain', description: 'Tipizzazione statica, Interfaces, Generics' },
      { name: 'React', level: 80, icon: 'devicon-react-original', description: 'Hooks, Context, Redux, Next.js' },
      { name: 'Angular', level: 80, icon: 'devicon-angularjs-plain', description: 'Components, Services, RxJS, NgRx' },
      { name: 'SASS', level: 85, icon: 'devicon-sass-original', description: 'Variables, Mixins, Nesting, Functions' },
      { name: 'Tailwind CSS', level: 90, icon: 'devicon-tailwindcss-plain', description: 'Utility-first, Responsive, Customization' },
      { name: 'Bootstrap', level: 90, icon: 'devicon-bootstrap-plain', description: 'Componenti, Grid, Personalizzazione' }
    ],
    backend: [
      { name: 'PHP', level: 85, icon: 'devicon-php-plain', description: 'OOP, Namespaces, Composer, PSR' },
      { name: 'Laravel', level: 90, icon: 'devicon-laravel-plain', description: 'Eloquent, Artisan, Blade, API Resources' },
      { name: 'MySQL', level: 80, icon: 'devicon-mysql-plain', description: 'Query optimization, Relationships, Indexing' },
      { name: 'Node.js', level: 70, icon: 'devicon-nodejs-plain', description: 'Express, NPM, Async Programming' }
    ],
    tools: [
      { name: 'Git', level: 85, icon: 'devicon-git-plain', description: 'Version control, Branching, Merging' },
      { name: 'Docker', level: 70, icon: 'devicon-docker-plain', description: 'Containerization, Docker Compose' },
      { name: 'Webpack/Vite', level: 75, icon: 'devicon-webpack-plain', description: 'Module bundling, Hot reload' },
      { name: 'VS Code', level: 95, icon: 'devicon-vscode-plain', description: 'Extensions, Debugging, IntelliSense' },
      { name: 'Figma', level: 80, icon: 'devicon-figma-plain', description: 'UI/UX Design, Prototyping, Collaboration' },
      { name: 'Canva', level: 75, icon: 'fas fa-palette', description: 'Graphic Design, Social Media, Branding' }
    ]
  }

      return (
      <section id="tech-stack" className="relative py-20 bg-gradient-to-br from-gray-50 via-white to-gray-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 overflow-hidden" ref={(el) => { sectionRef.current = el; ref(el); }}>
      {/* Enhanced Background Elements */}
      <div className="absolute inset-0">
        {/* Multi-layer gradient orbs */}
        <div className="absolute top-20 left-10 w-72 h-72 bg-gradient-to-r from-primary-400/10 to-accent-400/10 rounded-full blur-3xl animate-float"></div>
        <div className="absolute bottom-20 right-10 w-96 h-96 bg-gradient-to-r from-purple-400/10 to-pink-400/10 rounded-full blur-3xl animate-float-delay"></div>
        <div className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-gradient-to-r from-green-400/5 to-blue-400/5 rounded-full blur-3xl"></div>
      </div>

      <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Enhanced Header */}
        <div className="text-center mb-16" data-aos="fade-up">
          {/* Badge */}
          <div className="inline-flex items-center px-6 py-3 rounded-full bg-gradient-to-r from-primary-500/10 to-accent-500/10 border border-primary-200/30 dark:border-primary-700/30 text-primary-700 dark:text-primary-300 text-sm font-medium mb-8 hover:scale-105 transition-transform duration-300">
            <i className="fas fa-code mr-3 text-lg"></i>
            {t('techStack')}
          </div>

          <h2 className="text-4xl lg:text-5xl font-bold mb-6">
            <span className="bg-gradient-to-r from-primary-600 via-accent-500 to-pink-500 bg-clip-text text-transparent">
              {t('techStackTitle')}
            </span>
          </h2>
          <p className="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto leading-relaxed">
            {t('techStackSubtitle')}
          </p>
        </div>

        {/* Enhanced Tab Navigation */}
        <div className="flex flex-wrap justify-center mb-12" data-aos="fade-up" data-aos-delay="200">
          <div className="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl p-2 shadow-xl shadow-black/5 dark:shadow-black/20 border border-white/20 dark:border-slate-700/50">
            {tabs.map((tab) => (
              <button
                key={tab.id}
                onClick={() => setActiveTab(tab.id)}
                className={`
                  px-6 py-3 rounded-xl font-medium transition-all duration-300 flex items-center space-x-2 relative overflow-hidden
                  ${activeTab === tab.id
                    ? 'bg-gradient-to-r from-primary-600 to-accent-500 text-white shadow-lg shadow-primary-500/25'
                    : 'text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/20'
                  }
                `}
              >
                <i className={`${tab.icon} text-lg`}></i>
                <span>{tab.label}</span>
              </button>
            ))}
          </div>
        </div>

        {/* Enhanced Active Tab Content */}
        <div className="transition-all duration-500" data-aos="fade-up" data-aos-delay="400">
          {tabs.map((tab) => (
            activeTab === tab.id && (
              <div key={tab.id} className="text-center mb-16">
                {/* Tab Badge */}
                <div className="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-primary-500/10 to-accent-500/10 border border-primary-200/30 dark:border-primary-700/30 text-primary-700 dark:text-primary-300 text-sm font-medium mb-6">
                  <i className={`${tab.icon} mr-2`}></i>
                  {tab.label}
                </div>
                
                <h3 className="text-3xl font-bold mb-4">
                  <span className="bg-gradient-to-r from-primary-600 to-accent-500 bg-clip-text text-transparent">
                    {tab.title}
                  </span>
                </h3>
                <p className="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto leading-relaxed">
                  {tab.description}
                </p>
              </div>
            )
          ))}
        </div>

        {/* Enhanced Skills Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
          {techData[activeTab].map((tech, index) => (
            <div
              key={tech.name}
              className="group relative bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-3xl p-8 shadow-xl shadow-black/5 dark:shadow-black/20 border border-white/20 dark:border-slate-700/50 hover:scale-105 transition-all duration-500"
              data-aos="zoom-in"
              data-aos-delay={index * 100}
            >
              {/* Tech Icon */}
              <div className="text-center mb-6">
                <div className="w-20 h-20 mx-auto bg-gradient-to-br from-primary-100 to-accent-100 dark:from-primary-900/30 dark:to-accent-900/30 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                  <i className={`${tech.icon} text-4xl text-primary-600 dark:text-primary-400`}></i>
                </div>
                <h4 className="text-xl font-bold text-gray-900 dark:text-white mb-3">
                  {tech.name}
                </h4>
                <p className="text-sm text-gray-600 dark:text-gray-400 leading-relaxed mb-6">
                  {tech.description}
                </p>
              </div>

              {/* Enhanced Progress Bar */}
              <div className="relative">
                <div className="flex justify-between items-center mb-3">
                  <span className="text-sm font-medium text-gray-700 dark:text-gray-300">
                    Competenza
                  </span>
                  <span className="text-sm font-bold bg-gradient-to-r from-primary-600 to-accent-500 bg-clip-text text-transparent">
                    {tech.level}%
                  </span>
                </div>
                <div className="w-full bg-gray-200 dark:bg-slate-700 rounded-full h-3 shadow-inner">
                  <div
                    className={`
                      h-3 rounded-full bg-gradient-to-r from-primary-500 to-accent-500 transition-all duration-1000 ease-out shadow-lg
                      ${inView ? 'skill-progress animate' : 'skill-progress'}
                    `}
                    style={{ width: inView ? `${tech.level}%` : '0%' }}
                  ></div>
                </div>
              </div>

              {/* Hover glow effect */}
              <div className="absolute inset-0 rounded-3xl bg-gradient-to-r from-primary-500/5 to-accent-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            </div>
          ))}
        </div>

        {/* Enhanced Tech Stats Section */}
        <div className="mt-24 relative" data-aos="fade-up">
          {/* Background Element */}
          <div className="absolute inset-0 bg-gradient-to-r from-primary-50 to-accent-50 dark:from-primary-900/10 dark:to-accent-900/10 rounded-4xl -m-8"></div>
          
          <div className="relative bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-4xl p-12 shadow-2xl shadow-black/5 dark:shadow-black/20 border border-white/20 dark:border-slate-700/50">
            {/* Stats Header */}
                         <div className="text-center mb-12">
               <div className="inline-flex items-center px-6 py-3 rounded-full bg-gradient-to-r from-primary-500/10 to-accent-500/10 border border-primary-200/30 dark:border-primary-700/30 text-primary-700 dark:text-primary-300 text-sm font-medium mb-6">
                 <i className="fas fa-chart-line mr-3"></i>
                 {t('stackStats')}
               </div>
               <h3 className="text-3xl font-bold mb-4">
                 <span className="bg-gradient-to-r from-primary-600 to-accent-500 bg-clip-text text-transparent">
                   {t('stackNumbers')}
                 </span>
               </h3>
               <p className="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                 {t('stackOverview')}
               </p>
             </div>

            {/* Enhanced Stats Grid */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
              {/* Total Technologies */}
              <div className="group relative bg-gradient-to-br from-blue-50 to-primary-50 dark:from-blue-900/20 dark:to-primary-900/20 rounded-3xl p-8 border border-blue-200/30 dark:border-blue-700/30 hover:scale-105 transition-all duration-300">
                <div className="flex items-center justify-between mb-4">
                  <div className="p-3 bg-gradient-to-br from-primary-500 to-blue-600 rounded-2xl shadow-lg">
                    <i className="fas fa-code text-white text-xl"></i>
                  </div>
                                     <div className="text-right">
                     <div className="text-3xl font-bold bg-gradient-to-r from-primary-600 to-blue-600 bg-clip-text text-transparent">
                       {showCounters ? techCount : 0}+
                     </div>
                     <div className="text-sm text-gray-500 dark:text-gray-400">{t('technologies')}</div>
                   </div>
                </div>
                                 <p className="text-gray-600 dark:text-gray-300 text-sm">{t('completeModernStack')}</p>
               </div>

               {/* Categories */}
               <div className="group relative bg-gradient-to-br from-purple-50 to-accent-50 dark:from-purple-900/20 dark:to-accent-900/20 rounded-3xl p-8 border border-purple-200/30 dark:border-purple-700/30 hover:scale-105 transition-all duration-300">
                 <div className="flex items-center justify-between mb-4">
                   <div className="p-3 bg-gradient-to-br from-accent-500 to-purple-600 rounded-2xl shadow-lg">
                     <i className="fas fa-layer-group text-white text-xl"></i>
                   </div>
                   <div className="text-right">
                     <div className="text-3xl font-bold bg-gradient-to-r from-accent-500 to-purple-600 bg-clip-text text-transparent">
                       3
                     </div>
                      <div className="text-sm text-gray-500 dark:text-gray-400">{t('mainAreas')}</div>
                   </div>
                 </div>
                 <p className="text-gray-600 dark:text-gray-300 text-sm">{t('threeCategoriesDesc')}</p>
               </div>

               {/* Average Skill Level */}
               <div className="group relative bg-gradient-to-br from-pink-50 to-rose-50 dark:from-pink-900/20 dark:to-rose-900/20 rounded-3xl p-8 border border-pink-200/30 dark:border-pink-700/30 hover:scale-105 transition-all duration-300">
                 <div className="flex items-center justify-between mb-4">
                   <div className="p-3 bg-gradient-to-br from-pink-500 to-rose-600 rounded-2xl shadow-lg">
                     <i className="fas fa-chart-bar text-white text-xl"></i>
                   </div>
                   <div className="text-right">
                     <div className="text-3xl font-bold bg-gradient-to-r from-pink-500 to-rose-600 bg-clip-text text-transparent">
                        {showCounters ? avgSkillCount : 0}%
                     </div>
                      <div className="text-sm text-gray-500 dark:text-gray-400">{t('averageSkill')}</div>
                   </div>
                 </div>
                 <p className="text-gray-600 dark:text-gray-300 text-sm">{t('highMastery')}</p>
               </div>

               {/* Learning Mindset */}
               <div className="group relative bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-3xl p-8 border border-green-200/30 dark:border-green-700/30 hover:scale-105 transition-all duration-300">
                 <div className="flex items-center justify-between mb-4">
                   <div className="p-3 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-lg">
                     <i className="fas fa-graduation-cap text-white text-xl"></i>
                   </div>
                   <div className="text-right">
                       <div className="text-6xl font-bold bg-gradient-to-r from-green-500 to-emerald-600 bg-clip-text text-transparent">
                          ∞
                        </div>
                       <div className="text-sm text-gray-500 dark:text-gray-400">{t('continuousGrowth')}</div>
                      </div>
                  </div>
                 <p className="text-gray-600 dark:text-gray-300 text-sm">{t('alwaysLearning')}</p>
               </div>
            </div>

            {/* Additional Metrics */}
                         <div className="mt-12 pt-8 border-t border-gray-200 dark:border-slate-700">
               <div className="grid grid-cols-2 md:grid-cols-4 gap-8">
                 <div className="text-center">
                   <div className="text-2xl font-bold text-primary-600 dark:text-primary-400 mb-1">
                     {showCounters ? frontendCount : 0}
                   </div>
                   <div className="text-sm text-gray-500 dark:text-gray-400">{t('frontendTech')}</div>
                 </div>
                 <div className="text-center">
                   <div className="text-2xl font-bold text-accent-600 dark:text-accent-400 mb-1">
                     {showCounters ? backendCount : 0}
                   </div>
                   <div className="text-sm text-gray-500 dark:text-gray-400">{t('backendTech')}</div>
                 </div>
                 <div className="text-center">
                   <div className="text-2xl font-bold text-pink-600 dark:text-pink-400 mb-1">
                     {showCounters ? toolsCount : 0}
                   </div>
                   <div className="text-sm text-gray-500 dark:text-gray-400">{t('tools')}</div>
                 </div>
                 <div className="text-center">
                   <div className="text-2xl font-bold text-purple-600 dark:text-purple-400 mb-1">
                     2
                   </div>
                   <div className="text-sm text-gray-500 dark:text-gray-400">{t('yearsOfStudy')}</div>
                 </div>
               </div>
             </div>
          </div>
        </div>

      </div>
    </section>
  )
}

export default TechStack 
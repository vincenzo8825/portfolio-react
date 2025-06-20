import { useState } from 'react'
import { useInView } from 'react-intersection-observer'
import { useLanguage } from '../../context/LanguageContext'

const TechStack = () => {
  const { t } = useLanguage()
  const [activeTab, setActiveTab] = useState('frontend')
  const [ref, inView] = useInView({
    threshold: 0.3,
    triggerOnce: true
  })

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
      title: 'Strumenti e DevOps',
      description: 'Workflow moderni per sviluppo efficiente e deploy automatizzato'
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
    <section id="tech-stack" className="relative py-20 bg-gray-50 dark:bg-dark-800 overflow-hidden" ref={ref}>
      {/* Background Orbs */}
      <div className="absolute inset-0 pointer-events-none">
        <div className="absolute top-10 left-10 w-32 h-32 bg-primary-500/10 rounded-full blur-xl animate-float"></div>
        <div className="absolute top-40 right-20 w-24 h-24 bg-accent-500/10 rounded-full blur-xl animate-float" style={{ animationDelay: '2s' }}></div>
        <div className="absolute bottom-20 left-32 w-40 h-40 bg-pink-500/10 rounded-full blur-xl animate-float" style={{ animationDelay: '4s' }}></div>
      </div>

      <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Header */}
        <div className="text-center mb-16" data-aos="fade-up">
          <h2 className="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mb-4">
            {t('techStackTitle')}
          </h2>
          <p className="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
            {t('techStackSubtitle')}
          </p>
        </div>

        {/* Tab Navigation */}
        <div className="flex flex-wrap justify-center mb-12" data-aos="fade-up" data-aos-delay="200">
          <div className="bg-white dark:bg-dark-700 rounded-full p-2 shadow-lg border border-gray-200 dark:border-dark-600">
            {tabs.map((tab) => (
              <button
                key={tab.id}
                onClick={() => setActiveTab(tab.id)}
                className={`
                  px-6 py-3 rounded-full font-medium transition-all duration-300 flex items-center space-x-2
                  ${activeTab === tab.id
                    ? 'bg-primary-600 text-white shadow-lg'
                    : 'text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400'
                  }
                `}
              >
                <i className={`${tab.icon} text-sm`}></i>
                <span>{tab.label}</span>
              </button>
            ))}
          </div>
        </div>

        {/* Active Tab Content */}
        <div className="transition-all duration-500" data-aos="fade-up" data-aos-delay="400">
          {tabs.map((tab) => (
            activeTab === tab.id && (
              <div key={tab.id} className="text-center mb-12">
                <h3 className="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                  {tab.title}
                </h3>
                <p className="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                  {tab.description}
                </p>
              </div>
            )
          ))}
        </div>

        {/* Skills Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-16">
          {techData[activeTab].map((tech, index) => (
            <div
              key={tech.name}
              className="group bg-white dark:bg-dark-700 rounded-xl p-6 shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 border border-gray-200 dark:border-dark-600"
              data-aos="zoom-in"
              data-aos-delay={index * 100}
            >
              {/* Tech Icon */}
              <div className="text-center mb-4">
                <div className="w-16 h-16 mx-auto bg-gradient-to-br from-primary-100 to-accent-100 dark:from-primary-900/30 dark:to-accent-900/30 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                  <i className={`${tech.icon} text-3xl text-primary-600 dark:text-primary-400`}></i>
                </div>
                <h4 className="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                  {tech.name}
                </h4>
                <p className="text-sm text-gray-600 dark:text-gray-400 mb-4">
                  {tech.description}
                </p>
              </div>

              {/* Progress Bar */}
              <div className="relative">
                <div className="flex justify-between items-center mb-2">
                  <span className="text-sm font-medium text-gray-700 dark:text-gray-300">
                    Competenza
                  </span>
                  <span className="text-sm font-bold text-primary-600 dark:text-primary-400">
                    {tech.level}%
                  </span>
                </div>
                <div className="w-full bg-gray-200 dark:bg-dark-600 rounded-full h-2.5">
                  <div
                    className={`
                      h-2.5 rounded-full bg-gradient-to-r from-primary-500 to-accent-500 transition-all duration-1000 ease-out
                      ${inView ? 'skill-progress animate' : 'skill-progress'}
                    `}
                    style={{ width: inView ? `${tech.level}%` : '0%' }}
                  ></div>
                </div>
              </div>
            </div>
          ))}
        </div>

        {/* Achievements Section */}
        <div className="bg-white dark:bg-dark-700 rounded-2xl p-8 shadow-xl border border-gray-200 dark:border-dark-600" data-aos="fade-up">
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <div className="text-center">
              <div className="w-16 h-16 mx-auto bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center mb-4">
                <i className="fas fa-trophy text-2xl text-white"></i>
              </div>
              <h4 className="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                Progetti Completati
              </h4>
              <p className="text-3xl font-bold text-green-600 mb-2">10+</p>
              <p className="text-gray-600 dark:text-gray-400">
                Applicazioni web moderne e performanti
              </p>
            </div>

            <div className="text-center">
              <div className="w-16 h-16 mx-auto bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center mb-4">
                <i className="fas fa-graduation-cap text-2xl text-white"></i>
              </div>
              <h4 className="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                Bootcamp Completati
              </h4>
              <p className="text-3xl font-bold text-blue-600 mb-2">3+</p>
              <p className="text-gray-600 dark:text-gray-400">
                Formazione intensiva e specializzata
              </p>
            </div>

            <div className="text-center">
              <div className="w-16 h-16 mx-auto bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center mb-4">
                <i className="fas fa-code text-2xl text-white"></i>
              </div>
              <h4 className="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                Tecnologie Padroneggiate
              </h4>
              <p className="text-3xl font-bold text-purple-600 mb-2">12+</p>
              <p className="text-gray-600 dark:text-gray-400">
                Linguaggi e framework moderni
              </p>
            </div>
          </div>
        </div>


      </div>
    </section>
  )
}

export default TechStack 
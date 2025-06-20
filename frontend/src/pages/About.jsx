import { useState, useEffect, useRef } from 'react'
import { useLanguage } from '../context/LanguageContext'

const About = () => {
  const { t } = useLanguage()
  const [visibleStats, setVisibleStats] = useState(false)
  const [hoveredSkill, setHoveredSkill] = useState(null)
  const timelineRef = useRef(null)

  useEffect(() => {
    const timer = setTimeout(() => setVisibleStats(true), 1000)
    return () => clearTimeout(timer)
  }, [])

  // Counter animation
  const CountUpNumber = ({ end, duration = 2000 }) => {
    const [count, setCount] = useState(0)

    useEffect(() => {
      if (!visibleStats) return

      let startValue = 0
      const endValue = parseInt(end.toString().replace(/\D/g, ''))
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

    return <span>{count}{end.toString().replace(/\d/g, '')}</span>
  }

  const personalStats = [
    { label: t('yearsOfPassion'), value: "5+", icon: "fas fa-heart", color: "from-red-500 to-pink-500" },
    { label: t('projectsCompleted'), value: "20+", icon: "fas fa-code", color: "from-blue-500 to-cyan-500" },
    { label: t('technologiesLearned'), value: "15+", icon: "fas fa-tools", color: "from-green-500 to-emerald-500" },
    { label: t('hoursOfCoding'), value: "2000+", icon: "fas fa-clock", color: "from-purple-500 to-indigo-500" }
  ]

  const skills = [
    { name: "React", level: 80, category: "Frontend", color: "from-blue-400 to-blue-600" },
    { name: "Laravel", level: 90, category: "Backend", color: "from-red-400 to-red-600" },
    { name: "JavaScript", level: 85, category: "Frontend", color: "from-yellow-400 to-yellow-600" },
    { name: "PHP", level: 85, category: "Backend", color: "from-purple-400 to-purple-600" },
    { name: "MySQL", level: 80, category: "Database", color: "from-orange-400 to-orange-600" },
    { name: "Tailwind CSS", level: 90, category: "Frontend", color: "from-teal-400 to-teal-600" },
    { name: "Angular", level: 80, category: "Frontend", color: "from-red-400 to-red-600" },
    { name: "Node.js", level: 70, category: "Backend", color: "from-green-500 to-green-700" }
  ]

  const timeline = [
    {
      period: "SET 2024 - APR 2025",
      title: "FULL-STACK DEVELOPER",
      institution: "AULAB Digilab for future-Unimercatorum",
      type: "education",
      status: "current",
      description: "Formazione avanzata in sviluppo full-stack con focus su Laravel e React",
      achievements: ["Progetti real-world", "Metodologie Agile", "Best Practices"]
    },
    {
      period: "MAR 2024 - GIU 2024",
      title: "FRONT-END WEB DEVELOPER",
      institution: "DIGITAZON",
      type: "education",
      status: "completed",
      description: "Specializzazione in tecnologie frontend moderne e UX/UI design",
      achievements: ["React Advanced", "State Management", "Responsive Design"]
    },
    {
      period: "SET 2023 - FEB 2024",
      title: "FRONT-END WEB DEVELOPER",
      institution: "EPICODE",
      type: "education",
      status: "completed",
      description: "Fondamenti solidi dello sviluppo web e programmazione JavaScript",
      achievements: ["JavaScript ES6+", "HTML5 & CSS3", "Git & GitHub"]
    },
    {
      period: "2020 - 2022",
      title: "Tecnico delle Telecomunicazioni",
      institution: "Settore Impiantistica",
      type: "work",
      status: "completed",
      description: "Esperienza tecnica che ha sviluppato problem-solving e precisione",
      achievements: ["Troubleshooting", "Network Infrastructure", "Customer Support"]
    }
  ]

  const values = [
    {
      icon: "fas fa-lightbulb",
      title: t('innovation'),
      description: t('innovationDesc'),
      color: "from-yellow-400 to-orange-500"
    },
    {
      icon: "fas fa-users",
      title: t('collaboration'),
      description: t('collaborationDesc'),
      color: "from-blue-400 to-purple-500"
    },
    {
      icon: "fas fa-graduation-cap",
      title: t('learning'),
      description: t('learningDesc'),
      color: "from-green-400 to-teal-500"
    },
    {
      icon: "fas fa-target",
      title: t('quality'),
      description: t('qualityDesc'),
      color: "from-purple-400 to-pink-500"
    }
  ]

  return (
    <div className="min-h-screen pt-20 bg-gradient-to-br from-gray-50 via-white to-gray-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
      
      {/* Hero Section Premium */}
      <section className="relative py-20 overflow-hidden">
        {/* Animated background elements */}
        <div className="absolute inset-0">
          <div className="absolute top-20 left-10 w-72 h-72 bg-gradient-to-r from-primary-400/10 to-accent-400/10 rounded-full blur-3xl animate-float"></div>
          <div className="absolute bottom-20 right-10 w-96 h-96 bg-gradient-to-r from-purple-400/10 to-pink-400/10 rounded-full blur-3xl animate-float-delay"></div>
          <div className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-gradient-to-r from-green-400/5 to-blue-400/5 rounded-full blur-3xl"></div>
        </div>

        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
          <div className="text-center">
            {/* Badge */}
            <div className="inline-flex items-center px-6 py-3 rounded-full bg-gradient-to-r from-primary-500/10 to-accent-500/10 border border-primary-200/30 dark:border-primary-700/30 text-primary-700 dark:text-primary-300 text-sm font-medium mb-8 hover:scale-105 transition-transform duration-300">
              <i className="fas fa-user-circle mr-3 text-lg"></i>
              {t('aboutBadge')}
            </div>

            {/* Main Title with enhanced typography */}
            <h1 className="text-5xl lg:text-7xl font-bold mb-8">
              <span className="bg-gradient-to-r from-primary-600 via-accent-500 to-pink-500 bg-clip-text text-transparent">
                {t('aboutMainTitle')}
              </span>
            </h1>

            {/* Enhanced Bio */}
            <div className="max-w-4xl mx-auto text-xl text-gray-600 dark:text-gray-300 leading-relaxed mb-12">
              <p className="mb-6">
                {t('aboutDescription1')}
              </p>
              <p>
                {t('aboutDescription2')}
              </p>
            </div>

            {/* Contact Cards Premium */}
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto mb-16">
              {[
                { icon: "fas fa-phone", label: t('phone'), value: "+39 345 409 8887", color: "from-green-500 to-emerald-500" },
                { icon: "fas fa-envelope", label: t('email'), value: "info@vincenzorocca.it", color: "from-blue-500 to-cyan-500" },
                { icon: "fas fa-map-marker-alt", label: t('location'), value: t('italy'), color: "from-purple-500 to-pink-500" }
              ].map((contact, index) => (
                <div key={index} className="group relative bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-3xl p-6 shadow-xl shadow-black/5 dark:shadow-black/20 border border-white/20 dark:border-slate-700/50 hover:scale-105 transition-all duration-500">
                  <div className={`w-12 h-12 mx-auto mb-4 rounded-2xl bg-gradient-to-r ${contact.color} flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform duration-300`}>
                    <i className={`${contact.icon} text-xl`}></i>
                  </div>
                  <div className="text-sm text-gray-500 dark:text-gray-400 mb-1">{contact.label}</div>
                  <div className="font-semibold text-gray-900 dark:text-white">{contact.value}</div>
                </div>
              ))}
            </div>

            {/* Personal Stats */}
            <div className="grid grid-cols-2 lg:grid-cols-4 gap-6 max-w-4xl mx-auto">
              {personalStats.map((stat, index) => (
                <div
                  key={index}
                  className="group relative bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-3xl p-6 text-center shadow-xl shadow-black/5 dark:shadow-black/20 border border-white/20 dark:border-slate-700/50 hover:scale-105 transition-all duration-500"
                >
                  <div className={`w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-r ${stat.color} flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform duration-300`}>
                    <i className={`${stat.icon} text-2xl`}></i>
                  </div>
                  <div className="text-3xl font-bold mb-2 bg-gradient-to-r from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                    <CountUpNumber end={stat.value} />
                  </div>
                  <div className="text-sm text-gray-600 dark:text-gray-400">{stat.label}</div>
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* Skills Section Premium */}
      <section className="py-20 bg-white dark:bg-slate-900 relative overflow-hidden">
        <div className="absolute inset-0">
          <div className="absolute top-40 left-20 w-80 h-80 bg-gradient-to-r from-blue-400/5 to-purple-400/5 rounded-full blur-3xl"></div>
          <div className="absolute bottom-40 right-20 w-96 h-96 bg-gradient-to-r from-green-400/5 to-cyan-400/5 rounded-full blur-3xl"></div>
        </div>

        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
          <div className="text-center mb-16">
            <h2 className="text-4xl lg:text-5xl font-bold mb-6">
              <span className="bg-gradient-to-r from-primary-600 via-accent-500 to-pink-500 bg-clip-text text-transparent">
                {t('mySkills')}
              </span>
            </h2>
            <p className="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
              Un mix bilanciato di tecnologie frontend e backend per creare soluzioni complete
            </p>
          </div>

          <div className="grid lg:grid-cols-2 gap-8">
            {skills.map((skill, index) => (
              <div
                key={index}
                className="group relative bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-3xl p-6 shadow-xl shadow-black/5 dark:shadow-black/20 border border-white/20 dark:border-slate-700/50 hover:scale-105 transition-all duration-500"
                onMouseEnter={() => setHoveredSkill(index)}
                onMouseLeave={() => setHoveredSkill(null)}
              >
                <div className="flex items-center justify-between mb-4">
                  <div>
                    <h3 className="text-xl font-bold text-gray-900 dark:text-white">{skill.name}</h3>
                    <span className="text-sm text-gray-500 dark:text-gray-400">{skill.category}</span>
                  </div>
                  <div className="text-2xl font-bold text-gray-400">
                    {skill.level}%
                  </div>
                </div>
                
                <div className="relative">
                  <div className="w-full bg-gray-200 dark:bg-slate-700 rounded-full h-3">
                    <div 
                      className={`h-3 bg-gradient-to-r ${skill.color} rounded-full transition-all duration-1000 ease-out shadow-lg`}
                      style={{ 
                        width: hoveredSkill === index ? `${skill.level}%` : '0%',
                        transitionDelay: hoveredSkill === index ? `${index * 100}ms` : '0ms'
                      }}
                    ></div>
                  </div>
                  {hoveredSkill === index && (
                    <div className="absolute -top-8 right-0 text-sm font-medium text-gray-900 dark:text-white">
                      {skill.level}%
                    </div>
                  )}
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Timeline Section Premium */}
      <section className="py-20 bg-gradient-to-br from-gray-50 via-white to-gray-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 relative overflow-hidden">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
          <div className="text-center mb-16">
            <h2 className="text-4xl lg:text-5xl font-bold mb-6">
              <span className="bg-gradient-to-r from-primary-600 via-accent-500 to-pink-500 bg-clip-text text-transparent">
                {t('myJourney')}
              </span>
            </h2>
            <p className="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
              {t('journeySubtitle')}
            </p>
          </div>

          <div className="relative" ref={timelineRef}>
            {/* Timeline line */}
            <div className="absolute left-1/2 transform -translate-x-1/2 w-1 h-full bg-gradient-to-b from-primary-500 via-accent-500 to-pink-500 rounded-full"></div>

            <div className="space-y-12">
              {timeline.map((item, index) => (
                <div key={index} className={`flex items-center ${index % 2 === 0 ? 'flex-row' : 'flex-row-reverse'}`}>
                  <div className="flex-1">
                    <div className={`max-w-lg ${index % 2 === 0 ? 'ml-auto pr-8' : 'mr-auto pl-8'}`}>
                      <div className="group relative bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-3xl p-8 shadow-xl shadow-black/5 dark:shadow-black/20 border border-white/20 dark:border-slate-700/50 hover:scale-105 transition-all duration-500">
                        
                        {/* Status badge */}
                        <div className={`inline-flex items-center px-3 py-1 rounded-full text-xs font-medium mb-4 ${
                          item.status === 'current' 
                            ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' 
                            : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400'
                        }`}>
                          <div className={`w-2 h-2 rounded-full mr-2 ${
                            item.status === 'current' ? 'bg-green-500 animate-pulse' : 'bg-blue-500'
                          }`}></div>
                          {item.status === 'current' ? t('current') : t('completed')}
                        </div>

                        <div className="text-sm font-medium text-primary-600 dark:text-primary-400 mb-2">
                          {item.period}
                        </div>
                        
                        <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-2">
                          {item.title}
                        </h3>
                        
                        <div className="text-gray-600 dark:text-gray-400 mb-4">
                          {item.institution}
                        </div>
                        
                        <p className="text-gray-700 dark:text-gray-300 mb-4 leading-relaxed">
                          {item.description}
                        </p>

                        <div className="flex flex-wrap gap-2">
                          {item.achievements.map((achievement, achIndex) => (
                            <span 
                              key={achIndex}
                              className="px-3 py-1 bg-gradient-to-r from-primary-500/10 to-accent-500/10 text-primary-700 dark:text-primary-300 text-sm rounded-full border border-primary-200/30 dark:border-primary-700/30"
                            >
                              {achievement}
                            </span>
                          ))}
                        </div>
                      </div>
                    </div>
                  </div>

                  {/* Timeline dot */}
                  <div className="relative z-10">
                    <div className={`w-16 h-16 rounded-full bg-gradient-to-r ${
                      item.type === 'education' 
                        ? 'from-blue-500 to-purple-500' 
                        : 'from-green-500 to-teal-500'
                    } flex items-center justify-center text-white shadow-lg border-4 border-white dark:border-slate-900`}>
                      <i className={`fas ${item.type === 'education' ? 'fa-graduation-cap' : 'fa-briefcase'} text-xl`}></i>
                    </div>
                  </div>

                  <div className="flex-1"></div>
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* Values Section Premium */}
      <section className="py-20 bg-white dark:bg-slate-900 relative overflow-hidden">
        <div className="absolute inset-0">
          <div className="absolute top-20 left-1/4 w-64 h-64 bg-gradient-to-r from-primary-400/10 to-accent-400/10 rounded-full blur-3xl animate-pulse"></div>
          <div className="absolute bottom-20 right-1/4 w-80 h-80 bg-gradient-to-r from-pink-400/10 to-purple-400/10 rounded-full blur-3xl animate-pulse" style={{animationDelay: '1s'}}></div>
        </div>

        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
          <div className="text-center mb-16">
            <h2 className="text-4xl lg:text-5xl font-bold mb-6">
              <span className="bg-gradient-to-r from-primary-600 via-accent-500 to-pink-500 bg-clip-text text-transparent">
                {t('myValues')}
              </span>
            </h2>
            <p className="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
              {t('valuesSubtitle')}
            </p>
          </div>

          <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            {values.map((value, index) => (
              <div
                key={index}
                className="group relative bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-3xl p-8 text-center shadow-xl shadow-black/5 dark:shadow-black/20 border border-white/20 dark:border-slate-700/50 hover:scale-105 transition-all duration-500"
              >
                <div className={`w-20 h-20 mx-auto mb-6 rounded-3xl bg-gradient-to-r ${value.color} flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform duration-300`}>
                  <i className={`${value.icon} text-3xl`}></i>
                </div>
                
                <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-4">
                  {value.title}
                </h3>
                
                <p className="text-gray-600 dark:text-gray-300 leading-relaxed">
                  {value.description}
                </p>
              </div>
            ))}
          </div>
        </div>
      </section>



    </div>
  )
}

export default About 
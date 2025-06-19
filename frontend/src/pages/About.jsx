import { useState, useEffect, useRef } from 'react'

const About = () => {
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
    { label: "Anni di Passione", value: "5+", icon: "fas fa-heart", color: "from-red-500 to-pink-500" },
    { label: "Progetti Completati", value: "20+", icon: "fas fa-code", color: "from-blue-500 to-cyan-500" },
    { label: "Tecnologie Apprese", value: "15+", icon: "fas fa-tools", color: "from-green-500 to-emerald-500" },
    { label: "Ore di Coding", value: "2000+", icon: "fas fa-clock", color: "from-purple-500 to-indigo-500" }
  ]

  const skills = [
    { name: "React", level: 90, category: "Frontend", color: "from-blue-400 to-blue-600" },
    { name: "Laravel", level: 85, category: "Backend", color: "from-red-400 to-red-600" },
    { name: "JavaScript", level: 88, category: "Frontend", color: "from-yellow-400 to-yellow-600" },
    { name: "PHP", level: 80, category: "Backend", color: "from-purple-400 to-purple-600" },
    { name: "MySQL", level: 75, category: "Database", color: "from-orange-400 to-orange-600" },
    { name: "Tailwind CSS", level: 92, category: "Frontend", color: "from-teal-400 to-teal-600" },
    { name: "Vue.js", level: 70, category: "Frontend", color: "from-green-400 to-green-600" },
    { name: "Node.js", level: 65, category: "Backend", color: "from-green-500 to-green-700" }
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
      title: "Innovazione",
      description: "Sempre alla ricerca di soluzioni creative e tecnologie all'avanguardia",
      color: "from-yellow-400 to-orange-500"
    },
    {
      icon: "fas fa-users",
      title: "Collaborazione",
      description: "Il successo nasce dal lavoro di squadra e dalla condivisione delle conoscenze",
      color: "from-blue-400 to-purple-500"
    },
    {
      icon: "fas fa-graduation-cap",
      title: "Apprendimento",
      description: "Crescita continua attraverso formazione e sperimentazione",
      color: "from-green-400 to-teal-500"
    },
    {
      icon: "fas fa-target",
      title: "Qualità",
      description: "Attenzione ai dettagli e standard elevati in ogni progetto",
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
              Chi Sono
            </div>

            {/* Main Title with enhanced typography */}
            <h1 className="text-5xl lg:text-7xl font-bold mb-8">
              <span className="text-gray-900 dark:text-white">Sviluppatore con</span>
              <br />
              <span className="bg-gradient-to-r from-primary-600 via-accent-500 to-pink-500 bg-clip-text text-transparent">
                Passione per l'Innovazione
              </span>
            </h1>

            {/* Enhanced Bio */}
            <div className="max-w-4xl mx-auto text-xl text-gray-600 dark:text-gray-300 leading-relaxed mb-12">
              <p className="mb-6">
                <span className="font-semibold text-gray-900 dark:text-white">Full Stack Developer</span> con oltre 
                <span className="text-primary-600 dark:text-primary-400 font-semibold"> 2000 ore di formazione</span> intensiva 
                in sviluppo web moderno. Specializzato in <span className="text-accent-600 dark:text-accent-400 font-semibold">React, Laravel, e tecnologie all'avanguardia</span>.
              </p>
              <p>
                Trasformo idee in <span className="font-semibold text-gray-900 dark:text-white">soluzioni digitali innovative</span>, 
                combinando competenze tecniche solide con un approccio creativo al problem-solving.
              </p>
            </div>

            {/* Contact Cards Premium */}
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto mb-16">
              {[
                { icon: "fas fa-phone", label: "Telefono", value: "+39 345 409 8887", color: "from-green-500 to-emerald-500" },
                { icon: "fas fa-envelope", label: "Email", value: "info@vincenzorocca.it", color: "from-blue-500 to-cyan-500" },
                { icon: "fas fa-map-marker-alt", label: "Location", value: "Italia", color: "from-purple-500 to-pink-500" }
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
                Competenze Tecniche
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
                Il Mio Percorso
              </span>
            </h2>
            <p className="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
              Un viaggio di crescita continua attraverso formazione ed esperienza pratica
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
                          {item.status === 'current' ? 'In Corso' : 'Completato'}
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
                I Miei Valori
              </span>
            </h2>
            <p className="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
              Principi che guidano il mio approccio professionale e personale
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

      {/* CTA Section */}
      <section className="py-20 bg-gradient-to-r from-primary-600 via-accent-500 to-pink-500 relative overflow-hidden">
        <div className="absolute inset-0 bg-black/10"></div>
        
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">
          <h2 className="text-4xl lg:text-6xl font-bold text-white mb-6">
            Realizziamo Qualcosa di Straordinario
          </h2>
          <p className="text-xl text-white/90 mb-8 leading-relaxed">
            Hai un progetto in mente? Collaboriamo per trasformarlo in realtà con tecnologie moderne e design innovativo.
          </p>
          
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <a
              href="/contact"
              className="group inline-flex items-center px-8 py-4 bg-white text-primary-600 rounded-2xl font-bold hover:bg-gray-50 transition-all duration-300 shadow-2xl shadow-black/20 hover:scale-105"
            >
              Iniziamo a Collaborare
              <i className="fas fa-rocket ml-3 group-hover:translate-x-1 transition-transform duration-300"></i>
            </a>
            
            <a
              href="/projects"
              className="group inline-flex items-center px-8 py-4 bg-white/10 text-white border-2 border-white/30 rounded-2xl font-bold hover:bg-white/20 transition-all duration-300 backdrop-blur-sm hover:scale-105"
            >
              Esplora i Progetti
              <i className="fas fa-eye ml-3 group-hover:translate-x-1 transition-transform duration-300"></i>
            </a>
          </div>
        </div>
      </section>

    </div>
  )
}

export default About 
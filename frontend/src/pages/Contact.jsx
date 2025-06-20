import { useState, useEffect } from 'react'
import { useLanguage } from '../context/LanguageContext'
import { contactService } from '../services/contact'

const Contact = () => {
  const { t } = useLanguage()
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    subject: '',
    message: '',
    budget: '',
    timeline: '',
    projectType: ''
  })
  
  const [isSubmitting, setIsSubmitting] = useState(false)
  const [submitStatus, setSubmitStatus] = useState(null)
  const [focusedField, setFocusedField] = useState(null)
  const [typingAnimation, setTypingAnimation] = useState('')

  const typingText = t('contactTyping')
  
  useEffect(() => {
    let index = 0
    const timer = setInterval(() => {
      setTypingAnimation(typingText.slice(0, index))
      index++
      if (index > typingText.length) {
        index = 0
      }
    }, 100)
    
    return () => clearInterval(timer)
  }, [typingText])

  const handleChange = (e) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value
    })
  }

  const handleSubmit = async (e) => {
    e.preventDefault()
    setIsSubmitting(true)
    
    try {
      // Send message via API
      await contactService.sendMessage({
        name: formData.name,
        email: formData.email,
        subject: formData.subject || 'Contatto dal portfolio',
        message: formData.message,
        phone: formData.phone || null,
        company: formData.company || null
      })
      
      setSubmitStatus('success')
      setFormData({
        name: '',
        email: '',
        subject: '',
        message: '',
        budget: '',
        timeline: '',
        projectType: ''
      })
      
      // Reset status after 5 seconds
      setTimeout(() => {
        setSubmitStatus(null)
      }, 5000)
    } catch (error) {
      console.error('Error sending message:', error)
      setSubmitStatus('error')
      
      // Reset error status after 5 seconds
      setTimeout(() => {
        setSubmitStatus(null)
      }, 5000)
    } finally {
      setIsSubmitting(false)
    }
  }

  const contactMethods = [
    {
      icon: "fas fa-envelope",
      title: t('email'),
      value: "vincenzorocca88@gmail.com",
      description: t('replyWithin24h'),
      color: "from-blue-500 to-cyan-500",
      action: "mailto:vincenzorocca88@gmail.com"
    },
    {
      icon: "fas fa-phone",
      title: t('phone'),
      value: "3454098887",
      description: t('weekdaysSchedule'),
      color: "from-green-500 to-emerald-500",
      action: "tel:3454098887"
    },
    {
      icon: "fab fa-whatsapp",
      title: t('whatsapp'),
      value: t('chatDirect'),
      description: t('messagesAndCalls'),
      color: "from-green-400 to-green-600",
      action: "https://wa.me/3454098887"
    },
    {
      icon: "fas fa-map-marker-alt",
      title: t('location'),
      value: t('italy'),
      description: t('availableRemote'),
      color: "from-purple-500 to-pink-500",
      action: "#"
    }
  ]

  const projectTypes = [
    { value: 'e-commerce', label: 'E-Commerce' },
    { value: 'web-app', label: 'Web Application' },
    { value: 'landing-page', label: 'Landing Page' },
    { value: 'portfolio', label: 'Portfolio' },
    { value: 'blog', label: 'Blog/CMS' },
    { value: 'api', label: 'API Development' },
    { value: 'mobile', label: 'Mobile App' },
    { value: 'other', label: 'Altro' }
  ]

  const budgetRanges = [
    { value: '1k-5k', label: '€1.000 - €5.000' },
    { value: '5k-10k', label: '€5.000 - €10.000' },
    { value: '10k-25k', label: '€10.000 - €25.000' },
    { value: '25k+', label: '€25.000+' },
    { value: 'discuss', label: 'Da discutere' }
  ]

  const timelineOptions = [
    { value: '1-2-weeks', label: '1-2 settimane' },
    { value: '1-month', label: '1 mese' },
    { value: '2-3-months', label: '2-3 mesi' },
    { value: '3-6-months', label: '3-6 mesi' },
    { value: '6-months+', label: '6+ mesi' },
    { value: 'flexible', label: 'Flessibile' }
  ]

  const faqs = [
    {
      question: "Quanto tempo richiede un progetto tipico?",
      answer: "Dipende dalla complessità. Un sito web semplice 2-4 settimane, un'applicazione web complessa 2-6 mesi."
    },
    {
      question: "Quali tecnologie utilizzi?",
      answer: "Principalmente React, Angular, Laravel, Node.js, MySQL, PostgreSQL e tecnologie cloud moderne."
    },
    {
      question: "Offri supporto post-lancio?",
      answer: "Sì, offro pacchetti di manutenzione e supporto tecnico continuo per tutti i progetti."
    },
    {
      question: "Quanto costa sviluppare un sito web?",
      answer: "I costi variano in base alla complessità e alle funzionalità richieste. Offro sempre un preventivo gratuito personalizzato."
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
              <i className="fas fa-handshake mr-3 text-lg"></i>
{t('contactBadge')}
            </div>

            {/* Main Title */}
            <h1 className="text-5xl lg:text-7xl font-bold mb-8">
              <span className="bg-gradient-to-r from-primary-600 via-accent-500 to-pink-500 bg-clip-text text-transparent">
                {t('contactMainTitle')}
              </span>
            </h1>

            {/* Typing Animation */}
            <div className="max-w-4xl mx-auto text-xl text-gray-600 dark:text-gray-300 leading-relaxed mb-12">
              <p className="mb-4">
                {t('contactDescription')}
              </p>
              <div className="text-2xl font-bold text-primary-600 dark:text-primary-400 min-h-[2rem]">
                {typingAnimation}
                <span className="animate-pulse">|</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Contact Methods Grid */}
      <section className="py-12 bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            {contactMethods.map((method, index) => (
              <a
                key={index}
                href={method.action}
                target={method.action.startsWith('http') ? '_blank' : '_self'}
                rel={method.action.startsWith('http') ? 'noopener noreferrer' : ''}
                className="group relative bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-3xl p-6 text-center shadow-xl shadow-black/5 dark:shadow-black/20 border border-white/20 dark:border-slate-700/50 hover:scale-105 transition-all duration-500"
              >
                <div className={`w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-r ${method.color} flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform duration-300`}>
                  <i className={`${method.icon} text-2xl`}></i>
                </div>
                
                <h3 className="text-lg font-bold text-gray-900 dark:text-white mb-2">
                  {method.title}
                </h3>
                
                <p className="text-gray-900 dark:text-white font-medium mb-1">
                  {method.value}
                </p>
                
                <p className="text-sm text-gray-600 dark:text-gray-400">
                  {method.description}
                </p>
              </a>
            ))}
          </div>
        </div>
      </section>

      {/* Main Content */}
      <section className="py-20">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid lg:grid-cols-2 gap-16">
            
            {/* Contact Form Premium */}
            <div className="relative">
              <div className="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-3xl p-8 shadow-xl shadow-black/5 dark:shadow-black/20 border border-white/20 dark:border-slate-700/50">
                <h2 className="text-3xl font-bold text-gray-900 dark:text-white mb-8">
                  {t('getInTouch')}
                </h2>

                {submitStatus === 'success' && (
                  <div className="mb-6 p-4 bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-2xl text-green-800 dark:text-green-400">
                    <div className="flex items-center">
                      <i className="fas fa-check-circle mr-3 text-xl"></i>
                      <div>
                        <p className="font-medium">Messaggio inviato con successo!</p>
                        <p className="text-sm opacity-80">Ti risponderò entro 24 ore.</p>
                      </div>
                    </div>
                  </div>
                )}

                {submitStatus === 'error' && (
                  <div className="mb-6 p-4 bg-red-100 dark:bg-red-900/30 border border-red-200 dark:border-red-700 rounded-2xl text-red-800 dark:text-red-400">
                    <div className="flex items-center">
                      <i className="fas fa-exclamation-circle mr-3 text-xl"></i>
                      <div>
                        <p className="font-medium">Errore nell'invio del messaggio</p>
                        <p className="text-sm opacity-80">Si è verificato un errore. Riprova più tardi o contattami direttamente.</p>
                      </div>
                    </div>
                  </div>
                )}

                <form onSubmit={handleSubmit} className="space-y-6">
                  <div className="grid md:grid-cols-2 gap-6">
                    <div className="space-y-2">
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Nome Completo *
                      </label>
                      <input
                        type="text"
                        name="name"
                        required
                        value={formData.name}
                        onChange={handleChange}
                        onFocus={() => setFocusedField('name')}
                        onBlur={() => setFocusedField(null)}
                        className={`w-full px-4 py-3 rounded-2xl border transition-all duration-300 ${
                          focusedField === 'name'
                            ? 'border-primary-500 ring-4 ring-primary-500/20 scale-105'
                            : 'border-gray-200 dark:border-slate-700'
                        } bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm focus:outline-none`}
                        placeholder="Il tuo nome"
                      />
                    </div>

                    <div className="space-y-2">
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Email *
                      </label>
                      <input
                        type="email"
                        name="email"
                        required
                        value={formData.email}
                        onChange={handleChange}
                        onFocus={() => setFocusedField('email')}
                        onBlur={() => setFocusedField(null)}
                        className={`w-full px-4 py-3 rounded-2xl border transition-all duration-300 ${
                          focusedField === 'email'
                            ? 'border-primary-500 ring-4 ring-primary-500/20 scale-105'
                            : 'border-gray-200 dark:border-slate-700'
                        } bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm focus:outline-none`}
                        placeholder="la-tua-email@esempio.com"
                      />
                    </div>
                  </div>

                  <div className="space-y-2">
                    <label className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                      Tipo di Progetto
                    </label>
                    <select
                      name="projectType"
                      value={formData.projectType}
                      onChange={handleChange}
                      className="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/20 transition-all duration-300"
                    >
                      <option value="">Seleziona il tipo di progetto</option>
                      {projectTypes.map((type) => (
                        <option key={type.value} value={type.value}>{type.label}</option>
                      ))}
                    </select>
                  </div>

                  <div className="grid md:grid-cols-2 gap-6">
                    <div className="space-y-2">
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Budget Stimato
                      </label>
                      <select
                        name="budget"
                        value={formData.budget}
                        onChange={handleChange}
                        className="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/20 transition-all duration-300"
                      >
                        <option value="">Seleziona budget</option>
                        {budgetRanges.map((budget) => (
                          <option key={budget.value} value={budget.value}>{budget.label}</option>
                        ))}
                      </select>
                    </div>

                    <div className="space-y-2">
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Timeline
                      </label>
                      <select
                        name="timeline"
                        value={formData.timeline}
                        onChange={handleChange}
                        className="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-slate-700 bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/20 transition-all duration-300"
                      >
                        <option value="">Quando serve?</option>
                        {timelineOptions.map((timeline) => (
                          <option key={timeline.value} value={timeline.value}>{timeline.label}</option>
                        ))}
                      </select>
                    </div>
                  </div>

                  <div className="space-y-2">
                    <label className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                      Oggetto *
                    </label>
                    <input
                      type="text"
                      name="subject"
                      required
                      value={formData.subject}
                      onChange={handleChange}
                      onFocus={() => setFocusedField('subject')}
                      onBlur={() => setFocusedField(null)}
                      className={`w-full px-4 py-3 rounded-2xl border transition-all duration-300 ${
                        focusedField === 'subject'
                          ? 'border-primary-500 ring-4 ring-primary-500/20 scale-105'
                          : 'border-gray-200 dark:border-slate-700'
                      } bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm focus:outline-none`}
                      placeholder="Oggetto del messaggio"
                    />
                  </div>

                  <div className="space-y-2">
                    <label className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                      Messaggio *
                    </label>
                    <textarea
                      name="message"
                      required
                      rows={6}
                      value={formData.message}
                      onChange={handleChange}
                      onFocus={() => setFocusedField('message')}
                      onBlur={() => setFocusedField(null)}
                      className={`w-full px-4 py-3 rounded-2xl border transition-all duration-300 ${
                        focusedField === 'message'
                          ? 'border-primary-500 ring-4 ring-primary-500/20 scale-105'
                          : 'border-gray-200 dark:border-slate-700'
                      } bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm focus:outline-none resize-none`}
                      placeholder="Descrivi il tuo progetto, obiettivi, funzionalità desiderate e qualsiasi altra informazione rilevante..."
                    />
                  </div>

                  <button
                    type="submit"
                    disabled={isSubmitting}
                    className={`w-full group relative px-8 py-4 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-2xl font-bold hover:from-primary-700 hover:to-accent-600 transition-all duration-300 shadow-xl shadow-primary-500/25 hover:scale-105 ${
                      isSubmitting ? 'opacity-75 cursor-not-allowed' : ''
                    }`}
                  >
                    {isSubmitting ? (
                      <div className="flex items-center justify-center">
                        <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                          <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                          <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Invio in corso...
                      </div>
                    ) : (
                      <div className="flex items-center justify-center">
                        <span>Invia Messaggio</span>
                        <i className="fas fa-paper-plane ml-3 group-hover:translate-x-1 transition-transform duration-300"></i>
                      </div>
                    )}
                  </button>
                </form>
              </div>
            </div>

            {/* Info & FAQs */}
            <div className="space-y-8">
              
              {/* Response Time Card */}
              <div className="bg-gradient-to-r from-primary-600 via-accent-500 to-pink-500 rounded-3xl p-8 text-white">
                <div className="flex items-center mb-4">
                  <div className="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4">
                    <i className="fas fa-clock text-2xl"></i>
                  </div>
                  <div>
                    <h3 className="text-xl font-bold">Tempo di Risposta</h3>
                    <p className="text-white/80">Garantito entro 24 ore</p>
                  </div>
                </div>
                <p className="text-white/90">
                  Riceverai una risposta dettagliata entro 24 ore lavorative. Per progetti urgenti, 
                  contattami direttamente via WhatsApp.
                </p>
              </div>

              {/* Process Steps */}
              <div className="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-3xl p-8 shadow-xl shadow-black/5 dark:shadow-black/20 border border-white/20 dark:border-slate-700/50">
                <h3 className="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                  Come Lavoriamo Insieme
                </h3>
                <div className="space-y-4">
                  {[
                    { step: 1, title: "Analisi", desc: "Analizziamo insieme il tuo progetto e le esigenze" },
                    { step: 2, title: "Proposta", desc: "Ti presento una proposta dettagliata con tempistiche" },
                    { step: 3, title: "Sviluppo", desc: "Sviluppo iterativo con aggiornamenti costanti" },
                    { step: 4, title: "Lancio", desc: "Deploy e supporto post-lancio garantito" }
                  ].map((item, index) => (
                    <div key={index} className="flex items-center space-x-4">
                      <div className="w-10 h-10 bg-gradient-to-r from-primary-500 to-accent-500 text-white rounded-full flex items-center justify-center font-bold text-sm">
                        {item.step}
                      </div>
                      <div>
                        <h4 className="font-semibold text-gray-900 dark:text-white">{item.title}</h4>
                        <p className="text-sm text-gray-600 dark:text-gray-400">{item.desc}</p>
                      </div>
                    </div>
                  ))}
                </div>
              </div>

              {/* FAQs */}
              <div className="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-3xl p-8 shadow-xl shadow-black/5 dark:shadow-black/20 border border-white/20 dark:border-slate-700/50">
                <h3 className="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                  Domande Frequenti
                </h3>
                <div className="space-y-4">
                  {faqs.map((faq, index) => (
                    <details key={index} className="group">
                      <summary className="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-700/50 rounded-2xl cursor-pointer hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors duration-200">
                        <span className="font-medium text-gray-900 dark:text-white">{faq.question}</span>
                        <i className="fas fa-chevron-down group-open:rotate-180 transition-transform duration-200"></i>
                      </summary>
                      <div className="pt-4 px-4">
                        <p className="text-gray-600 dark:text-gray-300 leading-relaxed">{faq.answer}</p>
                      </div>
                    </details>
                  ))}
                </div>
              </div>

            </div>
          </div>
        </div>
      </section>

      {/* Social Proof Section */}
      <section className="py-20 bg-gradient-to-r from-primary-600 via-accent-500 to-pink-500 relative overflow-hidden">
        <div className="absolute inset-0 bg-black/10"></div>
        
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">
          <h2 className="text-4xl lg:text-6xl font-bold text-white mb-6">
            Iniziamo Oggi Stesso!
          </h2>
          <p className="text-xl text-white/90 mb-8 leading-relaxed">
            Unisciti ai clienti soddisfatti che hanno trasformato le loro idee in successi digitali.
          </p>
          
          <div className="grid md:grid-cols-3 gap-6 mb-8">
            {[
              { icon: "fas fa-users", label: "10+ Clienti Felici", value: "100%" },
              { icon: "fas fa-project-diagram", label: "Progetti Consegnati", value: "20+" },
              { icon: "fas fa-star", label: "Soddisfazione Media", value: "5/5" }
            ].map((stat, index) => (
              <div key={index} className="bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-center">
                <i className={`${stat.icon} text-3xl text-white mb-3`}></i>
                <div className="text-2xl font-bold text-white mb-1">{stat.value}</div>
                <div className="text-white/80 text-sm">{stat.label}</div>
              </div>
            ))}
          </div>
          
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <a
              href="tel:+393454098887"
              className="group inline-flex items-center px-8 py-4 bg-white text-primary-600 rounded-2xl font-bold hover:bg-gray-50 transition-all duration-300 shadow-2xl shadow-black/20 hover:scale-105"
            >
              Chiamami Ora
              <i className="fas fa-phone ml-3 group-hover:scale-110 transition-transform duration-300"></i>
            </a>
            
            <a
              href="https://wa.me/393454098887"
              target="_blank"
              rel="noopener noreferrer"
              className="group inline-flex items-center px-8 py-4 bg-white/10 text-white border-2 border-white/30 rounded-2xl font-bold hover:bg-white/20 transition-all duration-300 backdrop-blur-sm hover:scale-105"
            >
              WhatsApp
              <i className="fab fa-whatsapp ml-3 group-hover:scale-110 transition-transform duration-300"></i>
            </a>
          </div>
        </div>
      </section>

    </div>
  )
}

export default Contact
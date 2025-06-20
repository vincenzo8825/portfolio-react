import { createContext, useContext, useState, useEffect } from 'react'

const LanguageContext = createContext()

// Traduzioni
const translations = {
  it: {
    // Navigation
    home: 'Home',
    about: 'Chi Sono',
    projects: 'Progetti',
    contact: 'Contatti',
    admin: 'Admin',
    login: 'Login',
    logout: 'Logout',
    
    // Hero Section
    heroTitle: 'Ciao, sono Vincenzo Rocca',
    heroBadge: 'Full Stack Developer',
    heroSubtitle: 'Trasformo idee complesse in esperienze digitali intuitive e moderne',
    exploreWork: 'Esplora i miei lavori',
    contactMe: 'Contattami',
    specializedIn: 'Specializzato in:',
    
    // Stats
    projectsCount: '10+ Progetti',
    bootcampsCount: '3+ Bootcamp', 
    technologiesCount: '12+ Tecnologie',
    
    // Tech Stack
    techStackTitle: 'Stack Tecnologico',
    techStackSubtitle: 'Le tecnologie che padroneggio per creare soluzioni web moderne e performanti',
    frontend: 'Frontend',
    backend: 'Backend',
    tools: 'Tools',
    
    // Admin
    dashboard: 'Dashboard',
    projectManagement: 'Gestione Progetti',
    newProject: 'Nuovo Progetto',
    adminPanel: 'Pannello Admin',
    quickActions: 'Azioni Rapide',
    addNewProject: 'Aggiungi un nuovo progetto al portfolio',
    manageProjects: 'Visualizza e modifica i progetti esistenti',
    viewSite: 'Vai al portfolio pubblico',
    recentActivity: 'Attività Recente',
    noRecentActivity: 'Nessuna attività recente da mostrare',
    totalProjects: 'Totale Progetti',
    featuredProjects: 'Progetti in Evidenza', 
    totalVisits: 'Visite Totali',
    messagesReceived: 'Messaggi Ricevuti',
    
    // About
    aboutTitle: 'Chi Sono',
    aboutBadge: 'Chi Sono',
    aboutMainTitle: 'Sviluppatore con Passione per l\'Innovazione',
    aboutDescription1: 'Full Stack Developer con oltre 2000 ore di formazione intensiva in sviluppo web moderno. Specializzato in React, Angular, Laravel, e tecnologie all\'avanguardia.',
    aboutDescription2: 'Trasformo idee in soluzioni digitali innovative, combinando competenze tecniche solide con un approccio creativo al problem-solving.',
    yearsOfPassion: 'Anni di Passione',
    projectsCompleted: 'Progetti Completati',
    technologiesLearned: 'Tecnologie Apprese',
    hoursOfCoding: 'Ore di Coding',
    mySkills: 'Le Mie Competenze',
    myJourney: 'Il Mio Percorso',
    journeySubtitle: 'Un viaggio di crescita continua attraverso formazione ed esperienza pratica',
    myValues: 'I Miei Valori',
    valuesSubtitle: 'Principi che guidano il mio approccio professionale e personale',
    innovation: 'Innovazione',
    innovationDesc: 'Sempre alla ricerca di soluzioni creative e tecnologie all\'avanguardia',
    collaboration: 'Collaborazione',
    collaborationDesc: 'Il successo nasce dal lavoro di squadra e dalla condivisione delle conoscenze',
    learning: 'Apprendimento',
    learningDesc: 'Crescita continua attraverso formazione e sperimentazione',
    quality: 'Qualità',
    qualityDesc: 'Attenzione ai dettagli e standard elevati in ogni progetto',
    education: 'Formazione',
    experience: 'Esperienza',
    current: 'In Corso',
    completed: 'Completato',
    
    // Contact
    contactTitle: 'Contatti',
    contactBadge: 'Collaboriamo Insieme',
    contactMainTitle: 'Iniziamo a Collaborare',
    contactDescription: 'Hai un progetto innovativo in mente? Sono qui per trasformarlo in realtà digitale con tecnologie moderne e design all\'avanguardia.',
    contactTyping: 'Trasformiamo la tua idea in realtà digitale...',
    phone: 'Telefono',
    email: 'Email',
    location: 'Ubicazione',
    whatsapp: 'WhatsApp',
    chatDirect: 'Chat Diretta',
    messagesAndCalls: 'Messaggi e chiamate',
    replyWithin24h: 'Rispondo entro 24 ore',
    weekdaysSchedule: 'Lun-Ven 9:00-18:00',
    availableRemote: 'Disponibile da remoto',
    italy: 'Italia',
    getInTouch: 'Mettiamoci in Contatto',
    sendMessage: 'Invia Messaggio',
    name: 'Nome',
    subject: 'Oggetto',
    message: 'Messaggio',
    send: 'Invia Messaggio',
    projectType: 'Tipo di Progetto',
    selectProjectType: 'Seleziona il tipo di progetto',
    budget: 'Budget',
    selectBudget: 'Seleziona il budget',
    timeline: 'Tempistiche',
    selectTimeline: 'Seleziona le tempistiche',
    faq: 'Domande Frequenti',
    faqSubtitle: 'Risposte alle domande più comuni sui miei servizi',
    
    // Contact
    contactSubtitle: 'Hai un progetto in mente? Parliamone!',
    
    // Projects
    projectsTitle: 'I Miei Progetti',
    projectsSubtitle: 'Una selezione dei progetti che ho realizzato',
    allProjects: 'Tutti i Progetti',
    
    // Common
    loading: 'Caricamento...',
    save: 'Salva',
    cancel: 'Annulla',
    edit: 'Modifica',
    delete: 'Elimina',
    create: 'Crea',
    update: 'Aggiorna',
    welcome: 'Benvenuto',
    backToProjects: 'Torna ai progetti',
    viewAllProjects: 'Visualizza tutti i progetti',
    projectNotFound: 'Progetto non trovato',
    projectNotFoundDesc: 'Il progetto che stai cercando non esiste o è stato rimosso.',
    
    // Theme & Language
    darkMode: 'Modalità scura',
    lightMode: 'Modalità chiara',
    language: 'Lingua',
    
    // Footer
    madeWith: 'Realizzato con',
    by: 'da',
    
    // Additional translations
    featuredProjectsDesc: 'Una selezione dei progetti più innovativi e impattanti sviluppati di recente',
    loadingProjects: 'Caricamento progetti...',
    noFeaturedProjects: 'Nessun progetto in evidenza',
    noFeaturedProjectsDesc: 'I progetti saranno visibili qui una volta creati e messi in evidenza.',
    completedStatus: 'Completato',
    inProgressStatus: 'In Corso',
    pausedStatus: 'In Pausa',
    featured: 'In Evidenza',
    details: 'Dettagli',
    dashboardAdmin: 'Dashboard Admin',
    adminAccess: 'Accesso Admin',
    online: 'Online',
    availableForProjects: 'Disponibile per nuovi progetti',
    skillsOverview: 'Panoramica Competenze',
    fullStackDeveloper: 'Full Stack Developer',
    scrollToExplore: 'Scorri per esplorare',
    technologies: 'Tecnologie',
    
    // Home page sections
    resultsThatSpeak: 'Risultati che Parlano',
    resultsThatSpeakDesc: 'Numeri che dimostrano l\'eccellenza e la dedizione nei progetti sviluppati',
    projectInMind: 'Hai un Progetto in Mente?',
    projectInMindDesc: 'Sono un junior developer in cerca di opportunità lavorative, ma sono anche aperto a collaborazioni freelance. Trasformiamo le tue idee in soluzioni digitali innovative. Contattami per una consulenza gratuita!',
    startNow: 'Iniziamo Subito',
    viewPortfolio: 'Vedi Portfolio'
  },
  en: {
    // Navigation  
    home: 'Home',
    about: 'About Me',
    projects: 'Projects',
    contact: 'Contact',
    admin: 'Admin',
    login: 'Login',
    logout: 'Logout',
    
    // Hero Section
    heroTitle: 'Hi, I\'m Vincenzo Rocca',
    heroBadge: 'Full Stack Developer',
    heroSubtitle: 'I transform complex ideas into intuitive and modern digital experiences',
    exploreWork: 'Explore my work',
    contactMe: 'Contact Me',
    specializedIn: 'Specialized in:',
    
    // Stats
    projectsCount: '10+ Projects',
    bootcampsCount: '3+ Bootcamps',
    technologiesCount: '12+ Technologies',
    
    // Tech Stack
    techStackTitle: 'Tech Stack',
    techStackSubtitle: 'The technologies I master to create modern and performant web solutions',
    frontend: 'Frontend',
    backend: 'Backend', 
    tools: 'Tools',
    
    // Admin
    dashboard: 'Dashboard',
    projectManagement: 'Project Management',
    newProject: 'New Project',
    adminPanel: 'Admin Panel',
    quickActions: 'Quick Actions',
    addNewProject: 'Add a new project to the portfolio',
    manageProjects: 'View and edit existing projects',
    viewSite: 'Go to public portfolio',
    recentActivity: 'Recent Activity',
    noRecentActivity: 'No recent activity to show',
    totalProjects: 'Total Projects',
    featuredProjects: 'Featured Projects',
    totalVisits: 'Total Visits', 
    messagesReceived: 'Messages Received',
    
    // About
    aboutTitle: 'About Me',
    aboutBadge: 'About Me',
    aboutMainTitle: 'Developer with Passion for Innovation',
    aboutDescription1: 'Full Stack Developer with over 2000 hours of intensive training in modern web development. Specialized in React, Angular, Laravel, and cutting-edge technologies.',
    aboutDescription2: 'I transform ideas into innovative digital solutions, combining solid technical skills with a creative approach to problem-solving.',
    yearsOfPassion: 'Years of Passion',
    projectsCompleted: 'Projects Completed',
    technologiesLearned: 'Technologies Learned',
    hoursOfCoding: 'Hours of Coding',
    mySkills: 'My Skills',
    myJourney: 'My Journey',
    journeySubtitle: 'A journey of continuous growth through training and practical experience',
    myValues: 'My Values',
    valuesSubtitle: 'Principles that guide my professional and personal approach',
    innovation: 'Innovation',
    innovationDesc: 'Always looking for creative solutions and cutting-edge technologies',
    collaboration: 'Collaboration',
    collaborationDesc: 'Success comes from teamwork and knowledge sharing',
    learning: 'Learning',
    learningDesc: 'Continuous growth through training and experimentation',
    quality: 'Quality',
    qualityDesc: 'Attention to detail and high standards in every project',
    education: 'Education',
    experience: 'Experience',
    current: 'Current',
    completed: 'Completed',
    
    // Contact
    contactTitle: 'Contact',
    contactBadge: 'Let\'s Collaborate',
    contactMainTitle: 'Let\'s Start Collaborating',
    contactDescription: 'Have an innovative project in mind? I\'m here to transform it into digital reality with modern technologies and cutting-edge design.',
    contactTyping: 'Let\'s transform your idea into digital reality...',
    phone: 'Phone',
    email: 'Email', 
    location: 'Location',
    whatsapp: 'WhatsApp',
    chatDirect: 'Direct Chat',
    messagesAndCalls: 'Messages and calls',
    replyWithin24h: 'Reply within 24 hours',
    weekdaysSchedule: 'Mon-Fri 9:00-18:00',
    availableRemote: 'Available remotely',
    italy: 'Italy',
    getInTouch: 'Get In Touch',
    sendMessage: 'Send Message',
    name: 'Name',
    subject: 'Subject',
    message: 'Message',
    send: 'Send Message',
    projectType: 'Project Type',
    selectProjectType: 'Select project type',
    budget: 'Budget',
    selectBudget: 'Select budget',
    timeline: 'Timeline',
    selectTimeline: 'Select timeline',
    faq: 'Frequently Asked Questions',
    faqSubtitle: 'Answers to the most common questions about my services',
    contactSubtitle: 'Have a project in mind? Let\'s talk!',
    
    // Projects
    projectsTitle: 'My Projects',
    projectsSubtitle: 'A selection of projects I\'ve built',
    allProjects: 'All Projects',
    
    // Common
    loading: 'Loading...',
    save: 'Save',
    cancel: 'Cancel',
    edit: 'Edit',
    delete: 'Delete',
    create: 'Create',
    update: 'Update',
    welcome: 'Welcome',
    backToProjects: 'Back to projects',
    viewAllProjects: 'View all projects',
    projectNotFound: 'Project not found',
    projectNotFoundDesc: 'The project you are looking for does not exist or has been removed.',
    
    // Theme & Language
    darkMode: 'Dark mode',
    lightMode: 'Light mode',
    language: 'Language',
    
    // Footer
    madeWith: 'Made with',
    by: 'by',
    
    // Additional translations
    featuredProjectsDesc: 'A selection of the most innovative and impactful projects recently developed',
    loadingProjects: 'Loading projects...',
    noFeaturedProjects: 'No featured projects',
    noFeaturedProjectsDesc: 'Projects will be visible here once created and featured.',
    completedStatus: 'Completed',
    inProgressStatus: 'In Progress',
    pausedStatus: 'Paused',
    featured: 'Featured',
    details: 'Details',
    dashboardAdmin: 'Admin Dashboard',
    adminAccess: 'Admin Access',
    online: 'Online',
    availableForProjects: 'Available for new projects',
    skillsOverview: 'Skills Overview',
    fullStackDeveloper: 'Full Stack Developer',
    scrollToExplore: 'Scroll to explore',
    technologies: 'Technologies',
    
    // Home page sections
    resultsThatSpeak: 'Results That Speak',
    resultsThatSpeakDesc: 'Numbers that demonstrate excellence and dedication in developed projects',
    projectInMind: 'Have a Project in Mind?',
    projectInMindDesc: 'I\'m a junior developer looking for job opportunities, but I\'m also open to freelance collaborations. Let\'s transform your ideas into innovative digital solutions. Contact me for a free consultation!',
    startNow: 'Start Now',
    viewPortfolio: 'View Portfolio'
  }
}

export const LanguageProvider = ({ children }) => {
  const [language, setLanguage] = useState(() => {
    const saved = localStorage.getItem('portfolio-language')
    return saved || 'it' // Default italiano
  })

  useEffect(() => {
    localStorage.setItem('portfolio-language', language)
    document.documentElement.lang = language
  }, [language])

  const toggleLanguage = () => {
    setLanguage(prev => prev === 'it' ? 'en' : 'it')
  }

  const t = (key) => {
    return translations[language][key] || key
  }

  const value = {
    language,
    setLanguage,
    toggleLanguage,
    t,
    isItalian: language === 'it',
    isEnglish: language === 'en'
  }

  return (
    <LanguageContext.Provider value={value}>
      {children}
    </LanguageContext.Provider>
  )
}

export const useLanguage = () => {
  const context = useContext(LanguageContext)
  if (!context) {
    throw new Error('useLanguage must be used within a LanguageProvider')
  }
  return context
}

export default LanguageContext 
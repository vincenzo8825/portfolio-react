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
    education: 'Formazione',
    experience: 'Esperienza',
    
    // Contact
    contactTitle: 'Contatti',
    contactSubtitle: 'Hai un progetto in mente? Parliamone!',
    name: 'Nome',
    email: 'Email',
    subject: 'Oggetto',
    message: 'Messaggio',
    send: 'Invia Messaggio',
    
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
    by: 'da'
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
    education: 'Education',
    experience: 'Experience',
    
    // Contact
    contactTitle: 'Contact',
    contactSubtitle: 'Have a project in mind? Let\'s talk!',
    name: 'Name',
    email: 'Email', 
    subject: 'Subject',
    message: 'Message',
    send: 'Send Message',
    
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
    by: 'by'
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
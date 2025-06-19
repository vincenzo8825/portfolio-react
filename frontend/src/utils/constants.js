// API Configuration
export const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api'

// Application Settings
export const APP_NAME = 'Vincenzo Rocca Portfolio'
export const APP_VERSION = '1.0.0'

// Contact Information
export const CONTACT_INFO = {
  name: 'Vincenzo Rocca',
  email: 'vincenzorocca88@gmail.com',
  phone: '+39 345 409 8887',
  location: 'Italia',
  socialMedia: {
    github: 'https://github.com/vincenzo8825',
    linkedin: 'https://www.linkedin.com/in/webdevfullstack',
    email: 'mailto:info@vincenzorocca.it'
  }
}

// Project Categories
export const PROJECT_CATEGORIES = {
  ALL: '',
  WEB: 'Web',
  MOBILE: 'Mobile', 
  DESIGN: 'Design',
  OTHER: 'Altri'
}

// Error Messages
export const ERROR_MESSAGES = {
  GENERIC: 'Si è verificato un errore imprevisto',
  NETWORK: 'Errore di connessione. Riprova più tardi.',
  UNAUTHORIZED: 'Accesso non autorizzato',
  FORBIDDEN: 'Non hai i permessi per questa operazione'
} 
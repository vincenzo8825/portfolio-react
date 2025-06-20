// API Configuration
export const API_CONFIG = {
  BASE_URL: import.meta.env.VITE_API_BASE_URL || 'http://127.0.0.1:8000/api/v1',
  TIMEOUT: 10000,
}

// API Endpoints
export const API_ENDPOINTS = {
  // Authentication
  AUTH: {
    LOGIN: '/auth/login',
    LOGOUT: '/auth/logout',
    ME: '/auth/me',
    REFRESH: '/auth/refresh',
    CHANGE_PASSWORD: '/auth/change-password',
  },
  
  // Public endpoints
  PROJECTS: '/projects',
  TECHNOLOGIES: '/technologies',
  CONTACTS: '/contacts',
  
  // Admin endpoints (require authentication)
  ADMIN: {
    PROJECTS: '/admin/projects',
    PROJECTS_TOGGLE_FEATURED: '/admin/projects',
    TECHNOLOGIES: '/admin/technologies',
    CONTACTS: '/admin/contacts',
    CONTACTS_STATS: '/admin/contacts/stats',
  },
  
  // Specific resource endpoints
  PROJECTS_FEATURED: '/projects/featured',
  TECHNOLOGIES_FEATURED: '/technologies/featured',
  TECHNOLOGIES_BY_CATEGORY: '/technologies/by-category',
}

// App Configuration
export const APP_CONFIG = {
  NAME: 'Vincenzo Rocca Portfolio',
  VERSION: '1.0.0',
  AUTHOR: 'Vincenzo Rocca',
  EMAIL: 'vincenzorocca88@gmail.com',
}

// Admin Configuration
export const ADMIN_CONFIG = {
  DEFAULT_CREDENTIALS: {
    EMAIL: 'vincenzorocca88@gmail.com',
    PASSWORD: 'admin123', // Per demo - da cambiare in produzione
  },
  DASHBOARD_ROUTES: {
    PROJECTS: '/admin/projects',
    TECHNOLOGIES: '/admin/technologies',
    CONTACTS: '/admin/contacts',
    SETTINGS: '/admin/settings',
  }
}

// Theme and UI Configuration
export const UI_CONFIG = {
  THEME_STORAGE_KEY: 'portfolio-theme',
  LANGUAGE_STORAGE_KEY: 'portfolio-language',
  AUTH_TOKEN_KEY: 'auth-token',
  ANIMATION_DURATION: 300,
  DEBOUNCE_DELAY: 500,
}

// Social Links
export const SOCIAL_LINKS = {
  GITHUB: 'https://github.com/vincenzo8825',
  LINKEDIN: 'https://www.linkedin.com/in/webdevfullstack/',
  TWITTER: 'https://twitter.com/vincenzorocca',
  EMAIL: 'mailto:vincenzorocca88@gmail.com',
}

// SEO Configuration
export const SEO_CONFIG = {
  TITLE: 'Vincenzo Rocca - Full Stack Developer',
  DESCRIPTION: 'Portfolio di Vincenzo Rocca, Full Stack Developer specializzato in React, Laravel e tecnologie moderne.',
  KEYWORDS: 'Vincenzo Rocca, Full Stack Developer, React, Laravel, JavaScript, PHP, Portfolio',
  AUTHOR: 'Vincenzo Rocca',
  IMAGE: '/og-image.jpg',
  URL: 'https://vincenzorocca.dev',
}

// Application Settings
export const APP_NAME = 'Vincenzo Rocca Portfolio'
export const APP_VERSION = '1.0.0'

// Contact Information
export const CONTACT_INFO = {
  name: 'Vincenzo Rocca',
  email: 'vincenzorocca88@gmail.com',
  phone: '3454098887',
  location: 'Italia',
  socialMedia: {
    github: 'https://github.com/vincenzo8825',
    linkedin: 'https://www.linkedin.com/in/webdevfullstack/',
    email: 'mailto:vincenzorocca88@gmail.com'
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
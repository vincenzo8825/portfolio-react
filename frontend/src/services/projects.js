import { projectsServiceOverride } from './api-config'

// Esporta l'override come servizio principale
export const projectsService = projectsServiceOverride

// Note: projectsServiceOriginal rimosso perché causa errori linter
// Tutte le funzionalità sono implementate in projectsServiceOverride 
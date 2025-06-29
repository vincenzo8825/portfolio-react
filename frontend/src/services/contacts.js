import api from './api.js'

// Service dedicato per la gestione dei contatti
export const contactsService = {
  
  /**
   * Invia un nuovo messaggio di contatto
   */
  async sendMessage(contactData) {
    try {
      console.log('📧 Sending contact message...')
      const response = await api.post('/contacts', contactData)
      
      if (response.data) {
        console.log('✅ Contact message sent successfully')
        return response.data
      }
      
      throw new Error('No response data')
    } catch (error) {
      console.error('❌ Error sending contact message:', error)
      
      // Gestione specifica degli errori
      if (error.response) {
        const status = error.response.status
        const data = error.response.data
        
        if (status === 422) {
          // Errori di validazione
          const validationErrors = data.errors || {}
          const errorMessages = Object.values(validationErrors).flat().join(', ')
          throw new Error(`Validation error: ${errorMessages}`)
        } else if (status === 429) {
          // Rate limiting
          throw new Error('Too many requests. Please wait before sending another message.')
        } else if (status >= 500) {
          // Errori server
          throw new Error('Server error. Please try again later.')
        } else {
          // Altri errori HTTP
          throw new Error(data.message || `HTTP ${status}: ${error.response.statusText}`)
        }
      } else if (error.request) {
        console.error('Network error:', error.request)
        throw new Error('Network error. Please check your connection.')
      } else {
        console.error('Generic error:', error.message)
        throw new Error('Failed to send message')
      }
    }
  },

  /**
   * Ottiene tutti i messaggi (admin only)
   */
  async getAll() {
    try {
      console.log('📬 Fetching all contacts...')
      const response = await api.get('/admin/contacts')
      
      if (response.data) {
        console.log('✅ Contacts loaded successfully:', response.data.length || 0)
        return response.data
      }
      
      return []
    } catch (error) {
      console.error('❌ Error fetching contacts:', error)
      throw new Error('Failed to fetch contacts')
    }
  },

  /**
   * Ottiene statistiche contatti (admin only)
   */
  async getStats() {
    try {
      console.log('📊 Fetching contact stats...')
      const response = await api.get('/admin/contacts/stats')
      
      if (response.data) {
        console.log('✅ Contact stats loaded successfully')
        return response.data
      }
      
      return {}
    } catch (error) {
      console.error('❌ Error fetching contact stats:', error)
      throw new Error('Failed to fetch contact stats')
    }
  },

  /**
   * Test di connessione API contatti
   */
  async testConnection() {
    try {
      console.log('🧪 Testing contacts API connection...')
      const response = await api.get('/test')
      
      if (response.data) {
        console.log('✅ Contacts API connection successful')
        return true
      }
      
      return false
    } catch (error) {
      console.error('❌ Contacts API connection failed:', error)
      return false
    }
  }
}

export default contactsService 
import { apiService } from './api'

export const contactService = {
  // Send contact form email
  async sendContactMessage(messageData) {
    const response = await apiService.post('/contact', messageData)
    return response.data
  }
} 
import { useState } from 'react'
import { useAuth } from '../../context/AuthContext'
import { useNotification } from '../../context/NotificationContext'
import { useLanguage } from '../../context/LanguageContext'
import { authService } from '../../services/auth'

const ChangePassword = () => {
  const { t } = useLanguage()
  const { user } = useAuth()
  const { showNotification } = useNotification()
  
  const [passwords, setPasswords] = useState({
    current_password: '',
    new_password: '',
    new_password_confirmation: ''
  })
  const [loading, setLoading] = useState(false)
  const [showCurrentPassword, setShowCurrentPassword] = useState(false)
  const [showNewPassword, setShowNewPassword] = useState(false)
  const [showConfirmPassword, setShowConfirmPassword] = useState(false)

  const handleChange = (e) => {
    const { name, value } = e.target
    setPasswords(prev => ({
      ...prev,
      [name]: value
    }))
  }

  const validateForm = () => {
    if (!passwords.current_password) {
      showNotification('Inserisci la password attuale', 'error')
      return false
    }
    
    if (!passwords.new_password) {
      showNotification('Inserisci la nuova password', 'error')
      return false
    }
    
    if (passwords.new_password.length < 8) {
      showNotification('La nuova password deve essere di almeno 8 caratteri', 'error')
      return false
    }
    
    if (passwords.new_password !== passwords.new_password_confirmation) {
      showNotification('Le password non coincidono', 'error')
      return false
    }
    
    return true
  }

  const handleSubmit = async (e) => {
    e.preventDefault()
    
    if (!validateForm()) return
    
    setLoading(true)
    
    try {
      await authService.changePassword(passwords)
      
      showNotification('Password cambiata con successo!', 'success')
      
      // Reset form
      setPasswords({
        current_password: '',
        new_password: '',
        new_password_confirmation: ''
      })
      
    } catch (error) {
      console.error('Password change error:', error)
      showNotification(
        error.message || 'Errore durante il cambio password',
        'error'
      )
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="min-h-screen py-8">
      <div className="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Header */}
        <div className="mb-8">
          <div className="flex items-center mb-4">
            <button
              onClick={() => window.history.back()}
              className="mr-4 p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors"
            >
              <i className="fas fa-arrow-left text-xl"></i>
            </button>
            <div>
              <h1 className="text-3xl font-bold text-gray-900 dark:text-white">
                Cambia Password
              </h1>
              <p className="text-gray-600 dark:text-gray-400 mt-2">
                Aggiorna la password del tuo account amministratore
              </p>
            </div>
          </div>
        </div>

        {/* User Info Card */}
        <div className="bg-white dark:bg-dark-800 rounded-lg p-6 shadow-lg border border-gray-200 dark:border-dark-600 mb-8">
          <div className="flex items-center">
            <div className="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center mr-4">
              <i className="fas fa-user text-primary-600 dark:text-primary-400 text-xl"></i>
            </div>
            <div>
              <h3 className="text-lg font-semibold text-gray-900 dark:text-white">
                {user?.name}
              </h3>
              <p className="text-gray-600 dark:text-gray-400">
                {user?.email}
              </p>
            </div>
          </div>
        </div>

        {/* Password Change Form */}
        <div className="bg-white dark:bg-dark-800 rounded-lg shadow-lg border border-gray-200 dark:border-dark-600">
          <form onSubmit={handleSubmit} className="p-6 space-y-6">
            
            {/* Current Password */}
            <div>
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Password Attuale *
              </label>
              <div className="relative">
                <input
                  type={showCurrentPassword ? 'text' : 'password'}
                  name="current_password"
                  value={passwords.current_password}
                  onChange={handleChange}
                  className="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-dark-700 text-gray-900 dark:text-white pr-12"
                  placeholder="Inserisci la password attuale"
                  required
                />
                <button
                  type="button"
                  onClick={() => setShowCurrentPassword(!showCurrentPassword)}
                  className="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300"
                >
                  <i className={`fas ${showCurrentPassword ? 'fa-eye-slash' : 'fa-eye'}`}></i>
                </button>
              </div>
            </div>

            {/* New Password */}
            <div>
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Nuova Password *
              </label>
              <div className="relative">
                <input
                  type={showNewPassword ? 'text' : 'password'}
                  name="new_password"
                  value={passwords.new_password}
                  onChange={handleChange}
                  className="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-dark-700 text-gray-900 dark:text-white pr-12"
                  placeholder="Inserisci la nuova password (min. 8 caratteri)"
                  required
                  minLength={8}
                />
                <button
                  type="button"
                  onClick={() => setShowNewPassword(!showNewPassword)}
                  className="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300"
                >
                  <i className={`fas ${showNewPassword ? 'fa-eye-slash' : 'fa-eye'}`}></i>
                </button>
              </div>
              <div className="mt-2 text-xs text-gray-500 dark:text-gray-400">
                La password deve contenere almeno 8 caratteri
              </div>
            </div>

            {/* Confirm Password */}
            <div>
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Conferma Nuova Password *
              </label>
              <div className="relative">
                <input
                  type={showConfirmPassword ? 'text' : 'password'}
                  name="new_password_confirmation"
                  value={passwords.new_password_confirmation}
                  onChange={handleChange}
                  className="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-dark-700 text-gray-900 dark:text-white pr-12"
                  placeholder="Conferma la nuova password"
                  required
                />
                <button
                  type="button"
                  onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                  className="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300"
                >
                  <i className={`fas ${showConfirmPassword ? 'fa-eye-slash' : 'fa-eye'}`}></i>
                </button>
              </div>
            </div>

            {/* Security Notice */}
            <div className="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
              <div className="flex">
                <i className="fas fa-shield-alt text-yellow-600 dark:text-yellow-500 mt-0.5 mr-3"></i>
                <div>
                  <h4 className="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                    Nota sulla sicurezza
                  </h4>
                  <p className="text-sm text-yellow-700 dark:text-yellow-300 mt-1">
                    Dopo aver cambiato la password, verrai disconnesso automaticamente da tutti i dispositivi e dovrai effettuare nuovamente l'accesso.
                  </p>
                </div>
              </div>
            </div>

            {/* Submit Button */}
            <div className="flex justify-end space-x-4">
              <button
                type="button"
                onClick={() => window.history.back()}
                className="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-dark-700 transition-colors"
              >
                Annulla
              </button>
              <button
                type="submit"
                disabled={loading}
                className="px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center"
              >
                {loading ? (
                  <>
                    <div className="spinner mr-2"></div>
                    Cambiando password...
                  </>
                ) : (
                  <>
                    <i className="fas fa-key mr-2"></i>
                    Cambia Password
                  </>
                )}
              </button>
            </div>

          </form>
        </div>

      </div>
    </div>
  )
}

export default ChangePassword 
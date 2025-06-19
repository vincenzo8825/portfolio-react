import { useState, useEffect } from 'react'
import { useAuth } from '../context/AuthContext'
import { useNavigate, useLocation } from 'react-router-dom'
import { useNotification } from '../context/NotificationContext'

const Login = () => {
  const [credentials, setCredentials] = useState({
    email: '',
    password: ''
  })
  const [isLoading, setIsLoading] = useState(false)
  
  const { login, isAuthenticated } = useAuth()
  const { showError, showSuccess } = useNotification()
  const navigate = useNavigate()
  const location = useLocation()

  const from = location.state?.from?.pathname || '/admin'

  // Redirect if already authenticated
  useEffect(() => {
    if (isAuthenticated) {
      navigate(from, { replace: true })
    }
  }, [isAuthenticated, navigate, from])

  const handleChange = (e) => {
    setCredentials({
      ...credentials,
      [e.target.name]: e.target.value
    })
  }

  const handleSubmit = async (e) => {
    e.preventDefault()
    setIsLoading(true)

    try {
      const result = await login(credentials)
      
      if (result.success) {
        showSuccess('Login effettuato con successo!')
        navigate(from, { replace: true })
      } else {
        showError(result.error || 'Errore durante il login')
      }
    } catch (error) {
      console.error('Login error:', error)
      showError('Errore di connessione. Riprova più tardi.')
    } finally {
      setIsLoading(false)
    }
  }

  return (
    <div className="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
      <div className="max-w-md w-full space-y-8">
        
        {/* Header */}
        <div className="text-center">
          <div className="w-16 h-16 mx-auto bg-gradient-to-r from-primary-500 to-accent-500 rounded-full flex items-center justify-center text-white font-bold text-xl mb-4">
            VR
          </div>
          <h2 className="text-3xl font-bold text-gray-900 dark:text-white">
            Accesso Admin
          </h2>
          <p className="mt-2 text-sm text-gray-600 dark:text-gray-400">
            Inserisci le tue credenziali per accedere al pannello di controllo
          </p>
        </div>

        {/* Login Form */}
        <form className="mt-8 space-y-6" onSubmit={handleSubmit}>
          <div className="space-y-4">
            <div>
              <label htmlFor="email" className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Email
              </label>
              <input
                id="email"
                name="email"
                type="email"
                required
                value={credentials.email}
                onChange={handleChange}
                className="form-input"
                placeholder="admin@vincenzorocca.it"
                disabled={isLoading}
              />
            </div>

            <div>
              <label htmlFor="password" className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Password
              </label>
              <input
                id="password"
                name="password"
                type="password"
                required
                value={credentials.password}
                onChange={handleChange}
                className="form-input"
                placeholder="••••••••"
                disabled={isLoading}
              />
            </div>
          </div>

          <div>
            <button
              type="submit"
              disabled={isLoading}
              className="btn-primary w-full disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {isLoading ? (
                <>
                  <div className="spinner mr-2"></div>
                  Accesso in corso...
                </>
              ) : (
                <>
                  <i className="fas fa-sign-in-alt mr-2"></i>
                  Accedi
                </>
              )}
            </button>
          </div>

          {/* Demo Credentials */}
          <div className="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mt-6">
            <h4 className="text-sm font-medium text-blue-800 dark:text-blue-300 mb-2">
              <i className="fas fa-info-circle mr-1"></i>
              Credenziali Demo
            </h4>
            <p className="text-xs text-blue-700 dark:text-blue-400">
              Email: admin@vincenzorocca.it<br />
              Password: password123
            </p>
          </div>

          {/* Back to Home */}
          <div className="text-center">
            <a
              href="/"
              className="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-500 dark:hover:text-primary-300"
            >
              <i className="fas fa-arrow-left mr-1"></i>
              Torna alla Home
            </a>
          </div>
        </form>

      </div>
    </div>
  )
}

export default Login 
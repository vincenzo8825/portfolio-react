import { useAuth } from '../../context/AuthContext'
import { Navigate, useLocation } from 'react-router-dom'

const ProtectedRoute = ({ children }) => {
  const { isAuthenticated, isAdmin, isLoading } = useAuth()
  const location = useLocation()

  // Show loading while checking authentication
  if (isLoading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="text-center">
          <div className="spinner mx-auto mb-4"></div>
          <p className="text-gray-600 dark:text-gray-400">
            Verifica autenticazione...
          </p>
        </div>
      </div>
    )
  }

  // Redirect to login if not authenticated
  if (!isAuthenticated) {
    return <Navigate to="/login" state={{ from: location }} replace />
  }

  // Redirect to home if not admin
  if (!isAdmin()) {
    return <Navigate to="/" replace />
  }

  return children
}

export default ProtectedRoute 
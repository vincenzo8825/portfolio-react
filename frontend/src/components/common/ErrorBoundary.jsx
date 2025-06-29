import React from 'react'

class ErrorBoundary extends React.Component {
  constructor(props) {
    super(props)
    this.state = { hasError: false, error: null, errorInfo: null }
  }

  static getDerivedStateFromError() {
    return { hasError: true }
  }

  componentDidCatch(error, errorInfo) {
    console.error('ErrorBoundary caught an error:', error, errorInfo);
    this.setState({
      error: error,
      errorInfo: errorInfo
    });
  }

  render() {
    if (this.state.hasError) {
      return (
        <div className="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-dark-900">
          <div className="max-w-md w-full mx-4">
            <div className="bg-white dark:bg-dark-800 rounded-lg shadow-xl p-8 text-center">
              <div className="mb-6">
                <i className="fas fa-exclamation-triangle text-6xl text-red-500 mb-4"></i>
                <h1 className="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                  Oops! Qualcosa è andato storto
                </h1>
                <p className="text-gray-600 dark:text-gray-400">
                  Si è verificato un errore imprevisto. Ricarica la pagina per continuare.
                </p>
              </div>
              
              <div className="space-y-4">
                <button
                  onClick={() => window.location.reload()}
                  className="btn-primary w-full"
                >
                  <i className="fas fa-refresh mr-2"></i>
                  Ricarica la pagina
                </button>
                
                <button
                  onClick={() => window.location.href = '/'}
                  className="btn-secondary w-full"
                >
                  <i className="fas fa-home mr-2"></i>
                  Torna alla Home
                </button>
              </div>
            </div>
          </div>
        </div>
      )
    }

    return this.props.children
  }
}

export default ErrorBoundary 
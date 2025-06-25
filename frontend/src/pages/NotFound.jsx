import { Link } from 'react-router-dom'
import useDocumentTitle from '../hooks/useDocumentTitle'

const NotFound = () => {
  useDocumentTitle('404 - Pagina Non Trovata')

  return (
    <div className="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 flex items-center justify-center relative overflow-hidden">
      {/* Animated background */}
      <div className="absolute inset-0">
        <div className="absolute top-20 left-10 w-72 h-72 bg-gradient-to-r from-blue-400/10 to-purple-400/10 rounded-full blur-3xl animate-float"></div>
        <div className="absolute bottom-20 right-10 w-96 h-96 bg-gradient-to-r from-green-400/10 to-cyan-400/10 rounded-full blur-3xl animate-float-delay"></div>
        <div className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-gradient-to-r from-pink-400/10 to-orange-400/10 rounded-full blur-3xl animate-pulse"></div>
      </div>

      <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">
        {/* 404 Number */}
        <div className="mb-8">
          <h1 className="text-9xl lg:text-[12rem] font-bold bg-gradient-to-r from-primary-600 via-accent-500 to-pink-500 bg-clip-text text-transparent leading-none animate-bounce-slow">
            404
          </h1>
        </div>

        {/* Error Icon */}
        <div className="mb-8">
          <div className="w-24 h-24 mx-auto rounded-full bg-gradient-to-r from-red-500 to-pink-500 flex items-center justify-center shadow-xl shadow-red-500/25 animate-pulse">
            <i className="fas fa-exclamation-triangle text-3xl text-white"></i>
          </div>
        </div>

        {/* Title */}
        <h2 className="text-4xl lg:text-5xl font-bold mb-6 text-gray-900 dark:text-white">
          Oops! Pagina Non Trovata
        </h2>

        {/* Description */}
        <p className="text-xl text-gray-600 dark:text-gray-300 mb-8 max-w-2xl mx-auto leading-relaxed">
          La pagina che stai cercando sembra essere sparita nel vuoto digitale. 
          Ma non preoccuparti, posso aiutarti a tornare sulla strada giusta!
        </p>

        {/* Error Details */}
        <div className="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl p-6 mb-8 border border-white/20 dark:border-slate-700/50 shadow-xl max-w-md mx-auto">
          <div className="flex items-center justify-center space-x-4 text-gray-600 dark:text-gray-400">
            <div className="flex items-center">
              <i className="fas fa-globe mr-2"></i>
              <span className="text-sm">Errore: 404</span>
            </div>
            <div className="w-px h-4 bg-gray-300 dark:bg-gray-600"></div>
            <div className="flex items-center">
              <i className="fas fa-clock mr-2"></i>
              <span className="text-sm">{new Date().toLocaleTimeString()}</span>
            </div>
          </div>
        </div>

        {/* Action Buttons */}
        <div className="flex flex-col sm:flex-row gap-4 justify-center items-center mb-8">
          <Link
            to="/"
            className="group relative px-8 py-4 bg-gradient-to-r from-primary-600 to-accent-600 text-white font-semibold rounded-xl shadow-xl shadow-primary-500/25 hover:shadow-primary-500/40 transition-all duration-300 transform hover:-translate-y-1 hover:scale-105"
          >
            <span className="relative z-10 flex items-center">
              <i className="fas fa-home mr-2"></i>
              Torna alla Home
            </span>
            <div className="absolute inset-0 bg-gradient-to-r from-primary-700 to-accent-700 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </Link>

          <Link
            to="/projects"
            className="group relative px-8 py-4 bg-white dark:bg-slate-800 text-gray-900 dark:text-white border-2 border-gray-200 dark:border-slate-600 font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1"
          >
            <span className="flex items-center">
              <i className="fas fa-folder-open mr-2"></i>
              Vedi i Progetti
            </span>
          </Link>

          <button
            onClick={() => window.history.back()}
            className="group relative px-8 py-4 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1"
          >
            <span className="flex items-center">
              <i className="fas fa-arrow-left mr-2"></i>
              Pagina Precedente
            </span>
          </button>
        </div>

        {/* Quick Links */}
        <div className="bg-white/60 dark:bg-slate-800/60 backdrop-blur-xl rounded-2xl p-6 border border-white/20 dark:border-slate-700/50 shadow-xl">
          <h3 className="text-lg font-semibold mb-4 text-gray-900 dark:text-white">
            Link Utili
          </h3>
          <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
            <Link
              to="/"
              className="group flex flex-col items-center p-3 rounded-xl hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors duration-200"
            >
              <i className="fas fa-home text-2xl text-primary-600 group-hover:text-primary-700 mb-2"></i>
              <span className="text-sm font-medium text-gray-700 dark:text-gray-300">Home</span>
            </Link>
            <Link
              to="/about"
              className="group flex flex-col items-center p-3 rounded-xl hover:bg-accent-50 dark:hover:bg-accent-900/20 transition-colors duration-200"
            >
              <i className="fas fa-user text-2xl text-accent-600 group-hover:text-accent-700 mb-2"></i>
              <span className="text-sm font-medium text-gray-700 dark:text-gray-300">Chi Sono</span>
            </Link>
            <Link
              to="/projects"
              className="group flex flex-col items-center p-3 rounded-xl hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors duration-200"
            >
              <i className="fas fa-code text-2xl text-green-600 group-hover:text-green-700 mb-2"></i>
              <span className="text-sm font-medium text-gray-700 dark:text-gray-300">Progetti</span>
            </Link>
            <Link
              to="/contact"
              className="group flex flex-col items-center p-3 rounded-xl hover:bg-pink-50 dark:hover:bg-pink-900/20 transition-colors duration-200"
            >
              <i className="fas fa-envelope text-2xl text-pink-600 group-hover:text-pink-700 mb-2"></i>
              <span className="text-sm font-medium text-gray-700 dark:text-gray-300">Contatti</span>
            </Link>
          </div>
        </div>

        {/* Fun Message */}
        <div className="mt-8 text-sm text-gray-500 dark:text-gray-400">
          <p>💡 <strong>Tip:</strong> Usa la barra di navigazione per esplorare il portfolio</p>
        </div>
      </div>

      {/* Floating Elements */}
      <div className="absolute top-10 left-10 w-4 h-4 bg-primary-400 rounded-full animate-float opacity-60"></div>
      <div className="absolute top-20 right-20 w-6 h-6 bg-accent-400 rounded-full animate-float-delay opacity-60"></div>
      <div className="absolute bottom-10 left-20 w-3 h-3 bg-pink-400 rounded-full animate-float opacity-60"></div>
      <div className="absolute bottom-20 right-10 w-5 h-5 bg-green-400 rounded-full animate-float-delay opacity-60"></div>
    </div>
  )
}

export default NotFound 
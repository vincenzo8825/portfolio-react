import { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import { useTheme } from '../../context/ThemeContext'
import { useLanguage } from '../../context/LanguageContext'
import { useAuth } from '../../context/AuthContext'

const Header = ({ onToggleSidebar }) => {
  const { isDark, toggleTheme } = useTheme()
  const { t, toggleLanguage, language } = useLanguage()
  const { isAuthenticated } = useAuth()
  
  // Orologio in tempo reale
  const [currentTime, setCurrentTime] = useState(new Date())

  useEffect(() => {
    const timer = setInterval(() => {
      setCurrentTime(new Date())
    }, 1000)

    return () => clearInterval(timer)
  }, [])

  const formatTime = (date) => {
    return date.toLocaleTimeString('it-IT', {
      hour: '2-digit',
      minute: '2-digit'
    })
  }

  return (
    <header className="fixed top-0 left-0 right-0 h-16 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-white/20 dark:border-slate-700/50 shadow-lg shadow-black/5 dark:shadow-black/20 z-40">
      <div className="flex items-center justify-between h-full px-4">
        {/* Menu button con design migliorato - sempre visibile */}
        <button
          onClick={onToggleSidebar}
          className="group relative w-10 h-10 rounded-xl bg-gradient-to-r from-primary-500/10 to-accent-500/10 hover:from-primary-500/20 hover:to-accent-500/20 flex items-center justify-center text-primary-600 dark:text-primary-400 transition-all duration-300 hover:scale-105"
          aria-label="Toggle menu"
        >
          <i className="fas fa-bars text-lg group-hover:scale-110 transition-transform duration-300"></i>
          <div className="absolute inset-0 rounded-xl bg-gradient-to-r from-primary-400/20 to-accent-400/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        </button>

        {/* Centro: Logo/Brand con orologio */}
        <div className="flex items-center space-x-4">
          {/* Orologio compatto */}
          <div className="hidden sm:flex items-center px-3 py-1.5 rounded-xl bg-gradient-to-r from-primary-500/10 to-accent-500/10 border border-primary-200/30 dark:border-primary-700/30">
            <i className="fas fa-clock text-primary-600 dark:text-primary-400 text-sm mr-2"></i>
            <span className="text-sm font-mono font-bold bg-gradient-to-r from-primary-600 to-accent-500 bg-clip-text text-transparent">
              {formatTime(currentTime)}
            </span>
          </div>
          
          {/* Logo e nome */}
          <div className="flex items-center space-x-2">
            <div className="w-8 h-8 rounded-xl bg-gradient-to-br from-primary-500 via-accent-500 to-pink-500 p-0.5 shadow-lg">
              <div className="w-full h-full rounded-xl bg-white dark:bg-slate-800 flex items-center justify-center">
                <span className="text-sm font-bold bg-gradient-to-r from-primary-600 to-accent-500 bg-clip-text text-transparent">
                  VR
                </span>
              </div>
            </div>
            <span className="font-bold text-gray-900 dark:text-white hidden sm:block">
              Vincenzo Rocca
            </span>
          </div>
        </div>

        {/* Actions con design premium */}
        <div className="flex items-center space-x-2">
          {/* Login button - visibile solo se non autenticato */}
          {!isAuthenticated && (
            <Link
              to="/login"
              className="group relative px-4 py-2 rounded-xl bg-gradient-to-r from-primary-500/10 to-accent-500/10 hover:from-primary-500/20 hover:to-accent-500/20 flex items-center text-primary-600 dark:text-primary-400 transition-all duration-300 hover:scale-105 font-medium text-sm shadow-lg hover:shadow-xl border border-primary-200/30 dark:border-primary-700/30"
              aria-label="Accedi"
            >
              <i className="fas fa-sign-in-alt mr-2 text-sm group-hover:scale-110 transition-transform duration-300"></i>
              <span className="hidden sm:inline">Admin</span>
              <div className="absolute inset-0 rounded-xl bg-gradient-to-r from-primary-400/20 to-accent-400/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </Link>
          )}

          {/* Language toggle */}
          <button
            onClick={toggleLanguage}
            className="group relative w-10 h-10 rounded-xl bg-gradient-to-r from-blue-100 to-indigo-200 dark:from-blue-900/30 dark:to-indigo-900/30 hover:from-blue-200 hover:to-indigo-300 dark:hover:from-blue-800/40 dark:hover:to-indigo-800/40 flex items-center justify-center text-blue-600 dark:text-blue-400 transition-all duration-300 hover:scale-105 font-bold text-xs shadow-lg hover:shadow-xl"
            aria-label={t('language')}
          >
            {language.toUpperCase()}
            <div className="absolute inset-0 rounded-xl bg-gradient-to-r from-blue-400/20 to-indigo-400/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </button>
          
          {/* Theme toggle */}
          <button
            onClick={toggleTheme}
            className="group relative w-10 h-10 rounded-xl bg-gradient-to-r from-yellow-100 to-orange-200 dark:from-slate-700 dark:to-slate-600 hover:from-yellow-200 hover:to-orange-300 dark:hover:from-yellow-900/30 dark:hover:to-orange-900/30 flex items-center justify-center text-yellow-600 dark:text-yellow-400 transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-xl"
            aria-label={isDark ? t('lightMode') : t('darkMode')}
          >
            <i className={`fas ${isDark ? 'fa-sun' : 'fa-moon'} text-lg group-hover:scale-110 transition-transform duration-300`}></i>
            <div className="absolute inset-0 rounded-xl bg-gradient-to-r from-yellow-400/20 to-orange-400/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </button>
        </div>
      </div>
    </header>
  )
}

export default Header 
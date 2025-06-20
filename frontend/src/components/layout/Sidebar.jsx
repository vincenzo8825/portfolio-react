import { Link, useLocation } from 'react-router-dom'
import { useState, useEffect } from 'react'
import { useAuth } from '../../context/AuthContext'
import { useTheme } from '../../context/ThemeContext'
import { useLanguage } from '../../context/LanguageContext'

const Sidebar = ({ isOpen, onClose, isAdmin }) => {
  const location = useLocation()
  const { isAuthenticated, logout, user } = useAuth()
  const { isDark, toggleTheme } = useTheme()
  const { t, toggleLanguage, language } = useLanguage()
  
  // Orologio in tempo reale
  const [currentTime, setCurrentTime] = useState(new Date())

  useEffect(() => {
    const timer = setInterval(() => {
      setCurrentTime(new Date())
    }, 1000)

    return () => clearInterval(timer)
  }, [])

  // Navigation items
  const publicNavItems = [
    { path: '/', label: t('home'), icon: 'fas fa-home', gradient: 'from-blue-500 to-purple-500' },
    { path: '/about', label: t('about'), icon: 'fas fa-user', gradient: 'from-green-500 to-teal-500' },
    { path: '/projects', label: t('projects'), icon: 'fas fa-folder', gradient: 'from-purple-500 to-pink-500' },
    { path: '/contact', label: t('contact'), icon: 'fas fa-envelope', gradient: 'from-orange-500 to-red-500' }
  ]

  const adminNavItems = [
    { path: '/admin', label: t('dashboard'), icon: 'fas fa-tachometer-alt', gradient: 'from-indigo-500 to-purple-500' },
    { path: '/admin/projects', label: t('projectManagement'), icon: 'fas fa-folder-open', gradient: 'from-blue-500 to-cyan-500' },
    { path: '/admin/projects/create', label: t('newProject'), icon: 'fas fa-plus', gradient: 'from-green-500 to-emerald-500' }
  ]

  const navItems = isAdmin ? adminNavItems : publicNavItems

  const isActive = (path) => {
    if (path === '/') {
      return location.pathname === '/'
    }
    return location.pathname.startsWith(path)
  }

  const handleLogout = async () => {
    await logout()
    onClose()
  }

  const formatTime = (date) => {
    return date.toLocaleTimeString('it-IT', {
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit'
    })
  }

  const formatDate = (date) => {
    return date.toLocaleDateString('it-IT', {
      weekday: 'short',
      day: '2-digit',
      month: 'short'
    })
  }

  return (
    <>
      {/* Sidebar - sempre apribile/chiudibile */}
      <aside className={`
        fixed top-0 left-0 h-full w-72 
        bg-gradient-to-br from-white/95 via-white/90 to-white/95 
        dark:from-slate-900/95 dark:via-slate-800/90 dark:to-slate-900/95
        backdrop-blur-xl border-r border-white/20 dark:border-slate-700/50
        shadow-2xl shadow-black/10 dark:shadow-black/30 z-50 
        transform transition-all duration-500 ease-out
        ${isOpen ? 'translate-x-0' : '-translate-x-full'}
      `}>
        
        {/* Close button */}
        <button
          onClick={onClose}
          className="absolute top-4 right-4 z-10 w-8 h-8 rounded-xl bg-gradient-to-r from-gray-100 to-gray-200 dark:from-slate-700 dark:to-slate-600 flex items-center justify-center text-gray-600 dark:text-gray-400 hover:from-red-100 hover:to-red-200 dark:hover:from-red-900/30 dark:hover:to-red-800/30 transition-all duration-300 shadow-lg hover:shadow-xl"
        >
          <i className="fas fa-times text-sm"></i>
        </button>
        
        {/* Header with enhanced profile */}
        <div className="p-6 border-b border-white/20 dark:border-slate-700/50">
          {/* Orologio digitale */}
          <div className="mb-4 text-center">
            <div className="inline-flex flex-col items-center p-3 rounded-2xl bg-gradient-to-r from-primary-500/10 to-accent-500/10 border border-primary-200/30 dark:border-primary-700/30">
              <div className="text-2xl font-mono font-bold bg-gradient-to-r from-primary-600 to-accent-500 bg-clip-text text-transparent">
                {formatTime(currentTime)}
              </div>
              <div className="text-xs text-gray-500 dark:text-gray-400 font-medium">
                {formatDate(currentTime)}
              </div>
            </div>
          </div>

          <div className="flex items-center space-x-4">
            {/* Avatar con glow effect */}
            <div className="relative">
              <div className="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-500 via-accent-500 to-pink-500 p-0.5 shadow-lg">
                <div className="w-full h-full rounded-2xl bg-white dark:bg-slate-800 flex items-center justify-center">
                  <span className="text-lg font-bold bg-gradient-to-r from-primary-600 to-accent-500 bg-clip-text text-transparent">
                    VR
                  </span>
                </div>
              </div>
              {/* Status indicator */}
              <div className="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white dark:border-slate-800 animate-pulse"></div>
            </div>
            
            <div className="flex-1 min-w-0">
              <h2 className="text-lg font-bold text-gray-900 dark:text-white truncate">
                Vincenzo Rocca
              </h2>
              <p className="text-sm bg-gradient-to-r from-primary-600 to-accent-500 bg-clip-text text-transparent font-medium truncate">
                {isAdmin ? t('adminPanel') : t('heroBadge')}
              </p>
              {user && (
                <div className="flex items-center mt-1">
                  <div className="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                  <span className="text-xs text-gray-500 dark:text-gray-400">{t('online')}</span>
                </div>
              )}
            </div>
          </div>
        </div>

        {/* Navigation con design migliorato */}
        <nav className="flex-1 p-4 overflow-y-auto">
          <ul className="space-y-2">
            {navItems.map((item) => (
              <li key={item.path}>
                <Link
                  to={item.path}
                  onClick={onClose}
                  className={`
                    group flex items-center px-4 py-3.5 text-sm font-medium rounded-2xl
                    transition-all duration-300 relative overflow-hidden
                    ${isActive(item.path)
                      ? 'bg-gradient-to-r from-primary-500/20 to-accent-500/20 text-primary-700 dark:text-primary-300 shadow-lg shadow-primary-500/20'
                      : 'text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-gray-100/80 hover:to-gray-50/80 dark:hover:from-slate-800/80 dark:hover:to-slate-700/80'
                    }
                  `}
                >
                  {/* Icon con gradiente */}
                  <div className={`
                    w-10 h-10 rounded-xl flex items-center justify-center mr-3 transition-all duration-300
                    ${isActive(item.path) 
                      ? `bg-gradient-to-r ${item.gradient} text-white shadow-lg` 
                      : `bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-400 
                         group-hover:bg-gradient-to-r group-hover:${item.gradient} 
                         ${item.path === '/' ? 'group-hover:from-blue-500 group-hover:to-blue-600' :
                           item.path === '/about' ? 'group-hover:from-green-500 group-hover:to-green-600' :
                           item.path === '/projects' ? 'group-hover:from-purple-500 group-hover:to-purple-600' :
                           item.path === '/contact' ? 'group-hover:from-pink-500 group-hover:to-pink-600' :
                           'group-hover:from-blue-500 group-hover:to-blue-600'} 
                         group-hover:text-white`
                    }
                  `}>
                    <i className={`${item.icon} text-base`}></i>
                  </div>
                  
                  <span className="truncate flex-1">{item.label}</span>
                  
                  {isActive(item.path) && (
                    <div className="w-2 h-2 bg-primary-500 rounded-full animate-pulse"></div>
                  )}

                  {/* Hover effect background */}
                  <div className="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 -skew-x-12 transform translate-x-full group-hover:translate-x-[-100%] transition-transform duration-700"></div>
                </Link>
              </li>
            ))}
          </ul>

          {/* Admin section migliorata */}
          {isAuthenticated ? (
            <>
              <div className="my-6">
                <div className="h-px bg-gradient-to-r from-transparent via-gray-300 dark:via-slate-600 to-transparent"></div>
              </div>
              
              {/* Pannello Admin - sempre visibile per utenti autenticati */}
              <div className="space-y-2">
                <Link
                  to="/admin"
                  onClick={onClose}
                  className="group flex items-center px-4 py-3.5 text-sm font-medium rounded-2xl text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-indigo-100/80 hover:to-purple-100/80 dark:hover:from-indigo-900/30 dark:hover:to-purple-900/30 transition-all duration-300 relative overflow-hidden"
                >
                  <div className="w-10 h-10 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center mr-3 text-white shadow-lg">
                    <i className="fas fa-tachometer-alt text-base"></i>
                  </div>
                  <span className="flex-1">{t('dashboardAdmin')}</span>
                  <i className="fas fa-arrow-right text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
                </Link>

                <Link
                  to="/admin/projects/create"
                  onClick={onClose}
                  className="group flex items-center px-4 py-3.5 text-sm font-medium rounded-2xl text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-green-100/80 hover:to-emerald-100/80 dark:hover:from-green-900/30 dark:hover:to-emerald-900/30 transition-all duration-300 relative overflow-hidden"
                >
                  <div className="w-10 h-10 rounded-xl bg-gradient-to-r from-green-500 to-emerald-500 flex items-center justify-center mr-3 text-white shadow-lg">
                    <i className="fas fa-plus text-base"></i>
                  </div>
                  <span className="flex-1">{t('newProject')}</span>
                  <i className="fas fa-arrow-right text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
                </Link>

                <Link
                  to="/admin/projects"
                  onClick={onClose}
                  className="group flex items-center px-4 py-3.5 text-sm font-medium rounded-2xl text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-blue-100/80 hover:to-cyan-100/80 dark:hover:from-blue-900/30 dark:hover:to-cyan-900/30 transition-all duration-300 relative overflow-hidden"
                >
                  <div className="w-10 h-10 rounded-xl bg-gradient-to-r from-blue-500 to-cyan-500 flex items-center justify-center mr-3 text-white shadow-lg">
                    <i className="fas fa-folder-open text-base"></i>
                  </div>
                  <span className="flex-1">{t('manageProjects')}</span>
                  <i className="fas fa-arrow-right text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
                </Link>
              </div>

              <button
                onClick={handleLogout}
                className="group w-full flex items-center px-4 py-3.5 text-sm font-medium rounded-2xl text-red-600 dark:text-red-400 hover:bg-gradient-to-r hover:from-red-50 hover:to-red-100 dark:hover:from-red-900/20 dark:hover:to-red-800/20 transition-all duration-300 relative overflow-hidden mt-4"
              >
                <div className="w-10 h-10 rounded-xl bg-gradient-to-r from-red-500 to-red-600 flex items-center justify-center mr-3 text-white shadow-lg">
                  <i className="fas fa-sign-out-alt text-base"></i>
                </div>
                <span className="flex-1">{t('logout')}</span>
                <i className="fas fa-arrow-right text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
              </button>
            </>
          ) : (
            <>
              <div className="my-6">
                <div className="h-px bg-gradient-to-r from-transparent via-gray-300 dark:via-slate-600 to-transparent"></div>
              </div>
              
              {/* Login Button per utenti non autenticati */}
              <Link
                to="/login"
                onClick={onClose}
                className="group flex items-center px-4 py-3.5 text-sm font-medium rounded-2xl text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-primary-100/80 hover:to-accent-100/80 dark:hover:from-primary-900/30 dark:hover:to-accent-900/30 transition-all duration-300 relative overflow-hidden"
              >
                <div className="w-10 h-10 rounded-xl bg-gradient-to-r from-primary-500 to-accent-500 flex items-center justify-center mr-3 text-white shadow-lg">
                  <i className="fas fa-sign-in-alt text-base"></i>
                </div>
                <span className="flex-1">{t('adminAccess')}</span>
                <i className="fas fa-arrow-right text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
              </Link>
            </>
          )}
        </nav>

        {/* Footer migliorato */}
        <div className="p-4 border-t border-white/20 dark:border-slate-700/50">
          {/* Controls */}
          <div className="flex items-center justify-center space-x-3 mb-4">
            {/* Theme toggle con design premium */}
            <button
              onClick={toggleTheme}
              className="group relative w-12 h-12 rounded-2xl bg-gradient-to-r from-gray-100 to-gray-200 dark:from-slate-700 dark:to-slate-600 flex items-center justify-center text-gray-600 dark:text-gray-400 hover:from-yellow-100 hover:to-orange-100 dark:hover:from-yellow-900/30 dark:hover:to-orange-900/30 hover:text-orange-600 dark:hover:text-orange-400 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105"
              title={isDark ? t('lightMode') : t('darkMode')}
            >
              <i className={`fas ${isDark ? 'fa-sun' : 'fa-moon'} text-lg transition-transform duration-300 group-hover:scale-110`}></i>
              <div className="absolute inset-0 rounded-2xl bg-gradient-to-r from-yellow-400/20 to-orange-400/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </button>
            
            {/* Language toggle con design premium */}
            <button
              onClick={toggleLanguage}
              className="group relative w-12 h-12 rounded-2xl bg-gradient-to-r from-blue-100 to-indigo-200 dark:from-blue-900/30 dark:to-indigo-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 hover:from-blue-200 hover:to-indigo-300 dark:hover:from-blue-800/40 dark:hover:to-indigo-800/40 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105 font-bold text-sm"
              title={t('language')}
            >
              {language.toUpperCase()}
              <div className="absolute inset-0 rounded-2xl bg-gradient-to-r from-blue-400/20 to-indigo-400/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </button>
          </div>

          {/* Social links con design premium */}
          <div className="flex items-center justify-center space-x-3">
            <a
              href="https://github.com/vincenzo8825"
              target="_blank"
              rel="noopener noreferrer"
              className="group w-10 h-10 rounded-xl bg-gradient-to-r from-gray-800 to-gray-900 hover:from-gray-700 hover:to-gray-800 flex items-center justify-center text-white transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-110"
              title="GitHub"
            >
              <i className="fab fa-github text-base group-hover:scale-110 transition-transform duration-300"></i>
            </a>
                          <a
                href="https://www.linkedin.com/in/webdevfullstack/"
                target="_blank"
              rel="noopener noreferrer"
              className="group w-10 h-10 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 flex items-center justify-center text-white transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-110"
              title="LinkedIn"
            >
              <i className="fab fa-linkedin text-base group-hover:scale-110 transition-transform duration-300"></i>
            </a>
                          <a
                href="mailto:vincenzorocca88@gmail.com"
              className="group w-10 h-10 rounded-xl bg-gradient-to-r from-red-500 to-red-600 hover:from-red-400 hover:to-red-500 flex items-center justify-center text-white transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-110"
              title="Email"
            >
              <i className="fas fa-envelope text-base group-hover:scale-110 transition-transform duration-300"></i>
            </a>
          </div>

          {/* Copyright con stile */}
          <div className="text-center mt-4">
            <p className="text-xs text-gray-500 dark:text-gray-400">
              © 2025 Vincenzo Rocca
            </p>
          </div>
        </div>
      </aside>
    </>
  )
}

export default Sidebar 
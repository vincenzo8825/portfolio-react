import { useState } from 'react'
import { useLocation } from 'react-router-dom'
import Sidebar from './Sidebar'
import Header from './Header'
import Footer from './Footer'

const Layout = ({ children }) => {
  const [sidebarOpen, setSidebarOpen] = useState(false)
  const location = useLocation()
  
  // Check if current route is admin
  const isAdminRoute = location.pathname.startsWith('/admin')
  
  // Check if current route is home (for full-width hero)
  const isHomePage = location.pathname === '/'

  const toggleSidebar = () => {
    setSidebarOpen(!sidebarOpen)
  }

  const closeSidebar = () => {
    setSidebarOpen(false)
  }

  return (
    <div className="min-h-screen bg-white dark:bg-dark-900 transition-colors duration-300">
      {/* Header - sempre visibile */}
      <Header onToggleSidebar={toggleSidebar} />
      
      {/* Sidebar - sempre apribile/chiudibile */}
      <Sidebar 
        isOpen={sidebarOpen} 
        onClose={closeSidebar}
        isAdmin={isAdminRoute}
      />
      
      {/* Main Content - sempre full width quando sidebar è chiusa */}
      <main className={`
        transition-all duration-500 ease-in-out min-h-screen
        ${sidebarOpen ? 'lg:ml-72' : 'ml-0'}
        pt-16 lg:pt-16
      `}>
        <div className={`
          min-h-screen
          ${isHomePage ? 'lg:pt-0 pt-0' : 'pt-4'}
        `}>
          {children}
        </div>
        
        {/* Footer - only show on non-admin routes */}
        {!isAdminRoute && <Footer />}
      </main>

      {/* Mobile sidebar overlay */}
      {sidebarOpen && (
        <div 
          className="fixed inset-0 bg-black/50 backdrop-blur-sm z-40"
          onClick={closeSidebar}
        />
      )}
    </div>
  )
}

export default Layout 
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom'
import { AuthProvider } from './context/AuthContext'
import { ThemeProvider } from './context/ThemeContext'
import { NotificationProvider } from './context/NotificationContext'
import { LanguageProvider } from './context/LanguageContext'
import Layout from './components/layout/Layout'
import Home from './pages/Home'
import About from './pages/About'
import Projects from './pages/Projects'
import ProjectDetail from './pages/ProjectDetail'
import Contact from './pages/Contact'
import Login from './pages/Login'
import Dashboard from './pages/admin/Dashboard'
import ProjectsList from './pages/admin/ProjectsList'
import ProjectForm from './pages/admin/ProjectForm'
import ProjectEdit from './pages/admin/ProjectEdit'
import ProtectedRoute from './components/common/ProtectedRoute'
import ErrorBoundary from './components/common/ErrorBoundary'
import AOS from 'aos'
import 'aos/dist/aos.css'
import { useEffect } from 'react'

function App() {
  useEffect(() => {
    AOS.init({
      duration: 800,
      easing: 'ease-out-cubic',
      once: true,
      offset: 100
    })
  }, [])

  return (
    <ErrorBoundary>
      <AuthProvider>
        <ThemeProvider>
          <LanguageProvider>
            <NotificationProvider>
              <Router>
              <Layout>
                <Routes>
                  {/* Public Routes */}
                  <Route path="/" element={<Home />} />
                  <Route path="/about" element={<About />} />
                  <Route path="/projects" element={<Projects />} />
                  <Route path="/projects/:id" element={<ProjectDetail />} />
                  <Route path="/contact" element={<Contact />} />
                  <Route path="/login" element={<Login />} />
                  
                  {/* Admin Routes - Protected */}
                  <Route path="/admin" element={
                    <ProtectedRoute>
                      <Dashboard />
                    </ProtectedRoute>
                  } />
                  <Route path="/admin/projects" element={
                    <ProtectedRoute>
                      <ProjectsList />
                    </ProtectedRoute>
                  } />
                  <Route path="/admin/projects/create" element={
                    <ProtectedRoute>
                      <ProjectForm />
                    </ProtectedRoute>
                  } />
                  <Route path="/admin/projects/edit/:id" element={
                    <ProtectedRoute>
                      <ProjectEdit />
                    </ProtectedRoute>
                  } />
                  
                  {/* Catch all - 404 */}
                  <Route path="*" element={
                    <div className="flex items-center justify-center min-h-screen">
                      <div className="text-center">
                        <h1 className="text-6xl font-bold text-gray-400 mb-4">404</h1>
                        <p className="text-xl text-gray-600 dark:text-gray-400 mb-8">
                          Pagina non trovata
                        </p>
                        <a href="/" className="btn-primary">
                          Torna alla Home
                        </a>
                      </div>
                    </div>
                  } />
                </Routes>
              </Layout>
              </Router>
            </NotificationProvider>
          </LanguageProvider>
        </ThemeProvider>
      </AuthProvider>
    </ErrorBoundary>
  )
}

export default App

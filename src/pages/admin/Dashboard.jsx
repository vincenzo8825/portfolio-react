import { useLanguage } from '../../context/LanguageContext'

const Dashboard = () => {
  const { t } = useLanguage()
  
  return (
    <div className="min-h-screen py-8">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Header */}
        <div className="mb-8">
          <h1 className="text-3xl font-bold text-gray-900 dark:text-white">
            {t('dashboard')}
          </h1>
          <p className="text-gray-600 dark:text-gray-400 mt-2">
            {t('welcome')} nel pannello di controllo del portfolio
          </p>
        </div>

        {/* Stats Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div className="bg-white dark:bg-dark-800 rounded-lg p-6 shadow-lg border border-gray-200 dark:border-dark-600">
            <div className="flex items-center">
              <div className="p-3 rounded-full bg-blue-100 dark:bg-blue-900/30">
                <i className="fas fa-folder text-blue-600 dark:text-blue-400 text-xl"></i>
              </div>
              <div className="ml-4">
                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Totale Progetti</p>
                <p className="text-2xl font-semibold text-gray-900 dark:text-white">0</p>
              </div>
            </div>
          </div>

          <div className="bg-white dark:bg-dark-800 rounded-lg p-6 shadow-lg border border-gray-200 dark:border-dark-600">
            <div className="flex items-center">
              <div className="p-3 rounded-full bg-green-100 dark:bg-green-900/30">
                <i className="fas fa-star text-green-600 dark:text-green-400 text-xl"></i>
              </div>
              <div className="ml-4">
                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Progetti in Evidenza</p>
                <p className="text-2xl font-semibold text-gray-900 dark:text-white">0</p>
              </div>
            </div>
          </div>

          <div className="bg-white dark:bg-dark-800 rounded-lg p-6 shadow-lg border border-gray-200 dark:border-dark-600">
            <div className="flex items-center">
              <div className="p-3 rounded-full bg-purple-100 dark:bg-purple-900/30">
                <i className="fas fa-eye text-purple-600 dark:text-purple-400 text-xl"></i>
              </div>
              <div className="ml-4">
                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Visite Totali</p>
                <p className="text-2xl font-semibold text-gray-900 dark:text-white">-</p>
              </div>
            </div>
          </div>

          <div className="bg-white dark:bg-dark-800 rounded-lg p-6 shadow-lg border border-gray-200 dark:border-dark-600">
            <div className="flex items-center">
              <div className="p-3 rounded-full bg-orange-100 dark:bg-orange-900/30">
                <i className="fas fa-envelope text-orange-600 dark:text-orange-400 text-xl"></i>
              </div>
              <div className="ml-4">
                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Messaggi Ricevuti</p>
                <p className="text-2xl font-semibold text-gray-900 dark:text-white">-</p>
              </div>
            </div>
          </div>
        </div>

        {/* Quick Actions */}
        <div className="bg-white dark:bg-dark-800 rounded-lg p-6 shadow-lg border border-gray-200 dark:border-dark-600 mb-8">
          <h2 className="text-xl font-semibold text-gray-900 dark:text-white mb-4">
            {t('quickActions')}
          </h2>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a
              href="/admin/projects/create"
              className="flex items-center p-4 bg-primary-50 dark:bg-primary-900/20 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/30 transition-colors"
            >
              <i className="fas fa-plus text-primary-600 dark:text-primary-400 text-xl mr-3"></i>
              <div>
                <p className="font-medium text-primary-700 dark:text-primary-300">{t('newProject')}</p>
                <p className="text-sm text-primary-600 dark:text-primary-400">{t('addNewProject')}</p>
              </div>
            </a>

            <a
              href="/admin/projects"
              className="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors"
            >
              <i className="fas fa-list text-green-600 dark:text-green-400 text-xl mr-3"></i>
              <div>
                <p className="font-medium text-green-700 dark:text-green-300">{t('projectManagement')}</p>
                <p className="text-sm text-green-600 dark:text-green-400">{t('manageProjects')}</p>
              </div>
            </a>

            <a
              href="/"
              className="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors"
            >
              <i className="fas fa-external-link-alt text-blue-600 dark:text-blue-400 text-xl mr-3"></i>
              <div>
                <p className="font-medium text-blue-700 dark:text-blue-300">{t('viewSite')}</p>
                <p className="text-sm text-blue-600 dark:text-blue-400">{t('viewSite')}</p>
              </div>
            </a>
          </div>
        </div>

        {/* Recent Activity */}
        <div className="bg-white dark:bg-dark-800 rounded-lg p-6 shadow-lg border border-gray-200 dark:border-dark-600">
          <h2 className="text-xl font-semibold text-gray-900 dark:text-white mb-4">
            Attività Recente
          </h2>
          <div className="text-center py-8">
            <i className="fas fa-clock text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
            <p className="text-gray-500 dark:text-gray-400">
              Nessuna attività recente da mostrare
            </p>
          </div>
        </div>

      </div>
    </div>
  )
}

export default Dashboard 
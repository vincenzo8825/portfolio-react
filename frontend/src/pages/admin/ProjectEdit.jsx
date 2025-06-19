const ProjectEdit = () => {
  return (
    <div className="min-h-screen py-8">
      <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Header */}
        <div className="mb-8">
          <h1 className="text-3xl font-bold text-gray-900 dark:text-white">
            Modifica Progetto
          </h1>
          <p className="text-gray-600 dark:text-gray-400 mt-2">
            Modifica le informazioni del progetto selezionato
          </p>
        </div>

        {/* Form */}
        <div className="bg-white dark:bg-dark-800 rounded-lg shadow-lg border border-gray-200 dark:border-dark-600 p-6">
          <div className="text-center py-12">
            <div className="w-24 h-24 mx-auto mb-8 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
              <i className="fas fa-edit text-3xl text-yellow-600 dark:text-yellow-400"></i>
            </div>
            <h3 className="text-xl font-semibold text-gray-900 dark:text-white mb-4">
              Form di modifica progetto
            </h3>
            <p className="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
              Il form completo per la modifica dei progetti sarà implementato con il backend Laravel API.
            </p>
            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <a href="/admin/projects" className="btn-secondary">
                <i className="fas fa-arrow-left mr-2"></i>
                Torna alla lista
              </a>
              <a href="/admin" className="btn-primary">
                <i className="fas fa-tachometer-alt mr-2"></i>
                Dashboard
              </a>
            </div>
          </div>
        </div>

      </div>
    </div>
  )
}

export default ProjectEdit 
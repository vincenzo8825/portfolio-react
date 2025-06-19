const ProjectsList = () => {
  return (
    <div className="min-h-screen py-8">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Header */}
        <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
          <div>
            <h1 className="text-3xl font-bold text-gray-900 dark:text-white">
              Gestione Progetti
            </h1>
            <p className="text-gray-600 dark:text-gray-400 mt-2">
              Visualizza e gestisci tutti i progetti del portfolio
            </p>
          </div>
          <div className="mt-4 sm:mt-0">
            <a
              href="/admin/projects/create"
              className="btn-primary"
            >
              <i className="fas fa-plus mr-2"></i>
              Nuovo Progetto
            </a>
          </div>
        </div>

        {/* Empty State */}
        <div className="bg-white dark:bg-dark-800 rounded-lg shadow-lg border border-gray-200 dark:border-dark-600">
          <div className="text-center py-12">
            <div className="w-24 h-24 mx-auto mb-8 bg-gray-100 dark:bg-dark-700 rounded-full flex items-center justify-center">
              <i className="fas fa-folder-open text-3xl text-gray-400"></i>
            </div>
            <h3 className="text-xl font-semibold text-gray-900 dark:text-white mb-4">
              Nessun progetto trovato
            </h3>
            <p className="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
              Non hai ancora creato nessun progetto. Inizia creando il tuo primo progetto.
            </p>
            <a
              href="/admin/projects/create"
              className="btn-primary"
            >
              <i className="fas fa-plus mr-2"></i>
              Crea il tuo primo progetto
            </a>
          </div>
        </div>

      </div>
    </div>
  )
}

export default ProjectsList 
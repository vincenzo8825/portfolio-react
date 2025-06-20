const Footer = () => {
  return (
    <footer className="bg-gray-50 dark:bg-dark-800 border-t border-gray-200 dark:border-dark-600 py-8">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
          {/* Brand */}
          <div className="space-y-4">
            <div className="flex items-center space-x-2">
              <div className="w-8 h-8 rounded-full bg-gradient-to-r from-primary-500 to-accent-500 flex items-center justify-center text-white font-bold text-sm">
                VR
              </div>
              <span className="font-semibold text-gray-900 dark:text-white">
                Vincenzo Rocca
              </span>
            </div>
            <p className="text-sm text-gray-600 dark:text-gray-400">
              Full Stack Developer specializzato in soluzioni web moderne e performanti
            </p>
          </div>

          {/* Social & Contact */}
          <div className="space-y-4">
            <h3 className="font-semibold text-gray-900 dark:text-white">
              Seguimi
            </h3>
            <div className="flex space-x-4">
              <a
                href="https://github.com/vincenzo8825"
                target="_blank"
                rel="noopener noreferrer"
                className="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors duration-200"
                title="GitHub"
              >
                <i className="fab fa-github text-xl"></i>
              </a>
              <a
                href="https://www.linkedin.com/in/webdevfullstack"
                target="_blank"
                rel="noopener noreferrer"
                className="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors duration-200"
                title="LinkedIn"
              >
                <i className="fab fa-linkedin text-xl"></i>
              </a>
              <a
                href="mailto:info@vincenzorocca.it"
                className="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors duration-200"
                title="Email"
              >
                <i className="fas fa-envelope text-xl"></i>
              </a>
            </div>
          </div>
        </div>

        {/* Bottom */}
        <div className="mt-8 pt-8 border-t border-gray-200 dark:border-dark-600">
          <div className="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
            <p className="text-sm text-gray-600 dark:text-gray-400 text-center md:text-left">
              © 2025 Vincenzo Rocca. Tutti i diritti riservati.
            </p>
            <p className="text-sm text-gray-600 dark:text-gray-400 text-center md:text-right">
              Realizzato con <span className="text-red-500">❤️</span> usando React, Laravel, Angular e Tailwind CSS
            </p>
          </div>
        </div>
      </div>
    </footer>
  )
}

export default Footer 
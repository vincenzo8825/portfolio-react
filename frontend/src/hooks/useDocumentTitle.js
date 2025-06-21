import { useEffect } from 'react'

const useDocumentTitle = (title, deps = []) => {
  useEffect(() => {
    const baseTitle = 'Vincenzo Rocca - Full Stack Developer'
    const fullTitle = title ? `${title} | ${baseTitle}` : baseTitle
    
    document.title = fullTitle
    
    // Cleanup: ripristina il titolo base quando il componente viene smontato
    return () => {
      document.title = baseTitle
    }
  }, [title, ...deps])
}

export default useDocumentTitle 
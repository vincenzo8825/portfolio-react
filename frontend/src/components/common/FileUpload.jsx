import { useState, useRef } from 'react'
import { uploadService } from '../../services/api'
import { useNotification } from '../../context/NotificationContext'

const FileUpload = ({ 
  type = 'image', // 'image', 'video', 'gallery'
  onUploadSuccess,
  onUploadError,
  accept,
  multiple = false,
  maxSize = 10, // MB
  className = '',
  children
}) => {
  const [uploading, setUploading] = useState(false)
  const [dragActive, setDragActive] = useState(false)
  const fileInputRef = useRef(null)
  const { showError, showSuccess } = useNotification()

  const handleFiles = async (files) => {
    if (!files || files.length === 0) return

    // Validate file size
    for (let file of files) {
      if (file.size > maxSize * 1024 * 1024) {
        showError(`File troppo grande. Dimensione massima: ${maxSize}MB`)
        return
      }
    }

    setUploading(true)
    try {
      let result
      
      if (type === 'image' && !multiple) {
        result = await uploadService.uploadImage(files[0])
      } else if (type === 'video') {
        result = await uploadService.uploadVideo(files[0])
      } else if (type === 'gallery' || multiple) {
        result = await uploadService.uploadGallery(Array.from(files))
      }

      showSuccess('File caricato con successo!')
      onUploadSuccess?.(result)
      
    } catch (error) {
      console.error('Upload error:', error)
      showError(error.message || 'Errore durante il caricamento')
      onUploadError?.(error)
    } finally {
      setUploading(false)
    }
  }

  const handleDrag = (e) => {
    e.preventDefault()
    e.stopPropagation()
    if (e.type === "dragenter" || e.type === "dragover") {
      setDragActive(true)
    } else if (e.type === "dragleave") {
      setDragActive(false)
    }
  }

  const handleDrop = (e) => {
    e.preventDefault()
    e.stopPropagation()
    setDragActive(false)
    
    if (e.dataTransfer.files && e.dataTransfer.files[0]) {
      handleFiles(e.dataTransfer.files)
    }
  }

  const handleChange = (e) => {
    e.preventDefault()
    if (e.target.files && e.target.files[0]) {
      handleFiles(e.target.files)
    }
  }

  const openFileSelector = () => {
    fileInputRef.current?.click()
  }

  const getAcceptTypes = () => {
    if (accept) return accept
    if (type === 'image') return 'image/*'
    if (type === 'video') return 'video/*'
    return '*/*'
  }

  return (
    <div className={className}>
      <input
        ref={fileInputRef}
        type="file"
        multiple={multiple}
        accept={getAcceptTypes()}
        onChange={handleChange}
        className="hidden"
      />
      
      <div
        className={`
          relative border-2 border-dashed rounded-lg p-6 text-center cursor-pointer transition-colors duration-200
          ${dragActive 
            ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20' 
            : 'border-gray-300 dark:border-gray-600 hover:border-primary-400 dark:hover:border-primary-500'
          }
          ${uploading ? 'pointer-events-none opacity-50' : ''}
        `}
        onDragEnter={handleDrag}
        onDragLeave={handleDrag}
        onDragOver={handleDrag}
        onDrop={handleDrop}
        onClick={openFileSelector}
      >
        {uploading ? (
          <div className="flex flex-col items-center">
            <div className="w-8 h-8 border-4 border-primary-500 border-t-transparent rounded-full animate-spin mb-2"></div>
            <p className="text-sm text-gray-600 dark:text-gray-400">Caricamento in corso...</p>
          </div>
        ) : (
          children || (
            <div className="flex flex-col items-center">
              <i className={`fas ${type === 'video' ? 'fa-video' : 'fa-image'} text-3xl text-gray-400 mb-2`}></i>
              <p className="text-sm text-gray-600 dark:text-gray-400 mb-1">
                Trascina i file qui o clicca per selezionare
              </p>
              <p className="text-xs text-gray-500 dark:text-gray-500">
                {type === 'image' && 'Immagini: JPG, PNG, GIF, WebP'}
                {type === 'video' && 'Video: MP4, AVI, MOV, WebM'}
                {type === 'gallery' && 'Più immagini: JPG, PNG, GIF, WebP'}
                {` (Max ${maxSize}MB)`}
              </p>
            </div>
          )
        )}
      </div>
    </div>
  )
}

export default FileUpload 
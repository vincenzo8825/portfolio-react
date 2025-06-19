<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{
    /**
     * Upload di immagini
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240' // Max 10MB
        ]);

        try {
            $file = $request->file('image');

            // Genera un nome unico per il file
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

            // Salva il file nella cartella public/uploads/images
            $path = $file->storeAs('uploads/images', $fileName, 'public');

            // Genera l'URL completo
            $url = asset('storage/' . $path);

            return response()->json([
                'success' => true,
                'message' => 'Immagine caricata con successo',
                'data' => [
                    'url' => $url,
                    'path' => $path,
                    'filename' => $fileName
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore durante il caricamento dell\'immagine',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload di video
     */
    public function uploadVideo(Request $request): JsonResponse
    {
        $request->validate([
            'video' => 'required|mimes:mp4,avi,mov,wmv,flv,webm|max:102400' // Max 100MB
        ]);

        try {
            $file = $request->file('video');

            // Genera un nome unico per il file
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

            // Salva il file nella cartella public/uploads/videos
            $path = $file->storeAs('uploads/videos', $fileName, 'public');

            // Genera l'URL completo
            $url = asset('storage/' . $path);

            return response()->json([
                'success' => true,
                'message' => 'Video caricato con successo',
                'data' => [
                    'url' => $url,
                    'path' => $path,
                    'filename' => $fileName
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore durante il caricamento del video',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload multiplo di immagini per galleria
     */
    public function uploadGallery(Request $request): JsonResponse
    {
        $request->validate([
            'images' => 'required|array|max:10', // Max 10 immagini
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240' // Max 10MB per immagine
        ]);

        try {
            $uploadedImages = [];

            foreach ($request->file('images') as $file) {
                // Genera un nome unico per il file
                $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

                // Salva il file nella cartella public/uploads/gallery
                $path = $file->storeAs('uploads/gallery', $fileName, 'public');

                // Genera l'URL completo
                $url = asset('storage/' . $path);

                $uploadedImages[] = [
                    'url' => $url,
                    'path' => $path,
                    'filename' => $fileName
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Immagini caricate con successo',
                'data' => $uploadedImages
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore durante il caricamento delle immagini',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Elimina un file
     */
    public function deleteFile(Request $request): JsonResponse
    {
        $request->validate([
            'path' => 'required|string'
        ]);

        try {
            $path = $request->input('path');

            // Rimuovi il prefisso 'storage/' se presente
            $path = str_replace('storage/', '', $path);

            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);

                return response()->json([
                    'success' => true,
                    'message' => 'File eliminato con successo'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'File non trovato'
                ], 404);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'eliminazione del file',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

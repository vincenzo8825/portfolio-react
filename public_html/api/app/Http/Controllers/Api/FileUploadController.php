<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{
    /**
     * Upload di immagini con sicurezza avanzata
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|file|image|mimes:jpeg,png,jpg,gif,webp|max:5120|dimensions:max_width=4000,max_height=4000' // Max 5MB, 4000x4000px
        ]);

        try {
            $file = $request->file('image');

            // Validazione sicurezza avanzata
            if (!$this->isValidImageFile($file)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File non valido o potenzialmente pericoloso'
                ], 400);
            }

            // Genera un nome sicuro e unico
            $extension = $this->getSecureExtension($file);
            $fileName = $this->generateSecureFileName($extension);

            // Salva il file nella cartella protetta
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
            Log::error('File upload error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Errore durante il caricamento dell\'immagine'
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

    /**
     * Validazione sicurezza avanzata per file immagine
     */
    private function isValidImageFile($file): bool
    {
        // Verifica magic bytes (primi bytes del file)
        $validMagicBytes = [
            'jpeg' => [0xFF, 0xD8, 0xFF],
            'png' => [0x89, 0x50, 0x4E, 0x47],
            'gif' => [0x47, 0x49, 0x46],
            'webp' => [0x52, 0x49, 0x46, 0x46]
        ];

        $fileContent = file_get_contents($file->getPathname());
        $magicBytes = array_values(unpack('C*', substr($fileContent, 0, 4)));

        $isValid = false;
        foreach ($validMagicBytes as $format => $expectedBytes) {
            if (array_slice($magicBytes, 0, count($expectedBytes)) === $expectedBytes) {
                $isValid = true;
                break;
            }
        }

        // Verifica che non ci siano script embedded
        $suspiciousContent = [
            '<?php', '<?=', '<script', 'javascript:', 'vbscript:', 'onload=', 'onerror='
        ];

        $fileContentLower = strtolower($fileContent);
        foreach ($suspiciousContent as $pattern) {
            if (strpos($fileContentLower, strtolower($pattern)) !== false) {
                return false;
            }
        }

        return $isValid;
    }

    /**
     * Ottiene estensione sicura dal file
     */
    private function getSecureExtension($file): string
    {
        $mimeType = $file->getMimeType();
        $allowedMimes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp'
        ];

        return $allowedMimes[$mimeType] ?? 'jpg';
    }

    /**
     * Genera nome file sicuro e unico
     */
    private function generateSecureFileName(string $extension): string
    {
        // Usa timestamp + UUID per unicità assoluta
        $timestamp = now()->format('Ymd_His');
        $uuid = Str::uuid()->toString();
        $random = Str::random(8);

        return "img_{$timestamp}_{$random}_{$uuid}.{$extension}";
    }
}

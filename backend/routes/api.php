<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TechnologyController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FileUploadController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rotte di autenticazione (pubbliche)
Route::prefix('v1/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1'); // Max 5 tentativi per minuto

    // Rotte protette per admin autenticati
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
    });
});

// Rotte pubbliche (senza autenticazione)
Route::prefix('v1')->group(function () {

    // Test route
    Route::get('/test', function () {
        return response()->json([
            'message' => 'Laravel API funziona su Hostinger!',
            'timestamp' => now(),
            'server' => request()->server('SERVER_NAME'),
            'php_version' => PHP_VERSION
        ]);
    });

    // Progetti - rotte pubbliche
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/featured', [ProjectController::class, 'featured']);
    Route::get('/projects/{id}', [ProjectController::class, 'show']);

    // Tecnologie - rotte pubbliche
    Route::get('/technologies', [TechnologyController::class, 'index']);
    Route::get('/technologies/featured', [TechnologyController::class, 'featured']);
    Route::get('/technologies/by-category', [TechnologyController::class, 'byCategory']);
    Route::get('/technologies/{id}', [TechnologyController::class, 'show']);

    // Contatti - solo creazione pubblica con rate limiting
    Route::post('/contacts', [ContactController::class, 'store'])
        ->middleware('throttle:3,60'); // Max 3 messaggi per ora
});

// Rotte protette (solo admin con autenticazione)
Route::prefix('v1/admin')->middleware(['auth:sanctum', 'admin'])->group(function () {

    // Progetti - rotte admin
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::put('/projects/{id}', [ProjectController::class, 'update']);
    Route::patch('/projects/{id}/toggle-featured', [ProjectController::class, 'toggleFeatured']);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);

    // Tecnologie - rotte admin
    Route::post('/technologies', [TechnologyController::class, 'store']);
    Route::put('/technologies/{id}', [TechnologyController::class, 'update']);
    Route::delete('/technologies/{id}', [TechnologyController::class, 'destroy']);

    // Contatti - rotte admin
    Route::get('/contacts', [ContactController::class, 'index']);
    Route::get('/contacts/stats', [ContactController::class, 'stats']);
    Route::get('/contacts/{id}', [ContactController::class, 'show']);
    Route::put('/contacts/{id}', [ContactController::class, 'update']);
    Route::delete('/contacts/{id}', [ContactController::class, 'destroy']);

    // Upload di file - rotte admin
    Route::post('/upload/image', [FileUploadController::class, 'uploadImage']);
    Route::post('/upload/video', [FileUploadController::class, 'uploadVideo']);
    Route::post('/upload/gallery', [FileUploadController::class, 'uploadGallery']);
    Route::delete('/upload/file', [FileUploadController::class, 'deleteFile']);
});

// CORS preflight gestito automaticamente da Laravel

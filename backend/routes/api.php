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
    Route::post('/login', [AuthController::class, 'login']);

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

    // Progetti - rotte pubbliche
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/featured', [ProjectController::class, 'featured']);
    Route::get('/projects/{id}', [ProjectController::class, 'show']);

    // Tecnologie - rotte pubbliche
    Route::get('/technologies', [TechnologyController::class, 'index']);
    Route::get('/technologies/featured', [TechnologyController::class, 'featured']);
    Route::get('/technologies/by-category', [TechnologyController::class, 'byCategory']);
    Route::get('/technologies/{id}', [TechnologyController::class, 'show']);

    // Contatti - solo creazione pubblica
    Route::post('/contacts', [ContactController::class, 'store']);
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

// Rotta per gestione CORS preflight
Route::options('{any}', function () {
    return response('', 200)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
})->where('any', '.*');

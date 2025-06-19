<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Project::query()->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');

        // Filtro per progetti in evidenza
        if ($request->has('featured') && $request->featured) {
            $query->where('featured', true);
        }

        // Filtro per status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $projects = $query->get();

        return response()->json([
            'success' => true,
            'data' => $projects
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'long_description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'gallery' => 'nullable|array',
            'demo_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'video_url' => 'nullable|url',
            'technologies' => 'required|array',
            'status' => 'in:in-progress,completed,paused',
            'featured' => 'boolean',
            'project_date' => 'nullable|date',
            'sort_order' => 'nullable|integer',
            'client' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'features' => 'nullable|array',
            'challenges' => 'nullable|array',
            'results' => 'nullable|array',
            'testimonial' => 'nullable|string',
            'testimonial_author' => 'nullable|string|max:255',
            'testimonial_role' => 'nullable|string|max:255',
            'additional_links' => 'nullable|array'
        ]);

        // Genera slug automaticamente
        $validated['slug'] = Str::slug($validated['title']);

        // Assicurati che lo slug sia unico
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Project::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Imposta sort_order automatico se non fornito
        if (!isset($validated['sort_order'])) {
            $validated['sort_order'] = Project::max('sort_order') + 1;
        }

        // Gestione massimo 3 progetti featured
        if (isset($validated['featured']) && $validated['featured']) {
            $featuredCount = Project::where('featured', true)->count();
            if ($featuredCount >= 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'Massimo 3 progetti possono essere in evidenza. Rimuovi un progetto in evidenza prima di aggiungerne uno nuovo.'
                ], 422);
            }
        }

        $project = Project::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Progetto creato con successo',
            'data' => $project
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        // Permetti di cercare per ID o slug
        $project = Project::where('id', $id)
            ->orWhere('slug', $id)
            ->first();

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Progetto non trovato'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $project
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'long_description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'gallery' => 'nullable|array',
            'demo_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'video_url' => 'nullable|url',
            'technologies' => 'sometimes|required|array',
            'status' => 'in:in-progress,completed,paused',
            'featured' => 'boolean',
            'project_date' => 'nullable|date',
            'sort_order' => 'nullable|integer',
            'client' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'features' => 'nullable|array',
            'challenges' => 'nullable|array',
            'results' => 'nullable|array',
            'testimonial' => 'nullable|string',
            'testimonial_author' => 'nullable|string|max:255',
            'testimonial_role' => 'nullable|string|max:255',
            'additional_links' => 'nullable|array'
        ]);

        // Gestione massimo 3 progetti featured
        if (isset($validated['featured']) && $validated['featured'] && !$project->featured) {
            $featuredCount = Project::where('featured', true)->count();
            if ($featuredCount >= 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'Massimo 3 progetti possono essere in evidenza. Rimuovi un progetto in evidenza prima di aggiungerne uno nuovo.'
                ], 422);
            }
        }

        // Aggiorna slug se il titolo è cambiato
        if (isset($validated['title']) && $validated['title'] !== $project->title) {
            $validated['slug'] = Str::slug($validated['title']);

            // Assicurati che lo slug sia unico
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Project::where('slug', $validated['slug'])->where('id', '!=', $project->id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        $project->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Progetto aggiornato con successo',
            'data' => $project
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return response()->json([
            'success' => true,
            'message' => 'Progetto eliminato con successo'
        ]);
    }

    /**
     * Get featured projects (max 3)
     */
    public function featured(): JsonResponse
    {
        $projects = Project::where('featured', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $projects
        ]);
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(string $id): JsonResponse
    {
        $project = Project::findOrFail($id);

        if (!$project->featured) {
            // Controllla se ci sono già 3 progetti featured
            $featuredCount = Project::where('featured', true)->count();
            if ($featuredCount >= 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'Massimo 3 progetti possono essere in evidenza.'
                ], 422);
            }
        }

        $project->featured = !$project->featured;
        $project->save();

        return response()->json([
            'success' => true,
            'message' => $project->featured ? 'Progetto aggiunto agli evidenziati' : 'Progetto rimosso dagli evidenziati',
            'data' => $project
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Technology;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class TechnologyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Technology::ordered();

        // Filtro per categoria
        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        // Filtro per tecnologie in evidenza
        if ($request->has('featured') && $request->featured) {
            $query->featured();
        }

        // Filtro per livello di competenza minimo
        if ($request->has('min_proficiency')) {
            $query->minProficiency($request->min_proficiency);
        }

        $technologies = $query->get();

        return response()->json([
            'success' => true,
            'data' => $technologies
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7', // Per colori hex come #FF0000
            'category' => 'required|in:frontend,backend,database,tools,mobile,other',
            'proficiency' => 'required|integer|between:1,5',
            'sort_order' => 'integer',
            'featured' => 'boolean'
        ]);

        // Genera slug automaticamente
        $validated['slug'] = Str::slug($validated['name']);

        // Assicurati che lo slug sia unico
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Technology::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        $technology = Technology::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tecnologia creata con successo',
            'data' => $technology
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        // Permetti di cercare per ID o slug
        $technology = Technology::where('id', $id)
            ->orWhere('slug', $id)
            ->first();

        if (!$technology) {
            return response()->json([
                'success' => false,
                'message' => 'Tecnologia non trovata'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $technology
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $technology = Technology::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7',
            'category' => 'sometimes|required|in:frontend,backend,database,tools,mobile,other',
            'proficiency' => 'sometimes|required|integer|between:1,5',
            'sort_order' => 'integer',
            'featured' => 'boolean'
        ]);

        // Aggiorna slug se il nome è cambiato
        if (isset($validated['name']) && $validated['name'] !== $technology->name) {
            $validated['slug'] = Str::slug($validated['name']);

            // Assicurati che lo slug sia unico
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Technology::where('slug', $validated['slug'])->where('id', '!=', $technology->id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        $technology->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tecnologia aggiornata con successo',
            'data' => $technology
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $technology = Technology::findOrFail($id);
        $technology->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tecnologia eliminata con successo'
        ]);
    }

    /**
     * Get technologies grouped by category
     */
    public function byCategory(): JsonResponse
    {
        $categories = [
            'frontend' => Technology::byCategory('frontend')->ordered()->get(),
            'backend' => Technology::byCategory('backend')->ordered()->get(),
            'database' => Technology::byCategory('database')->ordered()->get(),
            'tools' => Technology::byCategory('tools')->ordered()->get(),
            'mobile' => Technology::byCategory('mobile')->ordered()->get(),
            'other' => Technology::byCategory('other')->ordered()->get()
        ];

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Get featured technologies
     */
    public function featured(): JsonResponse
    {
        $technologies = Technology::featured()->ordered()->get();

        return response()->json([
            'success' => true,
            'data' => $technologies
        ]);
    }
}

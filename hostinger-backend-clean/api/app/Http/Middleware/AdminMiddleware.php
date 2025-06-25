<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica che l'utente sia autenticato
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Accesso non autorizzato. Login richiesto.'
            ], 401);
        }

        // Verifica che l'utente sia admin
        if (!$request->user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Accesso negato. Privilegi amministratore richiesti.'
            ], 403);
        }

        return $next($request);
    }
}

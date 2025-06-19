<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Contact::latest();

        // Filtro per status
        if ($request->has('status')) {
            $query->byStatus($request->status);
        }

        // Filtro per messaggi non letti
        if ($request->has('unread') && $request->unread) {
            $query->unread();
        }

        $perPage = $request->get('per_page', 15);
        $contacts = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $contacts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255'
        ]);

        // Aggiungi informazioni sulla richiesta
        $validated['ip_address'] = $request->ip();
        $validated['user_agent'] = $request->userAgent();

        try {
            $contact = Contact::create($validated);

            // Invia email di notifica all'admin
            $this->sendAdminNotification($contact);

            // Invia email di conferma al mittente
            $this->sendConfirmationEmail($contact);

            return response()->json([
                'success' => true,
                'message' => 'Messaggio inviato con successo! Ti risponderemo presto.',
                'data' => $contact->only(['id', 'name', 'email', 'subject', 'created_at'])
            ], 201);

        } catch (\Exception $e) {
            Log::error('Errore invio contatto: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Si è verificato un errore durante l\'invio del messaggio. Riprova più tardi.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $contact = Contact::findOrFail($id);

        // Segna come letto se non è già stato letto
        if ($contact->status === 'new') {
            $contact->markAsRead();
        }

        return response()->json([
            'success' => true,
            'data' => $contact
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $contact = Contact::findOrFail($id);

        $validated = $request->validate([
            'status' => 'in:new,read,replied,archived',
            'admin_notes' => 'nullable|string'
        ]);

        // Se viene marcato come risposto, aggiorna il timestamp
        if (isset($validated['status']) && $validated['status'] === 'replied' && $contact->status !== 'replied') {
            $contact->markAsReplied();
        }

        $contact->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Contatto aggiornato con successo',
            'data' => $contact
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contatto eliminato con successo'
        ]);
    }

    /**
     * Get statistics about contacts
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total' => Contact::count(),
            'unread' => Contact::unread()->count(),
            'read' => Contact::byStatus('read')->count(),
            'replied' => Contact::byStatus('replied')->count(),
            'this_month' => Contact::whereMonth('created_at', now()->month)
                                  ->whereYear('created_at', now()->year)
                                  ->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Send notification email to admin
     */
    private function sendAdminNotification(Contact $contact): void
    {
        try {
            $adminEmail = config('app.admin_email', env('ADMIN_EMAIL'));

            if ($adminEmail) {
                Mail::send('emails.contact-notification', ['contact' => $contact], function ($message) use ($adminEmail, $contact) {
                    $message->to($adminEmail)
                           ->subject('Nuovo messaggio dal portfolio - ' . $contact->subject ?? 'Nessun oggetto');
                });
            }
        } catch (\Exception $e) {
            Log::error('Errore invio email admin: ' . $e->getMessage());
        }
    }

    /**
     * Send confirmation email to sender
     */
    private function sendConfirmationEmail(Contact $contact): void
    {
        try {
            Mail::send('emails.contact-confirmation', ['contact' => $contact], function ($message) use ($contact) {
                $message->to($contact->email)
                       ->subject('Grazie per il tuo messaggio - Vincenzo Rocca Portfolio');
            });
        } catch (\Exception $e) {
            Log::error('Errore invio email conferma: ' . $e->getMessage());
        }
    }
}

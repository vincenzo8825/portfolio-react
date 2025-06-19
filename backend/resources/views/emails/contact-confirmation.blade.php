<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grazie per il tuo messaggio</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #ffffff;
            padding: 40px;
            border: 1px solid #dee2e6;
        }
        .footer {
            background: #f8f9fa;
            color: #6c757d;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 10px 10px;
            font-size: 14px;
        }
        .highlight {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }
        .social-links {
            margin-top: 20px;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #007bff;
            text-decoration: none;
        }
        .contact-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        h2 {
            color: #007bff;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>✅ Messaggio Ricevuto!</h1>
        <p>Vincenzo Rocca - Full Stack Developer</p>
    </div>

    <div class="content">
        <p>Ciao <strong>{{ $contact->name }}</strong>,</p>

        <p>Grazie per avermi contattato attraverso il mio portfolio! Ho ricevuto il tuo messaggio e ti risponderò il prima possibile.</p>

        <div class="highlight">
            <h3>📄 Riepilogo del tuo messaggio:</h3>
            @if($contact->subject)
            <p><strong>Oggetto:</strong> {{ $contact->subject }}</p>
            @endif
            <p><strong>Messaggio:</strong> {{ Str::limit($contact->message, 200) }}...</p>
            <p><strong>Data invio:</strong> {{ $contact->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <h2>🚀 Nel frattempo...</h2>
        <p>Mentre aspetti la mia risposta, ti invito a:</p>
        <ul>
            <li>Dare un'occhiata ai miei <strong>progetti recenti</strong> sul portfolio</li>
            <li>Connetterti con me sui social network</li>
            <li>Scaricare il mio CV aggiornato</li>
        </ul>

        <div class="contact-info">
            <h3>📞 I miei contatti:</h3>
            <p><strong>Email:</strong> vincenzo@vincenzorocca.dev</p>
            <p><strong>Tempo di risposta:</strong> Solitamente entro 24-48 ore</p>

            <div class="social-links">
                <a href="https://linkedin.com/in/vincenzorocca" target="_blank">LinkedIn</a>
                <a href="https://github.com/vincenzorocca" target="_blank">GitHub</a>
                <a href="https://twitter.com/vincenzorocca" target="_blank">Twitter</a>
            </div>
        </div>

        <p>Apprezzo molto il tuo interesse e non vedo l'ora di collaborare con te!</p>

        <p>Un cordiale saluto,<br>
        <strong>Vincenzo Rocca</strong><br>
        <em>Full Stack Developer</em></p>
    </div>

    <div class="footer">
        <p>🤖 Questa è una email automatica generata dal portfolio di Vincenzo Rocca</p>
        <p>Se hai bisogno di assistenza immediata, puoi rispondere direttamente a questa email</p>
        <p>© {{ date('Y') }} Vincenzo Rocca Portfolio - Tutti i diritti riservati</p>
    </div>
</body>
</html>

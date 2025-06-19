<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuovo messaggio dal portfolio</title>
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
            background: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border: 1px solid #dee2e6;
        }
        .footer {
            background: #6c757d;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 0 0 5px 5px;
            font-size: 12px;
        }
        .info-item {
            margin-bottom: 15px;
        }
        .label {
            font-weight: bold;
            color: #495057;
        }
        .message-box {
            background: white;
            padding: 20px;
            border-left: 4px solid #007bff;
            margin: 20px 0;
            border-radius: 4px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📨 Nuovo Messaggio dal Portfolio</h1>
    </div>

    <div class="content">
        <p>Ciao Vincenzo,</p>
        <p>Hai ricevuto un nuovo messaggio dal tuo portfolio!</p>

        <div class="info-item">
            <span class="label">Nome:</span> {{ $contact->name }}
        </div>

        <div class="info-item">
            <span class="label">Email:</span> {{ $contact->email }}
        </div>

        @if($contact->phone)
        <div class="info-item">
            <span class="label">Telefono:</span> {{ $contact->phone }}
        </div>
        @endif

        @if($contact->company)
        <div class="info-item">
            <span class="label">Azienda:</span> {{ $contact->company }}
        </div>
        @endif

        @if($contact->subject)
        <div class="info-item">
            <span class="label">Oggetto:</span> {{ $contact->subject }}
        </div>
        @endif

        <div class="message-box">
            <span class="label">Messaggio:</span>
            <p>{{ $contact->message }}</p>
        </div>

        <div class="info-item">
            <span class="label">Data/Ora:</span> {{ $contact->created_at->format('d/m/Y H:i') }}
        </div>

        @if($contact->ip_address)
        <div class="info-item">
            <span class="label">IP Address:</span> {{ $contact->ip_address }}
        </div>
        @endif

        <p>
            <strong>Rispondi direttamente a questa email</strong> per contattare {{ $contact->name }}.
        </p>
    </div>

    <div class="footer">
        <p>Messaggio inviato automaticamente dal portfolio di Vincenzo Rocca</p>
        <p>{{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>

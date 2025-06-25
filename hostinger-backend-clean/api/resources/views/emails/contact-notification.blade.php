<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🚀 Nuovo Lead dal Portfolio!</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #2d3748;
            background-color: #f7fafc;
            padding: 20px;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .header p {
            font-size: 16px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .content {
            padding: 40px 30px;
        }

        .lead-alert {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 30px;
            font-weight: 600;
            font-size: 18px;
        }

        .contact-card {
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            border-left: 5px solid #667eea;
        }

        .contact-row {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .contact-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .contact-icon {
            width: 40px;
            height: 40px;
            background: #667eea;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
            font-size: 16px;
        }

        .contact-info {
            flex: 1;
        }

        .contact-label {
            font-size: 12px;
            color: #718096;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .contact-value {
            font-size: 16px;
            color: #2d3748;
            font-weight: 500;
        }

        .message-section {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            position: relative;
        }

        .message-section::before {
            content: '💬';
            position: absolute;
            top: -15px;
            left: 20px;
            background: white;
            padding: 0 10px;
            font-size: 20px;
        }

        .message-header {
            color: #667eea;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 15px;
        }

        .message-text {
            color: #4a5568;
            font-size: 16px;
            line-height: 1.8;
            background: #f7fafc;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            font-style: italic;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            flex: 1;
            justify-content: center;
            min-width: 140px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-secondary {
            background: #f7fafc;
            color: #4a5568;
            border: 2px solid #e2e8f0;
        }

        .metadata {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            font-size: 12px;
            color: #6c757d;
        }

        .footer {
            background: #2d3748;
            color: #a0aec0;
            padding: 25px 30px;
            text-align: center;
            font-size: 14px;
        }

        .footer-brand {
            color: white;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .timestamp {
            color: #718096;
            font-size: 12px;
        }

        @media (max-width: 600px) {
            .email-container {
                margin: 0;
                border-radius: 0;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                flex: none;
            }

            .contact-row {
                flex-direction: column;
                align-items: flex-start;
                text-align: left;
            }

            .contact-icon {
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
    <div class="header">
            <h1>🚀 Nuovo Lead Portfolio!</h1>
            <p>Qualcuno è interessato ai tuoi servizi</p>
    </div>

    <div class="content">
            <div class="lead-alert">
                ✨ Hai ricevuto un nuovo messaggio di interesse!
            </div>

            <div class="contact-card">
                <div class="contact-row">
                    <div class="contact-icon">👤</div>
                    <div class="contact-info">
                        <div class="contact-label">Nome Completo</div>
                        <div class="contact-value">{{ $contact->name }}</div>
                    </div>
        </div>

                <div class="contact-row">
                    <div class="contact-icon">📧</div>
                    <div class="contact-info">
                        <div class="contact-label">Email di Contatto</div>
                        <div class="contact-value">{{ $contact->email }}</div>
                    </div>
        </div>

        @if($contact->subject)
                <div class="contact-row">
                    <div class="contact-icon">🎯</div>
                    <div class="contact-info">
                        <div class="contact-label">Oggetto</div>
                        <div class="contact-value">{{ $contact->subject }}</div>
                    </div>
        </div>
        @endif

        @if($contact->project_type)
                <div class="contact-row">
                    <div class="contact-icon">🚀</div>
                    <div class="contact-info">
                        <div class="contact-label">Tipo di Progetto</div>
                        <div class="contact-value">{{ $contact->project_type }}</div>
                    </div>
        </div>
        @endif

        @if($contact->budget)
                <div class="contact-row">
                    <div class="contact-icon">💰</div>
                    <div class="contact-info">
                        <div class="contact-label">Budget</div>
                        <div class="contact-value">{{ $contact->budget }}</div>
                    </div>
        </div>
        @endif

        @if($contact->timeline)
                <div class="contact-row">
                    <div class="contact-icon">⏱️</div>
                    <div class="contact-info">
                        <div class="contact-label">Timeline</div>
                        <div class="contact-value">{{ $contact->timeline }}</div>
                    </div>
        </div>
        @endif
            </div>

            <div class="message-section">
                <div class="message-header">Messaggio Completo</div>
                <div class="message-text">
                    "{{ $contact->message }}"
                </div>
        </div>

            <div class="action-buttons">
                <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->subject }}" class="btn btn-primary">
                    📩 Rispondi Subito
                </a>
                @if($contact->project_type)
                <a href="mailto:{{ $contact->email }}?subject=Preventivo {{ $contact->project_type }}" class="btn btn-secondary">
                    💰 Invia Preventivo
                </a>
                @else
                <a href="mailto:{{ $contact->email }}?subject=Informazioni sui nostri servizi" class="btn btn-secondary">
                    📋 Invia Info
                </a>
                @endif
        </div>

            <div class="metadata">
                <div style="display: flex; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                    <span><strong>📅 Ricevuto:</strong> {{ $contact->created_at->format('d/m/Y H:i') }}</span>
        @if($contact->ip_address)
                    <span><strong>🌍 IP:</strong> {{ $contact->ip_address }}</span>
        @endif
                </div>
            </div>
    </div>

    <div class="footer">
            <div class="footer-brand">Vincenzo Rocca Portfolio</div>
            <div class="timestamp">Notifica automatica • {{ now()->format('d/m/Y H:i:s') }}</div>
        </div>
    </div>
</body>
</html>

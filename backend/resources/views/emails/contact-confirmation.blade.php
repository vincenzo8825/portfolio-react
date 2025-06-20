<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎉 Messaggio Ricevuto con Successo!</title>
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
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="sparkle" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="%23ffffff" opacity="0.3"/></pattern></defs><rect width="100" height="100" fill="url(%23sparkle)"/></svg>');
        }

        .header h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .header p {
            font-size: 18px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .success-badge {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            padding: 8px 20px;
            display: inline-block;
            margin-top: 15px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            z-index: 1;
        }

        .content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 20px;
            margin-bottom: 25px;
            color: #2d3748;
        }

        .greeting strong {
            color: #667eea;
        }

        .intro-text {
            font-size: 16px;
            margin-bottom: 25px;
            color: #4a5568;
            line-height: 1.7;
        }

        .response-time {
            background: linear-gradient(135deg, #fed7e2 0%, #fbb6ce 100%);
            border: 2px solid #f687b3;
            color: #702459;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            font-weight: 600;
            margin: 25px 0;
            font-size: 16px;
        }

        .response-time::before {
            content: '⏰';
            font-size: 24px;
            margin-right: 10px;
        }

        .message-summary {
            background: linear-gradient(135deg, #e6fffa 0%, #b2f5ea 100%);
            border: 2px solid #38b2ac;
            border-radius: 16px;
            padding: 30px;
            margin: 30px 0;
            position: relative;
        }

        .message-summary::before {
            content: '📋';
            position: absolute;
            top: -15px;
            left: 25px;
            background: white;
            padding: 0 10px;
            font-size: 24px;
        }

        .summary-title {
            color: #285e61;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .summary-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 12px;
            padding: 8px 0;
        }

        .summary-item:last-child {
            margin-bottom: 0;
        }

        .summary-label {
            font-weight: 600;
            color: #2c7a7b;
            min-width: 100px;
            margin-right: 15px;
            font-size: 14px;
        }

        .summary-value {
            color: #234e52;
            flex: 1;
            font-size: 14px;
        }

        .message-text {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #38b2ac;
            font-style: italic;
            color: #2d3748;
            margin-top: 10px;
        }

        .cta-section {
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            border-radius: 16px;
            padding: 30px;
            margin: 30px 0;
            text-align: center;
        }

        .cta-title {
            font-size: 20px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 15px;
        }

        .cta-description {
            color: #4a5568;
            margin-bottom: 25px;
            font-size: 16px;
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 25px;
        }

        .action-item {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #4a5568;
        }

        .action-item:hover {
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -8px rgba(102, 126, 234, 0.6);
        }

        .action-icon {
            font-size: 24px;
            margin-bottom: 8px;
            display: block;
        }

        .action-label {
            font-weight: 600;
            font-size: 14px;
        }

        .social-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
            border-radius: 16px;
        }

        .social-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .social-link {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 12px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .social-link:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .signature {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
            text-align: center;
        }

        .signature-text {
            color: #4a5568;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .signature-name {
            font-size: 20px;
            font-weight: 700;
            color: #667eea;
        }

        .signature-title {
            color: #718096;
            font-size: 14px;
            margin-top: 5px;
        }

        .footer {
            background: #2d3748;
            color: #a0aec0;
            padding: 30px;
            text-align: center;
        }

        .footer-main {
            color: white;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .footer-note {
            font-size: 14px;
            margin-bottom: 8px;
        }

        .footer-contact {
            color: #667eea;
            font-size: 14px;
        }

        @media (max-width: 600px) {
            .email-container {
                margin: 0;
                border-radius: 0;
            }

            .action-grid {
                grid-template-columns: 1fr;
            }

            .social-links {
                flex-direction: column;
                align-items: center;
            }

            .summary-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .summary-label {
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🎉 Perfetto!</h1>
            <p>Il tuo messaggio è arrivato con successo</p>
            <div class="success-badge">✅ Confermato</div>
        </div>

        <div class="content">
            <div class="greeting">
                Ciao <strong>{{ $contact->name }}</strong>! 👋
            </div>

            <div class="intro-text">
                Fantastico! Ho ricevuto il tuo messaggio e sono già al lavoro per darti una risposta dettagliata e personalizzata.
                Grazie per aver scelto di contattarmi attraverso il mio portfolio!
            </div>

            <div class="response-time">
                Ti risponderò entro <strong>24-48 ore lavorative</strong>
            </div>

            <div class="message-summary">
                <div class="summary-title">📋 Riepilogo del tuo messaggio</div>

                <div class="summary-item">
                    <span class="summary-label">👤 Nome:</span>
                    <span class="summary-value">{{ $contact->name }}</span>
                </div>

                <div class="summary-item">
                    <span class="summary-label">📧 Email:</span>
                    <span class="summary-value">{{ $contact->email }}</span>
                </div>

                @if($contact->phone)
                <div class="summary-item">
                    <span class="summary-label">📱 Telefono:</span>
                    <span class="summary-value">{{ $contact->phone }}</span>
                </div>
                @endif

                @if($contact->company)
                <div class="summary-item">
                    <span class="summary-label">🏢 Azienda:</span>
                    <span class="summary-value">{{ $contact->company }}</span>
                </div>
                @endif

                @if($contact->subject)
                <div class="summary-item">
                    <span class="summary-label">🎯 Oggetto:</span>
                    <span class="summary-value">{{ $contact->subject }}</span>
                </div>
                @endif

                <div class="summary-item">
                    <span class="summary-label">💬 Messaggio:</span>
                    <div class="summary-value">
                        <div class="message-text">"{{ $contact->message }}"</div>
                    </div>
                </div>

                <div class="summary-item">
                    <span class="summary-label">📅 Inviato:</span>
                    <span class="summary-value">{{ $contact->created_at->format('d/m/Y alle H:i') }}</span>
                </div>
            </div>

            <div class="cta-section">
                <div class="cta-title">🚀 Nel frattempo...</div>
                <div class="cta-description">
                    Esplora il mio lavoro e scopri di più sui miei progetti e competenze!
                </div>

                <div class="action-grid">
                    <a href="https://github.com/vincenzo8825" class="action-item">
                        <span class="action-icon">💻</span>
                        <span class="action-label">GitHub</span>
                    </a>
                    <a href="https://www.linkedin.com/in/webdevfullstack/" class="action-item">
                        <span class="action-icon">💼</span>
                        <span class="action-label">LinkedIn</span>
                    </a>
                    <a href="#" class="action-item">
                        <span class="action-icon">📁</span>
                        <span class="action-label">Portfolio</span>
                    </a>
                </div>
            </div>

            <div class="social-section">
                <div class="social-title">🌟 Rimaniamo connessi!</div>
                <div class="social-links">
                    <a href="https://github.com/vincenzo8825" class="social-link">
                        <span>💻</span> GitHub
                    </a>
                    <a href="https://www.linkedin.com/in/webdevfullstack/" class="social-link">
                        <span>💼</span> LinkedIn
                    </a>
                    <a href="mailto:vincenzorocca88@gmail.com" class="social-link">
                        <span>📧</span> Email
                    </a>
                </div>
            </div>

            <div class="signature">
                <div class="signature-text">Grazie ancora per il tuo interesse!</div>
                <div class="signature-text">A presto,</div>
                <div class="signature-name">Vincenzo Rocca</div>
                <div class="signature-title">Full Stack Developer</div>
            </div>
        </div>

        <div class="footer">
            <div class="footer-main">Portfolio di Vincenzo Rocca</div>
            <div class="footer-note">Questa è una conferma automatica. Non rispondere a questa email.</div>
            <div class="footer-contact">Per urgenze: vincenzorocca88@gmail.com</div>
        </div>
    </div>
</body>
</html>

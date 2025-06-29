@echo off
echo Riorganizzazione Deploy Portfolio
echo ===================================

REM Crea la directory principale
if not exist "public_html" mkdir "public_html"
if not exist "public_html\api" mkdir "public_html\api"
if not exist "public_html\assets" mkdir "public_html\assets"

echo.
echo Copiando file principali...

REM Copia file principali
copy "public_html_final\index.html" "public_html\index.html" >nul
copy "public_html_final\.htaccess" "public_html\.htaccess" >nul

REM Copia favicon e icone
copy "public_html_final\favicon.ico" "public_html\favicon.ico" >nul
copy "public_html_final\android-chrome-192x192.png" "public_html\android-chrome-192x192.png" >nul
copy "public_html_final\android-chrome-512x512.png" "public_html\android-chrome-512x512.png" >nul
copy "public_html_final\apple-touch-icon.png" "public_html\apple-touch-icon.png" >nul
copy "public_html_final\favicon-32x32.png" "public_html\favicon-32x32.png" >nul
copy "public_html_final\site.webmanifest" "public_html\site.webmanifest" >nul
copy "public_html_final\vite.svg" "public_html\vite.svg" >nul

echo File principali copiati.

echo.
echo Copiando API...

REM Copia API
copy "public_html_final\api\index.php" "public_html\api\index.php" >nul
copy "public_html_final\api\.htaccess" "public_html\api\.htaccess" >nul

echo API copiata.

echo.
echo Copiando assets...

REM Copia assets
xcopy "public_html_final\assets\*" "public_html\assets\" /E /Y >nul

echo Assets copiati.

echo.
echo Copiando file di test...

REM Copia file di test utili
copy "public_html_final\test-completo-corretto.php" "public_html\test-completo-corretto.php" >nul
copy "public_html_final\fix-deploy.php" "public_html\fix-deploy.php" >nul
copy "public_html_final\test-api-simple.php" "public_html\test-api-simple.php" >nul

echo File di test copiati.

echo.
echo Creando file .env...

REM Crea file .env corretto
(
echo APP_NAME="Portfolio Vincenzo Rocca"
echo APP_ENV=production
echo APP_KEY=base64:BG4Lm+aWL/EWG17EqNCfMYFkZkkHiw16kUqbKTOehA8=
echo APP_DEBUG=false
echo APP_TIMEZONE=Europe/Rome
echo APP_URL=https://vincenzorocca.com
echo.
echo DB_CONNECTION=mysql
echo DB_HOST=localhost
echo DB_PORT=3306
echo DB_DATABASE=u336414084_portfolioVince
echo DB_USERNAME=u336414084_vincenzorocca8
echo DB_PASSWORD=Ciaociao52.?
echo.
echo MAIL_MAILER=smtp
echo MAIL_HOST=smtp.gmail.com
echo MAIL_PORT=587
echo MAIL_USERNAME=vincenzorocca88@gmail.com
echo MAIL_PASSWORD=xxwlnbjfwvpcjsqn
echo MAIL_ENCRYPTION=tls
echo MAIL_FROM_ADDRESS=vincenzorocca88@gmail.com
echo MAIL_FROM_NAME="Portfolio Vincenzo Rocca"
echo.
echo CLOUDINARY_CLOUD_NAME=dcqbnmpyc
echo CLOUDINARY_API_KEY=765424175259583
echo CLOUDINARY_API_SECRET=bRbCHnxtGq_xAGmozLl1F1Y7rEk
) > "public_html\api\.env"

echo File .env creato.

echo.
echo Creando directory uploads...
if not exist "public_html\api\uploads" mkdir "public_html\api\uploads"
echo Directory uploads creata.

echo.
echo Creando README...

(
echo # Portfolio Vincenzo Rocca - Deploy Ready
echo.
echo Questa cartella contiene la versione corretta del portfolio.
echo.
echo ## Struttura:
echo - index.html - Frontend React
echo - assets/ - CSS, JS e altri asset
echo - api/ - Backend API con database
echo - .htaccess - Configurazione routing
echo.
echo ## Istruzioni:
echo 1. Carica tutto il contenuto nella directory /public_html/ su Hostinger
echo 2. Verifica che il file api/.env esista
echo 3. Assicurati che la directory api/uploads/ abbia permessi 755
echo 4. Testa con /test-completo-corretto.php
echo.
echo ## Database:
echo - Host: localhost
echo - Database: u336414084_portfolioVince
echo - Username: u336414084_vincenzorocca8
echo - Password: Ciaociao52.?
echo.
echo Generato automaticamente
) > "public_html\README-DEPLOY.md"

echo.
echo ===================================
echo RIORGANIZZAZIONE COMPLETATA!
echo ===================================
echo.
echo La cartella 'public_html' e pronta per essere caricata su Hostinger.
echo.
echo Contenuto:
echo - Frontend React completo
echo - API Backend funzionante
echo - File di configurazione corretti
echo - File .env con credenziali corrette
echo - Directory uploads creata
echo.
echo Prossimi passi:
echo 1. Carica la cartella 'public_html' su Hostinger
echo 2. Imposta permessi 755 su api/uploads/
echo 3. Testa con https://vincenzorocca.com/test-completo-corretto.php
echo.
pause 
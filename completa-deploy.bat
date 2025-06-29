@echo off
echo ========================================
echo COMPLETAMENTO DEPLOY PORTFOLIO
echo ========================================
echo.

echo Creando directory assets se non esiste...
if not exist "public_html\assets" mkdir "public_html\assets"

echo.
echo Copiando file assets necessari...

REM Copia i file assets principali (quelli referenziati nell'index.html)
if exist "public_html_final\assets\index-CoZPpuo8.css" (
    copy "public_html_final\assets\index-CoZPpuo8.css" "public_html\assets\" >nul
    echo ✅ CSS principale copiato
) else (
    echo ❌ CSS principale non trovato
)

if exist "public_html_final\assets\index-CXYiKPGX.js" (
    copy "public_html_final\assets\index-CXYiKPGX.js" "public_html\assets\" >nul
    echo ✅ JavaScript principale copiato
) else (
    echo ❌ JavaScript principale non trovato
)

if exist "public_html_final\assets\vendor-dQk0gtQ5.js" (
    copy "public_html_final\assets\vendor-dQk0gtQ5.js" "public_html\assets\" >nul
    echo ✅ Vendor bundle copiato
) else (
    echo ❌ Vendor bundle non trovato
)

if exist "public_html_final\assets\router-qtbhp7Me.js" (
    copy "public_html_final\assets\router-qtbhp7Me.js" "public_html\assets\" >nul
    echo ✅ Router bundle copiato
) else (
    echo ❌ Router bundle non trovato
)

if exist "public_html_final\assets\ui-KUd19APl.js" (
    copy "public_html_final\assets\ui-KUd19APl.js" "public_html\assets\" >nul
    echo ✅ UI bundle copiato
) else (
    echo ❌ UI bundle non trovato
)

echo.
echo Copiando favicon e icone...

REM Copia favicon e icone
if exist "public_html_final\favicon.ico" (
    copy "public_html_final\favicon.ico" "public_html\" >nul
    echo ✅ Favicon copiato
)

if exist "public_html_final\android-chrome-192x192.png" (
    copy "public_html_final\android-chrome-192x192.png" "public_html\" >nul
    echo ✅ Android icon 192 copiato
)

if exist "public_html_final\android-chrome-512x512.png" (
    copy "public_html_final\android-chrome-512x512.png" "public_html\" >nul
    echo ✅ Android icon 512 copiato
)

if exist "public_html_final\apple-touch-icon.png" (
    copy "public_html_final\apple-touch-icon.png" "public_html\" >nul
    echo ✅ Apple touch icon copiato
)

if exist "public_html_final\favicon-32x32.png" (
    copy "public_html_final\favicon-32x32.png" "public_html\" >nul
    echo ✅ Favicon 32x32 copiato
)

if exist "public_html_final\site.webmanifest" (
    copy "public_html_final\site.webmanifest" "public_html\" >nul
    echo ✅ Web manifest copiato
)

if exist "public_html_final\vite.svg" (
    copy "public_html_final\vite.svg" "public_html\" >nul
    echo ✅ Vite SVG copiato
)

echo.
echo Creando directory uploads API...
if not exist "public_html\api\uploads" (
    mkdir "public_html\api\uploads"
    echo ✅ Directory uploads creata
) else (
    echo ℹ️ Directory uploads già esistente
)

echo.
echo Copiando file di test utili...
if exist "public_html_final\test-completo-corretto.php" (
    copy "public_html_final\test-completo-corretto.php" "public_html\" >nul
    echo ✅ Test completo copiato
)

if exist "public_html_final\fix-deploy.php" (
    copy "public_html_final\fix-deploy.php" "public_html\" >nul
    echo ✅ Fix deploy copiato
)

echo.
echo ========================================
echo DEPLOY COMPLETATO!
echo ========================================
echo.
echo 📂 Cartella pronta: public_html\
echo.
echo 📋 Contenuto:
echo    ✅ Frontend React (index.html)
echo    ✅ API Backend (api\index.php)
echo    ✅ Assets CSS/JS
echo    ✅ Favicon e icone
echo    ✅ File di configurazione
echo    ✅ File di test
echo.
echo 🚀 PROSSIMI PASSI:
echo    1. Carica la cartella 'public_html' su Hostinger
echo    2. Rinomina api\env-production.txt in api\.env
echo    3. Imposta permessi 755 su api\uploads\
echo    4. Testa con https://vincenzorocca.com/test-deploy-finale.php
echo.
echo 🎯 Il portfolio sarà online e funzionante!
echo.
pause 
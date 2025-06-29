@echo off
echo 🚀 Deploy Automatico Portfolio su Hostinger (Windows)
echo ====================================================

:: Configurazione FTP Hostinger
set FTP_HOST=82.25.96.153
set FTP_USER=u336414084.vincenzo88
set FTP_PASS=Ciaociao5.2
set FTP_DIR=public_html

echo 📊 Step 1/3: Building for production...
call bash build-production.sh
if %errorlevel% neq 0 (
    echo ❌ Build fallito!
    pause
    exit /b 1
)
echo ✅ Build completato!

echo 📊 Step 2/3: Test connessione FTP...
curl -s "ftp://%FTP_USER%:%FTP_PASS%@%FTP_HOST%/%FTP_DIR%/" > nul
if %errorlevel% neq 0 (
    echo ❌ Connessione FTP fallita! Verifica credenziali.
    pause
    exit /b 1
)
echo ✅ Connessione FTP riuscita!

echo 📊 Step 3/3: Upload files to Hostinger...

:: Upload file principali
echo 📤 Uploading index.html...
curl -T "public_html_final/index.html" "ftp://%FTP_USER%:%FTP_PASS%@%FTP_HOST%/%FTP_DIR%/index.html"

echo 📤 Uploading .htaccess...
curl -T "public_html_final/.htaccess" "ftp://%FTP_USER%:%FTP_PASS%@%FTP_HOST%/%FTP_DIR%/.htaccess"

:: Upload favicon files
echo 📤 Uploading favicon files...
curl -T "public_html_final/favicon.ico" "ftp://%FTP_USER%:%FTP_PASS%@%FTP_HOST%/%FTP_DIR%/favicon.ico"
curl -T "public_html_final/android-chrome-192x192.png" "ftp://%FTP_USER%:%FTP_PASS%@%FTP_HOST%/%FTP_DIR%/android-chrome-192x192.png"
curl -T "public_html_final/android-chrome-512x512.png" "ftp://%FTP_USER%:%FTP_PASS%@%FTP_HOST%/%FTP_DIR%/android-chrome-512x512.png"
curl -T "public_html_final/apple-touch-icon.png" "ftp://%FTP_USER%:%FTP_PASS%@%FTP_HOST%/%FTP_DIR%/apple-touch-icon.png"
curl -T "public_html_final/site.webmanifest" "ftp://%FTP_USER%:%FTP_PASS%@%FTP_HOST%/%FTP_DIR%/site.webmanifest"

:: Upload assets directory files
echo 📤 Uploading assets files...
for /r "public_html_final\assets" %%f in (*) do (
    set "file=%%f"
    set "relative=!file:public_html_final\=!"
    set "relative=!relative:\=/!"
    echo   📄 Uploading %%~nxf...
    curl -T "%%f" "ftp://%FTP_USER%:%FTP_PASS%@%FTP_HOST%/%FTP_DIR%/!relative!"
)

echo.
echo 🎉 DEPLOY COMPLETATO!
echo 🌐 Portfolio live su: https://vincenzorocca.com
echo 🧪 Test il portfolio per verificare che tutto funzioni!
echo.
echo 📋 PROSSIMI PASSI:
echo 1. Apri: https://vincenzorocca.com
echo 2. Fai hard refresh: CTRL+SHIFT+R
echo 3. Verifica DevTools console per confermare nuovo bundle
echo 4. Testa login admin e form contatti

pause 
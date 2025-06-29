@echo off
echo ===================================
echo DEPLOY CORREZIONI FINALI
echo ===================================

echo.
echo Creando index.html aggiornato...
echo ^<!doctype html^> > public_html_final\index.html
echo ^<html lang="it"^> >> public_html_final\index.html
echo ^<head^> >> public_html_final\index.html
echo     ^<meta charset="UTF-8" /^> >> public_html_final\index.html
echo     ^<link rel="icon" type="image/x-icon" href="/favicon.ico" /^> >> public_html_final\index.html
echo     ^<meta name="viewport" content="width=device-width, initial-scale=1.0" /^> >> public_html_final\index.html
echo     ^<title^>Vincenzo Rocca - Full Stack Developer ^| Portfolio^</title^> >> public_html_final\index.html
echo     ^<script type="module" crossorigin src="/assets/index-CXYiKPGX.js?v=1751128456"^>^</script^> >> public_html_final\index.html
echo     ^<link rel="stylesheet" crossorigin href="/assets/index-CoZPpuo8.css?v=1751128456"^> >> public_html_final\index.html
echo ^</head^> >> public_html_final\index.html
echo ^<body^> >> public_html_final\index.html
echo     ^<div id="root"^>^</div^> >> public_html_final\index.html
echo ^</body^> >> public_html_final\index.html
echo ^</html^> >> public_html_final\index.html

echo.
echo 1. Caricamento index.html...
curl -T public_html_final/index.html ftp://82.25.96.153/public_html/index.html --user u336414084.vincenzo88:Ciaociao5.2

echo.
echo 2. Caricamento JavaScript...
curl -T public_html_final/assets/index-CXYiKPGX.js ftp://82.25.96.153/public_html/assets/index-CXYiKPGX.js --user u336414084.vincenzo88:Ciaociao5.2 --ftp-create-dirs

echo.
echo 3. Caricamento CSS...
curl -T public_html_final/assets/index-CoZPpuo8.css ftp://82.25.96.153/public_html/assets/index-CoZPpuo8.css --user u336414084.vincenzo88:Ciaociao5.2 --ftp-create-dirs

echo.
echo ===================================
echo DEPLOY COMPLETATO!
echo ===================================
echo.
echo Correzioni implementate:
echo - Gallery: Mostra TUTTE le foto caricate
echo - Home: Solo bottone Dettagli
echo - Cache buster: v=1751128456
echo.
echo URL: https://vincenzorocca.com
echo.
pause 
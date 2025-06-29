@echo off
echo DEPLOY CORREZIONI FINALI

echo Creando index.html...
echo ^<!doctype html^> > public_html_final\index.html
echo ^<html lang="it"^> >> public_html_final\index.html
echo ^<head^> >> public_html_final\index.html
echo     ^<meta charset="UTF-8" /^> >> public_html_final\index.html
echo     ^<title^>Vincenzo Rocca Portfolio^</title^> >> public_html_final\index.html
echo     ^<script type="module" src="/assets/index-CXYiKPGX.js?v=1751128456"^>^</script^> >> public_html_final\index.html
echo     ^<link rel="stylesheet" href="/assets/index-CoZPpuo8.css?v=1751128456"^> >> public_html_final\index.html
echo ^</head^> >> public_html_final\index.html
echo ^<body^> >> public_html_final\index.html
echo     ^<div id="root"^>^</div^> >> public_html_final\index.html
echo ^</body^> >> public_html_final\index.html
echo ^</html^> >> public_html_final\index.html

echo Caricamento FTP...
curl -T public_html_final/index.html ftp://82.25.96.153/public_html/index.html --user u336414084.vincenzo88:Ciaociao5.2
curl -T public_html_final/assets/index-CXYiKPGX.js ftp://82.25.96.153/public_html/assets/index-CXYiKPGX.js --user u336414084.vincenzo88:Ciaociao5.2 --ftp-create-dirs
curl -T public_html_final/assets/index-CoZPpuo8.css ftp://82.25.96.153/public_html/assets/index-CoZPpuo8.css --user u336414084.vincenzo88:Ciaociao5.2 --ftp-create-dirs

echo DEPLOY COMPLETATO!
pause 
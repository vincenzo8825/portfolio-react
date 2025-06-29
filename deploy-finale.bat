@echo off
echo ===================================
echo DEPLOY FINALE PORTFOLIO VINCENZO
echo ===================================

echo.
echo 1. Caricamento index.html...
curl -T public_html_final/index.html ftp://82.25.96.153/public_html/index.html --user u336414084.vincenzo88:Ciaociao5.2

echo.
echo 2. Caricamento nuovo bundle JavaScript...
curl -T public_html_final/assets/index-BjrjVhxN.js ftp://82.25.96.153/public_html/assets/index-BjrjVhxN.js --user u336414084.vincenzo88:Ciaociao5.2 --ftp-create-dirs

echo.
echo 3. Caricamento nuovo CSS...
curl -T public_html_final/assets/index-CG3Ep1cH.css ftp://82.25.96.153/public_html/assets/index-CG3Ep1cH.css --user u336414084.vincenzo88:Ciaociao5.2 --ftp-create-dirs

echo.
echo ===================================
echo DEPLOY COMPLETATO!
echo ===================================
echo.
echo Sito aggiornato con:
echo - Gallery dinamica (max 6 foto)
echo - Bottoni Home sempre cliccabili
echo - Cache buster: v=1751126789
echo.
echo URL: https://vincenzorocca.com
echo.
pause 
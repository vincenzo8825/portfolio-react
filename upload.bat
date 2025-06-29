@echo off
echo open 82.25.96.153 > ftp.txt
echo u336414084.vincenzo88 >> ftp.txt
echo Ciaociao5.2 >> ftp.txt
echo cd public_html >> ftp.txt
echo binary >> ftp.txt
echo put public_html_final\test-upload.php test-upload.php >> ftp.txt
echo put public_html_final\index-simple.html index.html >> ftp.txt
echo put public_html_final\test-modifiche-finali.php test-modifiche-finali.php >> ftp.txt
echo cd assets >> ftp.txt
echo put public_html_final\assets\index-CXYiKPGX.js index-CXYiKPGX.js >> ftp.txt
echo put public_html_final\assets\index-CoZPpuo8.css index-CoZPpuo8.css >> ftp.txt
echo quit >> ftp.txt
ftp -s:ftp.txt
del ftp.txt
echo Upload completato! 
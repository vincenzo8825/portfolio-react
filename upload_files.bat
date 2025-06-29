@echo off
echo Uploading files to Hostinger...

echo user u336414084.vincenzo88 Ciaociao5.2 > ftpcmd.dat
echo cd public_html >> ftpcmd.dat
echo binary >> ftpcmd.dat
echo put "public_html_final\test-upload.php" test-upload.php >> ftpcmd.dat
echo put "public_html_final\index-simple.html" index.html >> ftpcmd.dat
echo cd assets >> ftpcmd.dat
echo put "public_html_final\assets\index-CXYiKPGX.js" index-CXYiKPGX.js >> ftpcmd.dat
echo put "public_html_final\assets\index-CoZPpuo8.css" index-CoZPpuo8.css >> ftpcmd.dat
echo quit >> ftpcmd.dat

ftp -n -s:ftpcmd.dat 82.25.96.153

del ftpcmd.dat
echo Upload completed!
pause 
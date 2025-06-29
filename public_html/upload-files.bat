@echo off
echo Uploading files to Hostinger...

echo Uploading API...
curl -T "api/index.php" ftp://ftp.vincenzorocca.com/public_html/api/ --user "vincenzorocca@vincenzorocca.com:Ciaociao52.?" --ftp-create-dirs

echo Uploading test files...
curl -T "test-immediato.php" ftp://ftp.vincenzorocca.com/public_html/ --user "vincenzorocca@vincenzorocca.com:Ciaociao52.?"
curl -T "fix-login-immediato.php" ftp://ftp.vincenzorocca.com/public_html/ --user "vincenzorocca@vincenzorocca.com:Ciaociao52.?"

echo Upload completed!
pause 
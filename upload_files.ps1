# PowerShell FTP Upload Script
$ftpServer = "82.25.96.153"
$ftpUsername = "u336414084.vincenzo88"
$ftpPassword = "Ciaociao5.2"

# Files to upload
$files = @{
    "public_html_final/test-upload.php" = "/public_html/test-upload.php"
    "public_html_final/index-simple.html" = "/public_html/index.html"
    "public_html_final/assets/index-CXYiKPGX.js" = "/public_html/assets/index-CXYiKPGX.js"
    "public_html_final/assets/index-CoZPpuo8.css" = "/public_html/assets/index-CoZPpuo8.css"
    "public_html_final/test-modifiche-finali.php" = "/public_html/test-modifiche-finali.php"
}

foreach ($localFile in $files.Keys) {
    $remotePath = $files[$localFile]
    
    if (Test-Path $localFile) {
        Write-Host "Uploading $localFile to $remotePath..."
        
        try {
            $webclient = New-Object System.Net.WebClient
            $webclient.Credentials = New-Object System.Net.NetworkCredential($ftpUsername, $ftpPassword)
            $uri = "ftp://$ftpServer$remotePath"
            $webclient.UploadFile($uri, $localFile)
            Write-Host "✅ Uploaded successfully: $localFile" -ForegroundColor Green
        }
        catch {
            Write-Host "❌ Failed to upload $localFile : $($_.Exception.Message)" -ForegroundColor Red
        }
        finally {
            $webclient.Dispose()
        }
    }
    else {
        Write-Host "❌ File not found: $localFile" -ForegroundColor Red
    }
}

Write-Host "Upload process completed!" 
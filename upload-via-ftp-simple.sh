#!/bin/bash

# Upload files via curl to web interface
HOST="https://vincenzorocca.com"
UPLOAD_URL="$HOST/upload-via-web.php"

echo "🚀 Starting FTP-like upload via web interface..."

# Upload index.html
echo "📄 Uploading index.html..."
curl -X POST \
  -F "action=upload" \
  -F "target_path=index.html" \
  -F "file=@index.html" \
  "$UPLOAD_URL"

# Upload API files
echo "📄 Uploading API files..."
curl -X POST \
  -F "action=upload" \
  -F "target_path=api/index.php" \
  -F "file=@api/index.php" \
  "$UPLOAD_URL"

curl -X POST \
  -F "action=upload" \
  -F "target_path=api/.htaccess" \
  -F "file=@api/.htaccess" \
  "$UPLOAD_URL"

# Upload assets
echo "📄 Uploading assets..."
for file in assets/*; do
  if [ -f "$file" ]; then
    filename=$(basename "$file")
    echo "Uploading $filename..."
    curl -X POST \
      -F "action=upload" \
      -F "target_path=assets/$filename" \
      -F "file=@$file" \
      "$UPLOAD_URL"
  fi
done

echo "✅ Upload completed!" 
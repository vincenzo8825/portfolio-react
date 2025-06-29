#!/bin/bash

echo "⚡ Quick Deploy - Solo file modificati"

# Upload index.html
echo "📤 Uploading index.html..."
curl -T "public_html_final/index.html" "ftp://u336414084.vincenzo88:Ciaociao5.2@82.25.96.153/public_html/index.html"

# Upload nuovo JS
echo "📤 Uploading new JS bundle..."
curl -T "public_html_final/assets/index-jCzcb4LW.js" "ftp://u336414084.vincenzo88:Ciaociao5.2@82.25.96.153/public_html/assets/index-jCzcb4LW.js"

echo "✅ Quick deploy completato!" 
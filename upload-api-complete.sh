#!/bin/bash

echo "🚀 Upload Laravel API completo a Hostinger"
echo "=========================================="

FTP_HOST="82.25.96.153"
FTP_USER="u336414084.vincenzo88"
FTP_PASS="Ciaociao5.2"

# Funzione per creare directory ricorsivamente
create_remote_dir() {
    local dir="$1"
    echo "📁 Creating: $dir"
    curl -Q "MKD $dir" "ftp://$FTP_USER:$FTP_PASS@$FTP_HOST/" 2>/dev/null || true
}

# Funzione per upload file
upload_file() {
    local local_file="$1"
    local remote_file="$2"
    
    echo "📤 Uploading: $remote_file"
    curl -T "$local_file" "ftp://$FTP_USER:$FTP_PASS@$FTP_HOST/$remote_file"
    
    if [ $? -eq 0 ]; then
        echo "  ✅ $remote_file caricato"
    else
        echo "  ❌ Errore caricando $remote_file"
    fi
}

# Crea struttura directory
echo "📁 Creando struttura directory..."
create_remote_dir "api/app"
create_remote_dir "api/app/Http"
create_remote_dir "api/app/Http/Controllers"
create_remote_dir "api/app/Http/Controllers/Api"
create_remote_dir "api/app/Http/Middleware"
create_remote_dir "api/app/Models"
create_remote_dir "api/app/Providers"
create_remote_dir "api/bootstrap"
create_remote_dir "api/config"
create_remote_dir "api/database"
create_remote_dir "api/database/seeders"
create_remote_dir "api/database/schema"
create_remote_dir "api/public"
create_remote_dir "api/resources"
create_remote_dir "api/resources/views"
create_remote_dir "api/resources/views/emails"
create_remote_dir "api/routes"
create_remote_dir "api/storage"
create_remote_dir "api/storage/app"
create_remote_dir "api/storage/framework"
create_remote_dir "api/storage/framework/cache"
create_remote_dir "api/storage/framework/sessions"
create_remote_dir "api/storage/framework/views"
create_remote_dir "api/storage/logs"
create_remote_dir "api/vendor"

# Upload file essenziali
echo "📤 Upload file essenziali..."

# Controllers
upload_file "public_html_final/api/app/Http/Controllers/Api/AuthController.php" "api/app/Http/Controllers/Api/AuthController.php"
upload_file "public_html_final/api/app/Http/Controllers/Api/ContactController.php" "api/app/Http/Controllers/Api/ContactController.php"
upload_file "public_html_final/api/app/Http/Controllers/Api/FileUploadController.php" "api/app/Http/Controllers/Api/FileUploadController.php"
upload_file "public_html_final/api/app/Http/Controllers/Api/ProjectController.php" "api/app/Http/Controllers/Api/ProjectController.php"
upload_file "public_html_final/api/app/Http/Controllers/Api/TechnologyController.php" "api/app/Http/Controllers/Api/TechnologyController.php"
upload_file "public_html_final/api/app/Http/Controllers/Controller.php" "api/app/Http/Controllers/Controller.php"

# Models
upload_file "public_html_final/api/app/Models/Contact.php" "api/app/Models/Contact.php"
upload_file "public_html_final/api/app/Models/Project.php" "api/app/Models/Project.php"
upload_file "public_html_final/api/app/Models/Technology.php" "api/app/Models/Technology.php"
upload_file "public_html_final/api/app/Models/User.php" "api/app/Models/User.php"

# Middleware
upload_file "public_html_final/api/app/Http/Middleware/AdminMiddleware.php" "api/app/Http/Middleware/AdminMiddleware.php"
upload_file "public_html_final/api/app/Http/Middleware/SecurityHeaders.php" "api/app/Http/Middleware/SecurityHeaders.php"

# Config
upload_file "public_html_final/api/config/app.php" "api/config/app.php"
upload_file "public_html_final/api/config/auth.php" "api/config/auth.php"
upload_file "public_html_final/api/config/database.php" "api/config/database.php"
upload_file "public_html_final/api/config/cors.php" "api/config/cors.php"

# Routes
upload_file "public_html_final/api/routes/api.php" "api/routes/api.php"
upload_file "public_html_final/api/routes/web.php" "api/routes/web.php"

# Bootstrap
upload_file "public_html_final/api/bootstrap/app.php" "api/bootstrap/app.php"

# Composer
upload_file "public_html_final/api/composer.json" "api/composer.json"

# Vendor autoload
upload_file "public_html_final/api/vendor/autoload.php" "api/vendor/autoload.php"

echo ""
echo "🎉 Upload Laravel API completato!"
echo "🧪 Testa: https://vincenzorocca.com/api/v1/auth/me" 
<?php
// Test Bootstrap Laravel - Identificazione errore 500
echo "<h1>🔍 TEST BOOTSTRAP LARAVEL</h1>";
echo "<style>body{font-family:Arial;margin:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;} .info{color:blue;} pre{background:#f5f5f5;padding:10px;border-radius:5px;overflow-x:auto;}</style>";

// Abilita error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔧 1. Test Autoload Composer</h2>";
try {
    if (file_exists('api/vendor/autoload.php')) {
        require_once 'api/vendor/autoload.php';
        echo "<div class='success'>✅ Autoload Composer caricato</div>";
    } else {
        echo "<div class='error'>❌ File vendor/autoload.php non trovato</div>";
        echo "<div class='warning'>⚠️ Potrebbero mancare le dipendenze Composer</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>❌ Errore autoload: " . $e->getMessage() . "</div>";
}

echo "<h2>📋 2. Test File Bootstrap</h2>";
$bootstrap_file = 'api/bootstrap/app.php';
if (file_exists($bootstrap_file)) {
    echo "<div class='info'>📝 File bootstrap trovato</div>";
    try {
        // Mostra contenuto del file bootstrap
        $bootstrap_content = file_get_contents($bootstrap_file);
        echo "<h3>Contenuto bootstrap/app.php:</h3>";
        echo "<pre>" . htmlspecialchars($bootstrap_content) . "</pre>";
    } catch (Exception $e) {
        echo "<div class='error'>❌ Errore lettura bootstrap: " . $e->getMessage() . "</div>";
    }
} else {
    echo "<div class='error'>❌ File bootstrap/app.php non trovato</div>";
}

echo "<h2>⚙️ 3. Test Caricamento .env</h2>";
try {
    $env_file = 'api/.env';
    if (file_exists($env_file)) {
        // Simula il caricamento dell'env
        $env_lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $env_vars = [];
        foreach ($env_lines as $line) {
            if (strpos($line, '=') !== false && !str_starts_with($line, '#')) {
                list($key, $value) = explode('=', $line, 2);
                $env_vars[trim($key)] = trim($value);
            }
        }
        echo "<div class='success'>✅ File .env letto (" . count($env_vars) . " variabili)</div>";
        
        // Verifica variabili critiche
        $critical_vars = ['APP_KEY', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'];
        foreach ($critical_vars as $var) {
            if (isset($env_vars[$var])) {
                $value = $var === 'DB_PASSWORD' || $var === 'APP_KEY' ? str_repeat('*', 8) : $env_vars[$var];
                echo "<div class='info'>📝 $var = $value</div>";
            } else {
                echo "<div class='warning'>⚠️ $var mancante</div>";
            }
        }
    }
} catch (Exception $e) {
    echo "<div class='error'>❌ Errore .env: " . $e->getMessage() . "</div>";
}

echo "<h2>🚀 4. Test Bootstrap Laravel Completo</h2>";
try {
    // Cambia directory di lavoro
    $original_dir = getcwd();
    chdir('api');
    
    echo "<div class='info'>📁 Directory cambiata in: " . getcwd() . "</div>";
    
    // Test step by step
    if (file_exists('vendor/autoload.php')) {
        require_once 'vendor/autoload.php';
        echo "<div class='success'>✅ Step 1: Autoload caricato</div>";
        
        if (file_exists('bootstrap/app.php')) {
            try {
                $app = require_once 'bootstrap/app.php';
                echo "<div class='success'>✅ Step 2: App Laravel creata</div>";
                
                // Test kernel
                if (is_object($app)) {
                    echo "<div class='success'>✅ Step 3: App è un oggetto valido</div>";
                    
                    try {
                        $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
                        echo "<div class='success'>✅ Step 4: HTTP Kernel creato</div>";
                        
                        // Test request base
                        $request = Illuminate\Http\Request::createFromGlobals();
                        echo "<div class='success'>✅ Step 5: Request creato</div>";
                        
                    } catch (Exception $e) {
                        echo "<div class='error'>❌ Step 4-5 Errore Kernel/Request: " . $e->getMessage() . "</div>";
                        echo "<div class='info'>Stack trace:</div>";
                        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
                    }
                } else {
                    echo "<div class='error'>❌ Step 3: App non è un oggetto valido</div>";
                }
                
            } catch (Exception $e) {
                echo "<div class='error'>❌ Step 2 Errore bootstrap: " . $e->getMessage() . "</div>";
                echo "<div class='info'>Stack trace:</div>";
                echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
            }
        } else {
            echo "<div class='error'>❌ File bootstrap/app.php non trovato in " . getcwd() . "</div>";
        }
    } else {
        echo "<div class='error'>❌ File vendor/autoload.php non trovato in " . getcwd() . "</div>";
    }
    
    // Ripristina directory
    chdir($original_dir);
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Errore generale bootstrap: " . $e->getMessage() . "</div>";
    echo "<div class='info'>Stack trace:</div>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    
    // Ripristina directory in caso di errore
    chdir($original_dir);
}

echo "<h2>🛣️ 5. Test Routes</h2>";
try {
    $routes_file = 'api/routes/api.php';
    if (file_exists($routes_file)) {
        $routes_content = file_get_contents($routes_file);
        echo "<div class='success'>✅ File routes/api.php trovato</div>";
        echo "<h3>Prime 20 righe del file routes:</h3>";
        $lines = explode("\n", $routes_content);
        $preview = array_slice($lines, 0, 20);
        echo "<pre>" . htmlspecialchars(implode("\n", $preview)) . "</pre>";
    } else {
        echo "<div class='error'>❌ File routes/api.php non trovato</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>❌ Errore routes: " . $e->getMessage() . "</div>";
}

echo "<h2>🎛️ 6. Test Controllers</h2>";
$controllers = [
    'api/app/Http/Controllers/Api/AuthController.php',
    'api/app/Http/Controllers/Controller.php'
];

foreach ($controllers as $controller) {
    if (file_exists($controller)) {
        echo "<div class='success'>✅ " . basename($controller) . " trovato</div>";
        
        // Verifica sintassi PHP
        $output = [];
        $return_var = 0;
        exec("php -l \"$controller\" 2>&1", $output, $return_var);
        
        if ($return_var === 0) {
            echo "<div class='success'>✅ " . basename($controller) . " sintassi OK</div>";
        } else {
            echo "<div class='error'>❌ " . basename($controller) . " errore sintassi:</div>";
            echo "<pre>" . htmlspecialchars(implode("\n", $output)) . "</pre>";
        }
    } else {
        echo "<div class='error'>❌ " . basename($controller) . " non trovato</div>";
    }
}

echo "<h2>📊 7. Test Database Connection da Laravel</h2>";
try {
    // Test connessione diretta
    $pdo = new PDO(
        'mysql:host=localhost;dbname=u336414084_portfolioVince', 
        'u336414084_vincenzorocca8', 
        'Ciaociao52.?',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    echo "<div class='success'>✅ Database connesso direttamente</div>";
    
    // Test query admin
    $stmt = $pdo->prepare("SELECT id, email, password FROM users WHERE email = ?");
    $stmt->execute(['vincenzorocca88@gmail.com']);
    $admin = $stmt->fetch();
    
    if ($admin) {
        echo "<div class='success'>✅ Admin user trovato: " . $admin['email'] . "</div>";
        echo "<div class='info'>🔑 Password hash presente: " . substr($admin['password'], 0, 20) . "...</div>";
    } else {
        echo "<div class='error'>❌ Admin user non trovato</div>";
    }
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Errore database: " . $e->getMessage() . "</div>";
}

echo "<h2>🔍 8. Test PHP Error Log</h2>";
$php_error_log = ini_get('error_log');
if ($php_error_log && file_exists($php_error_log)) {
    echo "<div class='info'>📝 PHP Error Log: $php_error_log</div>";
    $errors = tail($php_error_log, 10);
    if ($errors) {
        echo "<pre>" . htmlspecialchars($errors) . "</pre>";
    }
} else {
    echo "<div class='warning'>⚠️ PHP Error Log non configurato o non accessibile</div>";
}

function tail($filename, $lines = 10) {
    $file = file($filename);
    return implode("", array_slice($file, -$lines));
}

echo "<br><h2>🎯 Conclusioni</h2>";
echo "<div class='info'>Questo test dovrebbe rivelare esattamente dove Laravel si blocca</div>";
echo "<div class='warning'>Se vedi errori di autoload o bootstrap, il problema è nella configurazione di Laravel</div>";
echo "<div class='warning'>Se vedi errori di sintassi nei controller, quelli vanno corretti</div>";
?> 
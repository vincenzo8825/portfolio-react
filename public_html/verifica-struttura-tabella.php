<?php
// Verifica struttura tabella projects su Hostinger

echo "<h2>🔍 Verifica Struttura Tabella Projects</h2>";

$host = 'localhost';
$dbname = 'u336414084_portfolioVince';
$username = 'u336414084_vincenzorocca8';
$password = 'Ciaociao52.?';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connessione database OK<br><br>";

    // Mostra struttura tabella projects
    echo "<h3>📋 Struttura Tabella 'projects':</h3>";
    $stmt = $pdo->prepare("DESCRIBE projects");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background-color: #f0f0f0;'>";
    echo "<th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th>";
    echo "</tr>";

    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td><strong>" . $column['Field'] . "</strong></td>";
        echo "<td>" . $column['Type'] . "</td>";
        echo "<td>" . $column['Null'] . "</td>";
        echo "<td>" . $column['Key'] . "</td>";
        echo "<td>" . ($column['Default'] ?? 'NULL') . "</td>";
        echo "<td>" . $column['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table><br>";

    // Verifica campi JSON specifici
    echo "<h3>🎯 Campi JSON Disponibili:</h3>";
    $jsonFields = ['gallery', 'features', 'challenges', 'results', 'additional_links', 'technologies'];
    foreach ($jsonFields as $field) {
        $found = false;
        foreach ($columns as $column) {
            if ($column['Field'] === $field) {
                echo "✅ <strong>$field</strong>: " . $column['Type'] . "<br>";
                $found = true;
                break;
            }
        }
        if (!$found) {
            echo "❌ <strong>$field</strong>: NON TROVATO<br>";
        }
    }

    // Verifica campi problematici
    echo "<br><h3>⚠️ Campi Problematici:</h3>";
    $problematicFields = ['long_description', 'client', 'duration', 'category', 'testimonial', 'testimonial_author', 'testimonial_role', 'linkedin_url', 'video_url'];
    foreach ($problematicFields as $field) {
        $found = false;
        foreach ($columns as $column) {
            if ($column['Field'] === $field) {
                echo "✅ <strong>$field</strong>: " . $column['Type'] . "<br>";
                $found = true;
                break;
            }
        }
        if (!$found) {
            echo "❌ <strong>$field</strong>: NON TROVATO<br>";
        }
    }

    // Conta progetti esistenti
    echo "<br><h3>📊 Progetti Esistenti:</h3>";
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM projects");
    $stmt->execute();
    $count = $stmt->fetch()['count'];
    echo "Totale progetti: <strong>$count</strong><br>";

    if ($count > 0) {
        echo "<br><h4>🔍 Esempio Progetto:</h4>";
        $stmt = $pdo->prepare("SELECT * FROM projects LIMIT 1");
        $stmt->execute();
        $project = $stmt->fetch(PDO::FETCH_ASSOC);
        
        foreach ($project as $field => $value) {
            if (in_array($field, ['gallery', 'features', 'challenges', 'results', 'additional_links', 'technologies'])) {
                echo "<strong>$field</strong>: " . (is_string($value) ? $value : json_encode($value)) . "<br>";
            }
        }
    }

} catch (Exception $e) {
    echo "❌ Errore: " . $e->getMessage() . "<br>";
}
?> 
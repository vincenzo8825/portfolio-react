<?php
// Test connessione database Hostinger
$host = 'localhost';
$dbname = 'u336414084_portfolioVince';
$username = 'u336414084_vincenzorocca8';
$password = 'Ciaociao52.?';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>✅ Database Connesso!</h1>";
    echo "<p>Host: $host</p>";
    echo "<p>Database: $dbname</p>";
    echo "<p>Username: $username</p>";
    
    // Test query
    $result = $pdo->query("SHOW TABLES");
    $tables = $result->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h2>Tabelle nel database:</h2>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";
    
    // Test users
    try {
        $users = $pdo->query("SELECT * FROM users")->fetchAll();
        echo "<h2>Utenti (". count($users) ."):</h2>";
        foreach ($users as $user) {
            echo "<p>- " . $user['name'] . " (" . $user['email'] . ") - Admin: " . ($user['is_admin'] ? 'Sì' : 'No') . "</p>";
        }
    } catch (Exception $e) {
        echo "<p>Errore nel caricamento utenti: " . $e->getMessage() . "</p>";
    }
    
    // Test technologies
    try {
        $techs = $pdo->query("SELECT COUNT(*) as count FROM technologies")->fetch();
        echo "<h2>Tecnologie: " . $techs['count'] . "</h2>";
    } catch (Exception $e) {
        echo "<p>Errore nel caricamento tecnologie: " . $e->getMessage() . "</p>";
    }
    
} catch(PDOException $e) {
    echo "<h1>❌ Errore Connessione Database</h1>";
    echo "<p>Errore: " . $e->getMessage() . "</p>";
}
?> 
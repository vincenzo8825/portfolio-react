<?php
// Test semplice per gallery
$host = 'localhost';
$dbname = 'u336414084_vincenzorocca8';
$username = 'u336414084_portfolioVince';
$password = 'Ciaociao52.?';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Aggiorniamo il progetto ID 14 con gallery
    $galleryJson = '["https://vincenzorocca.com/api/uploads/gallery1.jpg","https://vincenzorocca.com/api/uploads/gallery2.jpg","https://vincenzorocca.com/api/uploads/gallery3.jpg"]';
    
    $stmt = $pdo->prepare("UPDATE projects SET gallery = ? WHERE id = 14");
    $result = $stmt->execute([$galleryJson]);
    
    echo "Update result: " . ($result ? "SUCCESS" : "FAILED") . "<br>";
    
    // Verifichiamo
    $stmt = $pdo->prepare("SELECT gallery FROM projects WHERE id = 14");
    $stmt->execute();
    $project = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Gallery in DB: " . $project['gallery'] . "<br>";
    
    // Test API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://vincenzorocca.com/api/v1/projects/14");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    
    echo "<h3>API Response:</h3>";
    echo "<pre>" . $response . "</pre>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

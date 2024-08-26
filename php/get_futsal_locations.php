<?php
// Database connection
$host = 'localhost';
$dbname = 'futsal_reservation';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit();
}

// Get user's latitude and longitude from the query parameters
$lat = $_GET['lat'];
$lng = $_GET['lng'];
$radius = 10; // 10 km radius

// Query to find futsal locations within the radius
$stmt = $pdo->prepare("
    SELECT name, address, lat, lng,
    (6371 * acos(cos(radians(:lat)) * cos(radians(lat)) * cos(radians(lng) - radians(:lng)) + sin(radians(:lat)) * sin(radians(lat)))) AS distance
    FROM futsal_locations
    HAVING distance < :radius
    ORDER BY distance
");
$stmt->execute(['lat' => $lat, 'lng' => $lng, 'radius' => $radius]);

// Fetch the results
$futsalLocations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return the results as JSON
echo json_encode($futsalLocations);
?>

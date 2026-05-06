<?php
// config.php : connexion PDO + fonctions globales
session_start();

$host = 'localhost';
$db = 'visitor_management';
$user = 'root';
$pass = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur DB : " . $e->getMessage());
}

// Fonction de journalisation simple
function logAction($conn, $action, $userId) {
    $stmt = $conn->prepare("INSERT INTO logs (action, utilisateur_id) VALUES (?, ?)");
    $stmt->execute([$action, $userId]);
}
?>

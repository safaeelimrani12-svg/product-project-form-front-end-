<?php
 
$host = "localhost";
$port=3307;
$dbname = "gestionProduit";
$username = "root";
$password = "";

try {
 
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );
 
    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );
 
} catch (PDOException $e) {
 
    http_response_code(500);
 
    echo json_encode([
        "error" => "Erreur de connexion à la base de données"
    ]);
 
    exit;
}
 
<?php
header("Content-Type: application/json; charset=UTF-8");
 
require_once "database.php";

$stmt = $pdo->prepare("
    SELECT idCat, nomCat
    FROM Categorie
    ORDER BY nomCat ASC
");

$stmt->execute();

$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($categories);
?>
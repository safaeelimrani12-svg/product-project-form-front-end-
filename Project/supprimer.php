<?php
include('cnx.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM produit WHERE idPro = ?");
    $stmt->execute([$id]);
    
    header("Location: index.php");
    exit();
}
?>
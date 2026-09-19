<?php 
try {
    $pdo = new PDO('mysql:host=localhost;port=3307;dbname=gestionProduit', "root", "");
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (\Throwable $th) {
    throw $th;
}
?>
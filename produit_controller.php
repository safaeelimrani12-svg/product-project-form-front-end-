<?php
 
header("Content-Type: application/json; charset=UTF-8");
 
require_once "database.php";
 
$method = $_SERVER["REQUEST_METHOD"];
 
switch ($method) {
 
    // =========================
    // GET - Afficher les produits
    // =========================
    case "GET":
 
        $stmt = $pdo->query(
            "SELECT Produit.*, Categorie.nomCat
            FROM Produit
            INNER JOIN Categorie
                ON Produit.idCat = Categorie.idCat
            ORDER BY Produit.idPro DESC"
        );
 
        $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
        echo json_encode($produits);
 
        break;
 
 
    // =========================
    // POST - Ajouter un produit
    // =========================
    case "POST":
 
        $data = json_decode(
            file_get_contents("php://input"),
            true
        );
 
        if (
            empty($data["nom"]) ||
            empty($data["description"]) ||
            empty($data["prix"]) ||
            !isset($data["quantite"]) ||
            empty($data["categorie"])
        ) {
 
            http_response_code(400);
 
            echo json_encode([
                "error" => "Tous les champs sont obligatoires"
            ]);
 
            exit;
        }
 
        $sql = "
            INSERT INTO Produit
            (nom, photo, description, prix, quantite, idCat)
            VALUES (?, ?, ?, ?, ?, ?)
        ";
 
        $stmt = $pdo->prepare($sql);
 
        $stmt->execute([
            $data["nom"],
            "",
            $data["description"],
            $data["prix"],
            $data["quantite"],
            $data["categorie"]
        ]);
 
        echo json_encode([
            "message" => "Produit ajouté avec succès",
            "id" => $pdo->lastInsertId()
        ]);
 
        break;
 
 
    // =========================
    // PUT - Modifier un produit
    // =========================
    case "PUT":
 
        $data = json_decode(
            file_get_contents("php://input"),
            true
        );
 
        if (empty($data["idPro"])) {
 
            http_response_code(400);
 
            echo json_encode([
                "error" => "ID obligatoire"
            ]);
 
            exit;
        }

        if (
            empty($data["nom"]) ||
            empty($data["description"]) ||
            empty($data["prix"]) ||
            !isset($data["quantite"]) ||
            empty($data["categorie"])
        ) {

            http_response_code(400);

            echo json_encode([
                "error" => "Tous les champs sont obligatoires"
            ]);

            exit;
        }
 
        $sql = "
            UPDATE Produit
            SET nom = ?,
                description = ?,
                prix = ?,
                quantite = ?,
                idCat = ?
            WHERE idPro = ?
        ";
 
        $stmt = $pdo->prepare($sql);
 
        $stmt->execute([
            $data["nom"],
            $data["description"],
            $data["prix"],
            $data["quantite"],
            $data["categorie"],
            $data["idPro"]
        ]);
 
        echo json_encode([
            "message" => "Produit modifié avec succès"
        ]);
 
        break;
 
 
    // =========================
    // DELETE - Supprimer un produit
    // =========================
    case "DELETE":
 
        $data = json_decode(
            file_get_contents("php://input"),
            true
        );
 
        if (empty($data["idPro"])) {
 
            http_response_code(400);
 
            echo json_encode([
                "error" => "ID obligatoire"
            ]);
 
            exit;
        }
 
        $stmt = $pdo->prepare(
            "DELETE FROM Produit WHERE idPro = ?"
        );
 
        $stmt->execute([
            $data["idPro"]
        ]);
 
        echo json_encode([
            "message" => "Produit supprimé avec succès"
        ]);
 
        break;
 
 
    // =========================
    // METHOD NOT ALLOWED
    // =========================
    default:
 
        http_response_code(405);
 
        echo json_encode([
            "error" => "Méthode HTTP non autorisée"
        ]);
 
        break;
}
 
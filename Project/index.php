<?php
include('cnx.php');
session_start();

if (!isset($_SESSION['email'])) {
    $_SESSION['error'] = "Tu dois te connecter d'abord !";

    header('Location: login.php');
    exit();
}

$cat = $pdo->query("SELECT * FROM Categorie")->fetchAll();

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $photo = $_FILES['photo']['name'];
    move_uploaded_file($_FILES['photo']['tmp_name'] ,"images/" . $photo);
    $prix = $_POST['prix'];
    $quantite = $_POST['quantite'];
    $idCat = $_POST['categorie'];

    if (empty($nom) || empty($description) || empty($prix) || empty($quantite) || empty($idCat)) {
        $_SESSION['error'] = "Les champs sont vides !";
        header('Location:index.php');
        exit();
    }
    $stmt = $pdo -> prepare('INSERT INTO Produit(nom, photo, description, prix, quantite, idCat)
                        VALUES (?, ?, ?, ?, ?, ?)');
    $stmt -> execute([$nom, $photo, $description, $prix, $quantite, $idCat]);

    header('Location:index.php');
    exit();
}
// search

if (isset($_GET['search']) && $_GET['search'] != '') {
    $recherche = $_GET['search'];
    $stmt = $pdo->prepare("SELECT Produit.*, Categorie.nomCat AS categorie FROM Produit INNER JOIN Categorie
                            ON Produit.idCat = Categorie.idCat WHERE Produit.nom LIKE :recherche");
    $stmt->execute([':recherche' => '%' . $recherche . '%']);
} else {
    $stmt = $pdo->prepare("SELECT Produit.*,Categorie.nomCat AS categorie FROM Produit INNER JOIN Categorie ON Produit.idCat = Categorie.idCat ORDER BY Produit.idPro ASC");
    $stmt->execute();
}
$product = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title> 
    <style>
        *{
            box-sizing: border-box;
        }

        body{
            font-family: Arial, sans-serif;
            background-color: #F0D9E4;
        }

        h3{
            grid-area: title;
            margin: 0;
            text-transform: capitalize;
            padding-bottom: 15px;
            border-bottom: 1px solid #C1A0AC;
            color: #16131F;
        }

        .main{
            width: 90%;
            max-width: 1200px;
            margin: 15px auto;
            padding: 30px;

            background:
                linear-gradient(
                    rgba(255, 250, 251, 0.72),
                    rgba(255, 250, 251, 0.72)
                ),
                url("./pic.jpeg");

            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;

            border: 1px solid #C1A0AC;
            border-radius: 12px;

            display: grid;
            grid-template-columns: repeat(2, 1fr);

            grid-template-areas:
                "title title"
                "nom categorie"
                "description description"
                "prix quantite"
                "photo photo"
                "buttons buttons";
            gap: 20px;
        }
        .row1{
            grid-area: nom;
        }

        .row2{
            grid-area: description;
        }

        .row3{
            grid-area: prix;
        }

        .row4{
            grid-area: quantite;
        }

        .row5{
            grid-area: categorie;
        }
        .row6{
            grid-area: photo;
        }

        .row6{
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .row6 input[type="file"]{
            padding: 8px;
            background-color: #fffafb;
            border: 1px solid #C1A0AC;
            border-radius: 6px;
            color: #16131F;
            cursor: pointer;
        }
        .row1,
        .row2,
        .row3,
        .row4,
        .row5{
            display: flex;
            flex-direction: column;
            gap: 7px;
        }
        label{
            font-weight: bold;
            font-size: 14px;
            text-transform: capitalize;
            color: #4A3F4B;
        }
        input{
            width: 100%;
            padding: 10px;
            background-color: #fffafb;
            border: 1px solid #C1A0AC;
            border-radius: 6px;
            color: #16131F;
            box-sizing: border-box;
            outline: none;
        }
        input:focus{
            border-color: #806C79;
            box-shadow: 0 0 0 2px #F0D9E4;
        }

        ::placeholder{
            text-transform: capitalize;
            color: #806C79;
        }
   
        .main > div:last-child{
            grid-area: buttons;
        }

        button{
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-transform: capitalize;
        }

        button:first-child{
            background-color: #16131F;
            color: #F0D9E4;
        }

        button:first-child:hover{
            background-color: #4A3F4B;
        }

        button:last-child{
            background-color: #C1A0AC;
            color: #16131F;
            margin-left: 8px;
        }

        button:last-child:hover{
            background-color: #806C79;
            color: white;
        }
        .error-message{
            grid-column: 1 / -1;
            width: fit-content;
            padding: 6px 10px;
            margin-top: -5px;
            font-size: 12px;
            font-weight: 500;
            color: #8b1e2d;
            background-color: #f8d7da;
            border: 1px solid #e6aeb5;
            border-radius: 5px;
        }
        .row5 select{
            width: 100%;
            padding: 10px;
            background-color: #fffafb;
            border: 1px solid #C1A0AC;
            border-radius: 6px;
            color: #16131F;
            box-sizing: border-box;
            outline: none;
            font-size: 14px;
            cursor: pointer;
        }
        .row5 select:focus{
            border-color: #806C79;
            box-shadow: 0 0 0 2px #F0D9E4;
        }
        .table-section{
            width: 90%;
            max-width: 1200px;
            margin: 25px auto;
            padding: 30px;
            background-color: #fffafb;
            border: 1px solid #C1A0AC;
            border-radius: 12px;
            box-sizing: border-box;
        }
      .table-header{
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            padding-bottom: 18px;
            margin-bottom: 20px;
            border-bottom: 1px solid #C1A0AC;
        }
        .table-header h3{
            margin: 0;
            padding: 0;
            border: none;
            color: #16131F;
            text-transform: capitalize;
        }       
        .search-box{
            display: flex;
            justify-content: center;
            gap: 8px;
        }
        .search-box input{
            width: 250px;
            padding: 9px 12px;
            background-color: #fffafb;
            border: 1px solid #C1A0AC;
            border-radius: 6px;
            outline: none;
            color: #16131F;
        }
        .search-box input:focus{
            border-color: #806C79;
            box-shadow: 0 0 0 2px #F0D9E4;
        }
        .search-btn{
            padding: 9px 16px;
            background-color: #16131F;
            color: #F0D9E4;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .search-btn:hover{
            background-color: #4A3F4B;
        }
        .search-form{
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;

            display: flex;
            justify-content: center;
        }
        .table-container{
            width: 100%;
            overflow-x: auto;
        }
        table{
            width: 100%;

            border-collapse: collapse;

            color: #16131F;
        }
        thead{
            background-color: #4A3F4B;
            color: white;
        }
        th{
            padding: 13px 10px;

            text-align: left;

            font-size: 14px;
        }
        td{
            padding: 12px 10px;

            border-bottom: 1px solid #F0D9E4;

            font-size: 14px;
        }
        tbody tr{
            background-color: #fffafb;

            transition: 0.2s;
        }
        tbody tr:hover{
            background-color: #F0D9E4;
        }
        .product-img{
            width: 55px;
            height: 55px;
            object-fit: cover;
            border-radius: 7px;
            border: 1px solid #C1A0AC;
        }
        .actions{
            white-space: nowrap;
        }
        .edit-btn,
        .delete-btn{
            display: inline-flex;
            width: 34px;
            height: 34px;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            text-decoration: none;
            margin-right: 5px;
            transition: 0.2s;
        }
        .edit-btn{
            background-color: #806C79;
            color: white;
        }
        .edit-btn:hover{
            background-color: #4A3F4B;
        }

        .delete-btn{
            background-color: #C1A0AC;
            color: #16131F;
        }
        .delete-btn:hover{
            background-color: #8b1e2d;
            color: white;
        }
        .logout-container{
            width: 90%;
            max-width: 1200px;
            margin: 15px auto 0;

            display: flex;
            justify-content: flex-end;
        }
        .logout-btn{
            padding: 10px 18px;

            background-color: #16131F;
            color: #F0D9E4;

            border: none;
            border-radius: 6px;

            cursor: pointer;
        }
        .logout-btn:hover{
            background-color: #4A3F4B;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <div class="logout-container">
        <form action="logout.php" method="post">
            <button type="submit" class="logout-btn">Log out</button>
        </form>
    </div>

    <form action="index.php" method="post" enctype="multipart/form-data">
        <div class="main">
            <h3>ajouter un produit</h3>
            <?php if(isset($_SESSION['error'])): ?>
                <p style="color: red;">
                    <?php echo $_SESSION['error']; ?>
                </p>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
        <div class="row1">
            <label for="nom" class="">nom du produit</label>
            <div class="nom">
            <input type="text" class="nom" id="nom" name="nom" placeholder="Ex: Clavier mecanique">
            </div>
        </div>
        <div class="row2">
            <label for="description" class="">description</label>
            <div class="description">
            <input type="text" class="description" name="description" id="description" placeholder="ex: clavier gamer avec retroeclairage">
            </div>
        </div>
        <div class="row3">
            <label for="prix" class="">prix (DH)</label>
            <div class="prix">
            <input type="number" class="prix" name="prix"  id="prix" placeholder="0.00">
            </div>
        </div>
        <div class="row4">
            <label for="quantite" class="">quantite</label>
            <div class="quantite">
            <input type="number" class="quantite" name="quantite" id="quantite" placeholder="0">
            </div>
        </div>
        <div class="row5">
            <label for="categorie" class="">categorie</label>
            <select name="categorie">
                <option value="">-- selectionner une categorie --</option>
                <?php foreach ($cat as $c): ?>
                    <option value="<?= $c['idCat'] ?>"><?= $c['nomCat'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="row6">
            <label for="photo">Photo du produit</label>
            <input type="file" name="photo" id="photo" accept="image/*">
        </div>
        <div>
            <button type="submit">ajouter le produit</button>
            <button type="reset">renitialiser</button>
        </div>
        </div>
    </form>

    <form action="index.php" method="get" class="search-form">
        <div class="search-box">
            <input type="search"
                name="search"
                id="search"
                placeholder="Rechercher un produit...">

            <button type="submit" class="search-btn">
                Rechercher
            </button>
        </div>
    </form>

    <div class="table-section">
        <div class="table-header">
            <h3>Liste des produits</h3>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Photo</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Prix (DH)</th>
                        <th>Quantité</th>
                        <th>Catégorie</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($product as $p) { ?>
                        <tr>
                            <td><?= $p['idPro'] ?></td>
                            <td>
                                <img src="images/<?= $p['photo']; ?>" class="product-img">
                            </td>
                            <td><?= $p['nom'] ?></td>
                            <td><?= $p['description'] ?></td>
                            <td><?= $p['prix'] ?></td>
                            <td><?= $p['quantite'] ?></td>
                            <td><?= $p['categorie'] ?></td>
                            <td class="actions">
                                <a href="supprimer.php?id=<?= $p['idPro'] ?>"
                                class="delete-btn"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                                <a href="modifier.php?id=<?= $p['idPro'] ?>"
                                class="edit-btn">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
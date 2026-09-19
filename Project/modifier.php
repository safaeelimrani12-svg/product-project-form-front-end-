<?php
include('cnx.php');
$produit = null;
$categorie = [];
try {
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM Produit WHERE idPro = ?");
        $stmt->execute([$id]);
        $produit = $stmt->fetch(PDO::FETCH_ASSOC); 
    }

    $stmt = $pdo->prepare("SELECT * FROM Categorie");   
    $stmt->execute();  
    $categorie = $stmt->fetchAll(PDO::FETCH_ASSOC); 

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['idPro'];
        $nom = $_POST['nom'];
        $description = $_POST['description'];
        $prix = $_POST['prix'];
        $quantite = $_POST['quantite'];
        $idCat = $_POST['categorie'];

        $stmt = $pdo->prepare("SELECT photo FROM Produit WHERE idPro = ?");
        $stmt->execute([$id]);
        $ancienProduit = $stmt->fetch(PDO::FETCH_ASSOC);
        $photo = $ancienProduit['photo'];
        if (!empty($_FILES['photo']['name'])) {
            $photo = $_FILES['photo']['name'];
            move_uploaded_file($_FILES['photo']['tmp_name'], "images/" . $photo);
        }

        $stmt = $pdo->prepare("UPDATE Produit 
                            SET nom=?, description=?, photo=?, prix=?, quantite=?, idCat=?
                            WHERE idPro=?");
        $stmt->execute([$nom, $description, $photo, $prix, $quantite, $idCat, $id]);
        header("Location: index.php");
        exit;
    }
} catch (\Throwable $th) {
    echo "Error: " . $th->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
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
    </style>
<body>
    <form action="modifier.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="idPro" value="<?= $produit['idPro'] ?>">
        <div class="main">
            <h3>Modifier un produit</h3>
            <?php if(isset($_SESSION['error'])): ?>
                <p style="color: red;">
                    <?php echo $_SESSION['error']; ?>
                </p>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
        <div class="row1">
            <label for="nom" class="">nom du produit</label>
            <div class="nom">
            <input type="text" class="nom" id="nom" name="nom" placeholder="Ex: Clavier mecanique" value="<?php echo $produit['nom'] ?>">
            </div>
        </div>
        <div class="row2">
            <label for="description" class="">description</label>
            <div class="description">
            <input type="text" class="description" name="description" id="description" placeholder="ex: clavier gamer avec retroeclairage" value="<?php echo $produit['description'] ?>">
            </div>
        </div>
        <div class="row3">
            <label for="prix" class="">prix (DH)</label>
            <div class="prix">
            <input type="number" class="prix" name="prix"  id="prix" placeholder="0.00" value="<?php echo $produit['prix'] ?>">
            </div>
        </div>
        <div class="row4">
            <label for="quantite" class="">quantite</label>
            <div class="quantite">
            <input type="number" class="quantite" name="quantite" id="quantite" placeholder="0" value="<?php echo $produit['quantite'] ?>">
            </div>
        </div>
        <div class="row5">
            <label for="categorie" class="">categorie</label>
            <select name="categorie">
                <?php foreach ($categorie as $cat): ?>
                    <option value="<?= $cat['idCat'] ?>"
                        <?= $cat['idCat'] == $produit['idCat'] ? 'selected' : '' ?>>
                        <?= $cat['nomCat'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="row6">
            <label for="photo">Photo du produit</label>
            <input type="file" name="photo" id="photo" accept="image/*">
        </div>
        <div>
            <button type="submit">modifier</button>
        </div>
        </div>
    </form>
</body>
</html>
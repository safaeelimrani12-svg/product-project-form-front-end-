<?php
try {
    include('cnx.php');
    session_start();   

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $pwd = $_POST['pwd'];

        if (!empty($email) && !empty($pwd)) {
            $stmt = $pdo->prepare("SELECT * FROM cnx WHERE email = ? AND motPasse = ?");
            $stmt->execute([$email, $pwd]);
            $user = $stmt->fetch();

            if ($user) {
                $_SESSION['email'] = $user['email'];

                header('Location: index.php');
                exit();
            } else {
                $_SESSION['error'] = "les donnees incorrect!";
                header('Location: login.php');
                exit();
            }
        } else {
            $_SESSION['error'] = "les donnees sont obligatoire!";

            header('Location: login.php');
            exit();
        }
    }
} catch (\Throwable $e) {
    throw $e;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
<style>
    body{
    margin: 0;
    min-height: 100vh;

    display: flex;
    align-items: center;
    justify-content: center;

    font-family: Arial, sans-serif;

    background:
        linear-gradient(
            rgba(22, 19, 31, 0.35),
            rgba(22, 19, 31, 0.35)
        ),
        url("./pic.jpeg");

    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    }
    .form-group{
        margin-bottom: 22px;
    }

    .login-card{
        width: 90%;
        max-width: 420px;
        padding: 35px;

        background-color: #4A3F4B;

        border: 1px solid #806C79;
        border-radius: 12px;
    }

    h1{
        margin: 0 0 25px 0;
        padding-bottom: 15px;

        text-align: center;

        color: #F0D9E4;

        border-bottom: 1px solid #806C79;
    }

    label{
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        font-weight: bold;
        color: #F0D9E4;
    }

    input{
        width: 100%;
        padding: 11px;
        background-color: #fffafb;
        border: 1px solid #C1A0AC;
        border-radius: 6px;
        color: #16131F;
        outline: none;
    }

    input:focus{
        border-color: #F0D9E4;
        box-shadow: 0 0 0 2px #806C79;
    }

    button{
        width: 100%;
        padding: 11px;
        margin-top: 5px;
        background-color: #F0D9E4;
        color: #4A3F4B;
        border: none;
        border-radius: 6px;
        font-weight: bold;
        cursor: pointer;
    }

    .error-message{
        color: red;
        margin: 10px;
        text-align: center;
    }

    button:hover{
        background-color: #C1A0AC;
    }

    ::placeholder{
        color: #806C79;
    }
</style>
</head>

<body>

    <div class="login-card">

        <h1>Connexion</h1>

        <form action="" method="post">

            <div class="form-group">
                <label for="email">Email</label>

                <input
                    type="email"
                    name="email"
                    id="email"
                    placeholder="Ex: exemple@gmail.com"
                >
            </div>

            <div class="form-group">
                <label for="pwd">Mot de passe</label>

                <input
                    type="password"
                    name="pwd"
                    id="pwd"
                    placeholder="Votre mot de passe"
                >
            </div>

            <button type="submit">
                Se connecter
            </button>

        </form>

        <?php if (isset($_SESSION['error'])): ?>

            <div class="error-message">
                <?= $_SESSION['error']; ?>
            </div>

            <?php unset($_SESSION['error']); ?>

        <?php endif; ?>

    </div>

</body>
</html>
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <link rel="stylesheet" href="sign.css">
    <title>Inscription Connexion</title>
</head>

<body>
    <header>
        <div class="logo">
            <i class="fas fa-arrow-left"></i>
            <a href="index.php">
                <span>Room </span>Quest
            </a>
        </div>
    </header>
    <div class="container">
        <div class="signin-sign-up">

            <!-- formulaire de  connexion  -->
            <form method="post" class="sign-in-form">
                <h2 class="title">Connexion</h2>
                <div class="input-field">
                    <i class="fas fa-envelope"></i>
                    <input type="text" name="email" placeholder="email">
                </div>
                <div class="input-field">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="passwd" placeholder="Password">
                </div>
                <a href="#" class="forgot-password">Mot de passe oublié ?</a>
                <input type="submit" name="connexion" value="Connexion" class="btn">
                <p>Vous n'avez pas de compte ? <a href="#" class="account-text" id="sign-up-link">S'inscrire</a></p>
            </form>

            <?php
            include "config/database.php";
            global $db;
            if (isset($_POST["connexion"])) {
                $email = $_POST["email"];
                $password = $_POST["passwd"];
                // Vérifier si l'utilisateur existe dans la base de données
                $query = $db->prepare("SELECT * FROM client WHERE email = :email");
                $query->bindParam(":email", $email);
                $query->execute();
                if ($query->rowCount() > 0) {
                    $user = $query->fetch(PDO::FETCH_ASSOC);
                    // Vérifier si le mot de passe est correct
                    if (password_verify($password, $user["mot_de_passe"])) {
                        $_SESSION['loggedin'] = true;
                        $_SESSION['prenom'] = $user["prenom"];
                        header("Location: index.php");
                    } else {
                        echo "Mot de passe incorrect.";
                    }
                } else {
                    echo "Utilisateur non trouvé.";
                }
            }
            ?>

            <!-- formulaire d'inscription -->
            <form method="post" class="sign-up-form">
                <h2 class="title">Inscription</h2>
                <div class="input-field">
                    <i class="fas fa-user"></i>
                    <input type="text" name="nom" id="nom" placeholder="Nom">
                </div>
                <div class="input-field">
                    <i class="fas fa-user"></i>
                    <input type="text" name="prenom" id="prenom" placeholder="Prénom">
                </div>
                <div class="input-field">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" id="email" placeholder="Email">
                </div>
                <div class="input-field">
                    <i class="fas fa-phone"></i>
                    <input type="text" name="num" id="num" placeholder="Téléphone">
                </div>
                <div class="input-field">
                    <i class="fas fa-home"></i>
                    <input type="text" name="adresse" id="adresse" placeholder="Adresse">
                </div>
                <div class="input-field">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="pswd1" id="pswd1" placeholder="Mot de passe">
                </div>
                <div class="input-field">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="pswd2" id="pswd2" placeholder="Confirmer le mot de passe">
                </div>
                <input type="submit" name="formsend" value="S'inscrire" class="btn" id="formsend">
                <p>Vous avez déjà un compte ? <a href="#" class="account-text" id="sign-in-link">Se connecter</a></p>
            </form>

            <?php
            include "config/database.php";
            global $db;

            if (isset($_POST["formsend"])) {
                extract($_POST);
                if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($num) && !empty($pswd1) && !empty($pswd2)) {
                    if ($pswd1 == $pswd2) {
                        $options = [
                            'cost' => 12,
                        ];
                        $hashpswd = password_hash($pswd1, PASSWORD_BCRYPT, $options);
                        $q = $db->prepare("INSERT INTO client (nom, prenom, adresse, telephone, email, mot_de_passe)
                            VALUES (:nom, :prenom, :adresse, :num, :email, :pswd1)");
                        $q->execute([
                            'nom' => $nom,
                            'prenom' => $prenom,
                            'adresse' => $adresse,
                            'num' => $num,
                            'email' => $email,
                            'pswd1' => $hashpswd
                        ]);
                        header("Location: index.php");
                    } else {
                        echo "les mots de passe sont différents";
                    }
                } else {
                    echo "veuillez remplir tous les champs , s'il vous plait...";
                }
            }
            // Fermer la connexion à la base de données
            $db = null;
            ?>
        </div>
    </div>
    <script src="connexion.js"></script>
</body>

</html>
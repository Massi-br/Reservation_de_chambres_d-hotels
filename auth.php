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
            <a href="index.html">
                <span>Room </span>Quest
            </a>
        </div>
    </header>
    <div class="container">
        <div class="signin-sign-up">

            <!-- formulaire de  connexion  -->
            <form action="" class="sign-in-form">
                <h2 class="title">Connexion</h2>
                <div class="input-field">
                    <i class="fas fa-user"></i>
                    <input type="text" placeholder="Username">
                </div>
                <div class="input-field">
                    <i class="fas fa-lock"></i>
                    <input type="password" placeholder="Password">
                </div>
                <a href="#" class="forgot-password">Mot de passe oublié ?</a>
                <input type="submit" value="Connexion" class="btn">
                <p>Vous n'avez pas de compte ? <a href="#" class="account-text" id="sign-up-link">S'inscrire</a></p>
            </form>

            <!-- formulaire d'inscription -->
            <form action="" class="sign-up-form">
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
                <input type="submit" value="S'inscrire" class="btn" id="formsend">
                <p>Vous avez déjà un compte ? <a href="#" class="account-text" id="sign-in-link">Se connecter</a></p>
            </form>

            <?php
            if (isset($_POST["formsend"])) {
                extract($_POST);
                if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($num) && !empty($pswd1) && !empty($pswd2)) {
                    if ($pswd1 == $pswd2) {
                        $options = [
                            'cost' => 12,
                        ];
                        $hashpswd = password_hash($pswd1, PASSWORD_BCRYPT, $options);

                        include "config/database.php";
                        global $db;
                        $q = $db->prepare("INSERT INTO Client (nom, prenom, adresse, telephone, email, mot_de_passe)
                        VALUES (:nom, :prenom, :adresse, :num, :email, :pswd1)");
                        $q->execute([
                            'nom' => $nom,
                            'prenom' => $prenom,
                            'adresse' => $adresse,
                            'num' => $num,
                            'email' => $email,
                            'password' => $hashpswd
                        ]);
                    }
                }
                header("Location: index.html");
            }
            ?>
        </div>
    </div>
    <script src="connexion.js"></script>
</body>

</html>
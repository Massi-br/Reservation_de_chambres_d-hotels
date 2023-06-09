<?php
session_start();
// Vérifier si l'utilisateur est connecté avant d'accéder à la clé "prenom"
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_SESSION['prenom'])) {
    $prenom = $_SESSION['prenom'];
} else {
    $prenom = ""; // Valeur par défaut si la clé n'est pas définie
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoomQuest</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <div class="logo">
            <a href="index.html"> <span>Room</span> Quest</a>
        </div>
        <ul class="menu">
            <li><a href="#">Acceuil</a></li>
            <li><a href="#a-propos">à propos</a></li>
            <li><a href="#popular-destination">destinations</a></li>
        </ul>
        <div class="sign_in_up">
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) { ?>
                <div class="user-info">
                    <span class="user-desc">Bienvenue , <?php echo $prenom; ?></span>
                    <a href="deconnexion.php" class="btn-reservation">Déconnexion</a>
                </div>
            <?php } else { ?>
                <a href="auth.php?mode=connexion" class="btn-reservation">Se connecter</a>
                <a href="auth.php?mode=inscription" class="btn-reservation">S'inscrire</a>
            <?php } ?>
        </div>

        <div class="responsive-menu"></div>
    </header>

    <!-- acceuil  -->
    <section id="home">
        <h2>Votre porte d'entrée vers des séjours inoubliables</h2>
        <h4>RoomQuest</h4>
        <p>Explorez le monde et trouvez votre havre de paix avec notre plateforme de réservation de
            chambres d'hôtel, où confort et simplicité vous attendent</p>
        <a href="#" class="btn-reservation home-btn">Réserver Maintenant</a>
        <div class="find_trip">
            <form action="">
                <div>
                    <label>Ville</label>
                    <input type="text" placeholder="Entrez une ville">
                </div>
                <div>
                    <label>Date d'entrée</label>
                    <input type="text" placeholder="Date-d'arrivée">
                </div>
                <div>
                    <label>Date de départ</label>
                    <input type="text" placeholder="Date-départ">
                </div>
                <div>
                    <label>Personnes</label>
                    <input type="text" placeholder="nombre de personnes">
                </div>
                <input type="submit" value="rechercher" formaction="result.php">
            </form>
        </div>
    </section>

    <!-- A propos  -->
    <section id="a-propos">
        <h1 class="title">à propos</h1>
        <div class="img-desc">
            <div class="left">
                <video src="video/vid1.mp4" autoplay loop></video>
            </div>
            <div class="right">
                <h3>Nous voyageons pour chercher d'autres états, d'autres vies, d'autres, âmes.</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Lorem ipsum dolor sit amet consectetur
                    adipisicing elit. Quaerat ipsam officiis atque, doloremque eos reprehenderit deleniti ipsum dicta
                    aliquid .</p>
                <a href="#">Lire Plus</a>
            </div>
        </div>

    </section>

    <!--  destination -->
    <section id="popular-destination">
        <h1 class="title">destinations populaires</h1>
        <div class="content">
            <!-- box -->
            <div class="box">
                <img src="img/paris.jpg" alt="">
                <div class="content">
                    <div>
                        <h4>Paris</h4>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                        <p>Ea iusto ipsa repudiandae amet conseq.</p>
                        <a href="#">Lire Plus</a>
                    </div>
                </div>
            </div>
            <!-- box -->
            <!-- box -->
            <div class="box">
                <img src="img/moscow.jpg" alt="">
                <div class="content">
                    <div>
                        <h4>Moscou</h4>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                        <p>Ea iusto ipsa repudiandae amet conseq.</p>
                        <a href="#">Lire Plus</a>
                    </div>
                </div>
            </div>
            <!-- box -->
            <!-- box -->
            <div class="box">
                <img src="img/miami.jpg" alt="">
                <div class="content">
                    <div>
                        <h4>Miami</h4>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                        <p>Ea iusto ipsa repudiandae amet conseq.</p>
                        <a href="#">Lire Plus</a>
                    </div>
                </div>
            </div>
            <!-- box -->

        </div>
    </section>


    <footer>
        <p> Réalisé par <span>BRAHIMI Massinissa & DIALLO Lamine</span> | Tous les droits sont réservés.</p>
    </footer>

    <script>
        var toggle_menu = document.querySelector('.responsive-menu');
        var menu = document.querySelector('.menu');
        toggle_menu.onclick = function() {
            toggle_menu.classList.toggle('active');
            menu.classList.toggle('responsive')
        }
    </script>


</body>

</html>
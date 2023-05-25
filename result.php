<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="resultat.css">
    <title>Résultats de recherche d'hôtels</title>
</head>

<body>
    <h1>Résultats de recherche d'hôtels</h1>

    <ul class="hotel-list">
        <?php
        function generateStars($ranking)
        {
            $stars = '';
            for ($i = 0; $i < $ranking; $i++) {
                $stars .= '<i class="fas fa-star"></i>';
            }
            return $stars;
        }

        // Étape 1 : Connexion à la base de données
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "siteweb";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Étape 2 : Récupérer les enregistrements des hôtels en utilisant une requête préparée
            $reqprp = $conn->prepare("SELECT id, nom, adresse, ville, classement FROM hotel");
            $reqprp->execute();


            // Étape 3 : Afficher les détails de chaque hôtel
            if ($reqprp->rowCount() > 0) {
                while ($row = $reqprp->fetch()) {
                    $hotelId = $row["id"];
                    $hotelName = $row["nom"];
                    $hotelAddress = $row["adresse"];
                    $hotelCity = $row["ville"];
                    $hotelRanking = $row["classement"];
        ?>
                    <a href="dest.php?id=<?php echo $hotelId; ?>">
                        <li class="hotel-item">
                            <img class="hotel-image" src="img/room.jpg" alt="Hotel <?php echo $hotelId; ?>">
                            <div class="hotel-details">
                                <h2 class="hotel-title"><?php echo $hotelName; ?></h2>
                                <p class="hotel-location"><?php echo $hotelAddress . ', ' . $hotelCity; ?></p>
                                <p class="hotel-ranking">Classement: <?php echo generateStars($hotelRanking); ?></p>
                            </div>
                        </li>
                    </a>
        <?php
                }
            } else {
                echo "Aucun résultat trouvé.";
            }
        } catch (PDOException $e) {
            echo "Erreur de connexion à la base de données : " . $e->getMessage();
        }
        // Fermer la connexion à la base de données
        $conn = null;
        ?>
    </ul>
</body>

</html>
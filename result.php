<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="rslt.css">
    <title>Résultats de recherche d'hôtels</title>
    <script>
        function validateForm() {
            var priceFrom = parseInt(document.getElementById('price-from').value);
            var priceTo = parseInt(document.getElementById('price-to').value);

            if (priceFrom <= 0) {
                alert("Le prix minimum doit être supérieur à 0.");
                return false;
            }

            if (priceTo <= priceFrom) {
                alert("Le prix maximum doit être supérieur au prix minimum.");
                return false;
            }

            return true;
        }
    </script>
</head>

<body>
    <h1>Résultats de recherche d'hôtels</h1>
    <form method="GET" onsubmit="return validateForm()">
        <div class="filter-container">
            <label for="ranking-filter">Filtrer par classement :</label>
            <select name="ranking" id="ranking-filter">
                <option value="">Tous</option>
                <option value="1">1 étoile</option>
                <option value="2">2 étoiles</option>
                <option value="3">3 étoiles</option>
                <option value="4">4 étoiles</option>
                <option value="5">5 étoiles</option>
            </select>
            <label for="price-from">Prix minimum :</label>
            <input type="number" name="price_from" id="price-from" min="1" value="<?php echo isset($_GET['price_from']) ? $_GET['price_from'] : ''; ?>">
            <label for="price-to">Prix maximum :</label>
            <input type="number" name="price_to" id="price-to" min="1" value="<?php echo isset($_GET['price_to']) ? $_GET['price_to'] : ''; ?>">
            <button type="submit">Filtrer</button>
        </div>
    </form>

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
        $dbname = "website";

        try {
            $db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Étape 2 : Construction de la requête SQL en fonction des filtres
            $sql = "SELECT h.id, h.nom, h.adresse, h.ville, h.classement 
            FROM hotel h
            INNER JOIN chambre c ON h.id = c.hotel_id
            WHERE 1 = 1";

            // Vérification des filtres
            $rankingFilter = isset($_GET['ranking']) ? $_GET['ranking'] : '';
            $priceFromFilter = isset($_GET['price_from']) ? $_GET['price_from'] : '';
            $priceToFilter = isset($_GET['price_to']) ? $_GET['price_to'] : '';

            if ($rankingFilter != '') {
                $sql .= " AND h.classement = :ranking";
            }

            if ($priceFromFilter != '' && $priceToFilter != '') {
                $sql .= " AND c.prix BETWEEN :priceFrom AND :priceTo";
            }

            $sql .= " GROUP BY h.id";

            // Étape 3 : Préparation de la requête avec les paramètres
            $q = $db->prepare($sql);

            if ($rankingFilter != '') {
                $q->bindParam(':ranking', $rankingFilter, PDO::PARAM_INT);
            }

            if ($priceFromFilter != '' && $priceToFilter != '') {
                $q->bindParam(':priceFrom', $priceFromFilter, PDO::PARAM_INT);
                $q->bindParam(':priceTo', $priceToFilter, PDO::PARAM_INT);
            }

            // Étape 4 : Exécution de la requête
            $q->execute();

            // Étape 5 : Affichage des détails de chaque hôtel
            if ($q->rowCount() > 0) {
                while ($row = $q->fetch()) {
                    $hotelId = $row["id"];
                    $hotelName = $row["nom"];
                    $hotelAddress = $row["adresse"];
                    $hotelCity = $row["ville"];
                    $hotelRanking = $row["classement"];
        ?>
                    <a href="dest.php?id=<?php echo $hotelId; ?>&price_from=<?php echo $priceFromFilter; ?>&price_to=<?php echo $priceToFilter; ?>">
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
        $db = null;
        ?>
    </ul>
</body>

</html>
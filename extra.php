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

  <form method="GET" action="">
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
      <input type="number" name="price_from" id="price-from">
      <label for="price-to">Prix maximum :</label>
      <input type="number" name="price_to" id="price-to">
      <button type="submit">Filtrer</button>
    </div>
  </form>

  <ul class="hotel-list">
    <?php
    // Étape 1 : Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "website";

    try {
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // Étape 2 : Construction de la requête SQL en fonction des filtres
      $sql = "SELECT id, nom, adresse, ville, classement FROM hotel";

      // Vérification des filtres
      $rankingFilter = isset($_GET['ranking']) ? $_GET['ranking'] : '';
      $priceFromFilter = isset($_GET['price_from']) ? $_GET['price_from'] : '';
      $priceToFilter = isset($_GET['price_to']) ? $_GET['price_to'] : '';

      if ($rankingFilter != '') {
        $sql .= " WHERE classement = :ranking";
      }

      if ($priceFromFilter != '' && $priceToFilter != '') {
        if ($rankingFilter != '') {
          $sql .= " AND";
        } else {
          $sql .= " WHERE";
        }
        $sql .= " prix BETWEEN :priceFrom AND :priceTo";
      }

      // Étape 3 : Préparation de la requête avec les paramètres
      $stmt = $conn->prepare($sql);

      if ($rankingFilter != '') {
        $stmt->bindParam(':ranking', $rankingFilter, PDO::PARAM_INT);
      }

      if ($priceFromFilter != '' && $priceToFilter != '') {
        $stmt->bindParam(':priceFrom', $priceFromFilter, PDO::PARAM_INT);
        $stmt->bindParam(':priceTo', $priceToFilter, PDO::PARAM_INT);
      }

      // Étape 4 : Exécution de la requête
      $stmt->execute();

      // Étape 5 : Affichage des détails de chaque hôtel
      if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch()) {
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
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>resultatDeRecherche</title>
</head>

<body>
    <?php
    // Vérifier si les paramètres POST requis sont présents
    if (isset($_POST['type']) && isset($_POST['hotelId'])) {
        $type = $_POST['type'];
        $hotelId = $_POST['hotelId'];
        // Vérifier si les paramètres GET des prix sont présents
        if (isset($_GET['price_from']) && isset($_GET['price_to'])) {
            $priceFrom = $_GET['price_from'];
            $priceTo = $_GET['price_to'];

            // Étape 1 : Connexion à la base de données
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "website";

            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Étape 2 : Construction de la requête SQL en fonction du type de chambre
                $sql = "SELECT * FROM chambre WHERE hotel_id = :hotelId";
                if ($type === "chambre") {
                    $sql .= " AND type = 1";
                } else if ($type === "dortoir") {
                    $sql .= " AND type = 2";
                }
                $sql .= " AND prix >= :priceFrom AND prix <= :priceTo";

                // Étape 3 : Préparation de la requête avec les paramètres
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(":hotelId", $hotelId);
                $stmt->bindParam(":priceFrom", $priceFrom);
                $stmt->bindParam(":priceTo", $priceTo);
                $stmt->execute();

                // Étape 4 : Affichage des résultats des chambres
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        // Afficher les détails de la chambre selon votre structure HTML souhaitée
                        echo "<div class='chambre-item'>";
                        echo "<h4>Numéro de chambre : " . $row['numero'] . "</h4>";
                        echo "<h4>prix de chambre : " . $row['prix'] . "</h4>";
    ?>
                        <button onclick="checkLoginAndReserve(<?php echo $row['numero']; ?>)">Réserver</button>
    <?php
                        echo "</div>";
                    }
                } else {
                    echo "Aucun résultat trouvé pour le type de chambre sélectionné.";
                }
            } catch (PDOException $e) {
                echo "Erreur de connexion à la base de données : " . $e->getMessage();
            }
            // Fermer la connexion à la base de données
            $conn = null;
        } else {
            echo "Paramètres manquants pour récupérer la plage de prix.";
        }
    } else {
        echo "Paramètres manquants pour récupérer les résultats des chambres.";
    }
    ?>
    <script>
        function checkLoginAndReserve(roomId) {
            <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) : ?>
                var confirmation = confirm("Vous devez vous connecter ou vous inscrire pour effectuer cette action. Voulez-vous continuer ?");
                if (confirmation) {
                    window.location.href = "auth.php";
                }
            <?php else : ?>
                // L'utilisateur est connecté, appeler la fonction reserveRoom()
                reserveRoom(roomId);
            <?php endif; ?>
        }

        function reserveRoom(roomId) {
            // Code pour réserver la chambre
            // ...
        }
    </script>

</body>

</html>
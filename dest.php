<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dest.css">
    <title>Détails de l'hôtel</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".choices-list a").click(function(e) {
                e.preventDefault();

                var type = $(this).attr("data-type");
                var hotelId = $(this).attr("data-hotel-id");
                var priceFrom = "<?php echo isset($_GET['price_from']) ? $_GET['price_from'] : ''; ?>";
                var priceTo = "<?php echo isset($_GET['price_to']) ? $_GET['price_to'] : ''; ?>";

                $.ajax({
                    url: "chambre_dortoir.php?price_from=" + priceFrom + "&price_to=" + priceTo,
                    method: "POST",
                    data: {
                        type: type,
                        hotelId: hotelId
                    },
                    success: function(response) {
                        $(".chambres-container").html(response);
                    }
                });
            });
        });
    </script>
</head>

<body>
    <?php
    // Vérifier si l'ID de l'hôtel est passé en paramètre d'URL
    if (isset($_GET['id'])) {
        $hotelId = $_GET['id'];

        // Étape 1 : Connexion à la base de données
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "website";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Étape 2 : Récupérer les détails de l'hôtel en utilisant une requête préparée
            $reqprp = $conn->prepare("SELECT nom, adresse, ville FROM hotel WHERE id = :hotelId");
            $reqprp->bindParam(":hotelId", $hotelId);
            $reqprp->execute();

            // Étape 3 : Afficher les détails de l'hôtel
            if ($reqprp->rowCount() > 0) {
                $row = $reqprp->fetch();
                $hotelName = $row["nom"];
                $hotelAddress = $row["adresse"];
                $hotelCity = $row["ville"];
    ?>
                <h1>Détails de l'hôtel</h1>
                <div class="hotel-details">
                    <h2 class="hotel-title"><?php echo $hotelName; ?></h2>
                    <p class="hotel-location"><?php echo $hotelAddress . ', ' . $hotelCity; ?></p>
                </div>

                <div class="choices-container">
                    <h3>Que voulez-vous réserver:</h3>
                    <ul class="choices-list">
                        <li><a href="#" data-type="dortoir" data-hotel-id="<?php echo $hotelId; ?>&price_from=<?php echo isset($_GET['price_from']) ? $_GET['price_from'] : ''; ?>&price_to=<?php echo isset($_GET['price_to']) ? $_GET['price_to'] : ''; ?>">Dortoir</a></li>
                        <li><a href="#" data-type="chambre" data-hotel-id="<?php echo $hotelId; ?>&price_from=<?php echo isset($_GET['price_from']) ? $_GET['price_from'] : ''; ?>&price_to=<?php echo isset($_GET['price_to']) ? $_GET['price_to'] : ''; ?>">Chambre</a></li>
                    </ul>
                </div>
                <div class="chambres-container">
                    <!-- Les résultats des chambres seront affichés ici -->
                </div>
    <?php
            } else {
                echo "Aucun résultat trouvé pour l'hôtel sélectionné.";
            }
        } catch (PDOException $e) {
            echo "Erreur de connexion à la base de données : " . $e->getMessage();
        }
        // Fermer la connexion à la base de données
        $conn = null;
    } else {
        echo "Aucun ID d'hôtel spécifié.";
    }
    ?>
</body>

</html>
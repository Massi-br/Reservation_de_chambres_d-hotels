<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultat de recherche</title>
</head>

<body>
    <?php
    // Vérifier si les paramètres POST requis sont présents
    if (isset($_POST['type']) && isset($_POST['hotelId'])) {
        $type = $_POST['type'];
        $hotelId = $_POST['hotelId'];

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

            // Étape 3 : Préparation de la requête avec les paramètres
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":hotelId", $hotelId);
            $stmt->execute();

            // Étape 4 : Affichage des résultats des chambres
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // Vérifier le type de chambre
                    if ($type === "chambre") {
                        // Traitement pour une chambre
                        echo "<div class='chambre-item'>";
                        echo "<h4>Numéro de chambre : " . $row['numero'] . "</h4>";
                        echo "<h4>Prix de chambre : " . $row['prix'] . "</h4>";
                        echo "<button onclick='reserveRoom(" . $row['numero'] . ", " . $row['capacite'] . ")'>Réserver</button>";
                        echo "</div>";
                    } else if ($type === "dortoir") {
                        // Vérifier la capacité du dortoir
                        if ($row['capacite'] < $numOfPersons) {
                            // Capacité insuffisante, ne rien afficher
                            continue;
                        } else if ($row['capacite'] === $numOfPersons) {
                            // Traitement pour un dortoir avec capacité suffisante
                            echo "<div class='chambre-item'>";
                            echo "<h4>Numéro de dortoir : " . $row['numero'] . "</h4>";
                            echo "<h4>Prix du dortoir : " . $row['prix'] . "</h4>";
                            echo "<button onclick='reserveRoom(" . $row['numero'] . ", " . $row['capacite'] . ")'>Réserver</button>";
                            echo "</div>";
                        } else {
                            // Capacité supérieure au nombre de personnes
                            echo "<div class='chambre-item'>";
                            echo "<h4>Numéro de dortoir : " . $row['numero'] . "</h4>";
                            echo "<h4>Prix du dortoir : " . $row['prix'] . "</h4>";
                            echo "<button onclick='confirmReservation(" . $row['numero'] . ", " . $row['capacite'] . ")'>Réserver</button>";
                            echo "</div>";
                        }
                    }
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
        echo "Paramètres manquants pour récupérer les résultats des chambres.";
    }
    ?>

    <script>
        function reserveRoom(roomId, capacity) {
            // Effectuer une requête AJAX vers le script PHP pour réserver la chambre ou le dortoir
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "reserveroom.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Afficher la réponse du script PHP dans la console
                    console.log(xhr.responseText);
                    // Mettez ici le code pour afficher la réponse dans la page, par exemple :
                    document.getElementById("result").innerHTML = xhr.responseText;
                }
            };

            // Construire les paramètres POST à envoyer au script PHP
            var params = "roomId=" + roomId;

            // Envoyer la requête AJAX avec les paramètres
            xhr.send(params);
        }

        function confirmReservation(roomId, capacity) {
            // Demander la confirmation de la réservation
            var confirmation = confirm("Voulez-vous réserver ce dortoir pour " + capacity + " personnes ?");

            if (confirmation) {
                // Effectuer une requête AJAX vers le script PHP pour réserver le dortoir
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "reserveroom.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // Afficher la réponse du script PHP dans la console
                        console.log(xhr.responseText);
                        // Mettez ici le code pour afficher la réponse dans la page, par exemple :
                        document.getElementById("result").innerHTML = xhr.responseText;
                    }
                };

                // Construire les paramètres POST à envoyer au script PHP
                var params = "roomId=" + roomId + "&capacity=" + capacity;

                // Envoyer la requête AJAX avec les paramètres
                xhr.send(params);
            }
        }
    </script>
</body>

</html>
<?php
try {
    $dsn = "mysql:host=localhost;dbname=website";
    $db = new PDO($dsn, "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit('Erreur : ' . $e->getMessage());
}

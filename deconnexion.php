<?php
session_start();

// Destruction de la session
session_unset();
session_destroy();

// Redirection vers la page d'accueil ou une autre page après la déconnexion
header("Location: index.php");
exit();

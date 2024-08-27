<?php
session_start();
session_unset();
session_destroy();

// Rediriger vers la page de connexion ou d'accueil
header("Location: connexion.php");
exit;
?>

<?php
// Démarrer ou reprendre la session
session_start();

// Vérifier si une session est active
if (session_status() === PHP_SESSION_ACTIVE) {
    // Supprimer toutes les variables de session
    $_SESSION = array();

    // Si vous souhaitez détruire la session complètement, y compris le cookie de session
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Détruire la session
    session_destroy();
}

// Rediriger vers la page de connexion ou d'accueil
header("Location: connexion.php");
exit;
?>

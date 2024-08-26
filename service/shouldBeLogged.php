<?php

/**
 * La fonction sert à vérifier si l'utilisateur est connection, 
 * Si true, l'utilisateur est connecté
 * si false, il est déconnecté
 *
 * @param boolean $logged
 * @param string $redict
 * @return void
 */
function shouldBeLogged(bool $logged = true, string $redirect = "/"):void{

    if($logged){
        // On regarde si la session possède un temps d"expiration
        if (isset($_SESSION["expire"])) {
            // si le temps est expiré, on ferme la session 
            if (time() > $_SESSION["expire"]) {
                unset($_SESSION);
                session_destroy();
                setcookie("PHPSESSID", "",time()-3600);
            }
            // sinon on renouvelle pour une heure
            else{
                $_SESSION["expire"] = time() + 3600;
            }
        }
        // s'il n'est pas connecté, on le redirige
        if (!isset($_SESSION["logged"]) || $_SESSION["logged"]!== true) {
            header("Location: $redirect");
        }
    }
    else{
        // s'il est déconnecté, on le redirige
        if (isset($_SESSION["logged"]) && $_SESSION["logged"] === true ) {
            header("Location: $redirect");
            exit;
        }
    }
}
?>
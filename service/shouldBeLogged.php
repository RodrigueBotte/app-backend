<?php
if(session_start() === PHP_SESSION_NONE)
    session_start();

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
        if (isset($_SESSION["expire"])) {
            if (time() > $_SESSION["expire"]) {
                unset($_SESSION);
                session_destroy();
                setcookie("PHPSESSID", "",time()-3600);
            }
            else{
                $_SESSION["expire"] = time() + 3600;
            }
        }
        if (!isset($_SESSION["logged"]) || $_SESSION["logged"]!== true) {
            header("Location: $redirect");
        }
    }
    else{
        if (isset($_SESSION["logged"]) && $_SESSION["logged"] === true ) {
            header("Location: $redirect");
            exit;
        }
    }
}
?>
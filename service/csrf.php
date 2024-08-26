<?php
if(session_status() === PHP_SESSION_NONE)
    session_start();

    /**
     * créer un otken de session et le place dans un input hidden
     *
     * @param integer $time
     * @return void
     */
function setCSRF(int $time = 0): void{
    // Si $time > 0, on génère un token 
    if($time>0)
    $_SESSION["tokenExpire"] = time() +60*$time;

    // On crée un token qui prendra un nombre d'octet aléatoire (jusqu'a 50)
    // Qu'on transforme ensuite en hexadécimal
    $_SESSION["token"] = bin2hex(random_bytes(50));
    // On affiche le token dans un input caché 
    echo '<input type="hidden" name="token" value="'.$_SESSION["token"].'">';
}


/**
 * On vérifie si le token est toujours valable
 *
 * @return boolean
 */
function isCSRFValid(): bool {
    // Si le token n'a pas de date d'expiration ou s'il est toujours valide
    if (!isset($_SESSION['tokenExpire']) || $_SESSION['tokenExpire'] > time()) {
        // On vérifie que le token correspond bien à celui dans le formulaire
        if (isset($_SESSION['token'], $_POST['token']) && $_SESSION['token'] == $_POST['token']) {
            return true;
        }
    }
    // Sinon on retourne une erreur 405
    if (isset($_SERVER['SERVER_PROTOCOL'])) {
        header($_SERVER['SERVER_PROTOCOL'].' 405 Method Not Allowed');
        exit;
    }
    return false;
}

/**
 * va permettre de nettoyer les entrées des utilisateurs
 *
 * @param string $data
 * @return string
 */
function cleanData(string $data): string {
    // Trim retire les espaces avant et après $data
    $data = trim($data);
    // striplashes retire les antislash de $data
    $data = stripslashes($data);
    // htmlspecialchars transforme les caractères spéciaux en html
    return htmlspecialchars($data);
}
?>